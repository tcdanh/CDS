@extends('layouts.app')

@section('title', 'Quan hệ gia đình')

@section('content')
@php
    $canEdit = $canEdit ?? true;
    $canManageProfiles = $canManageProfiles ?? false;
    $viewer = request()->user();
    $targetUser = $targetUser ?? $viewer;
    $viewingOwnProfile = $viewer && $targetUser && $targetUser->is($viewer);
    $personalRouteParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];
    $selfFamily = $info->immediateFamilyMembers;
    $spouseFamily = $info->spouseFamilyMembers;
    $assets = $info->familyAssets;
@endphp

<div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-4">
    <div class="d-flex gap-2">
        @if ($canManageProfiles)
            <a href="{{ route('scientific-profiles.family') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left-short me-1"></i>
                Quay lại danh sách quan hệ gia đình
            </a>
        @endif
        <a href="{{ route('scientific-profiles.show', $personalRouteParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-person-vcard me-1"></i>
            Xem thông tin cá nhân
        </a>
    </div>
    @if ($canEdit)
        <a href="{{ route('scientific-profiles.edit') }}" class="btn btn-primary">
            <i class="bi bi-pencil-square me-1"></i>
            Cập nhật hồ sơ
        </a>
    @endif
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card shadow-sm h-100">
            <div class="card-body text-center">
                <img src="{{ $info->avatar_url }}" alt="Ảnh đại diện" class="rounded-circle mb-3" width="120" height="120">
                <h5 class="fw-bold mb-1">{{ $info->full_name }}</h5>
                <p class="text-muted mb-4">Thông tin gia đình & tài sản</p>
                <div class="row g-3 mb-4">
                    <div class="col-6">
                        <div class="border border-primary border-opacity-25 rounded-3 py-3">
                            <div class="text-muted small text-uppercase">Gia đình</div>
                            <div class="fs-4 fw-bold">{{ $selfFamily->count() }}</div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="border border-danger border-opacity-25 rounded-3 py-3">
                            <div class="text-muted small text-uppercase">Gia đình vợ/chồng</div>
                            <div class="fs-4 fw-bold">{{ $spouseFamily->count() }}</div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="border border-success border-opacity-25 rounded-3 py-3">
                            <div class="text-muted small text-uppercase">Tài sản nhà đất</div>
                            <div class="fs-4 fw-bold">{{ $assets->count() }}</div>
                        </div>
                    </div>
                </div>
                <div class="text-start small text-muted">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-telephone me-2"></i>
                        <span>{{ $info->phone_number ?? 'Chưa cập nhật số điện thoại' }}</span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-envelope-at me-2"></i>
                        <span>{{ $info->email ?? 'Chưa cập nhật email' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">Thành viên gia đình (bản thân)</h4>
            </div>
            <div class="card-body">
                @if ($selfFamily->isNotEmpty())
                    <div class="vstack gap-4">
                        @foreach ($selfFamily as $member)
                            <div class="position-relative ps-4 pb-2 border-start border-3 border-primary">
                                <span class="position-absolute top-0 start-0 translate-middle rounded-circle bg-primary border border-white" style="width: 14px; height: 14px;"></span>
                                <div class="d-flex flex-wrap justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="fw-semibold mb-1">{{ $member->full_name ?? 'Chưa cập nhật' }}</h5>
                                        <span class="badge text-bg-light text-dark">{{ $member->relationship ?? '—' }}</span>
                                    </div>
                                    <div class="text-muted small">Năm sinh: {{ $member->birth_year ?? '—' }}</div>
                                </div>
                                <div class="row g-3 small text-muted">
                                    <div class="col-md-6">
                                        <strong>Quê quán:</strong> {{ $member->hometown ?? '—' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Nơi ở hiện nay:</strong> {{ $member->residence ?? '—' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Nghề nghiệp:</strong> {{ $member->occupation ?? '—' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Đơn vị công tác:</strong> {{ $member->workplace ?? '—' }}
                                    </div>
                                    <div class="col-12">
                                        <strong>Ghi chú:</strong> {{ $member->notes ?? '—' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted fst-italic mb-0">Chưa cập nhật thông tin thành viên gia đình.</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">Thành viên gia đình vợ/chồng</h4>
            </div>
            <div class="card-body">
                @if ($spouseFamily->isNotEmpty())
                    <div class="vstack gap-4">
                        @foreach ($spouseFamily as $member)
                            <div class="position-relative ps-4 pb-2 border-start border-3 border-danger">
                                <span class="position-absolute top-0 start-0 translate-middle rounded-circle bg-danger border border-white" style="width: 14px; height: 14px;"></span>
                                <div class="d-flex flex-wrap justify-content-between align-items-start mb-2">
                                    <div>
                                        <h5 class="fw-semibold mb-1">{{ $member->full_name ?? 'Chưa cập nhật' }}</h5>
                                        <span class="badge text-bg-light text-dark">{{ $member->relationship ?? '—' }}</span>
                                    </div>
                                    <div class="text-muted small">Năm sinh: {{ $member->birth_year ?? '—' }}</div>
                                </div>
                                <div class="row g-3 small text-muted">
                                    <div class="col-md-6">
                                        <strong>Quê quán:</strong> {{ $member->hometown ?? '—' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Nơi ở hiện nay:</strong> {{ $member->residence ?? '—' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Nghề nghiệp:</strong> {{ $member->occupation ?? '—' }}
                                    </div>
                                    <div class="col-md-6">
                                        <strong>Đơn vị công tác:</strong> {{ $member->workplace ?? '—' }}
                                    </div>
                                    <div class="col-12">
                                        <strong>Ghi chú:</strong> {{ $member->notes ?? '—' }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted fst-italic mb-0">Chưa cập nhật thông tin gia đình bên vợ/chồng.</p>
                @endif
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h4 class="card-title mb-0">Tài sản nhà đất</h4>
            </div>
            <div class="card-body">
                @if ($assets->isNotEmpty())
                    <div class="list-group list-group-flush">
                        @foreach ($assets as $asset)
                            <div class="list-group-item px-0">
                                <div class="d-flex justify-content-between align-items-start mb-1">
                                    <h5 class="fw-semibold mb-0">{{ $asset->asset_description ?? 'Chưa cập nhật mô tả' }}</h5>
                                    <span class="badge bg-light text-success">#{{ $loop->iteration }}</span>
                                </div>
                                <div class="text-muted small mb-1">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $asset->asset_address ?? 'Chưa cập nhật địa chỉ' }}
                                </div>
                                <div class="small text-muted">
                                    <strong>Ghi chú:</strong> {{ $asset->notes ?? '—' }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted fst-italic mb-0">Chưa cập nhật thông tin tài sản nhà đất.</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection