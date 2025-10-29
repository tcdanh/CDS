@extends('layouts.app')

@php
    $mode = $mode ?? 'personal';
    $isFamily = $mode === 'family';
@endphp

@section('title', $isFamily ? 'Quan hệ gia đình' : 'Sơ yếu lý lịch nhân sự')

@section('content')
<div class="card shadow-sm">
    <div class="card-header d-flex flex-wrap gap-2 justify-content-between align-items-center bg-white">
        <div>
            <h3 class="card-title mb-0">{{ $isFamily ? 'Danh sách quan hệ gia đình' : 'Danh sách sơ yếu lý lịch' }}</h3>
            <p class="mb-0 text-muted small">
                {{ $isFamily ? 'Xem và quản lý thông tin gia đình, tài sản nhà đất của từng nhân sự' : 'Quản lý và xem hồ sơ của từng nhân sự' }}
            </p>
        </div>
        <div class="d-flex gap-2">
            <a
                href="{{ route($isFamily ? 'scientific-profiles.show' : 'scientific-profiles.family', ['user' => $currentUser->getKey()]) }}"
                class="btn btn-outline-secondary"
            >
                <i class="bi bi-arrow-left-right me-1"></i>
                {{ $isFamily ? 'Xem thông tin cá nhân' : 'Xem quan hệ gia đình' }}
            </a>
            <a href="{{ route($isFamily ? 'scientific-profiles.family' : 'scientific-profiles.show', ['user' => $currentUser->getKey()]) }}" class="btn btn-outline-primary">
                <i class="bi bi-person-badge me-1"></i>
                Hồ sơ của tôi
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th scope="col" class="text-center" style="width: 80px;">STT</th>
                        <th scope="col">Họ và tên</th>
                        <th scope="col" style="width: 220px;">Cập nhật lúc</th>
                        <th scope="col" class="text-center" style="width: 120px;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($personalInfos as $index => $personalInfo)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="fw-semibold">{{ $personalInfo->full_name ?? $personalInfo->user?->name ?? 'Chưa cập nhật' }}</div>
                                @if ($isFamily)
                                    @php
                                        $familyCount = $personalInfo->family_members_count ?? 0;
                                        $assetCount = $personalInfo->family_assets_count ?? 0;
                                    @endphp
                                    <div class="text-muted small">
                                        {{ $familyCount }} thành viên gia đình
                                        <span class="mx-1">•</span>
                                        {{ $assetCount }} tài sản nhà đất
                                    </div>
                                @else
                                    <div class="text-muted small">{{ $personalInfo->main_job_title ?? '—' }}</div>
                                @endif
                            </td>
                            <td>
                                @if ($personalInfo->updated_at)
                                    {{ $personalInfo->updated_at->timezone(config('app.timezone'))->format('d/m/Y H:i') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-center">
                                <a
                                    href="{{ route($isFamily ? 'scientific-profiles.family' : 'scientific-profiles.show', ['user' => $personalInfo->user_id]) }}"
                                    class="btn btn-sm btn-outline-primary"
                                    title="{{ $isFamily ? 'Xem thông tin gia đình' : 'Xem thông tin cá nhân' }}"
                                >
                                    <i class="bi {{ $isFamily ? 'bi-people' : 'bi-eye' }}"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Chưa có hồ sơ nào trong hệ thống.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection