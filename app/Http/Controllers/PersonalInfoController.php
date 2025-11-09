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
use Illuminate\Support\Collection;
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
        $mode = $request->input('redirect_to') ?? 'general';

        switch ($mode) {
            case 'compensation':
                $this->updateCompensationRecords(
                    $info,
                    collect($request->input('salary_records', [])),
                    collect($request->input('allowance_records', []))
                );
                break;
            case 'recognition':
                $this->updateRecognitionRecords(
                    $info,
                    collect($request->input('reward_records', [])),
                    collect($request->input('discipline_records', []))
                );
                break;
            case 'teaching':
                $this->updateTeachingActivities(
                    $info,
                    collect($request->input('teaching_activity_records', [])),
                    collect($request->input('supervision_records', []))
                );
                break;
            case 'research':
                $this->updateResearchProjects($info, collect($request->input('research_project_records', [])));
                break;
            case 'awards':
                $this->updateScientificAwards($info, collect($request->input('scientific_awards', [])));
                break;
            case 'publications':
                $this->updatePublicationsAndIntellectualProperties(
                    $info,
                    collect($request->input('scientific_publications', [])),
                    collect($request->input('intellectual_property_records', []))
                );
                break;
            default:
                $this->updateGeneralProfile($info, $request);
                break;
        }

        $redirectRoute = match ($mode) {
            'compensation' => 'scientific-profiles.compensation',
            'recognition' => 'scientific-profiles.recognition',
            'teaching' => 'scientific-profiles.teaching',
            'research' => 'scientific-profiles.research',
            'awards' => 'scientific-profiles.awards',
            'publications' => 'scientific-profiles.publications',
            default => 'scientific-profiles.show',
        };

        return redirect()
            ->route($redirectRoute)
            ->with('status', 'personal-info-updated');
    }
    protected function updateGeneralProfile(PersonalInfo $info, UpdatePersonalInfoRequest $request): void
    {
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
            $data['redirect_to'],
            $data['teaching_activity_records'],
            $data['supervision_records'],
            $data['research_project_records'],
            $data['scientific_awards'],
            $data['scientific_publications'],
            $data['intellectual_property_records'],
        );

        $info->fill($data);
        $info->save();

        $this->syncFamilyMembers($info, collect($request->input('family_members', [])));
        $this->syncFamilyAssets($info, collect($request->input('family_assets', [])));
        $this->syncWorkExperiences($info, collect($request->input('work_experiences', [])));
        $this->syncPlanningRecords($info, collect($request->input('planning_records', [])));
        $this->syncPersonalHistory($info, collect($request->input('personal_history', [])));
        $this->syncTrainingRecords($info, $request);
    }
    protected function updateCompensationRecords(PersonalInfo $info, Collection $salaryRecordsInput, Collection $allowanceRecordsInput): void
    {
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
    }
    protected function updateRecognitionRecords(PersonalInfo $info, Collection $rewardRecordsInput, Collection $disciplineRecordsInput): void
    {
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
    
    protected function updateTeachingActivities(PersonalInfo $info, Collection $teachingActivitiesInput, Collection $supervisionRecordsInput): void
    {
        $teachingActivities = $teachingActivitiesInput
            ->filter(function ($record) {
                if (! is_array($record)) {
                    return false;
                }

                return collect($record)
                    ->only(['academic_year', 'undergraduate_hours', 'graduate_hours', 'doctoral_hours', 'notes'])
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
                    'academic_year' => isset($record['academic_year']) && is_string($record['academic_year'])
                        ? trim($record['academic_year']) ?: null
                        : ($record['academic_year'] ?? null),
                    'undergraduate_hours' => isset($record['undergraduate_hours']) && is_numeric($record['undergraduate_hours'])
                        ? (int) $record['undergraduate_hours']
                        : null,
                    'graduate_hours' => isset($record['graduate_hours']) && is_numeric($record['graduate_hours'])
                        ? (int) $record['graduate_hours']
                        : null,
                    'doctoral_hours' => isset($record['doctoral_hours']) && is_numeric($record['doctoral_hours'])
                        ? (int) $record['doctoral_hours']
                        : null,
                    'notes' => isset($record['notes']) && is_string($record['notes'])
                        ? trim($record['notes']) ?: null
                        : ($record['notes'] ?? null),
                    'position' => $position,
                ];
            });

        $info->teachingActivities()->delete();

        if ($teachingActivities->isNotEmpty()) {
            $info->teachingActivities()->createMany($teachingActivities->all());
        }

        $supervisionRecords = $supervisionRecordsInput
            ->filter(function ($record) {
                if (! is_array($record)) {
                    return false;
                }

                return collect($record)
                    ->only(['level', 'student_name', 'topic', 'year', 'notes'])
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
                    'level' => isset($record['level']) && is_string($record['level'])
                        ? trim($record['level']) ?: null
                        : ($record['level'] ?? null),
                    'student_name' => isset($record['student_name']) && is_string($record['student_name'])
                        ? trim($record['student_name']) ?: null
                        : ($record['student_name'] ?? null),
                    'topic' => isset($record['topic']) && is_string($record['topic'])
                        ? trim($record['topic']) ?: null
                        : ($record['topic'] ?? null),
                    'year' => isset($record['year']) && is_string($record['year'])
                        ? trim($record['year']) ?: null
                        : ($record['year'] ?? null),
                    'notes' => isset($record['notes']) && is_string($record['notes'])
                        ? trim($record['notes']) ?: null
                        : ($record['notes'] ?? null),
                    'position' => $position,
                ];
            });

        $info->supervisionActivities()->delete();

        if ($supervisionRecords->isNotEmpty()) {
            $info->supervisionActivities()->createMany($supervisionRecords->all());
        }
    }
    protected function updateResearchProjects(PersonalInfo $info, Collection $projectsInput): void
    {
        $projects = $projectsInput
            ->filter(function ($record) {
                if (! is_array($record)) {
                    return false;
                }

                return collect($record)
                    ->only(['from_period', 'to_period', 'project_name', 'project_type', 'role', 'budget_million_vnd', 'status', 'notes'])
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
                    'from_period' => isset($record['from_period']) && is_string($record['from_period'])
                        ? trim($record['from_period']) ?: null
                        : ($record['from_period'] ?? null),
                    'to_period' => isset($record['to_period']) && is_string($record['to_period'])
                        ? trim($record['to_period']) ?: null
                        : ($record['to_period'] ?? null),
                    'project_name' => isset($record['project_name']) && is_string($record['project_name'])
                        ? trim($record['project_name']) ?: null
                        : ($record['project_name'] ?? null),
                    'project_type' => isset($record['project_type']) && is_string($record['project_type'])
                        ? trim($record['project_type']) ?: null
                        : ($record['project_type'] ?? null),
                    'role' => isset($record['role']) && is_string($record['role'])
                        ? trim($record['role']) ?: null
                        : ($record['role'] ?? null),
                    'budget_million_vnd' => isset($record['budget_million_vnd']) && $record['budget_million_vnd'] !== ''
                        ? (int) $record['budget_million_vnd']
                        : null,
                    'status' => isset($record['status']) && is_string($record['status'])
                        ? trim($record['status']) ?: null
                        : ($record['status'] ?? null),
                    'notes' => isset($record['notes']) && is_string($record['notes'])
                        ? trim($record['notes']) ?: null
                        : ($record['notes'] ?? null),
                    'position' => $position,
                ];
            });

        $info->researchProjectRecords()->delete();

        if ($projects->isNotEmpty()) {
            $info->researchProjectRecords()->createMany($projects->all());
        }
    }
    
    protected function updateScientificAwards(PersonalInfo $info, Collection $awardsInput): void
    {
        $awards = $awardsInput
            ->filter(function ($record) {
                if (! is_array($record)) {
                    return false;
                }

                return collect($record)
                    ->only(['year', 'award_name', 'organization', 'notes'])
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
                    'award_name' => isset($record['award_name']) && is_string($record['award_name'])
                        ? trim($record['award_name']) ?: null
                        : ($record['award_name'] ?? null),
                    'organization' => isset($record['organization']) && is_string($record['organization'])
                        ? trim($record['organization']) ?: null
                        : ($record['organization'] ?? null),
                    'notes' => isset($record['notes']) && is_string($record['notes'])
                        ? trim($record['notes']) ?: null
                        : ($record['notes'] ?? null),
                    'position' => $position,
                ];
            });

        $info->scientificAwards()->delete();

        if ($awards->isNotEmpty()) {
            $info->scientificAwards()->createMany($awards->all());
        }
    }
    protected function updatePublicationsAndIntellectualProperties(PersonalInfo $info, Collection $publicationsInput, Collection $intellectualPropertyInput): void
    {
        $publications = $publicationsInput
            ->filter(function ($record) {
                if (! is_array($record)) {
                    return false;
                }

                return collect($record)
                    ->only(['year', 'title', 'role', 'publication_type', 'publisher', 'notes'])
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
                    'role' => isset($record['role']) && is_string($record['role'])
                        ? trim($record['role']) ?: null
                        : ($record['role'] ?? null),
                    'publication_type' => isset($record['publication_type']) && is_string($record['publication_type'])
                        ? trim($record['publication_type']) ?: null
                        : ($record['publication_type'] ?? null),
                    'publisher' => isset($record['publisher']) && is_string($record['publisher'])
                        ? trim($record['publisher']) ?: null
                        : ($record['publisher'] ?? null),
                    'notes' => isset($record['notes']) && is_string($record['notes'])
                        ? trim($record['notes']) ?: null
                        : ($record['notes'] ?? null),
                    'position' => $position,
                ];
            });
        $info->scientificPublications()->delete();
        if ($publications->isNotEmpty()) {
            $info->scientificPublications()->createMany($publications->all());
        }

        $intellectualProperties = $intellectualPropertyInput
            ->filter(function ($record) {
                if (! is_array($record)) {
                    return false;
                }
                return collect($record)
                    ->only(['year', 'name', 'ip_type', 'registration_number', 'notes'])
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
                    'name' => isset($record['name']) && is_string($record['name'])
                        ? trim($record['name']) ?: null
                        : ($record['name'] ?? null),
                    'ip_type' => isset($record['ip_type']) && is_string($record['ip_type'])
                        ? trim($record['ip_type']) ?: null
                        : ($record['ip_type'] ?? null),
                    'registration_number' => isset($record['registration_number']) && is_string($record['registration_number'])
                        ? trim($record['registration_number']) ?: null
                        : ($record['registration_number'] ?? null),
                    'notes' => isset($record['notes']) && is_string($record['notes'])
                        ? trim($record['notes']) ?: null
                        : ($record['notes'] ?? null),
                    'position' => $position,
                ];
            });

        $info->intellectualPropertyRecords()->delete();
        if ($intellectualProperties->isNotEmpty()) {
            $info->intellectualPropertyRecords()->createMany($intellectualProperties->all());
        }
    }
    protected function syncFamilyMembers(PersonalInfo $info, Collection $familyMembers): void
    {
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
    }
    protected function syncFamilyAssets(PersonalInfo $info, Collection $familyAssets): void
    {
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
    }
    protected function syncWorkExperiences(PersonalInfo $info, Collection $workExperiences): void
    {
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
    }
    protected function syncPlanningRecords(PersonalInfo $info, Collection $planningRecordsInput): void
    {

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
    }
    protected function syncPersonalHistory(PersonalInfo $info, Collection $historyInput): void
    {
        $normalized = $historyInput
            ->only(['imprisonment_history', 'old_regime_roles', 'foreign_relations'])
            ->map(function ($value) {
                if (is_string($value)) {
                    $trimmed = trim($value);
                    return $trimmed === '' ? null : $trimmed;
                }

                return $value;
            });

        if ($normalized->filter(fn ($value) => filled($value))->isNotEmpty()) {
            $info->personalHistory()->updateOrCreate([], $normalized->all());
        } else {
            $info->personalHistory()->delete();
        }
    }
    protected function syncTrainingRecords(PersonalInfo $info, UpdatePersonalInfoRequest $request): void
    {

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
