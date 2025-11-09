<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\InteractsWithPersonalInfo;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePersonalInfoRequest;
use App\Models\PersonalInfo;
use App\Models\FamilyMember;
use App\Models\TrainingRecord;
use App\Models\PlanningRecord;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;


class PersonalInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use InteractsWithPersonalInfo;

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request): View
    {

        $currentUser = $request->user();
        $canManageProfiles = $this->userCanManageProfiles($currentUser);

        if ($canManageProfiles && ! $request->filled('user')) {
            $personalInfos = PersonalInfo::with(['user:id,name'])
                ->orderByDesc('updated_at')
                ->orderBy('full_name')
                ->get();

            return view('scientific_profiles.index', [
                'personalInfos' => $personalInfos,
                'currentUser' => $currentUser,
                'mode' => 'personal',
            ]);
        }

        $targetUser = $currentUser;

        if ($request->filled('user')) {
            $requestedUserId = (int) $request->query('user');

            if (! $canManageProfiles && $requestedUserId !== $currentUser->getKey()) {
                abort(403);
            }

            $targetUser = User::findOrFail($requestedUserId);
        }

        $info = $this->ensureInfo($targetUser);

        return view('scientific_profiles.show', [
            'info' => $info,
            'canEdit' => $targetUser->is($currentUser),
            'canManageProfiles' => $canManageProfiles,
            'targetUser' => $targetUser,
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request): View
    {
        $info = $this->ensureInfo($request->user());
        $info->load([
            'immediateFamilyMembers', 
            'spouseFamilyMembers', 
            'familyAssets', 
            'personalHistory',
            'workExperiences' => function ($query) {
                $query->orderBy('position')->orderBy('id');
            },
            'trainingRecords' => function ($query) {
                $query->orderBy('category')->orderBy('position')->orderBy('id');
            },
            'planningRecords' => function ($query) {
                $query->orderBy('category')->orderBy('position')->orderBy('id');
            },
            'salaryRecords' => function ($query) {
                $query->orderBy('position')->orderBy('id');
            },
            'allowanceRecords' => function ($query) {
                $query->orderBy('position')->orderBy('id');
            },
        ]);

        return view('scientific_profiles.edit', [
            'info' => $info,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePersonalInfoRequest $request): RedirectResponse
    {
        $info = $this->ensureInfo($request->user());
        $data = $request->validated();

        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($info->avatar_path && file_exists(public_path($info->avatar_path))) {
                unlink(public_path($info->avatar_path));
            }

            // Tạo thư mục nếu chưa có
            if (!file_exists(public_path('uploads/avatars'))) {
                mkdir(public_path('uploads/avatars'), 0777, true);
            }

            // Lưu file mới
            $file = $request->file('avatar');
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/avatars'), $fileName);

            // Lưu đường dẫn vào DB
            $data['avatar_path'] = 'uploads/avatars/' . $fileName;
        }

        $familyMembers = collect($request->input('family_members', []));
        $familyAssets = collect($request->input('family_assets', []));
        $workExperiences = collect($request->input('work_experiences', []));
        $salaryRecordsInput = collect($request->input('salary_records', []));
        $rewardRecordsInput = collect($request->input('reward_records', []));
        $disciplineRecordsInput = collect($request->input('discipline_records', []));
        $allowanceRecordsInput = collect($request->input('allowance_records', []));
        $planningRecordsInput = collect($request->input('planning_records', []));
        $historyInput = collect($request->input('personal_history', []))
            ->only(['imprisonment_history', 'old_regime_roles', 'foreign_relations'])
            ->map(function ($value) {
                if (is_string($value)) {
                    $trimmed = trim($value);

                    return $trimmed === '' ? null : $trimmed;
                }

                return $value;
            });

        //unset($data['avatar']);
        unset(
            $data['avatar'], 
            $data['family_members'], 
            $data['family_assets'], 
            $data['work_experiences'],
            $data['personal_history'],
            $data['formal_training'],
            $data['professional_development'],
            $data['management_training'],
            $data['political_theory'],
            $data['national_defense'],
            $data['foreign_language'],
            $data['informatics'],
            $data['planning_records'],
            $data['salary_records'],
            $data['allowance_records'],
            $data['reward_records'],
            $data['discipline_records'],
            $data['redirect_to']
        );

        $info->fill($data);
        $info->save();

        $normalizedFamilyMembers = $familyMembers
            ->mapWithKeys(function ($members, $group) {
                $side = $group === FamilyMember::SIDE_SPOUSE ? FamilyMember::SIDE_SPOUSE : FamilyMember::SIDE_SELF;

                return [$side => collect($members ?? [])];
            });

        $records = collect();

        foreach ([FamilyMember::SIDE_SELF, FamilyMember::SIDE_SPOUSE] as $side) {
            if (! $normalizedFamilyMembers->has($side)) {
                continue;
            }

            $sideMembers = $normalizedFamilyMembers->get($side)
                ->filter(function ($member) {
                    if (! is_array($member)) {
                        return false;
                    }

                    if (! filled(data_get($member, 'full_name'))) {
                        return false;
                    }

                    return collect($member)
                        ->only(['relationship', 'full_name', 'birth_year', 'hometown', 'residence', 'occupation', 'workplace', 'notes'])
                        ->filter(fn($value) => filled($value))
                        ->isNotEmpty();
                })
                ->values()
                ->map(function ($member, $index) use ($side) {
                    $position = isset($member['position']) && is_numeric($member['position'])
                        ? (int) $member['position']
                        : $index;

                    return [
                        'side' => $side,
                        'relationship' => $member['relationship'] ?? null,
                        'full_name' => $member['full_name'] ?? null,
                        'birth_year' => isset($member['birth_year']) && $member['birth_year'] !== ''
                            ? (int) $member['birth_year']
                            : null,
                        'hometown' => $member['hometown'] ?? null,
                        'residence' => $member['residence'] ?? null,
                        'occupation' => $member['occupation'] ?? null,
                        'workplace' => $member['workplace'] ?? null,
                        'notes' => $member['notes'] ?? null,
                        'position' => $position,
                    ];
                });

            $records = $records->merge($sideMembers);
        }

        $info->familyMembers()->delete();

        if ($records->isNotEmpty()) {
            $info->familyMembers()->createMany($records->all());
        }

        $assetRecords = $familyAssets
            ->filter(function ($asset) {
                if (! is_array($asset)) {
                    return false;
                }

                return collect($asset)
                    ->only(['asset_description', 'asset_address', 'notes'])
                    ->filter(fn($value) => filled($value))
                    ->isNotEmpty();
            })
            ->values()
            ->map(function ($asset, $index) {
                $position = isset($asset['position']) && is_numeric($asset['position'])
                    ? (int) $asset['position']
                    : $index;

                return [
                    'asset_description' => $asset['asset_description'] ?? null,
                    'asset_address' => $asset['asset_address'] ?? null,
                    'notes' => $asset['notes'] ?? null,
                    'position' => $position,
                ];
            });

        $info->familyAssets()->delete();

        if ($assetRecords->isNotEmpty()) {
            $info->familyAssets()->createMany($assetRecords->all());
        }

        $experienceRecords = $workExperiences
            ->filter(function ($experience) {
                if (! is_array($experience)) {
                    return false;
                }

                return collect($experience)
                    ->only(['from_period', 'to_period', 'unit_name', 'job_title', 'notes'])
                    ->map(function ($value) {
                        if (is_string($value)) {
                            $value = trim($value);

                            return $value === '' ? null : $value;
                        }

                        return $value;
                    })
                    ->filter(fn ($value) => filled($value))
                    ->isNotEmpty();
            })
            ->values()
            ->map(function ($experience, $index) {
                $position = isset($experience['position']) && is_numeric($experience['position'])
                    ? (int) $experience['position']
                    : $index;

                return [
                    'from_period' => isset($experience['from_period']) && is_string($experience['from_period'])
                        ? trim($experience['from_period']) ?: null
                        : ($experience['from_period'] ?? null),
                    'to_period' => isset($experience['to_period']) && is_string($experience['to_period'])
                        ? trim($experience['to_period']) ?: null
                        : ($experience['to_period'] ?? null),
                    'unit_name' => isset($experience['unit_name']) && is_string($experience['unit_name'])
                        ? trim($experience['unit_name']) ?: null
                        : ($experience['unit_name'] ?? null),
                    'job_title' => isset($experience['job_title']) && is_string($experience['job_title'])
                        ? trim($experience['job_title']) ?: null
                        : ($experience['job_title'] ?? null),
                    'notes' => isset($experience['notes']) && is_string($experience['notes'])
                        ? trim($experience['notes']) ?: null
                        : ($experience['notes'] ?? null),
                    'position' => $position,
                ];
            });

        $info->workExperiences()->delete();

        if ($experienceRecords->isNotEmpty()) {
            $info->workExperiences()->createMany($experienceRecords->all());
        }

        $salaryRecords = $salaryRecordsInput
            ->filter(function ($record) {
                if (! is_array($record)) {
                    return false;
                }

                return collect($record)
                    ->only(['from_period', 'to_period', 'coefficient', 'benefit_percentage'])
                    ->map(function ($value) {
                        if (is_string($value)) {
                            $value = trim($value);

                            return $value === '' ? null : $value;
                        }

                        return $value;
                    })
                    ->filter(fn ($value) => filled($value))
                    ->isNotEmpty();
            })
            ->values()
            ->map(function ($record, $index) {
                $position = isset($record['position']) && is_numeric($record['position'])
                    ? (int) $record['position']
                    : $index;

                $coefficient = $record['coefficient'] ?? null;
                $benefitPercentage = $record['benefit_percentage'] ?? null;

                return [
                    'from_period' => isset($record['from_period']) && is_string($record['from_period'])
                        ? trim($record['from_period']) ?: null
                        : ($record['from_period'] ?? null),
                    'to_period' => isset($record['to_period']) && is_string($record['to_period'])
                        ? trim($record['to_period']) ?: null
                        : ($record['to_period'] ?? null),
                    'coefficient' => is_numeric($coefficient) ? (float) $coefficient : null,
                    'benefit_percentage' => is_numeric($benefitPercentage) ? (float) $benefitPercentage : null,
                    'position' => $position,
                ];
            });

        $info->salaryRecords()->delete();

        if ($salaryRecords->isNotEmpty()) {
            $info->salaryRecords()->createMany($salaryRecords->all());
        }

        $allowanceRecords = $allowanceRecordsInput
            ->filter(function ($record) {
                if (! is_array($record)) {
                    return false;
                }

                return collect($record)
                    ->only(['from_period', 'to_period', 'allowance_type', 'salary_percentage', 'coefficient', 'amount'])
                    ->map(function ($value) {
                        if (is_string($value)) {
                            $value = trim($value);

                            return $value === '' ? null : $value;
                        }

                        return $value;
                    })
                    ->filter(fn ($value) => filled($value))
                    ->isNotEmpty();
            })
            ->values()
            ->map(function ($record, $index) {
                $position = isset($record['position']) && is_numeric($record['position'])
                    ? (int) $record['position']
                    : $index;

                $salaryPercentage = $record['salary_percentage'] ?? null;
                $coefficient = $record['coefficient'] ?? null;
                $amount = $record['amount'] ?? null;

                return [
                    'from_period' => isset($record['from_period']) && is_string($record['from_period'])
                        ? trim($record['from_period']) ?: null
                        : ($record['from_period'] ?? null),
                    'to_period' => isset($record['to_period']) && is_string($record['to_period'])
                        ? trim($record['to_period']) ?: null
                        : ($record['to_period'] ?? null),
                    'allowance_type' => isset($record['allowance_type']) && is_string($record['allowance_type'])
                        ? trim($record['allowance_type']) ?: null
                        : ($record['allowance_type'] ?? null),
                    'salary_percentage' => is_numeric($salaryPercentage) ? (float) $salaryPercentage : null,
                    'coefficient' => is_numeric($coefficient) ? (float) $coefficient : null,
                    'amount' => is_numeric($amount) ? (float) $amount : null,
                    'position' => $position,
                ];
            });

        $info->allowanceRecords()->delete();

        if ($allowanceRecords->isNotEmpty()) {
            $info->allowanceRecords()->createMany($allowanceRecords->all());
        }

        $redirectTo = $request->input('redirect_to');
        
        if ($redirectTo === 'recognition') {
            $rewardRecords = $rewardRecordsInput
                ->filter(function ($record) {
                    if (! is_array($record)) {
                        return false;
                    }

                    return collect($record)
                        ->only(['year', 'title', 'awarding_level', 'awarding_form'])
                        ->map(function ($value) {
                            if (is_string($value)) {
                                $value = trim($value);

                                return $value === '' ? null : $value;
                            }

                            return $value;
                        })
                        ->filter(fn ($value) => filled($value))
                        ->isNotEmpty();
                })
                ->values()
                ->map(function ($record, $index) {
                    $position = isset($record['position']) && is_numeric($record['position'])
                        ? (int) $record['position']
                        : $index;

                    return [
                        'year' => isset($record['year']) && is_string($record['year'])
                            ? trim($record['year']) ?: null
                            : ($record['year'] ?? null),
                        'title' => isset($record['title']) && is_string($record['title'])
                            ? trim($record['title']) ?: null
                            : ($record['title'] ?? null),
                        'awarding_level' => isset($record['awarding_level']) && is_string($record['awarding_level'])
                            ? trim($record['awarding_level']) ?: null
                            : ($record['awarding_level'] ?? null),
                        'awarding_form' => isset($record['awarding_form']) && is_string($record['awarding_form'])
                            ? trim($record['awarding_form']) ?: null
                            : ($record['awarding_form'] ?? null),
                        'position' => $position,
                    ];
                });

            $info->rewardRecords()->delete();

            if ($rewardRecords->isNotEmpty()) {
                $info->rewardRecords()->createMany($rewardRecords->all());
            }

            $disciplineRecords = $disciplineRecordsInput
                ->filter(function ($record) {
                    if (! is_array($record)) {
                        return false;
                    }

                    return collect($record)
                        ->only(['year', 'discipline_form', 'reason', 'issued_by'])
                        ->map(function ($value) {
                            if (is_string($value)) {
                                $value = trim($value);

                                return $value === '' ? null : $value;
                            }

                            return $value;
                        })
                        ->filter(fn ($value) => filled($value))
                        ->isNotEmpty();
                })
                ->values()
                ->map(function ($record, $index) {
                    $position = isset($record['position']) && is_numeric($record['position'])
                        ? (int) $record['position']
                        : $index;

                    return [
                        'year' => isset($record['year']) && is_string($record['year'])
                            ? trim($record['year']) ?: null
                            : ($record['year'] ?? null),
                        'discipline_form' => isset($record['discipline_form']) && is_string($record['discipline_form'])
                            ? trim($record['discipline_form']) ?: null
                            : ($record['discipline_form'] ?? null),
                        'reason' => isset($record['reason']) && is_string($record['reason'])
                            ? trim($record['reason']) ?: null
                            : ($record['reason'] ?? null),
                        'issued_by' => isset($record['issued_by']) && is_string($record['issued_by'])
                            ? trim($record['issued_by']) ?: null
                            : ($record['issued_by'] ?? null),
                        'position' => $position,
                    ];
                });

            $info->disciplineRecords()->delete();

            if ($disciplineRecords->isNotEmpty()) {
                $info->disciplineRecords()->createMany($disciplineRecords->all());
            }
        }

        $allowedPlanningCategories = collect(PlanningRecord::categories())->keys();

        $planningRecords = $planningRecordsInput
            ->filter(function ($records, $category) use ($allowedPlanningCategories) {
                return $allowedPlanningCategories->contains($category) && is_array($records);
            })
            ->map(function ($records) {
                return collect($records)
                    ->filter(function ($record) {
                        if (! is_array($record)) {
                            return false;
                        }

                        return collect($record)
                            ->only(['position_title', 'stage', 'status', 'notes'])
                            ->map(function ($value) {
                                if (is_string($value)) {
                                    $value = trim($value);

                                    return $value === '' ? null : $value;
                                }

                                return $value;
                            })
                            ->filter(fn ($value) => filled($value))
                            ->isNotEmpty();
                    })
                    ->values();
            })
            ->filter->isNotEmpty();

        $planningRecordsPayload = collect();

        foreach ($planningRecords as $category => $records) {
            $records->each(function ($record, $index) use (&$planningRecordsPayload, $category) {
                $position = isset($record['position']) && is_numeric($record['position'])
                    ? (int) $record['position']
                    : $index;

                $planningRecordsPayload->push([
                    'category' => $category,
                    'position_title' => isset($record['position_title']) && is_string($record['position_title'])
                        ? (trim($record['position_title']) ?: null)
                        : ($record['position_title'] ?? null),
                    'stage' => isset($record['stage']) && is_string($record['stage'])
                        ? (trim($record['stage']) ?: null)
                        : ($record['stage'] ?? null),
                    'status' => isset($record['status']) && is_string($record['status'])
                        ? (trim($record['status']) ?: null)
                        : ($record['status'] ?? null),
                    'notes' => isset($record['notes']) && is_string($record['notes'])
                        ? (trim($record['notes']) ?: null)
                        : ($record['notes'] ?? null),
                    'position' => $position,
                ]);
            });
        }

        $info->planningRecords()->delete();

        if ($planningRecordsPayload->isNotEmpty()) {
            $info->planningRecords()->createMany($planningRecordsPayload->all());
        }

        if ($historyInput->filter(fn ($value) => filled($value))->isNotEmpty()) {
            $info->personalHistory()->updateOrCreate([], $historyInput->all());
        } else {
            $info->personalHistory()->delete();
        }

        $trainingGroups = [
            'formal_training' => [
                'category' => TrainingRecord::CATEGORY_FORMAL_TRAINING,
                'fields' => ['timeframe', 'institution', 'major', 'training_form', 'qualification'],
            ],
            'professional_development' => [
                'category' => TrainingRecord::CATEGORY_PROFESSIONAL_DEVELOPMENT,
                'fields' => ['program_name', 'certificate', 'institution', 'year_awarded'],
                'integer_fields' => ['year_awarded'],
            ],
            'management_training' => [
                'category' => TrainingRecord::CATEGORY_MANAGEMENT_TRAINING,
                'fields' => ['program_name', 'certificate', 'institution', 'year_awarded'],
                'integer_fields' => ['year_awarded'],
            ],
            'political_theory' => [
                'category' => TrainingRecord::CATEGORY_POLITICAL_THEORY,
                'fields' => ['level', 'institution', 'year_awarded'],
                'integer_fields' => ['year_awarded'],
            ],
            'national_defense' => [
                'category' => TrainingRecord::CATEGORY_NATIONAL_DEFENSE,
                'fields' => ['program_name', 'institution', 'year_awarded'],
                'integer_fields' => ['year_awarded'],
            ],
            'foreign_language' => [
                'category' => TrainingRecord::CATEGORY_FOREIGN_LANGUAGE,
                'fields' => ['language', 'level', 'certificate', 'institution', 'year_awarded'],
                'integer_fields' => ['year_awarded'],
            ],
            'informatics' => [
                'category' => TrainingRecord::CATEGORY_INFORMATICS,
                'fields' => ['program_name', 'level', 'certificate', 'institution', 'year_awarded'],
                'integer_fields' => ['year_awarded'],
            ],
        ];

        $info->trainingRecords()
            ->whereIn('category', collect($trainingGroups)->pluck('category'))
            ->delete();

        foreach ($trainingGroups as $inputKey => $config) {
            $entries = collect($request->input($inputKey, []))
                ->filter(function ($entry) use ($config) {
                    if (! is_array($entry)) {
                        return false;
                    }

                    return collect($config['fields'])
                        ->map(fn ($field) => $entry[$field] ?? null)
                        ->contains(function ($value) {
                            if (is_string($value)) {
                                return trim($value) !== '';
                            }

                            return filled($value);
                        });
                })
                ->values()
                ->map(function ($entry, $index) use ($config) {
                    $payload = [
                        'category' => $config['category'],
                        'position' => isset($entry['position']) && is_numeric($entry['position'])
                            ? (int) $entry['position']
                            : $index,
                    ];

                    foreach ($config['fields'] as $field) {
                        $value = $entry[$field] ?? null;

                        if (in_array($field, $config['integer_fields'] ?? [], true)) {
                            $value = $value !== null && $value !== '' ? (int) $value : null;
                        } elseif (is_string($value)) {
                            $value = trim($value);
                            $value = $value === '' ? null : $value;
                        }

                        $payload[$field] = $value;
                    }

                    return $payload;
                });

            if ($entries->isNotEmpty()) {
                $info->trainingRecords()->createMany($entries->all());
            }
        }


        $redirectTo = $request->input('redirect_to');
        $redirectRoute = 'scientific-profiles.show';

        if ($redirectTo === 'compensation') {
            $redirectRoute = 'scientific-profiles.compensation';
        }

        return redirect()
            /** ->route('scientific-profiles.show') */
            ->route($redirectRoute)
            ->with('status', 'personal-info-updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


    protected function ensureInfo(User $user): PersonalInfo
    {
        return $user->personalInfo()->firstOrCreate([], [
            'full_name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
