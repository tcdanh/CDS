@extends('layouts.app')

@section('title', 'Lương & Phụ cấp')

@section('content')
@php
    $canEdit = $canEdit ?? true;
    $canManageProfiles = $canManageProfiles ?? false;
    $viewer = request()->user();
    $targetUser = $targetUser ?? $viewer;
    $viewingOwnProfile = $viewer && $targetUser && $targetUser->is($viewer);
    $personalRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $familyRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $historyRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $trainingRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $workRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $planningRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];

    $salaryRecords = optional($info)->salaryRecords ?? collect();
    $allowanceRecords = optional($info)->allowanceRecords ?? collect();

    if (! $salaryRecords instanceof \Illuminate\Support\Collection) {
        $salaryRecords = collect($salaryRecords);
    }

    if (! $allowanceRecords instanceof \Illuminate\Support\Collection) {
        $allowanceRecords = collect($allowanceRecords);
    }

    $formatDecimal = function ($value, string $suffix = '') {
        if ($value === null) {
            return '—';
        }

        $formatted = rtrim(rtrim(number_format((float) $value, 2, ',', '.'), '0'), ',');

        return $suffix ? $formatted . $suffix : $formatted;
    };

    $formatCurrency = function ($value) {
        if ($value === null) {
            return '—';
        }

        return number_format((float) $value, 0, ',', '.') . ' ₫';
    };
@endphp

@if (session('status') === 'personal-info-updated')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        Lưu thông tin cá nhân thành công.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-4">
    <div class="d-flex flex-wrap gap-2">
        @if ($canManageProfiles)
            <a href="{{ route('scientific-profiles.compensation') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-short me-1"></i>
                Quay lại danh sách lương & phụ cấp
            </a>
        @endif
        <a href="{{ route('scientific-profiles.show', $personalRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-person-vcard me-1"></i>
            Xem thông tin cá nhân
        </a>
        <a href="{{ route('scientific-profiles.family', $familyRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-people me-1"></i>
            Xem quan hệ gia đình
        </a>
        <a href="{{ route('scientific-profiles.history', $historyRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-journal-text me-1"></i>
            Xem lịch sử bản thân
        </a>
        <a href="{{ route('scientific-profiles.training', $trainingRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-mortarboard me-1"></i>
            Quá trình đào tạo
        </a>
        <a href="{{ route('scientific-profiles.work', $workRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-briefcase me-1"></i>
            Quá trình công tác
        </a>
        <a href="{{ route('scientific-profiles.planning', $planningRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-diagram-3 me-1"></i>
            Quy hoạch
        </a>
    </div>
    @if ($canEdit)
        <a href="{{ route('scientific-profiles.compensation.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i>
            Cập nhật hồ sơ
        </a>
    @endif
</div>

<div class="row g-4 align-items-start">
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <img src="{{ $info->avatar_url }}" alt="Ảnh đại diện" class="rounded-circle mb-3" width="120" height="120">
                <h5 class="fw-bold mb-1">{{ $info->full_name }}</h5>
                <p class="text-muted mb-4">Thông tin lương & phụ cấp</p>
                <div class="vstack gap-3 small text-start text-muted">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-cash-stack"></i>
                        <div>
                            <div class="fw-semibold">Tổng mục lương</div>
                            <div>{{ $salaryRecords->count() }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-wallet2"></i>
                        <div>
                            <div class="fw-semibold">Tổng mục phụ cấp</div>
                            <div>{{ $allowanceRecords->count() }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-person-badge"></i>
                        <div>
                            <div class="fw-semibold">Chức vụ hiện tại</div>
                            <div>{{ $info->main_job_title ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Lương</h4>
                <span class="badge text-bg-light text-dark">{{ $salaryRecords->count() }} mục</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px;">STT</th>
                                <th style="width: 150px;">Từ</th>
                                <th style="width: 150px;">Đến</th>
                                <th style="width: 140px;">Hệ số</th>
                                <th style="width: 160px;">Phần trăm hưởng</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($salaryRecords as $index => $record)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $record->from_period ?? '—' }}</td>
                                    <td>{{ $record->to_period ?? '—' }}</td>
                                    <td>{{ $formatDecimal($record->coefficient) }}</td>
                                    <td>{{ $formatDecimal($record->benefit_percentage, '%') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Chưa cập nhật thông tin lương.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Phụ cấp</h4>
                <span class="badge text-bg-light text-dark">{{ $allowanceRecords->count() }} mục</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 60px;">STT</th>
                                <th style="width: 150px;">Từ</th>
                                <th style="width: 150px;">Đến</th>
                                <th>Loại phụ cấp</th>
                                <th style="width: 160px;">Phần trăm lương</th>
                                <th style="width: 140px;">Hệ số</th>
                                <th style="width: 160px;">Giá trị</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($allowanceRecords as $index => $record)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $record->from_period ?? '—' }}</td>
                                    <td>{{ $record->to_period ?? '—' }}</td>
                                    <td>{{ $record->allowance_type ?? '—' }}</td>
                                    <td>{{ $formatDecimal($record->salary_percentage, '%') }}</td>
                                    <td>{{ $formatDecimal($record->coefficient) }}</td>
                                    <td>{{ $formatCurrency($record->amount) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-muted">Chưa cập nhật thông tin phụ cấp.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection