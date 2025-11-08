@php
    $mode = $mode ?? 'compensation';
@endphp 

@csrf
@method('PUT')
<input type="hidden" name="redirect_to" value="{{ $mode === 'recognition' ? 'recognition' : 'compensation' }}">

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
@if ($mode === 'compensation')
    @php
    $salaryRows = collect(old('salary_records', optional($info)->salaryRecords?->map(function ($record) {
        return [
            'from_period' => $record->from_period,
            'to_period' => $record->to_period,
            'coefficient' => $record->coefficient,
            'benefit_percentage' => $record->benefit_percentage,
            'position' => $record->position ?? 0,
        ];
    })->toArray() ?? []));

    $allowanceRows = collect(old('allowance_records', optional($info)->allowanceRecords?->map(function ($record) {
        return [
            'from_period' => $record->from_period,
            'to_period' => $record->to_period,
            'allowance_type' => $record->allowance_type,
            'salary_percentage' => $record->salary_percentage,
            'coefficient' => $record->coefficient,
            'amount' => $record->amount,
            'position' => $record->position ?? 0,
        ];
    })->toArray() ?? []));

    $salaryRows = $salaryRows->sortBy('position')->values();
    $allowanceRows = $allowanceRows->sortBy('position')->values();

    if ($salaryRows->isEmpty()) {
        $salaryRows = collect([[
            'from_period' => null,
            'to_period' => null,
            'coefficient' => null,
            'benefit_percentage' => null,
            'position' => 0,
        ]]);
    }

    if ($allowanceRows->isEmpty()) {
        $allowanceRows = collect([[
            'from_period' => null,
            'to_period' => null,
            'allowance_type' => null,
            'salary_percentage' => null,
            'coefficient' => null,
            'amount' => null,
            'position' => 0,
        ]]);
    }
    @endphp

<div class="vstack gap-4" data-compensation-form-root>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
            <div>
                <i class="bi bi-cash-stack text-primary me-2"></i>
                <span class="fw-semibold">Lương</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-compensation-row="salary">
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
                            <th style="width: 160px;">Hệ số</th>
                            <th style="width: 160px;">Phần trăm hưởng</th>
                            <th class="text-center" style="width: 60px;"></th>
                        </tr>
                    </thead>
                    <tbody data-compensation-group="salary">
                        @foreach ($salaryRows as $index => $row)
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="compensation-row-order"></span>
                                    <input type="hidden" data-field="position" name="salary_records[{{ $index }}][position]" value="{{ $row['position'] ?? $index }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control @error('salary_records.' . $index . '.from_period') is-invalid @enderror" data-field="from_period" name="salary_records[{{ $index }}][from_period]" value="{{ $row['from_period'] }}">
                                    @error('salary_records.' . $index . '.from_period')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="text" class="form-control @error('salary_records.' . $index . '.to_period') is-invalid @enderror" data-field="to_period" name="salary_records[{{ $index }}][to_period]" value="{{ $row['to_period'] }}">
                                    @error('salary_records.' . $index . '.to_period')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control text-end @error('salary_records.' . $index . '.coefficient') is-invalid @enderror" data-field="coefficient" name="salary_records[{{ $index }}][coefficient]" value="{{ $row['coefficient'] }}">
                                    @error('salary_records.' . $index . '.coefficient')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control text-end @error('salary_records.' . $index . '.benefit_percentage') is-invalid @enderror" data-field="benefit_percentage" name="salary_records[{{ $index }}][benefit_percentage]" value="{{ $row['benefit_percentage'] }}">
                                        <span class="input-group-text">%</span>
                                        @error('salary_records.' . $index . '.benefit_percentage')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-compensation-row>
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
                <i class="bi bi-wallet2 text-primary me-2"></i>
                <span class="fw-semibold">Phụ cấp</span>
            </div>
            <button type="button" class="btn btn-sm btn-outline-primary" data-add-compensation-row="allowance">
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
                            <th>Loại phụ cấp</th>
                            <th style="width: 160px;">Phần trăm lương</th>
                            <th style="width: 160px;">Hệ số</th>
                            <th style="width: 180px;">Giá trị (VNĐ)</th>
                            <th class="text-center" style="width: 60px;"></th>
                        </tr>
                    </thead>
                    <tbody data-compensation-group="allowance">
                        @foreach ($allowanceRows as $index => $row)
                            <tr>
                                <td class="text-center align-middle">
                                    <span class="compensation-row-order"></span>
                                    <input type="hidden" data-field="position" name="allowance_records[{{ $index }}][position]" value="{{ $row['position'] ?? $index }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control @error('allowance_records.' . $index . '.from_period') is-invalid @enderror" data-field="from_period" name="allowance_records[{{ $index }}][from_period]" value="{{ $row['from_period'] }}">
                                    @error('allowance_records.' . $index . '.from_period')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="text" class="form-control @error('allowance_records.' . $index . '.to_period') is-invalid @enderror" data-field="to_period" name="allowance_records[{{ $index }}][to_period]" value="{{ $row['to_period'] }}">
                                    @error('allowance_records.' . $index . '.to_period')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="text" class="form-control @error('allowance_records.' . $index . '.allowance_type') is-invalid @enderror" data-field="allowance_type" name="allowance_records[{{ $index }}][allowance_type]" value="{{ $row['allowance_type'] }}">
                                    @error('allowance_records.' . $index . '.allowance_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control text-end @error('allowance_records.' . $index . '.salary_percentage') is-invalid @enderror" data-field="salary_percentage" name="allowance_records[{{ $index }}][salary_percentage]" value="{{ $row['salary_percentage'] }}">
                                        <span class="input-group-text">%</span>
                                        @error('allowance_records.' . $index . '.salary_percentage')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </td>
                                <td>
                                    <input type="number" step="0.01" class="form-control text-end @error('allowance_records.' . $index . '.coefficient') is-invalid @enderror" data-field="coefficient" name="allowance_records[{{ $index }}][coefficient]" value="{{ $row['coefficient'] }}">
                                    @error('allowance_records.' . $index . '.coefficient')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control text-end @error('allowance_records.' . $index . '.amount') is-invalid @enderror" data-field="amount" name="allowance_records[{{ $index }}][amount]" value="{{ $row['amount'] }}">
                                        <span class="input-group-text">₫</span>
                                        @error('allowance_records.' . $index . '.amount')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <button type="button" class="btn btn-link text-danger p-0" data-remove-compensation-row>
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

    {{-- 👉 ĐẶT 2 TEMPLATE Ở ĐÂY, TRONG ROOT, NHƯNG KHÔNG BỌC CARD --}}
    <template id="compensation-salary-row-template">
        <tr>
            <td class="text-center align-middle">
                <span class="compensation-row-order"></span>
                <input type="hidden" data-field="position" value="">
            </td>
            <td><input type="text" class="form-control" data-field="from_period"></td>
            <td><input type="text" class="form-control" data-field="to_period"></td>
            <td><input type="number" step="0.01" class="form-control" data-field="coefficient"></td>
            <td><input type="number" step="0.01" class="form-control" data-field="benefit_percentage"></td>
            <td class="text-center">
                <button type="button"
                        class="btn btn-link text-danger p-0"
                        data-remove-compensation-row>
                    <i class="bi bi-x-circle"></i>
                </button>
            </td>
        </tr>
    </template>

    <template id="compensation-allowance-row-template">
        <tr>
            <td class="text-center align-middle">
                <span class="compensation-row-order"></span>
                <input type="hidden" data-field="position" value="">
            </td>
            <td><input type="text" class="form-control" data-field="from_period"></td>
            <td><input type="text" class="form-control" data-field="to_period"></td>
            <td><input type="text" class="form-control" data-field="allowance_type"></td>
            <td><input type="number" step="0.01" class="form-control" data-field="benefit_percentage"></td>
            <td><input type="number" step="0.01" class="form-control" data-field="coefficient"></td>
            <td><input type="number" step="0.01" class="form-control" data-field="value"></td>
            <td class="text-center">
                <button type="button"
                        class="btn btn-link text-danger p-0"
                        data-remove-compensation-row>
                    <i class="bi bi-x-circle"></i>
                </button>
            </td>
        </tr>
    </template>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-1"></i>
            Lưu thay đổi
        </button>
    </div>
</div>

<template id="compensation-salary-row-template">
    <tr>
        <td class="text-center align-middle">
            <span class="compensation-row-order"></span>
            <input type="hidden" data-field="position" value="0">
        </td>
        <td>
            <input type="text" class="form-control" data-field="from_period">
        </td>
        <td>
            <input type="text" class="form-control" data-field="to_period">
        </td>
        <td>
            <input type="number" step="0.01" class="form-control text-end" data-field="coefficient">
        </td>
        <td>
            <div class="input-group">
                <input type="number" step="0.01" class="form-control text-end" data-field="benefit_percentage">
                <span class="input-group-text">%</span>
            </div>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-link text-danger p-0" data-remove-compensation-row>
                <i class="bi bi-x-circle"></i>
            </button>
        </td>
    </tr>
</template>

<template id="compensation-allowance-row-template">
    <tr>
        <td class="text-center align-middle">
            <span class="compensation-row-order"></span>
            <input type="hidden" data-field="position" value="0">
        </td>
        <td>
            <input type="text" class="form-control" data-field="from_period">
        </td>
        <td>
            <input type="text" class="form-control" data-field="to_period">
        </td>
        <td>
            <input type="text" class="form-control" data-field="allowance_type">
        </td>
        <td>
            <div class="input-group">
                <input type="number" step="0.01" class="form-control text-end" data-field="salary_percentage">
                <span class="input-group-text">%</span>
            </div>
        </td>
        <td>
            <input type="number" step="0.01" class="form-control text-end" data-field="coefficient">
        </td>
        <td>
            <div class="input-group">
                <input type="number" step="0.01" class="form-control text-end" data-field="amount">
                <span class="input-group-text">₫</span>
            </div>
        </td>
        <td class="text-center align-middle">
            <button type="button" class="btn btn-link text-danger p-0" data-remove-compensation-row>
                <i class="bi bi-x-circle"></i>
            </button>
        </td>
    </tr>
</template>

@elseif ($mode === 'recognition')
    @php
        $rewardRows = collect(old('reward_records', optional($info)->rewardRecords?->map(function ($record) {
            return [
                'year' => $record->year,
                'title' => $record->title,
                'awarding_level' => $record->awarding_level,
                'awarding_form' => $record->awarding_form,
                'position' => $record->position ?? 0,
            ];
        })->toArray() ?? []));

        $disciplineRows = collect(old('discipline_records', optional($info)->disciplineRecords?->map(function ($record) {
            return [
                'year' => $record->year,
                'discipline_form' => $record->discipline_form,
                'reason' => $record->reason,
                'issued_by' => $record->issued_by,
                'position' => $record->position ?? 0,
            ];
        })->toArray() ?? []));

        $rewardRows = $rewardRows->sortBy('position')->values();
        $disciplineRows = $disciplineRows->sortBy('position')->values();

        if ($rewardRows->isEmpty()) {
            $rewardRows = collect([[
                'year' => null,
                'title' => null,
                'awarding_level' => null,
                'awarding_form' => null,
                'position' => 0,
            ]]);
        }

        if ($disciplineRows->isEmpty()) {
            $disciplineRows = collect([[
                'year' => null,
                'discipline_form' => null,
                'reason' => null,
                'issued_by' => null,
                'position' => 0,
            ]]);
        }
    @endphp

    <div class="vstack gap-4" data-compensation-form-root>
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3 d-flex align-items-center justify-content-between">
                <div>
                    <i class="bi bi-trophy-fill text-primary me-2"></i>
                    <span class="fw-semibold">Khen thưởng</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-add-compensation-row="reward">
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
                                <th>Danh hiệu khen thưởng</th>
                                <th style="width: 220px;">Cấp khen thưởng</th>
                                <th style="width: 220px;">Hình thức khen thưởng</th>
                                <th class="text-center" style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody data-compensation-group="reward" data-compensation-prefix="reward_records">
                            @foreach ($rewardRows as $index => $row)
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="compensation-row-order"></span>
                                        <input type="hidden" data-field="position" name="reward_records[{{ $index }}][position]" value="{{ $row['position'] ?? $index }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('reward_records.' . $index . '.year') is-invalid @enderror" data-field="year" name="reward_records[{{ $index }}][year]" value="{{ $row['year'] }}">
                                        @error('reward_records.' . $index . '.year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('reward_records.' . $index . '.title') is-invalid @enderror" data-field="title" name="reward_records[{{ $index }}][title]" value="{{ $row['title'] }}">
                                        @error('reward_records.' . $index . '.title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('reward_records.' . $index . '.awarding_level') is-invalid @enderror" data-field="awarding_level" name="reward_records[{{ $index }}][awarding_level]" value="{{ $row['awarding_level'] }}">
                                        @error('reward_records.' . $index . '.awarding_level')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('reward_records.' . $index . '.awarding_form') is-invalid @enderror" data-field="awarding_form" name="reward_records[{{ $index }}][awarding_form]" value="{{ $row['awarding_form'] }}">
                                        @error('reward_records.' . $index . '.awarding_form')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-link text-danger p-0" data-remove-compensation-row>
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
                    <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>
                    <span class="fw-semibold">Kỷ luật</span>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" data-add-compensation-row="discipline">
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
                                <th style="width: 220px;">Hình thức kỷ luật</th>
                                <th>Lý do</th>
                                <th style="width: 220px;">Cơ quan ban hành</th>
                                <th class="text-center" style="width: 60px;"></th>
                            </tr>
                        </thead>
                        <tbody data-compensation-group="discipline" data-compensation-prefix="discipline_records">
                            @foreach ($disciplineRows as $index => $row)
                                <tr>
                                    <td class="text-center align-middle">
                                        <span class="compensation-row-order"></span>
                                        <input type="hidden" data-field="position" name="discipline_records[{{ $index }}][position]" value="{{ $row['position'] ?? $index }}">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('discipline_records.' . $index . '.year') is-invalid @enderror" data-field="year" name="discipline_records[{{ $index }}][year]" value="{{ $row['year'] }}">
                                        @error('discipline_records.' . $index . '.year')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('discipline_records.' . $index . '.discipline_form') is-invalid @enderror" data-field="discipline_form" name="discipline_records[{{ $index }}][discipline_form]" value="{{ $row['discipline_form'] }}">
                                        @error('discipline_records.' . $index . '.discipline_form')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('discipline_records.' . $index . '.reason') is-invalid @enderror" data-field="reason" name="discipline_records[{{ $index }}][reason]" value="{{ $row['reason'] }}">
                                        @error('discipline_records.' . $index . '.reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td>
                                        <input type="text" class="form-control @error('discipline_records.' . $index . '.issued_by') is-invalid @enderror" data-field="issued_by" name="discipline_records[{{ $index }}][issued_by]" value="{{ $row['issued_by'] }}">
                                        @error('discipline_records.' . $index . '.issued_by')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </td>
                                    <td class="text-center align-middle">
                                        <button type="button" class="btn btn-link text-danger p-0" data-remove-compensation-row>
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

    <template id="compensation-reward-row-template">
        <tr>
            <td class="text-center align-middle">
                <span class="compensation-row-order"></span>
                <input type="hidden" data-field="position" value="0">
            </td>
            <td>
                <input type="text" class="form-control" data-field="year">
            </td>
            <td>
                <input type="text" class="form-control" data-field="title">
            </td>
            <td>
                <input type="text" class="form-control" data-field="awarding_level">
            </td>
            <td>
                <input type="text" class="form-control" data-field="awarding_form">
            </td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-link text-danger p-0" data-remove-compensation-row>
                    <i class="bi bi-x-circle"></i>
                </button>
            </td>
        </tr>
    </template>

    <template id="compensation-discipline-row-template">
        <tr>
            <td class="text-center align-middle">
                <span class="compensation-row-order"></span>
                <input type="hidden" data-field="position" value="0">
            </td>
            <td>
                <input type="text" class="form-control" data-field="year">
            </td>
            <td>
                <input type="text" class="form-control" data-field="discipline_form">
            </td>
            <td>
                <input type="text" class="form-control" data-field="reason">
            </td>
            <td>
                <input type="text" class="form-control" data-field="issued_by">
            </td>
            <td class="text-center align-middle">
                <button type="button" class="btn btn-link text-danger p-0" data-remove-compensation-row>
                    <i class="bi bi-x-circle"></i>
                </button>
            </td>
        </tr>
    </template>
@endif

<script>
    (() => {
        const initializeCompensationForm = () => {
            const root = document.querySelector('[data-compensation-form-root]');

            if (!root || root.dataset.enhanced === 'true') {
                return;
            }

            root.dataset.enhanced = 'true';

            const groups = {
                salary: {
                    tbody: root.querySelector('[data-compensation-group="salary"]'),
                    template: root.querySelector('#compensation-salary-row-template'),
                    prefix: 'salary_records'
                },
                allowance: {
                    tbody: root.querySelector('[data-compensation-group="allowance"]'),
                    template: root.querySelector('#compensation-allowance-row-template'),
                    prefix: 'allowance_records'
                }
            };

            const updateRowOrder = (groupKey) => {
                const group = groups[groupKey];

                if (!group || !group.tbody) {
                    return;
                }

                Array.from(group.tbody.children).forEach((row, index) => {
                    const orderElement = row.querySelector('.compensation-row-order');

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

            root.querySelectorAll('[data-add-compensation-row]').forEach((button) => {
                const groupKey = button.dataset.addCompensationRow;

                button.addEventListener('click', () => addRow(groupKey));
            });

            Object.entries(groups).forEach(([groupKey, group]) => {
                if (!group.tbody) {
                    return;
                }

                updateRowOrder(groupKey);

                group.tbody.addEventListener('click', (event) => {
                    if (!event.target.closest('[data-remove-compensation-row]')) {
                        return;
                    }

                    const rows = Array.from(group.tbody.children);

                    if (rows.length === 1) {
                        rows[0].querySelectorAll('input').forEach((input) => {
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

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initializeCompensationForm);
        } else {
            initializeCompensationForm();
        }
    })();
</script>