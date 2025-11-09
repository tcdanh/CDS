@php
    $mode = $mode ?? 'teaching';
@endphp

@csrf
@method('PUT')
<input type="hidden" name="redirect_to" value="{{ $mode }}">

@if ($errors->any())
    <div class="alert alert-danger">
        <div class="fw-semibold mb-2">Vui lòng kiểm tra lại thông tin:</div>
        <ul class="mb-0 ps-3 small">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($mode === 'teaching')
    @php
        $teachingRows = collect(old('teaching_activity_records', optional($info)->teachingActivities?->map(function ($record) {
            return [
                'academic_year' => $record->academic_year,
                'undergraduate_hours' => $record->undergraduate_hours,
                'graduate_hours' => $record->graduate_hours,
                'doctoral_hours' => $record->doctoral_hours,
                'notes' => $record->notes,
                'position' => $record->position ?? 0,
            ];
        })->toArray() ?? []))->sortBy('position')->values();

        $supervisionRows = collect(old('supervision_records', optional($info)->supervisionActivities?->map(function ($record) {
            return [
                'level' => $record->level,
                'student_name' => $record->student_name,
                'topic' => $record->topic,
                'year' => $record->year,
                'notes' => $record->notes,
                'position' => $record->position ?? 0,
            ];
        })->toArray() ?? []))->sortBy('position')->values();

        if ($teachingRows->isEmpty()) {
            $teachingRows = collect([[
                'academic_year' => null,
                'undergraduate_hours' => null,
                'graduate_hours' => null,
                'doctoral_hours' => null,
                'notes' => null,
                'position' => 0,
            ]]);
        }

        if ($supervisionRows->isEmpty()) {
            $supervisionRows = collect([[
                'level' => null,
                'student_name' => null,
                'topic' => null,
                'year' => null,
                'notes' => null,
                'position' => 0,
            ]]);
        }
    @endphp

    <div class="vstack gap-4" data-teachresearch-form-root>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-easel2 text-primary me-2"></i>
                    <span class="fw-semibold">Khối lượng giảng dạy</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-add-teachresearch-row="teaching">
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
                                <th style="width: 140px;">Năm học</th>
                                <th style="width: 160px;">Đại học (tiết)</th>
                                <th style="width: 160px;">Cao học (tiết)</th>
                                <th style="width: 180px;">Nghiên cứu sinh (tiết)</th>
                                <th>Ghi chú</th>
                                <th class="text-center" style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody data-teachresearch-group="teaching" data-teachresearch-prefix="teaching_activity_records">
                            @foreach ($teachingRows as $index => $row)
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="teachresearch-row-order"></span>
                                        <input type="hidden" data-field="position" name="teaching_activity_records[{{ $index }}][position]" value="{{ $row['position'] ?? $index }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('teaching_activity_records.' . $index . '.academic_year') is-invalid @enderror" data-field="academic_year" name="teaching_activity_records[{{ $index }}][academic_year]" value="{{ $row['academic_year'] }}">
                                        @error('teaching_activity_records.' . $index . '.academic_year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control text-end @error('teaching_activity_records.' . $index . '.undergraduate_hours') is-invalid @enderror" data-field="undergraduate_hours" name="teaching_activity_records[{{ $index }}][undergraduate_hours]" value="{{ $row['undergraduate_hours'] }}">
                                        @error('teaching_activity_records.' . $index . '.undergraduate_hours')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control text-end @error('teaching_activity_records.' . $index . '.graduate_hours') is-invalid @enderror" data-field="graduate_hours" name="teaching_activity_records[{{ $index }}][graduate_hours]" value="{{ $row['graduate_hours'] }}">
                                        @error('teaching_activity_records.' . $index . '.graduate_hours')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control text-end @error('teaching_activity_records.' . $index . '.doctoral_hours') is-invalid @enderror" data-field="doctoral_hours" name="teaching_activity_records[{{ $index }}][doctoral_hours]" value="{{ $row['doctoral_hours'] }}">
                                        @error('teaching_activity_records.' . $index . '.doctoral_hours')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('teaching_activity_records.' . $index . '.notes') is-invalid @enderror" data-field="notes" name="teaching_activity_records[{{ $index }}][notes]" value="{{ $row['notes'] }}">
                                        @error('teaching_activity_records.' . $index . '.notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-link text-danger p-0" data-remove-teachresearch-row>
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
                    <i class="bi bi-people text-primary me-2"></i>
                    <span class="fw-semibold">Công tác hướng dẫn</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-add-teachresearch-row="supervision">
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
                                <th style="width: 180px;">Cấp đào tạo</th>
                                <th style="width: 200px;">Học viên/Học trò</th>
                                <th>Chủ đề hướng dẫn</th>
                                <th style="width: 140px;">Năm</th>
                                <th style="width: 200px;">Ghi chú</th>
                                <th class="text-center" style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody data-teachresearch-group="supervision" data-teachresearch-prefix="supervision_records">
                            @foreach ($supervisionRows as $index => $row)
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="teachresearch-row-order"></span>
                                        <input type="hidden" data-field="position" name="supervision_records[{{ $index }}][position]" value="{{ $row['position'] ?? $index }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('supervision_records.' . $index . '.level') is-invalid @enderror" data-field="level" name="supervision_records[{{ $index }}][level]" value="{{ $row['level'] }}">
                                        @error('supervision_records.' . $index . '.level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('supervision_records.' . $index . '.student_name') is-invalid @enderror" data-field="student_name" name="supervision_records[{{ $index }}][student_name]" value="{{ $row['student_name'] }}">
                                        @error('supervision_records.' . $index . '.student_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('supervision_records.' . $index . '.topic') is-invalid @enderror" data-field="topic" name="supervision_records[{{ $index }}][topic]" value="{{ $row['topic'] }}">
                                        @error('supervision_records.' . $index . '.topic')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('supervision_records.' . $index . '.year') is-invalid @enderror" data-field="year" name="supervision_records[{{ $index }}][year]" value="{{ $row['year'] }}">
                                        @error('supervision_records.' . $index . '.year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('supervision_records.' . $index . '.notes') is-invalid @enderror" data-field="notes" name="supervision_records[{{ $index }}][notes]" value="{{ $row['notes'] }}">
                                        @error('supervision_records.' . $index . '.notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-link text-danger p-0" data-remove-teachresearch-row>
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

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Lưu thay đổi
            </button>
        </div>
    </div>

    <template id="teachresearch-teaching-row-template">
        <tr>
            <td class="text-center align-middle">
                <span class="teachresearch-row-order"></span>
                <input type="hidden" data-field="position" value="0">
            </td>
            <td><input type="text" class="form-control" data-field="academic_year"></td>
            <td><input type="number" min="0" class="form-control text-end" data-field="undergraduate_hours"></td>
            <td><input type="number" min="0" class="form-control text-end" data-field="graduate_hours"></td>
            <td><input type="number" min="0" class="form-control text-end" data-field="doctoral_hours"></td>
            <td><input type="text" class="form-control" data-field="notes"></td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-link text-danger p-0" data-remove-teachresearch-row>
                    <i class="bi bi-x-circle"></i>
                </button>
            </td>
        </tr>
    </template>

    <template id="teachresearch-supervision-row-template">
        <tr>
            <td class="text-center align-middle">
                <span class="teachresearch-row-order"></span>
                <input type="hidden" data-field="position" value="0">
            </td>
            <td><input type="text" class="form-control" data-field="level"></td>
            <td><input type="text" class="form-control" data-field="student_name"></td>
            <td><input type="text" class="form-control" data-field="topic"></td>
            <td><input type="text" class="form-control" data-field="year"></td>
            <td><input type="text" class="form-control" data-field="notes"></td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-link text-danger p-0" data-remove-teachresearch-row>
                    <i class="bi bi-x-circle"></i>
                </button>
            </td>
        </tr>
    </template>
@endif

@if ($mode === 'research')
    @php
        $projectRows = collect(old('research_project_records', optional($info)->researchProjectRecords?->map(function ($record) {
            return [
                'from_period' => $record->from_period,
                'to_period' => $record->to_period,
                'project_name' => $record->project_name,
                'project_type' => $record->project_type,
                'role' => $record->role,
                'budget_million_vnd' => $record->budget_million_vnd,
                'status' => $record->status,
                'notes' => $record->notes,
                'position' => $record->position ?? 0,
            ];
        })->toArray() ?? []))->sortBy('position')->values();

        if ($projectRows->isEmpty()) {
            $projectRows = collect([[
                'from_period' => null,
                'to_period' => null,
                'project_name' => null,
                'project_type' => null,
                'role' => null,
                'budget_million_vnd' => null,
                'status' => null,
                'notes' => null,
                'position' => 0,
            ]]);
        }
    @endphp

    <div class="vstack gap-4" data-teachresearch-form-root>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-collection text-primary me-2"></i>
                    <span class="fw-semibold">Đề tài - Dự án</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-add-teachresearch-row="projects">
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
                                <th style="width: 140px;">Từ</th>
                                <th style="width: 140px;">Đến</th>
                                <th>Tên đề tài/dự án</th>
                                <th style="width: 180px;">Loại đề tài</th>
                                <th style="width: 160px;">Vai trò</th>
                                <th style="width: 180px;">Kinh phí (triệu đồng)</th>
                                <th style="width: 160px;">Tình trạng</th>
                                <th style="width: 220px;">Ghi chú</th>
                                <th class="text-center" style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody data-teachresearch-group="projects" data-teachresearch-prefix="research_project_records">
                            @foreach ($projectRows as $index => $row)
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="teachresearch-row-order"></span>
                                        <input type="hidden" data-field="position" name="research_project_records[{{ $index }}][position]" value="{{ $row['position'] ?? $index }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('research_project_records.' . $index . '.from_period') is-invalid @enderror" data-field="from_period" name="research_project_records[{{ $index }}][from_period]" value="{{ $row['from_period'] }}">
                                        @error('research_project_records.' . $index . '.from_period')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('research_project_records.' . $index . '.to_period') is-invalid @enderror" data-field="to_period" name="research_project_records[{{ $index }}][to_period]" value="{{ $row['to_period'] }}">
                                        @error('research_project_records.' . $index . '.to_period')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('research_project_records.' . $index . '.project_name') is-invalid @enderror" data-field="project_name" name="research_project_records[{{ $index }}][project_name]" value="{{ $row['project_name'] }}">
                                        @error('research_project_records.' . $index . '.project_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('research_project_records.' . $index . '.project_type') is-invalid @enderror" data-field="project_type" name="research_project_records[{{ $index }}][project_type]" value="{{ $row['project_type'] }}">
                                        @error('research_project_records.' . $index . '.project_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('research_project_records.' . $index . '.role') is-invalid @enderror" data-field="role" name="research_project_records[{{ $index }}][role]" value="{{ $row['role'] }}">
                                        @error('research_project_records.' . $index . '.role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="number" min="0" class="form-control text-end @error('research_project_records.' . $index . '.budget_million_vnd') is-invalid @enderror" data-field="budget_million_vnd" name="research_project_records[{{ $index }}][budget_million_vnd]" value="{{ $row['budget_million_vnd'] }}">
                                        @error('research_project_records.' . $index . '.budget_million_vnd')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('research_project_records.' . $index . '.status') is-invalid @enderror" data-field="status" name="research_project_records[{{ $index }}][status]" value="{{ $row['status'] }}">
                                        @error('research_project_records.' . $index . '.status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('research_project_records.' . $index . '.notes') is-invalid @enderror" data-field="notes" name="research_project_records[{{ $index }}][notes]" value="{{ $row['notes'] }}">
                                        @error('research_project_records.' . $index . '.notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-link text-danger p-0" data-remove-teachresearch-row>
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

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Lưu thay đổi
            </button>
        </div>
    </div>

    <template id="teachresearch-projects-row-template">
        <tr>
            <td class="text-center align-middle">
                <span class="teachresearch-row-order"></span>
                <input type="hidden" data-field="position" value="0">
            </td>
            <td><input type="text" class="form-control" data-field="from_period"></td>
            <td><input type="text" class="form-control" data-field="to_period"></td>
            <td><input type="text" class="form-control" data-field="project_name"></td>
            <td><input type="text" class="form-control" data-field="project_type"></td>
            <td><input type="text" class="form-control" data-field="role"></td>
            <td><input type="number" min="0" class="form-control text-end" data-field="budget_million_vnd"></td>
            <td><input type="text" class="form-control" data-field="status"></td>
            <td><input type="text" class="form-control" data-field="notes"></td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-link text-danger p-0" data-remove-teachresearch-row>
                    <i class="bi bi-x-circle"></i>
                </button>
            </td>
        </tr>
    </template>
@endif

@if ($mode === 'awards')
    @php
        $awardRows = collect(old('scientific_awards', optional($info)->scientificAwards?->map(function ($record) {
            return [
                'year' => $record->year,
                'award_name' => $record->award_name,
                'organization' => $record->organization,
                'notes' => $record->notes,
                'position' => $record->position ?? 0,
            ];
        })->toArray() ?? []))->sortBy('position')->values();

        if ($awardRows->isEmpty()) {
            $awardRows = collect([[
                'year' => null,
                'award_name' => null,
                'organization' => null,
                'notes' => null,
                'position' => 0,
            ]]);
        }
    @endphp

    <div class="vstack gap-4" data-teachresearch-form-root>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-trophy text-primary me-2"></i>
                    <span class="fw-semibold">Giải thưởng khoa học & giảng dạy</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-add-teachresearch-row="awards">
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
                                <th style="width: 140px;">Năm</th>
                                <th>Giải thưởng</th>
                                <th style="width: 220px;">Cơ quan/Tổ chức trao</th>
                                <th style="width: 220px;">Ghi chú</th>
                                <th class="text-center" style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody data-teachresearch-group="awards" data-teachresearch-prefix="scientific_awards">
                            @foreach ($awardRows as $index => $row)
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="teachresearch-row-order"></span>
                                        <input type="hidden" data-field="position" name="scientific_awards[{{ $index }}][position]" value="{{ $row['position'] ?? $index }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('scientific_awards.' . $index . '.year') is-invalid @enderror" data-field="year" name="scientific_awards[{{ $index }}][year]" value="{{ $row['year'] }}">
                                        @error('scientific_awards.' . $index . '.year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('scientific_awards.' . $index . '.award_name') is-invalid @enderror" data-field="award_name" name="scientific_awards[{{ $index }}][award_name]" value="{{ $row['award_name'] }}">
                                        @error('scientific_awards.' . $index . '.award_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('scientific_awards.' . $index . '.organization') is-invalid @enderror" data-field="organization" name="scientific_awards[{{ $index }}][organization]" value="{{ $row['organization'] }}">
                                        @error('scientific_awards.' . $index . '.organization')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('scientific_awards.' . $index . '.notes') is-invalid @enderror" data-field="notes" name="scientific_awards[{{ $index }}][notes]" value="{{ $row['notes'] }}">
                                        @error('scientific_awards.' . $index . '.notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-link text-danger p-0" data-remove-teachresearch-row>
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

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Lưu thay đổi
            </button>
        </div>
    </div>

    <template id="teachresearch-awards-row-template">
        <tr>
            <td class="text-center align-middle">
                <span class="teachresearch-row-order"></span>
                <input type="hidden" data-field="position" value="0">
            </td>
            <td><input type="text" class="form-control" data-field="year"></td>
            <td><input type="text" class="form-control" data-field="award_name"></td>
            <td><input type="text" class="form-control" data-field="organization"></td>
            <td><input type="text" class="form-control" data-field="notes"></td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-link text-danger p-0" data-remove-teachresearch-row>
                    <i class="bi bi-x-circle"></i>
                </button>
            </td>
        </tr>
    </template>
@endif

@if ($mode === 'publications')
    @php
        $publicationRows = collect(old('scientific_publications', optional($info)->scientificPublications?->map(function ($record) {
            return [
                'year' => $record->year,
                'title' => $record->title,
                'role' => $record->role,
                'publication_type' => $record->publication_type,
                'publisher' => $record->publisher,
                'notes' => $record->notes,
                'position' => $record->position ?? 0,
            ];
        })->toArray() ?? []))->sortBy('position')->values();

        $ipRows = collect(old('intellectual_property_records', optional($info)->intellectualPropertyRecords?->map(function ($record) {
            return [
                'year' => $record->year,
                'name' => $record->name,
                'ip_type' => $record->ip_type,
                'registration_number' => $record->registration_number,
                'notes' => $record->notes,
                'position' => $record->position ?? 0,
            ];
        })->toArray() ?? []))->sortBy('position')->values();

        if ($publicationRows->isEmpty()) {
            $publicationRows = collect([[
                'year' => null,
                'title' => null,
                'role' => null,
                'publication_type' => null,
                'publisher' => null,
                'notes' => null,
                'position' => 0,
            ]]);
        }

        if ($ipRows->isEmpty()) {
            $ipRows = collect([[
                'year' => null,
                'name' => null,
                'ip_type' => null,
                'registration_number' => null,
                'notes' => null,
                'position' => 0,
            ]]);
        }
    @endphp

    <div class="vstack gap-4" data-teachresearch-form-root>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-journal-richtext text-primary me-2"></i>
                    <span class="fw-semibold">Công bố khoa học</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-add-teachresearch-row="publications">
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
                                <th style="width: 120px;">Năm</th>
                                <th>Tiêu đề</th>
                                <th style="width: 160px;">Vai trò</th>
                                <th style="width: 160px;">Loại</th>
                                <th style="width: 220px;">Nơi công bố</th>
                                <th style="width: 220px;">Ghi chú</th>
                                <th class="text-center" style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody data-teachresearch-group="publications" data-teachresearch-prefix="scientific_publications">
                            @foreach ($publicationRows as $index => $row)
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="teachresearch-row-order"></span>
                                        <input type="hidden" data-field="position" name="scientific_publications[{{ $index }}][position]" value="{{ $row['position'] ?? $index }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('scientific_publications.' . $index . '.year') is-invalid @enderror" data-field="year" name="scientific_publications[{{ $index }}][year]" value="{{ $row['year'] }}">
                                        @error('scientific_publications.' . $index . '.year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('scientific_publications.' . $index . '.title') is-invalid @enderror" data-field="title" name="scientific_publications[{{ $index }}][title]" value="{{ $row['title'] }}">
                                        @error('scientific_publications.' . $index . '.title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('scientific_publications.' . $index . '.role') is-invalid @enderror" data-field="role" name="scientific_publications[{{ $index }}][role]" value="{{ $row['role'] }}">
                                        @error('scientific_publications.' . $index . '.role')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('scientific_publications.' . $index . '.publication_type') is-invalid @enderror" data-field="publication_type" name="scientific_publications[{{ $index }}][publication_type]" value="{{ $row['publication_type'] }}">
                                        @error('scientific_publications.' . $index . '.publication_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('scientific_publications.' . $index . '.publisher') is-invalid @enderror" data-field="publisher" name="scientific_publications[{{ $index }}][publisher]" value="{{ $row['publisher'] }}">
                                        @error('scientific_publications.' . $index . '.publisher')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('scientific_publications.' . $index . '.notes') is-invalid @enderror" data-field="notes" name="scientific_publications[{{ $index }}][notes]" value="{{ $row['notes'] }}">
                                        @error('scientific_publications.' . $index . '.notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-link text-danger p-0" data-remove-teachresearch-row>
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
                    <i class="bi bi-file-earmark-lock text-primary me-2"></i>
                    <span class="fw-semibold">Sở hữu trí tuệ</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-add-teachresearch-row="intellectual-property">
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
                                <th style="width: 120px;">Năm</th>
                                <th>Tên SHTT</th>
                                <th style="width: 160px;">Loại</th>
                                <th style="width: 200px;">Số hiệu</th>
                                <th style="width: 220px;">Ghi chú</th>
                                <th class="text-center" style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody data-teachresearch-group="intellectual-property" data-teachresearch-prefix="intellectual_property_records">
                            @foreach ($ipRows as $index => $row)
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="teachresearch-row-order"></span>
                                        <input type="hidden" data-field="position" name="intellectual_property_records[{{ $index }}][position]" value="{{ $row['position'] ?? $index }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('intellectual_property_records.' . $index . '.year') is-invalid @enderror" data-field="year" name="intellectual_property_records[{{ $index }}][year]" value="{{ $row['year'] }}">
                                        @error('intellectual_property_records.' . $index . '.year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('intellectual_property_records.' . $index . '.name') is-invalid @enderror" data-field="name" name="intellectual_property_records[{{ $index }}][name]" value="{{ $row['name'] }}">
                                        @error('intellectual_property_records.' . $index . '.name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('intellectual_property_records.' . $index . '.ip_type') is-invalid @enderror" data-field="ip_type" name="intellectual_property_records[{{ $index }}][ip_type]" value="{{ $row['ip_type'] }}">
                                        @error('intellectual_property_records.' . $index . '.ip_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('intellectual_property_records.' . $index . '.registration_number') is-invalid @enderror" data-field="registration_number" name="intellectual_property_records[{{ $index }}][registration_number]" value="{{ $row['registration_number'] }}">
                                        @error('intellectual_property_records.' . $index . '.registration_number')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('intellectual_property_records.' . $index . '.notes') is-invalid @enderror" data-field="notes" name="intellectual_property_records[{{ $index }}][notes]" value="{{ $row['notes'] }}">
                                        @error('intellectual_property_records.' . $index . '.notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-link text-danger p-0" data-remove-teachresearch-row>
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

        <div class="d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save me-1"></i>
                Lưu thay đổi
            </button>
        </div>
    </div>

    <template id="teachresearch-publications-row-template">
        <tr>
            <td class="text-center align-middle">
                <span class="teachresearch-row-order"></span>
                <input type="hidden" data-field="position" value="0">
            </td>
            <td><input type="text" class="form-control" data-field="year"></td>
            <td><input type="text" class="form-control" data-field="title"></td>
            <td><input type="text" class="form-control" data-field="role"></td>
            <td><input type="text" class="form-control" data-field="publication_type"></td>
            <td><input type="text" class="form-control" data-field="publisher"></td>
            <td><input type="text" class="form-control" data-field="notes"></td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-link text-danger p-0" data-remove-teachresearch-row>
                    <i class="bi bi-x-circle"></i>
                </button>
            </td>
        </tr>
    </template>

    <template id="teachresearch-intellectual-property-row-template">
        <tr>
            <td class="text-center align-middle">
                <span class="teachresearch-row-order"></span>
                <input type="hidden" data-field="position" value="0">
            </td>
            <td><input type="text" class="form-control" data-field="year"></td>
            <td><input type="text" class="form-control" data-field="name"></td>
            <td><input type="text" class="form-control" data-field="ip_type"></td>
            <td><input type="text" class="form-control" data-field="registration_number"></td>
            <td><input type="text" class="form-control" data-field="notes"></td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-link text-danger p-0" data-remove-teachresearch-row>
                    <i class="bi bi-x-circle"></i>
                </button>
            </td>
        </tr>
    </template>
@endif

<script>
    (() => {
        const initializeTeachResearchForm = (root) => {
            if (!root || root.dataset.enhanced === 'true') {
                return;
            }

            root.dataset.enhanced = 'true';

            const groups = {};

            root.querySelectorAll('[data-teachresearch-group]').forEach((tbody) => {
                const key = tbody.dataset.teachresearchGroup;
                const templateId = `teachresearch-${key}-row-template`;
                const template = root.querySelector(`#${templateId}`);
                const prefix = tbody.dataset.teachresearchPrefix || key;

                if (key && template) {
                    groups[key] = { tbody, template, prefix };
                }
            });

            const updateRowOrder = (groupKey) => {
                const group = groups[groupKey];

                if (!group || !group.tbody) {
                    return;
                }

                Array.from(group.tbody.children).forEach((row, index) => {
                    const orderElement = row.querySelector('.teachresearch-row-order');

                    if (orderElement) {
                        orderElement.textContent = index + 1;
                    }

                    row.querySelectorAll('[data-field]').forEach((field) => {
                        const fieldName = field.dataset.field;
                        field.name = `${group.prefix}[${index}][${fieldName}]`;

                        if (fieldName === 'position') {
                            field.value = index;
                        }
                    });
                });
            };

            const addRow = (groupKey) => {
                const group = groups[groupKey];

                if (!group || !group.tbody || !group.template || !group.template.content.firstElementChild) {
                    return;
                }

                const clone = group.template.content.firstElementChild.cloneNode(true);
                group.tbody.appendChild(clone);
                updateRowOrder(groupKey);
            };

            root.querySelectorAll('[data-add-teachresearch-row]').forEach((button) => {
                button.addEventListener('click', () => addRow(button.dataset.addTeachresearchRow));
            });

            Object.entries(groups).forEach(([groupKey, group]) => {
                if (!group.tbody) {
                    return;
                }

                updateRowOrder(groupKey);

                group.tbody.addEventListener('click', (event) => {
                    if (!event.target.closest('[data-remove-teachresearch-row]')) {
                        return;
                    }

                    const rows = Array.from(group.tbody.children);

                    if (rows.length === 1) {
                        rows[0].querySelectorAll('input, textarea').forEach((input) => {
                            if (input.dataset.field !== 'position') {
                                input.value = '';
                            }
                        });

                        return;
                    }

                    event.target.closest('tr').remove();
                    updateRowOrder(groupKey);
                });
            });
        };

        const setupTeachResearchForms = () => {
            document.querySelectorAll('[data-teachresearch-form-root]').forEach((root) => {
                initializeTeachResearchForm(root);
            });
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', setupTeachResearchForms);
        } else {
            setupTeachResearchForms();
        }
    })();
</script>