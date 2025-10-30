@csrf
@method('PUT')

@php
    $historyValues = [
        'imprisonment_history' => old('personal_history.imprisonment_history', optional($info->personalHistory)->imprisonment_history),
        'old_regime_roles' => old('personal_history.old_regime_roles', optional($info->personalHistory)->old_regime_roles),
        'foreign_relations' => old('personal_history.foreign_relations', optional($info->personalHistory)->foreign_relations),
    ];
    $existingWorkExperiences = $info->workExperiences ?? collect();

    if (! $existingWorkExperiences instanceof \Illuminate\Support\Collection) {
        $existingWorkExperiences = collect($existingWorkExperiences);
    }

    $workExperienceDefaults = $existingWorkExperiences
        ->map(function ($experience) {
            return [
                'position' => $experience->position,
                'from_period' => $experience->from_period,
                'to_period' => $experience->to_period,
                'unit_name' => $experience->unit_name,
                'job_title' => $experience->job_title,
                'notes' => $experience->notes,
            ];
        })
        ->toArray();

    $workExperienceValues = collect(old('work_experiences', $workExperienceDefaults))
        ->map(function ($experience, $index) {
            $experience = is_array($experience) ? $experience : [];

            return [
                'position' => $experience['position'] ?? $index,
                'from_period' => $experience['from_period'] ?? null,
                'to_period' => $experience['to_period'] ?? null,
                'unit_name' => $experience['unit_name'] ?? null,
                'job_title' => $experience['job_title'] ?? null,
                'notes' => $experience['notes'] ?? null,
            ];
        })
        ->values();

    if ($workExperienceValues->isEmpty()) {
        $workExperienceValues = collect([
            [
                'position' => 0,
                'from_period' => null,
                'to_period' => null,
                'unit_name' => null,
                'job_title' => null,
                'notes' => null,
            ],
        ]);
    }

    $planningCategories = \App\Models\PlanningRecord::categories();

    $existingPlanningRecords = $info->planningRecords ?? collect();

    if (! $existingPlanningRecords instanceof \Illuminate\Support\Collection) {
        $existingPlanningRecords = collect($existingPlanningRecords);
    }

    $planningDefaults = $existingPlanningRecords
        ->groupBy('category')
        ->map(function ($records) {
            return $records
                ->sortBy('position')
                ->values()
                ->map(function ($record, $index) {
                    return [
                        'position' => $record->position ?? $index,
                        'position_title' => $record->position_title,
                        'stage' => $record->stage,
                        'status' => $record->status,
                        'notes' => $record->notes,
                    ];
                })
                ->toArray();
        });

    $planningValues = collect($planningCategories)
        ->mapWithKeys(function ($label, $category) use ($planningDefaults) {
            $defaults = $planningDefaults->get($category, []);

            $values = collect(old("planning_records.$category", $defaults))
                ->map(function ($record, $index) {
                    $record = is_array($record) ? $record : [];

                    return [
                        'position' => $record['position'] ?? $index,
                        'position_title' => $record['position_title'] ?? null,
                        'stage' => $record['stage'] ?? null,
                        'status' => $record['status'] ?? null,
                        'notes' => $record['notes'] ?? null,
                    ];
                })
                ->values();

            if ($values->isEmpty()) {
                $values = collect([
                    [
                        'position' => 0,
                        'position_title' => null,
                        'stage' => null,
                        'status' => null,
                        'notes' => null,
                    ],
                ]);
            }

            return [$category => $values];
        });

@endphp

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Thông tin cá nhân</h5>
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="full_name">Họ và tên</label>
            <input type="text" id="full_name" name="full_name" class="form-control @error('full_name') is-invalid @enderror" value="{{ old('full_name', $info->full_name) }}" required>
            @error('full_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="alternate_name">Tên gọi khác</label>
            <input type="text" id="alternate_name" name="alternate_name" class="form-control @error('alternate_name') is-invalid @enderror" value="{{ old('alternate_name', $info->alternate_name) }}">
            @error('alternate_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="birth_date">Ngày sinh</label>
            <input type="date" id="birth_date" name="birth_date" class="form-control @error('birth_date') is-invalid @enderror" value="{{ old('birth_date', optional($info->birth_date)->format('Y-m-d')) }}">
            @error('birth_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="gender">Giới tính</label>
            <select id="gender" name="gender" class="form-select @error('gender') is-invalid @enderror">
                <option value="">-- Chọn --</option>
                <option value="male" @selected(old('gender', $info->gender) === 'male')>Nam</option>
                <option value="female" @selected(old('gender', $info->gender) === 'female')>Nữ</option>
                <option value="other" @selected(old('gender', $info->gender) === 'other')>Khác</option>
            </select>
            @error('gender')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="birth_place">Nơi sinh</label>
            <input type="text" id="birth_place" name="birth_place" class="form-control @error('birth_place') is-invalid @enderror" value="{{ old('birth_place', $info->birth_place) }}">
            @error('birth_place')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="hometown">Quê quán</label>
            <input type="text" id="hometown" name="hometown" class="form-control @error('hometown') is-invalid @enderror" value="{{ old('hometown', $info->hometown) }}">
            @error('hometown')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="residence">Nơi ở hiện tại</label>
            <input type="text" id="residence" name="residence" class="form-control @error('residence') is-invalid @enderror" value="{{ old('residence', $info->residence) }}">
            @error('residence')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="ethnicity">Dân tộc</label>
            <input type="text" id="ethnicity" name="ethnicity" class="form-control @error('ethnicity') is-invalid @enderror" value="{{ old('ethnicity', $info->ethnicity) }}">
            @error('ethnicity')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="religion">Tôn giáo</label>
            <input type="text" id="religion" name="religion" class="form-control @error('religion') is-invalid @enderror" value="{{ old('religion', $info->religion) }}">
            @error('religion')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="cccd_number">Số CCCD</label>
            <input type="text" id="cccd_number" name="cccd_number" class="form-control @error('cccd_number') is-invalid @enderror" value="{{ old('cccd_number', $info->cccd_number) }}">
            @error('cccd_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="cccd_issued_date">Ngày cấp CCCD</label>
            <input type="date" id="cccd_issued_date" name="cccd_issued_date" class="form-control @error('cccd_issued_date') is-invalid @enderror" value="{{ old('cccd_issued_date', optional($info->cccd_issued_date)->format('Y-m-d')) }}">
            @error('cccd_issued_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="tax_code">Mã số thuế</label>
            <input type="text" id="tax_code" name="tax_code" class="form-control @error('tax_code') is-invalid @enderror" value="{{ old('tax_code', $info->tax_code) }}">
            @error('tax_code')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="phone_number">Số điện thoại</label>
            <input type="text" id="phone_number" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror" value="{{ old('phone_number', $info->phone_number) }}">
            @error('phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="email">Email</label>
            <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $info->email) }}">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="avatar">Ảnh đại diện</label>
            <input type="file" id="avatar" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
            @error('avatar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="health_insurance_number">Số BHYT</label>
            <input type="text" id="health_insurance_number" name="health_insurance_number" class="form-control @error('health_insurance_number') is-invalid @enderror" value="{{ old('health_insurance_number', $info->health_insurance_number) }}">
            @error('health_insurance_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="social_insurance_number">Số BHXH</label>
            <input type="text" id="social_insurance_number" name="social_insurance_number" class="form-control @error('social_insurance_number') is-invalid @enderror" value="{{ old('social_insurance_number', $info->social_insurance_number) }}">
            @error('social_insurance_number')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Thông tin công tác</h5>
    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="employment_start_date">Ngày vào cơ quan</label>
            <input type="date" id="employment_start_date" name="employment_start_date" class="form-control @error('employment_start_date') is-invalid @enderror" value="{{ old('employment_start_date', optional($info->employment_start_date)->format('Y-m-d')) }}">
            @error('employment_start_date')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="organization_name">Tên cơ quan</label>
            <input type="text" id="organization_name" name="organization_name" class="form-control @error('organization_name') is-invalid @enderror" value="{{ old('organization_name', $info->organization_name) }}">
            @error('organization_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="contract_type">Loại hợp đồng</label>
            <input type="text" id="contract_type" name="contract_type" class="form-control @error('contract_type') is-invalid @enderror" value="{{ old('contract_type', $info->contract_type) }}">
            @error('contract_type')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="main_job_title">Công việc chính/Chức vụ cao nhất</label>
            <input type="text" id="main_job_title" name="main_job_title" class="form-control @error('main_job_title') is-invalid @enderror" value="{{ old('main_job_title', $info->main_job_title) }}">
            @error('main_job_title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="professional_title">Chức danh nghề nghiệp/Ngạch viên chức</label>
            <input type="text" id="professional_title" name="professional_title" class="form-control @error('professional_title') is-invalid @enderror" value="{{ old('professional_title', $info->professional_title) }}">
            @error('professional_title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="expertise">Sở trường công tác</label>
            <input type="text" id="expertise" name="expertise" class="form-control @error('expertise') is-invalid @enderror" value="{{ old('expertise', $info->expertise) }}">
            @error('expertise')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="previous_job">Nghề nghiệp trước khi được tuyển dụng</label>
            <input type="text" id="previous_job" name="previous_job" class="form-control @error('previous_job') is-invalid @enderror" value="{{ old('previous_job', $info->previous_job) }}">
            @error('previous_job')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Quy hoạch chức danh</h5>
    <div class="vstack gap-4">
        @foreach ($planningCategories as $category => $label)
            @php
                $entries = $planningValues->get($category, collect());
                $categoryHint = $category === \App\Models\PlanningRecord::CATEGORY_GOVERNMENT
                    ? 'Cập nhật thông tin quy hoạch trong hệ thống chính quyền'
                    : 'Cập nhật thông tin quy hoạch trong tổ chức Đảng';
            @endphp
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="card-title mb-0">{{ $label }}</h4>
                        <p class="text-muted small mb-0">{{ $categoryHint }}</p>
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" data-add-planning-row data-group="{{ $category }}">
                        <i class="bi bi-plus-circle me-1"></i>
                        Thêm dòng
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="text-center" style="width: 60px;">STT</th>
                                    <th>Đối tượng quy hoạch</th>
                                    <th style="width: 180px;">Giai đoạn</th>
                                    <th style="width: 180px;">Trạng thái</th>
                                    <th>Ghi chú</th>
                                    <th class="text-center" style="width: 50px;"></th>
                                </tr>
                            </thead>
                            <tbody data-planning-group="{{ $category }}">
                                @foreach ($entries as $index => $record)
                                    @php $baseKey = "planning_records.$category.$index"; @endphp
                                    <tr>
                                        <td class="text-center align-middle">
                                            <span class="planning-row-order">{{ $loop->iteration }}</span>
                                            <input type="hidden" data-field="position" name="planning_records[{{ $category }}][{{ $index }}][position]" value="{{ $record['position'] ?? $loop->index }}">
                                        </td>
                                        <td>
                                            @php $field = $baseKey . '.position_title'; @endphp
                                            <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="position_title" name="planning_records[{{ $category }}][{{ $index }}][position_title]" value="{{ $record['position_title'] ?? '' }}">
                                            @error($field)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            @php $field = $baseKey . '.stage'; @endphp
                                            <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="stage" name="planning_records[{{ $category }}][{{ $index }}][stage]" value="{{ $record['stage'] ?? '' }}">
                                            @error($field)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            @php $field = $baseKey . '.status'; @endphp
                                            <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="status" name="planning_records[{{ $category }}][{{ $index }}][status]" value="{{ $record['status'] ?? '' }}">
                                            @error($field)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td>
                                            @php $field = $baseKey . '.notes'; @endphp
                                            <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="notes" name="planning_records[{{ $category }}][{{ $index }}][notes]" value="{{ $record['notes'] ?? '' }}">
                                            @error($field)
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </td>
                                        <td class="text-center align-middle">
                                            <button type="button" class="btn btn-link text-danger p-0" data-remove-planning-row>
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Quá trình công tác</h5>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-briefcase-fill text-primary me-2"></i>
                <span class="fw-semibold">Các đơn vị đã công tác</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-work-row>
                <i class="bi bi-plus-circle me-1"></i>
                Thêm dòng
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 60px;">STT</th>
                            <th style="width: 160px;">Từ</th>
                            <th style="width: 160px;">Đến</th>
                            <th>Đơn vị</th>
                            <th>Chức vụ</th>
                            <th>Ghi chú</th>
                            <th class="text-center" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody data-work-experience-group>
                        @foreach ($workExperienceValues as $index => $experience)
                            @php $baseKey = "work_experiences.$index"; @endphp
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="work-row-order">{{ $loop->iteration }}</span>
                                    <input type="hidden" data-field="position" name="work_experiences[{{ $index }}][position]" value="{{ $experience['position'] ?? $loop->index }}">
                                </td>
                                <td>
                                    @php $field = $baseKey . '.from_period'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="from_period" name="work_experiences[{{ $index }}][from_period]" value="{{ $experience['from_period'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.to_period'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="to_period" name="work_experiences[{{ $index }}][to_period]" value="{{ $experience['to_period'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.unit_name'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="unit_name" name="work_experiences[{{ $index }}][unit_name]" value="{{ $experience['unit_name'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.job_title'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="job_title" name="work_experiences[{{ $index }}][job_title]" value="{{ $experience['job_title'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.notes'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="notes" name="work_experiences[{{ $index }}][notes]" value="{{ $experience['notes'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-work-row>
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Tổ chức - Đảng - Đoàn</h5>
    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="youth_union_joined_at">Ngày vào Đoàn TNCS</label>
            <input type="date" id="youth_union_joined_at" name="youth_union_joined_at" class="form-control @error('youth_union_joined_at') is-invalid @enderror" value="{{ old('youth_union_joined_at', optional($info->youth_union_joined_at)->format('Y-m-d')) }}">
            @error('youth_union_joined_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="trade_union_joined_at">Ngày gia nhập Công đoàn</label>
            <input type="date" id="trade_union_joined_at" name="trade_union_joined_at" class="form-control @error('trade_union_joined_at') is-invalid @enderror" value="{{ old('trade_union_joined_at', optional($info->trade_union_joined_at)->format('Y-m-d')) }}">
            @error('trade_union_joined_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="communist_party_joined_at">Ngày vào Đảng CSVN</label>
            <input type="date" id="communist_party_joined_at" name="communist_party_joined_at" class="form-control @error('communist_party_joined_at') is-invalid @enderror" value="{{ old('communist_party_joined_at', optional($info->communist_party_joined_at)->format('Y-m-d')) }}">
            @error('communist_party_joined_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="army_enlisted_at">Ngày nhập ngũ</label>
            <input type="date" id="army_enlisted_at" name="army_enlisted_at" class="form-control @error('army_enlisted_at') is-invalid @enderror" value="{{ old('army_enlisted_at', optional($info->army_enlisted_at)->format('Y-m-d')) }}">
            @error('army_enlisted_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="army_discharged_at">Ngày xuất ngũ</label>
            <input type="date" id="army_discharged_at" name="army_discharged_at" class="form-control @error('army_discharged_at') is-invalid @enderror" value="{{ old('army_discharged_at', optional($info->army_discharged_at)->format('Y-m-d')) }}">
            @error('army_discharged_at')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="highest_army_rank">Quân hàm cao nhất</label>
            <input type="text" id="highest_army_rank" name="highest_army_rank" class="form-control @error('highest_army_rank') is-invalid @enderror" value="{{ old('highest_army_rank', $info->highest_army_rank) }}">
            @error('highest_army_rank')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Lịch sử bản thân</h5>
    <div class="row g-4">
        <div class="col-12">
            <label class="form-label fw-semibold" for="personal_history_imprisonment">Khai ai, bị bắt, bị tù</label>
            <textarea
                id="personal_history_imprisonment"
                name="personal_history[imprisonment_history]"
                rows="4"
                class="form-control @error('personal_history.imprisonment_history') is-invalid @enderror">{{ $historyValues['imprisonment_history'] }}</textarea>
            @error('personal_history.imprisonment_history')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold" for="personal_history_old_regime">Chức vụ trong chế độ cũ</label>
            <textarea
                id="personal_history_old_regime"
                name="personal_history[old_regime_roles]"
                rows="4"
                class="form-control @error('personal_history.old_regime_roles') is-invalid @enderror">{{ $historyValues['old_regime_roles'] }}</textarea>
            @error('personal_history.old_regime_roles')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-12">
            <label class="form-label fw-semibold" for="personal_history_foreign_relations">Quan hệ với tổ chức, cá nhân nước ngoài</label>
            <textarea
                id="personal_history_foreign_relations"
                name="personal_history[foreign_relations]"
                rows="4"
                class="form-control @error('personal_history.foreign_relations') is-invalid @enderror">{{ $historyValues['foreign_relations'] }}</textarea>
            @error('personal_history.foreign_relations')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Học vấn và danh hiệu</h5>
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="general_education_level">Trình độ giáo dục phổ thông</label>
            <input type="text" id="general_education_level" name="general_education_level" class="form-control @error('general_education_level') is-invalid @enderror" value="{{ old('general_education_level', $info->general_education_level) }}">
            @error('general_education_level')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="highest_academic_level">Trình độ chuyên môn cao nhất</label>
            <input type="text" id="highest_academic_level" name="highest_academic_level" class="form-control @error('highest_academic_level') is-invalid @enderror" value="{{ old('highest_academic_level', $info->highest_academic_level) }}">
            @error('highest_academic_level')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="highest_academic_year">Năm đạt được</label>
            <input type="number" min="1900" max="2100" id="highest_academic_year" name="highest_academic_year" class="form-control @error('highest_academic_year') is-invalid @enderror" value="{{ old('highest_academic_year', $info->highest_academic_year) }}">
            @error('highest_academic_year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-8">
            <label class="form-label fw-semibold" for="graduation_major">Ngành tốt nghiệp</label>
            <input type="text" id="graduation_major" name="graduation_major" class="form-control @error('graduation_major') is-invalid @enderror" value="{{ old('graduation_major', $info->graduation_major) }}">
            @error('graduation_major')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="state_honors">Danh hiệu nhà nước phong tặng</label>
            <input type="text" id="state_honors" name="state_honors" class="form-control @error('state_honors') is-invalid @enderror" value="{{ old('state_honors', $info->state_honors) }}">
            @error('state_honors')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="state_honors_year">Năm phong</label>
            <input type="number" min="1900" max="2100" id="state_honors_year" name="state_honors_year" class="form-control @error('state_honors_year') is-invalid @enderror" value="{{ old('state_honors_year', $info->state_honors_year) }}">
            @error('state_honors_year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="academic_title">Học hàm</label>
            <input type="text" id="academic_title" name="academic_title" class="form-control @error('academic_title') is-invalid @enderror" value="{{ old('academic_title', $info->academic_title) }}">
            @error('academic_title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="academic_title_year">Năm học hàm</label>
            <input type="number" min="1900" max="2100" id="academic_title_year" name="academic_title_year" class="form-control @error('academic_title_year') is-invalid @enderror" value="{{ old('academic_title_year', $info->academic_title_year) }}">
            @error('academic_title_year')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-3">
            <label class="form-label fw-semibold" for="professor_council">Hội đồng giáo sư</label>
            <input type="text" id="professor_council" name="professor_council" class="form-control @error('professor_council') is-invalid @enderror" value="{{ old('professor_council', $info->professor_council) }}">
            @error('professor_council')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Sức khỏe và thể chất</h5>
    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="health_status">Tình trạng sức khoẻ</label>
            <input type="text" id="health_status" name="health_status" class="form-control @error('health_status') is-invalid @enderror" value="{{ old('health_status', $info->health_status) }}">
            @error('health_status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-4">
            <label class="form-label fw-semibold" for="blood_group">Nhóm máu</label>
            <input type="text" id="blood_group" name="blood_group" class="form-control @error('blood_group') is-invalid @enderror" value="{{ old('blood_group', $info->blood_group) }}">
            @error('blood_group')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold" for="height">Chiều cao</label>
            <input type="text" id="height" name="height" class="form-control @error('height') is-invalid @enderror" value="{{ old('height', $info->height) }}">
            @error('height')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-2">
            <label class="form-label fw-semibold" for="weight">Cân nặng</label>
            <input type="text" id="weight" name="weight" class="form-control @error('weight') is-invalid @enderror" value="{{ old('weight', $info->weight) }}">
            @error('weight')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

<div class="mb-4">
    <h5 class="fw-bold text-primary mb-3">Lĩnh vực chuyên môn</h5>
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="teaching_field">Lĩnh vực giảng dạy</label>
            <input type="text" id="teaching_field" name="teaching_field" class="form-control @error('teaching_field') is-invalid @enderror" value="{{ old('teaching_field', $info->teaching_field) }}">
            @error('teaching_field')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6">
            <label class="form-label fw-semibold" for="research_field">Lĩnh vực nghiên cứu</label>
            <input type="text" id="research_field" name="research_field" class="form-control @error('research_field') is-invalid @enderror" value="{{ old('research_field', $info->research_field) }}">
            @error('research_field')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

@php
    $existingTrainingRecords = $info->trainingRecords ?? collect();

    if (! $existingTrainingRecords instanceof \Illuminate\Support\Collection) {
        $existingTrainingRecords = collect($existingTrainingRecords);
    }

    $trainingConfigs = [
        'formal_training' => [
            'category' => \App\Models\TrainingRecord::CATEGORY_FORMAL_TRAINING,
            'fields' => ['timeframe', 'institution', 'major', 'training_form', 'qualification'],
        ],
        'professional_development' => [
            'category' => \App\Models\TrainingRecord::CATEGORY_PROFESSIONAL_DEVELOPMENT,
            'fields' => ['program_name', 'certificate', 'institution', 'year_awarded'],
        ],
        'management_training' => [
            'category' => \App\Models\TrainingRecord::CATEGORY_MANAGEMENT_TRAINING,
            'fields' => ['program_name', 'certificate', 'institution', 'year_awarded'],
        ],
        'political_theory' => [
            'category' => \App\Models\TrainingRecord::CATEGORY_POLITICAL_THEORY,
            'fields' => ['level', 'institution', 'year_awarded'],
        ],
        'national_defense' => [
            'category' => \App\Models\TrainingRecord::CATEGORY_NATIONAL_DEFENSE,
            'fields' => ['program_name', 'institution', 'year_awarded'],
        ],
        'foreign_language' => [
            'category' => \App\Models\TrainingRecord::CATEGORY_FOREIGN_LANGUAGE,
            'fields' => ['language', 'level', 'certificate', 'institution', 'year_awarded'],
        ],
        'informatics' => [
            'category' => \App\Models\TrainingRecord::CATEGORY_INFORMATICS,
            'fields' => ['program_name', 'level', 'certificate', 'institution', 'year_awarded'],
        ],
    ];

    $trainingValues = [];

    foreach ($trainingConfigs as $key => $config) {
        $records = $existingTrainingRecords
            ->where('category', $config['category'])
            ->values()
            ->map(function ($record) use ($config) {
                $entry = ['position' => $record->position];

                foreach ($config['fields'] as $field) {
                    $entry[$field] = $record->{$field};
                }

                return $entry;
            })
            ->toArray();

        $oldRecords = old($key, $records);

        $normalized = collect($oldRecords)
            ->map(function ($record, $index) use ($config) {
                $record = is_array($record) ? $record : [];
                $entry = ['position' => $record['position'] ?? $index];

                foreach ($config['fields'] as $field) {
                    $entry[$field] = $record[$field] ?? null;
                }

                return $entry;
            })
            ->values();

        if ($normalized->isEmpty()) {
            $entry = ['position' => 0];

            foreach ($config['fields'] as $field) {
                $entry[$field] = null;
            }

            $normalized = collect([$entry]);
        }

        $trainingValues[$key] = $normalized;
    }
@endphp

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Quá trình đào tạo &amp; bồi dưỡng</h5>

    @php $formalTrainingRows = $trainingValues['formal_training']; @endphp
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-mortarboard-fill text-primary me-2"></i>
                <span class="fw-semibold">Quá trình đào tạo (từ trung cấp trở lên)</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-training-row data-group="formal_training">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm dòng
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th style="width: 140px;">Thời gian</th>
                            <th>Cơ sở đào tạo</th>
                            <th>Chuyên ngành</th>
                            <th>Hình thức</th>
                            <th>Văn bằng</th>
                            <th class="text-center" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody data-training-group="formal_training">
                        @foreach ($formalTrainingRows as $index => $row)
                            @php $baseKey = "formal_training.$index"; @endphp
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="training-row-order">{{ $loop->iteration }}</span>
                                    <input type="hidden" data-field="position" name="formal_training[{{ $index }}][position]" value="{{ $row['position'] ?? $loop->index }}">
                                </td>
                                <td>
                                    @php $field = $baseKey . '.timeframe'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="timeframe" name="formal_training[{{ $index }}][timeframe]" value="{{ $row['timeframe'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.institution'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="institution" name="formal_training[{{ $index }}][institution]" value="{{ $row['institution'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.major'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="major" name="formal_training[{{ $index }}][major]" value="{{ $row['major'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.training_form'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="training_form" name="formal_training[{{ $index }}][training_form]" value="{{ $row['training_form'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.qualification'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="qualification" name="formal_training[{{ $index }}][qualification]" value="{{ $row['qualification'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @php $professionalRows = $trainingValues['professional_development']; @endphp
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-journal-check text-success me-2"></i>
                <span class="fw-semibold">Bồi dưỡng nghiệp vụ</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-training-row data-group="professional_development">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm dòng
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th>Chương trình</th>
                            <th>Chứng chỉ</th>
                            <th>Cơ sở đào tạo</th>
                            <th class="text-center" style="width: 120px;">Năm đạt</th>
                            <th class="text-center" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody data-training-group="professional_development">
                        @foreach ($professionalRows as $index => $row)
                            @php $baseKey = "professional_development.$index"; @endphp
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="training-row-order">{{ $loop->iteration }}</span>
                                    <input type="hidden" data-field="position" name="professional_development[{{ $index }}][position]" value="{{ $row['position'] ?? $loop->index }}">
                                </td>
                                <td>
                                    @php $field = $baseKey . '.program_name'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="program_name" name="professional_development[{{ $index }}][program_name]" value="{{ $row['program_name'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.certificate'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="certificate" name="professional_development[{{ $index }}][certificate]" value="{{ $row['certificate'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.institution'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="institution" name="professional_development[{{ $index }}][institution]" value="{{ $row['institution'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.year_awarded'; @endphp
                                    <input type="number" min="1900" max="2100" class="form-control text-center {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="year_awarded" name="professional_development[{{ $index }}][year_awarded]" value="{{ $row['year_awarded'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @php $managementRows = $trainingValues['management_training']; @endphp
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-briefcase text-primary me-2"></i>
                <span class="fw-semibold">Bồi dưỡng quản lý</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-training-row data-group="management_training">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm dòng
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th>Chương trình</th>
                            <th>Chứng chỉ</th>
                            <th>Cơ sở đào tạo</th>
                            <th class="text-center" style="width: 120px;">Năm đạt</th>
                            <th class="text-center" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody data-training-group="management_training">
                        @foreach ($managementRows as $index => $row)
                            @php $baseKey = "management_training.$index"; @endphp
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="training-row-order">{{ $loop->iteration }}</span>
                                    <input type="hidden" data-field="position" name="management_training[{{ $index }}][position]" value="{{ $row['position'] ?? $loop->index }}">
                                </td>
                                <td>
                                    @php $field = $baseKey . '.program_name'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="program_name" name="management_training[{{ $index }}][program_name]" value="{{ $row['program_name'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.certificate'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="certificate" name="management_training[{{ $index }}][certificate]" value="{{ $row['certificate'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.institution'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="institution" name="management_training[{{ $index }}][institution]" value="{{ $row['institution'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.year_awarded'; @endphp
                                    <input type="number" min="1900" max="2100" class="form-control text-center {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="year_awarded" name="management_training[{{ $index }}][year_awarded]" value="{{ $row['year_awarded'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @php $politicalRows = $trainingValues['political_theory']; @endphp
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-award-fill text-danger me-2"></i>
                <span class="fw-semibold">Lý luận chính trị</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-training-row data-group="political_theory">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm dòng
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th>Trình độ</th>
                            <th>Cơ sở đào tạo</th>
                            <th class="text-center" style="width: 120px;">Năm tốt nghiệp</th>
                            <th class="text-center" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody data-training-group="political_theory">
                        @foreach ($politicalRows as $index => $row)
                            @php $baseKey = "political_theory.$index"; @endphp
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="training-row-order">{{ $loop->iteration }}</span>
                                    <input type="hidden" data-field="position" name="political_theory[{{ $index }}][position]" value="{{ $row['position'] ?? $loop->index }}">
                                </td>
                                <td>
                                    @php $field = $baseKey . '.level'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="level" name="political_theory[{{ $index }}][level]" value="{{ $row['level'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.institution'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="institution" name="political_theory[{{ $index }}][institution]" value="{{ $row['institution'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.year_awarded'; @endphp
                                    <input type="number" min="1900" max="2100" class="form-control text-center {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="year_awarded" name="political_theory[{{ $index }}][year_awarded]" value="{{ $row['year_awarded'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @php $defenseRows = $trainingValues['national_defense']; @endphp
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-shield-lock-fill text-success me-2"></i>
                <span class="fw-semibold">An ninh quốc phòng</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-training-row data-group="national_defense">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm dòng
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th>Chương trình</th>
                            <th>Cơ sở đào tạo</th>
                            <th class="text-center" style="width: 120px;">Năm đạt</th>
                            <th class="text-center" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody data-training-group="national_defense">
                        @foreach ($defenseRows as $index => $row)
                            @php $baseKey = "national_defense.$index"; @endphp
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="training-row-order">{{ $loop->iteration }}</span>
                                    <input type="hidden" data-field="position" name="national_defense[{{ $index }}][position]" value="{{ $row['position'] ?? $loop->index }}">
                                </td>
                                <td>
                                    @php $field = $baseKey . '.program_name'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="program_name" name="national_defense[{{ $index }}][program_name]" value="{{ $row['program_name'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.institution'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="institution" name="national_defense[{{ $index }}][institution]" value="{{ $row['institution'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.year_awarded'; @endphp
                                    <input type="number" min="1900" max="2100" class="form-control text-center {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="year_awarded" name="national_defense[{{ $index }}][year_awarded]" value="{{ $row['year_awarded'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @php $languageRows = $trainingValues['foreign_language']; @endphp
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-translate text-primary me-2"></i>
                <span class="fw-semibold">Ngoại ngữ</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-training-row data-group="foreign_language">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm dòng
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th>Ngoại ngữ</th>
                            <th>Trình độ</th>
                            <th>Chứng chỉ</th>
                            <th>Cơ sở đào tạo</th>
                            <th class="text-center" style="width: 120px;">Năm đạt</th>
                            <th class="text-center" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody data-training-group="foreign_language">
                        @foreach ($languageRows as $index => $row)
                            @php $baseKey = "foreign_language.$index"; @endphp
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="training-row-order">{{ $loop->iteration }}</span>
                                    <input type="hidden" data-field="position" name="foreign_language[{{ $index }}][position]" value="{{ $row['position'] ?? $loop->index }}">
                                </td>
                                <td>
                                    @php $field = $baseKey . '.language'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="language" name="foreign_language[{{ $index }}][language]" value="{{ $row['language'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.level'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="level" name="foreign_language[{{ $index }}][level]" value="{{ $row['level'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.certificate'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="certificate" name="foreign_language[{{ $index }}][certificate]" value="{{ $row['certificate'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.institution'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="institution" name="foreign_language[{{ $index }}][institution]" value="{{ $row['institution'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.year_awarded'; @endphp
                                    <input type="number" min="1900" max="2100" class="form-control text-center {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="year_awarded" name="foreign_language[{{ $index }}][year_awarded]" value="{{ $row['year_awarded'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @php $informaticsRows = $trainingValues['informatics']; @endphp
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-pc-display-horizontal text-secondary me-2"></i>
                <span class="fw-semibold">Tin học</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-training-row data-group="informatics">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm dòng
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 50px;">STT</th>
                            <th>Chương trình/Kỹ năng</th>
                            <th>Trình độ</th>
                            <th>Chứng chỉ</th>
                            <th>Cơ sở đào tạo</th>
                            <th class="text-center" style="width: 120px;">Năm đạt</th>
                            <th class="text-center" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody data-training-group="informatics">
                        @foreach ($informaticsRows as $index => $row)
                            @php $baseKey = "informatics.$index"; @endphp
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="training-row-order">{{ $loop->iteration }}</span>
                                    <input type="hidden" data-field="position" name="informatics[{{ $index }}][position]" value="{{ $row['position'] ?? $loop->index }}">
                                </td>
                                <td>
                                    @php $field = $baseKey . '.program_name'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="program_name" name="informatics[{{ $index }}][program_name]" value="{{ $row['program_name'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.level'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="level" name="informatics[{{ $index }}][level]" value="{{ $row['level'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.certificate'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="certificate" name="informatics[{{ $index }}][certificate]" value="{{ $row['certificate'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.institution'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="institution" name="informatics[{{ $index }}][institution]" value="{{ $row['institution'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.year_awarded'; @endphp
                                    <input type="number" min="1900" max="2100" class="form-control text-center {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="year_awarded" name="informatics[{{ $index }}][year_awarded]" value="{{ $row['year_awarded'] ?? '' }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@php
    $selfFamily = old('family_members.self');
    $spouseFamily = old('family_members.spouse');
    $familyAssets = old('family_assets');

    if (is_null($selfFamily)) {
        $selfFamily = $info->immediateFamilyMembers
            ->map(fn($member) => [
                'relationship' => $member->relationship,
                'full_name' => $member->full_name,
                'birth_year' => $member->birth_year,
                'hometown' => $member->hometown,
                'residence' => $member->residence,
                'occupation' => $member->occupation,
                'workplace' => $member->workplace,
                'notes' => $member->notes,
                'position' => $member->position,
            ])
            ->toArray();
    }

    if (is_null($spouseFamily)) {
        $spouseFamily = $info->spouseFamilyMembers
            ->map(fn($member) => [
                'relationship' => $member->relationship,
                'full_name' => $member->full_name,
                'birth_year' => $member->birth_year,
                'hometown' => $member->hometown,
                'residence' => $member->residence,
                'occupation' => $member->occupation,
                'workplace' => $member->workplace,
                'notes' => $member->notes,
                'position' => $member->position,
            ])
            ->toArray();
    }

    if (is_null($familyAssets)) {
        $familyAssets = $info->familyAssets
            ->map(fn($asset) => [
                'asset_description' => $asset->asset_description,
                'asset_address' => $asset->asset_address,
                'notes' => $asset->notes,
                'position' => $asset->position,
            ])
            ->toArray();
    }

    if (empty($selfFamily)) {
        $selfFamily = [[]];
    }

    if (empty($spouseFamily)) {
        $spouseFamily = [[]];
    }

    if (empty($familyAssets)) {
        $familyAssets = [[]];
    }
@endphp

<div class="mb-5">
    <h5 class="fw-bold text-primary mb-3">Gia đình</h5>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-people-fill text-primary me-2"></i>
                <span class="fw-semibold">Thành viên gia đình</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-family-row data-group="self">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm thành viên
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 40px;">STT</th>
                            <th>Quan hệ</th>
                            <th>Họ và tên</th>
                            <th class="text-center" style="width: 110px;">Năm sinh</th>
                            <th>Quê quán</th>
                            <th>Nơi ở hiện nay</th>
                            <th>Nghề nghiệp</th>
                            <th>Đơn vị công tác</th>
                            <th>Ghi chú</th>
                            <th class="text-center" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody data-family-group="self">
                        @foreach ($selfFamily as $index => $member)
                            @php
                                $baseKey = "family_members.self.$index";
                            @endphp
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="family-row-order">{{ $loop->iteration }}</span>
                                    <input type="hidden" data-field="position" name="family_members[self][{{ $index }}][position]" value="{{ data_get($member, 'position', $loop->index) }}">
                                </td>
                                <td>
                                    @php $field = $baseKey . '.relationship'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="relationship" name="family_members[self][{{ $index }}][relationship]" value="{{ data_get($member, 'relationship') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.full_name'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="full_name" name="family_members[self][{{ $index }}][full_name]" value="{{ data_get($member, 'full_name') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.birth_year'; @endphp
                                    <input type="number" min="1900" max="2100" class="form-control text-center {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="birth_year" name="family_members[self][{{ $index }}][birth_year]" value="{{ data_get($member, 'birth_year') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.hometown'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="hometown" name="family_members[self][{{ $index }}][hometown]" value="{{ data_get($member, 'hometown') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.residence'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="residence" name="family_members[self][{{ $index }}][residence]" value="{{ data_get($member, 'residence') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.occupation'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="occupation" name="family_members[self][{{ $index }}][occupation]" value="{{ data_get($member, 'occupation') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.workplace'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="workplace" name="family_members[self][{{ $index }}][workplace]" value="{{ data_get($member, 'workplace') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.notes'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="notes" name="family_members[self][{{ $index }}][notes]" value="{{ data_get($member, 'notes') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-family-row>
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-heart-fill text-danger me-2"></i>
                <span class="fw-semibold">Thành viên gia đình vợ/chồng</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-family-row data-group="spouse">
                <i class="bi bi-plus-circle me-1"></i>
                Thêm thành viên
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 40px;">STT</th>
                            <th>Quan hệ</th>
                            <th>Họ và tên</th>
                            <th class="text-center" style="width: 110px;">Năm sinh</th>
                            <th>Quê quán</th>
                            <th>Nơi ở hiện nay</th>
                            <th>Nghề nghiệp</th>
                            <th>Đơn vị công tác</th>
                            <th>Ghi chú</th>
                            <th class="text-center" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody data-family-group="spouse">
                        @foreach ($spouseFamily as $index => $member)
                            @php
                                $baseKey = "family_members.spouse.$index";
                            @endphp
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="family-row-order">{{ $loop->iteration }}</span>
                                    <input type="hidden" data-field="position" name="family_members[spouse][{{ $index }}][position]" value="{{ data_get($member, 'position', $loop->index) }}">
                                </td>
                                <td>
                                    @php $field = $baseKey . '.relationship'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="relationship" name="family_members[spouse][{{ $index }}][relationship]" value="{{ data_get($member, 'relationship') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.full_name'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="full_name" name="family_members[spouse][{{ $index }}][full_name]" value="{{ data_get($member, 'full_name') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.birth_year'; @endphp
                                    <input type="number" min="1900" max="2100" class="form-control text-center {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="birth_year" name="family_members[spouse][{{ $index }}][birth_year]" value="{{ data_get($member, 'birth_year') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.hometown'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="hometown" name="family_members[spouse][{{ $index }}][hometown]" value="{{ data_get($member, 'hometown') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.residence'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="residence" name="family_members[spouse][{{ $index }}][residence]" value="{{ data_get($member, 'residence') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.occupation'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="occupation" name="family_members[spouse][{{ $index }}][occupation]" value="{{ data_get($member, 'occupation') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.workplace'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="workplace" name="family_members[spouse][{{ $index }}][workplace]" value="{{ data_get($member, 'workplace') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.notes'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="notes" name="family_members[spouse][{{ $index }}][notes]" value="{{ data_get($member, 'notes') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-family-row>
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-house-door-fill text-success me-2"></i>
                <span class="fw-semibold">Tài sản nhà đất</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-asset-row>
                <i class="bi bi-plus-circle me-1"></i>
                Thêm tài sản
            </button>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" style="width: 40px;">STT</th>
                            <th>Loại tài sản - Diện tích</th>
                            <th>Địa chỉ</th>
                            <th>Giấy CN quyền sở hữu</th>
                            <th class="text-center" style="width: 50px;"></th>
                        </tr>
                    </thead>
                    <tbody data-family-asset-group>
                        @foreach ($familyAssets as $index => $asset)
                            @php
                                $baseKey = "family_assets.$index";
                            @endphp
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="family-asset-row-order">{{ $loop->iteration }}</span>
                                    <input type="hidden" data-field="position" name="family_assets[{{ $index }}][position]" value="{{ data_get($asset, 'position', $loop->index) }}">
                                </td>
                                <td>
                                    @php $field = $baseKey . '.asset_description'; @endphp
                                    <textarea class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" rows="2" data-field="asset_description" name="family_assets[{{ $index }}][asset_description]">{{ data_get($asset, 'asset_description') }}</textarea>
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.asset_address'; @endphp
                                    <input type="text" class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" data-field="asset_address" name="family_assets[{{ $index }}][asset_address]" value="{{ data_get($asset, 'asset_address') }}">
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    @php $field = $baseKey . '.notes'; @endphp
                                    <textarea class="form-control {{ $errors->has($field) ? 'is-invalid' : '' }}" rows="2" data-field="notes" name="family_assets[{{ $index }}][notes]">{{ data_get($asset, 'notes') }}</textarea>
                                    @error($field)
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-asset-row>
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-end">
    <button type="submit" class="btn btn-primary px-4">
        <i class="bi bi-save me-1"></i>
        Lưu thông tin
    </button>
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const updateRowOrder = (tbody) => {
                    const group = tbody.dataset.familyGroup;

                    Array.from(tbody.children).forEach((row, index) => {
                        const orderElement = row.querySelector('.family-row-order');

                        if (orderElement) {
                            orderElement.textContent = index + 1;
                        }

                        row.querySelectorAll('[data-field]').forEach((field) => {
                            const fieldName = field.dataset.field;
                            field.name = `family_members[${group}][${index}][${fieldName}]`;

                            if (fieldName === 'position') {
                                field.value = index;
                            }
                        });
                    });
                };

                const addRow = (group) => {
                    const tbody = document.querySelector(`[data-family-group="${group}"]`);
                    const template = document.getElementById('family-row-template');

                    if (!tbody || !template) {
                        return;
                    }

                    const clone = template.content.firstElementChild.cloneNode(true);

                    tbody.appendChild(clone);
                    updateRowOrder(tbody);
                };

                document.querySelectorAll('[data-add-family-row]').forEach((button) => {
                    button.addEventListener('click', () => {
                        addRow(button.dataset.group);
                    });
                });

                document.querySelectorAll('tbody[data-family-group]').forEach((tbody) => {
                    tbody.addEventListener('click', (event) => {
                        if (event.target.closest('[data-remove-family-row]')) {
                            const rows = Array.from(tbody.children);

                            if (rows.length === 1) {
                                rows[0].querySelectorAll('input').forEach((input) => {
                                    if (input.dataset.field !== 'position') {
                                        input.value = '';
                                    }
                                });

                                return;
                            }

                            event.target.closest('tr').remove();
                            updateRowOrder(tbody);
                        }
                    });
                });

                const updateAssetRowOrder = (tbody) => {
                    Array.from(tbody.children).forEach((row, index) => {
                        const orderElement = row.querySelector('.family-asset-row-order');

                        if (orderElement) {
                            orderElement.textContent = index + 1;
                        }

                        row.querySelectorAll('[data-field]').forEach((field) => {
                            const fieldName = field.dataset.field;
                            field.name = `family_assets[${index}][${fieldName}]`;

                            if (fieldName === 'position') {
                                field.value = index;
                            }
                        });
                    });
                };

                const addAssetRow = () => {
                    const tbody = document.querySelector('[data-family-asset-group]');
                    const template = document.getElementById('family-asset-row-template');

                    if (!tbody || !template) {
                        return;
                    }

                    const clone = template.content.firstElementChild.cloneNode(true);

                    tbody.appendChild(clone);
                    updateAssetRowOrder(tbody);
                };

                document.querySelectorAll('[data-add-asset-row]').forEach((button) => {
                    button.addEventListener('click', addAssetRow);
                });

                const assetTbody = document.querySelector('[data-family-asset-group]');

                if (assetTbody) {
                    updateAssetRowOrder(assetTbody);

                    assetTbody.addEventListener('click', (event) => {
                        if (event.target.closest('[data-remove-asset-row]')) {
                            const rows = Array.from(assetTbody.children);

                            if (rows.length === 1) {
                                rows[0].querySelectorAll('input, textarea').forEach((input) => {
                                    if (input.dataset.field !== 'position') {
                                        input.value = '';
                                    }
                                });

                                return;
                            }

                            event.target.closest('tr').remove();
                            updateAssetRowOrder(assetTbody);
                        }
                    });
                }

                const updateTrainingRowOrder = (tbody) => {
                    const group = tbody.dataset.trainingGroup;

                    Array.from(tbody.children).forEach((row, index) => {
                        const orderElement = row.querySelector('.training-row-order');

                        if (orderElement) {
                            orderElement.textContent = index + 1;
                        }

                        row.querySelectorAll('[data-field]').forEach((field) => {
                            const fieldName = field.dataset.field;
                            field.name = `${group}[${index}][${fieldName}]`;

                            if (fieldName === 'position') {
                                field.value = index;
                            }
                        });
                    });
                };

                const addTrainingRow = (group) => {
                    if (!group) {
                        return;
                    }

                    const tbody = document.querySelector(`[data-training-group="${group}"]`);
                    const template = document.getElementById(`training-row-template-${group}`);

                    if (!tbody || !template || !template.content.firstElementChild) {
                        return;
                    }

                    const clone = template.content.firstElementChild.cloneNode(true);

                    tbody.appendChild(clone);
                    updateTrainingRowOrder(tbody);
                };

                document.querySelectorAll('[data-add-training-row]').forEach((button) => {
                    const group = button.getAttribute('data-group');

                    button.addEventListener('click', () => addTrainingRow(group));
                });

                document.querySelectorAll('tbody[data-training-group]').forEach((tbody) => {
                    updateTrainingRowOrder(tbody);

                    tbody.addEventListener('click', (event) => {
                        if (event.target.closest('[data-remove-training-row]')) {
                            const rows = Array.from(tbody.children);

                            if (rows.length === 1) {
                                rows[0].querySelectorAll('input, select, textarea').forEach((input) => {
                                    if (input.dataset.field !== 'position') {
                                        input.value = '';
                                    }
                                });

                                return;
                            }

                            event.target.closest('tr').remove();
                            updateTrainingRowOrder(tbody);
                        }
                    });
                });

                const updatePlanningRowOrder = (tbody) => {
                    const group = tbody.dataset.planningGroup;

                    Array.from(tbody.children).forEach((row, index) => {
                        const orderElement = row.querySelector('.planning-row-order');

                        if (orderElement) {
                            orderElement.textContent = index + 1;
                        }

                        row.querySelectorAll('[data-field]').forEach((field) => {
                            const fieldName = field.dataset.field;
                            field.name = `planning_records[${group}][${index}][${fieldName}]`;

                            if (fieldName === 'position') {
                                field.value = index;
                            }
                        });
                    });
                };


                const addPlanningRow = (group) => {
                    const tbody = document.querySelector(`[data-planning-group="${group}"]`);
                    const template = document.getElementById('planning-row-template');

                    if (!tbody || !template) {
                        return;
                    }

                    const clone = template.content.firstElementChild.cloneNode(true);

                    tbody.appendChild(clone);
                    updatePlanningRowOrder(tbody);
                };

                document.querySelectorAll('[data-add-planning-row]').forEach((button) => {
                    button.addEventListener('click', () => addPlanningRow(button.dataset.group));
                });

                document.querySelectorAll('tbody[data-planning-group]').forEach((tbody) => {
                    updatePlanningRowOrder(tbody);

                    tbody.addEventListener('click', (event) => {
                        if (event.target.closest('[data-remove-planning-row]')) {
                            const rows = Array.from(tbody.children);

                            if (rows.length === 1) {
                                rows[0].querySelectorAll('input').forEach((input) => {
                                    if (input.dataset.field !== 'position') {
                                        input.value = '';
                                    }
                                });

                                return;
                            }

                            event.target.closest('tr').remove();
                            updatePlanningRowOrder(tbody);
                        }
                    });
                });

                const updateWorkRowOrder = (tbody) => {
                    Array.from(tbody.children).forEach((row, index) => {
                        const orderElement = row.querySelector('.work-row-order');

                        if (orderElement) {
                            orderElement.textContent = index + 1;
                        }

                        row.querySelectorAll('[data-field]').forEach((field) => {
                            const fieldName = field.dataset.field;
                            field.name = `work_experiences[${index}][${fieldName}]`;

                            if (fieldName === 'position') {
                                field.value = index;
                            }
                        });
                    });
                };

                const addWorkRow = () => {
                    const tbody = document.querySelector('[data-work-experience-group]');
                    const template = document.getElementById('work-experience-row-template');

                    if (!tbody || !template) {
                        return;
                    }

                    const clone = template.content.firstElementChild.cloneNode(true);

                    tbody.appendChild(clone);
                    updateWorkRowOrder(tbody);
                };

                document.querySelectorAll('[data-add-work-row]').forEach((button) => {
                    button.addEventListener('click', addWorkRow);
                });

                const workTbody = document.querySelector('[data-work-experience-group]');

                if (workTbody) {
                    updateWorkRowOrder(workTbody);

                    workTbody.addEventListener('click', (event) => {
                        if (event.target.closest('[data-remove-work-row]')) {
                            const rows = Array.from(workTbody.children);

                            if (rows.length === 1) {
                                rows[0].querySelectorAll('input').forEach((input) => {
                                    if (input.dataset.field !== 'position') {
                                        input.value = '';
                                    }
                                });

                                return;
                            }

                            event.target.closest('tr').remove();
                            updateWorkRowOrder(workTbody);
                        }
                    });
                }
            });
        </script>
        <template id="family-row-template">
            <tr>
                <td class="text-center align-middle">
                    <span class="family-row-order"></span>
                    <input type="hidden" data-field="position" value="0">
                </td>
                <td>
                    <input type="text" class="form-control" data-field="relationship">
                </td>
                <td>
                    <input type="text" class="form-control" data-field="full_name">
                </td>
                <td>
                    <input type="number" min="1900" max="2100" class="form-control text-center" data-field="birth_year">
                </td>
                <td>
                    <input type="text" class="form-control" data-field="hometown">
                </td>
                <td>
                    <input type="text" class="form-control" data-field="residence">
                </td>
                <td>
                    <input type="text" class="form-control" data-field="occupation">
                </td>
                <td>
                    <input type="text" class="form-control" data-field="workplace">
                </td>
                <td>
                    <input type="text" class="form-control" data-field="notes">
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-link text-danger p-0" data-remove-family-row>
                        <i class="bi bi-x-circle"></i>
                    </button>
                </td>
            </tr>
        </template>
        <template id="family-asset-row-template">
            <tr>
                <td class="text-center align-middle">
                    <span class="family-asset-row-order"></span>
                    <input type="hidden" data-field="position" value="0">
                </td>
                <td>
                    <textarea class="form-control" rows="2" data-field="asset_description"></textarea>
                </td>
                <td>
                    <input type="text" class="form-control" data-field="asset_address">
                </td>
                <td>
                    <textarea class="form-control" rows="2" data-field="notes"></textarea>
                </td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-link text-danger p-0" data-remove-asset-row>
                        <i class="bi bi-x-circle"></i>
                    </button>
                </td>
            </tr>
        </template>
        <template id="planning-row-template">
            <tr>
                <td class="text-center align-middle">
                    <span class="planning-row-order"></span>
                    <input type="hidden" data-field="position" value="0">
                </td>
                <td><input type="text" class="form-control" data-field="position_title"></td>
                <td><input type="text" class="form-control" data-field="stage"></td>
                <td><input type="text" class="form-control" data-field="status"></td>
                <td><input type="text" class="form-control" data-field="notes"></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-link text-danger p-0" data-remove-planning-row>
                        <i class="bi bi-x-circle"></i>
                    </button>
                </td>
            </tr>
        </template>
        <template id="work-experience-row-template">
            <tr>
                <td class="text-center align-middle">
                    <span class="work-row-order"></span>
                    <input type="hidden" data-field="position" value="0">
                </td>
                <td><input type="text" class="form-control" data-field="from_period"></td>
                <td><input type="text" class="form-control" data-field="to_period"></td>
                <td><input type="text" class="form-control" data-field="unit_name"></td>
                <td><input type="text" class="form-control" data-field="job_title"></td>
                <td><input type="text" class="form-control" data-field="notes"></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-link text-danger p-0" data-remove-work-row>
                        <i class="bi bi-x-circle"></i>
                    </button>
                </td>
            </tr>
        </template>
        <template id="training-row-template-formal_training">
            <tr>
                <td class="text-center align-middle">
                    <span class="training-row-order"></span>
                    <input type="hidden" data-field="position" value="0">
                </td>
                <td><input type="text" class="form-control" data-field="timeframe"></td>
                <td><input type="text" class="form-control" data-field="institution"></td>
                <td><input type="text" class="form-control" data-field="major"></td>
                <td><input type="text" class="form-control" data-field="training_form"></td>
                <td><input type="text" class="form-control" data-field="qualification"></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                        <i class="bi bi-x-circle"></i>
                    </button>
                </td>
            </tr>
        </template>
        <template id="training-row-template-professional_development">
            <tr>
                <td class="text-center align-middle">
                    <span class="training-row-order"></span>
                    <input type="hidden" data-field="position" value="0">
                </td>
                <td><input type="text" class="form-control" data-field="program_name"></td>
                <td><input type="text" class="form-control" data-field="certificate"></td>
                <td><input type="text" class="form-control" data-field="institution"></td>
                <td><input type="number" min="1900" max="2100" class="form-control text-center" data-field="year_awarded"></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                        <i class="bi bi-x-circle"></i>
                    </button>
                </td>
            </tr>
        </template>
        <template id="training-row-template-management_training">
            <tr>
                <td class="text-center align-middle">
                    <span class="training-row-order"></span>
                    <input type="hidden" data-field="position" value="0">
                </td>
                <td><input type="text" class="form-control" data-field="program_name"></td>
                <td><input type="text" class="form-control" data-field="certificate"></td>
                <td><input type="text" class="form-control" data-field="institution"></td>
                <td><input type="number" min="1900" max="2100" class="form-control text-center" data-field="year_awarded"></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                        <i class="bi bi-x-circle"></i>
                    </button>
                </td>
            </tr>
        </template>
        <template id="training-row-template-political_theory">
            <tr>
                <td class="text-center align-middle">
                    <span class="training-row-order"></span>
                    <input type="hidden" data-field="position" value="0">
                </td>
                <td><input type="text" class="form-control" data-field="level"></td>
                <td><input type="text" class="form-control" data-field="institution"></td>
                <td><input type="number" min="1900" max="2100" class="form-control text-center" data-field="year_awarded"></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                        <i class="bi bi-x-circle"></i>
                    </button>
                </td>
            </tr>
        </template>
        <template id="training-row-template-national_defense">
            <tr>
                <td class="text-center align-middle">
                    <span class="training-row-order"></span>
                    <input type="hidden" data-field="position" value="0">
                </td>
                <td><input type="text" class="form-control" data-field="program_name"></td>
                <td><input type="text" class="form-control" data-field="institution"></td>
                <td><input type="number" min="1900" max="2100" class="form-control text-center" data-field="year_awarded"></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                        <i class="bi bi-x-circle"></i>
                    </button>
                </td>
            </tr>
        </template>
        <template id="training-row-template-foreign_language">
            <tr>
                <td class="text-center align-middle">
                    <span class="training-row-order"></span>
                    <input type="hidden" data-field="position" value="0">
                </td>
                <td><input type="text" class="form-control" data-field="language"></td>
                <td><input type="text" class="form-control" data-field="level"></td>
                <td><input type="text" class="form-control" data-field="certificate"></td>
                <td><input type="text" class="form-control" data-field="institution"></td>
                <td><input type="number" min="1900" max="2100" class="form-control text-center" data-field="year_awarded"></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                        <i class="bi bi-x-circle"></i>
                    </button>
                </td>
            </tr>
        </template>
        <template id="training-row-template-informatics">
            <tr>
                <td class="text-center align-middle">
                    <span class="training-row-order"></span>
                    <input type="hidden" data-field="position" value="0">
                </td>
                <td><input type="text" class="form-control" data-field="program_name"></td>
                <td><input type="text" class="form-control" data-field="level"></td>
                <td><input type="text" class="form-control" data-field="certificate"></td>
                <td><input type="text" class="form-control" data-field="institution"></td>
                <td><input type="number" min="1900" max="2100" class="form-control text-center" data-field="year_awarded"></td>
                <td class="text-center align-middle">
                    <button type="button" class="btn btn-link text-danger p-0" data-remove-training-row>
                        <i class="bi bi-x-circle"></i>
                    </button>
                </td>
            </tr>
        </template>
    @endpush
@endonce