@extends('layouts.app')

@section('title', 'Lịch sử bản thân')

@section('content')
@php
    $canEdit = $canEdit ?? true;
    $canManageProfiles = $canManageProfiles ?? false;
    $viewer = request()->user();
    $targetUser = $targetUser ?? $viewer;
    $viewingOwnProfile = $viewer && $targetUser && $targetUser->is($viewer);
    $personalRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $familyRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $history = $history ?? optional($info)->personalHistory;
    $workRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $trainingRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
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
            <a href="{{ route('scientific-profiles.history') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-short me-1"></i>
                Quay lại danh sách lịch sử bản thân
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
        <a href="{{ route('scientific-profiles.work', $workRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-briefcase me-1"></i>
            Quá trình công tác
        </a>
        <a href="{{ route('scientific-profiles.training', $trainingRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-mortarboard me-1"></i>
            Quá trình đào tạo
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
                <p class="text-muted mb-4">Thông tin lịch sử bản thân</p>
                <div class="vstack gap-3 small text-start text-muted">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-person-badge"></i>
                        <div>
                            <div class="fw-semibold">Chức vụ hiện tại</div>
                            <div>{{ $info->main_job_title ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-telephone"></i>
                        <div>
                            <div class="fw-semibold">Điện thoại</div>
                            <div>{{ $info->phone_number ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-envelope-at"></i>
                        <div>
                            <div class="fw-semibold">Email</div>
                            <div>{{ $info->email ?? 'Chưa cập nhật' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">Khai ai, bị bắt, bị tù</h4>
            </div>
            <div class="card-body">
                @php
                    $imprisonment = optional($history)->imprisonment_history;
                @endphp
                @if ($imprisonment)
                    <p class="mb-0" style="white-space: pre-line;">{{ $imprisonment }}</p>
                @else
                    <p class="text-muted fst-italic mb-0">Chưa cập nhật thông tin.</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">Chức vụ trong chế độ cũ</h4>
            </div>
            <div class="card-body">
                @php
                    $oldRegime = optional($history)->old_regime_roles;
                @endphp
                @if ($oldRegime)
                    <p class="mb-0" style="white-space: pre-line;">{{ $oldRegime }}</p>
                @else
                    <p class="text-muted fst-italic mb-0">Chưa cập nhật thông tin.</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">Quan hệ với tổ chức, cá nhân nước ngoài</h4>
            </div>
            <div class="card-body">
                @php
                    $foreignRelations = optional($history)->foreign_relations;
                @endphp
                @if ($foreignRelations)
                    <p class="mb-0" style="white-space: pre-line;">{{ $foreignRelations }}</p>
                @else
                    <p class="text-muted fst-italic mb-0">Chưa cập nhật thông tin.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection