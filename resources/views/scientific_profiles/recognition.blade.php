@extends('layouts.app')

@section('title', 'Khen thưởng - Kỷ luật')

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
    $compensationRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];

    $rewardRecords = optional($info)->rewardRecords ?? collect();
    $disciplineRecords = optional($info)->disciplineRecords ?? collect();

    if (! $rewardRecords instanceof \Illuminate\Support\Collection) {
        $rewardRecords = collect($rewardRecords);
    }

    if (! $disciplineRecords instanceof \Illuminate\Support\Collection) {
        $disciplineRecords = collect($disciplineRecords);
    }
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
            <a href="{{ route('scientific-profiles.recognition') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-short me-1"></i>
                Quay lại danh sách khen thưởng - kỷ luật
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
        <a href="{{ route('scientific-profiles.compensation', $compensationRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-cash-coin me-1"></i>
            Lương - Phụ cấp
        </a>
    </div>
    @if ($canEdit)
        <a href="{{ route('scientific-profiles.recognition.edit') }}" class="btn btn-primary">
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
                <p class="text-muted mb-4">Thông tin khen thưởng - kỷ luật</p>
                <div class="vstack gap-3 small text-start text-muted">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-trophy"></i>
                        <div>
                            <div class="fw-semibold">Tổng mục khen thưởng</div>
                            <div>{{ $rewardRecords->count() }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-exclamation-triangle"></i>
                        <div>
                            <div class="fw-semibold">Tổng mục kỷ luật</div>
                            <div>{{ $disciplineRecords->count() }}</div>
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
                <h4 class="card-title mb-0">Khen thưởng</h4>
                <span class="badge text-bg-light text-dark">{{ $rewardRecords->count() }} mục</span>
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
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($rewardRecords as $index => $record)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $record->year ?? '—' }}</td>
                                    <td>{{ $record->title ?? '—' }}</td>
                                    <td>{{ $record->awarding_level ?? '—' }}</td>
                                    <td>{{ $record->awarding_form ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Chưa cập nhật thông tin khen thưởng.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Kỷ luật</h4>
                <span class="badge text-bg-light text-dark">{{ $disciplineRecords->count() }} mục</span>
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
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($disciplineRecords as $index => $record)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $record->year ?? '—' }}</td>
                                    <td>{{ $record->discipline_form ?? '—' }}</td>
                                    <td>{{ $record->reason ?? '—' }}</td>
                                    <td>{{ $record->issued_by ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Chưa cập nhật thông tin kỷ luật.</td>
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