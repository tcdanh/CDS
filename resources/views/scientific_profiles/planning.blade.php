@extends('layouts.app')

@section('title', 'Quy hoạch')

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

    $records = optional($info)->planningRecords ?? collect();

    if (! $records instanceof \Illuminate\Support\Collection) {
        $records = collect($records);
    }

    $groupedRecords = $records
        ->groupBy('category')
        ->map(fn ($group) => $group->sortBy('position')->values());

    $categories = [
        \App\Models\PlanningRecord::CATEGORY_GOVERNMENT => [
            'label' => 'Quy hoạch chính quyền',
            'icon' => 'bi-building',
            'description' => 'Theo dõi quy hoạch chính quyền theo từng giai đoạn',
        ],
        \App\Models\PlanningRecord::CATEGORY_PARTY => [
            'label' => 'Quy hoạch Đảng',
            'icon' => 'bi-flag',
            'description' => 'Thông tin quy hoạch trong tổ chức Đảng của cán bộ',
        ],
    ];
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
            <a href="{{ route('scientific-profiles.planning') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-short me-1"></i>
                Quay lại danh sách quy hoạch
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
    </div>
    @if ($canEdit)
        <a href="{{ route('scientific-profiles.edit') }}" class="btn btn-primary">
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
                <p class="text-muted mb-4">Thông tin quy hoạch chức danh</p>
                <div class="vstack gap-3 small text-start text-muted">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-diagram-3"></i>
                        <div>
                            <div class="fw-semibold">Tổng số mục</div>
                            <div>{{ $records->count() }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-collection"></i>
                        <div>
                            <div class="fw-semibold">Số nhóm quy hoạch</div>
                            <div>{{ $groupedRecords->filter->isNotEmpty()->count() }}</div>
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
        <div class="vstack gap-4">
            @foreach ($categories as $category => $config)
                @php
                    $items = $groupedRecords->get($category, collect());
                @endphp
                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <div>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi {{ $config['icon'] }} text-primary"></i>
                                <h4 class="card-title mb-0">{{ $config['label'] }}</h4>
                            </div>
                            <p class="text-muted small mb-0">{{ $config['description'] }}</p>
                        </div>
                        <span class="badge text-bg-light text-dark">{{ $items->count() }} mục</span>
                    </div>
                    <div class="card-body p-0">
                        @if ($items->isNotEmpty())
                            <div class="list-group list-group-flush">
                                @foreach ($items as $index => $record)
                                    <div class="list-group-item">
                                        <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                                            <div>
                                                <div class="fw-semibold">{{ $record->position_title ?? '—' }}</div>
                                                <div class="text-muted small text-uppercase">Đối tượng</div>
                                            </div>
                                            <div class="text-end">
                                                <div class="fw-semibold">{{ $record->stage ?? '—' }}</div>
                                                <div class="text-muted small text-uppercase">Giai đoạn</div>
                                            </div>
                                        </div>
                                        @if ($record->status)
                                            <div class="mt-2">
                                                <span class="badge rounded-pill text-bg-light text-primary fw-semibold">{{ $record->status }}</span>
                                            </div>
                                        @endif
                                        @if ($record->notes)
                                            <p class="text-muted small mb-0 mt-2">{{ $record->notes }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="p-4 text-center text-muted">
                                <i class="bi bi-inboxes mb-2 fs-3 d-block"></i>
                                <p class="mb-0">Chưa cập nhật thông tin quy hoạch cho nhóm này.</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection