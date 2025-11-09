@extends('layouts.app')

@section('title', 'Đề tài - Dự án')

@section('content')
@php
    $canEdit = $canEdit ?? true;
    $canManageProfiles = $canManageProfiles ?? false;
    $viewer = request()->user();
    $targetUser = $targetUser ?? $viewer;
    $viewingOwnProfile = $viewer && $targetUser && $targetUser->is($viewer);
    $routeParams = $viewingOwnProfile ? [] : ['user' => $targetUser->getKey()];

    $projects = optional($info)->researchProjectRecords ?? collect();

    if (! $projects instanceof \Illuminate\Support\Collection) {
        $projects = collect($projects);
    }

    $totalBudget = $projects->sum(fn ($record) => $record->budget_million_vnd ?? 0);
@endphp

@if (session('status') === 'personal-info-updated')
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle-fill me-2"></i>
        Cập nhật dữ liệu thành công.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<div class="d-flex flex-wrap gap-2 align-items-center justify-content-between mb-4">
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route('scientific-profiles.show', $routeParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-person-vcard me-1"></i>
            Thông tin cá nhân
        </a>
        <a href="{{ route('scientific-profiles.family', $routeParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-people me-1"></i>
            Quan hệ gia đình
        </a>
        <a href="{{ route('scientific-profiles.history', $routeParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-journal-text me-1"></i>
            Lịch sử bản thân
        </a>
        <a href="{{ route('scientific-profiles.training', $routeParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-mortarboard me-1"></i>
            Quá trình đào tạo
        </a>
        <a href="{{ route('scientific-profiles.work', $routeParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-briefcase me-1"></i>
            Quá trình công tác
        </a>
        <a href="{{ route('scientific-profiles.planning', $routeParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-diagram-3 me-1"></i>
            Quy hoạch
        </a>
        <a href="{{ route('scientific-profiles.teaching', $routeParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-easel2 me-1"></i>
            Hoạt động giảng dạy
        </a>
        <a href="{{ route('scientific-profiles.awards', $routeParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-trophy me-1"></i>
            Giải thưởng
        </a>
        <a href="{{ route('scientific-profiles.publications', $routeParams) }}" class="btn btn-outline-primary">
            <i class="bi bi-journal-richtext me-1"></i>
            Xuất bản & SHTT
        </a>
    </div>
    @if ($canEdit)
        <a href="{{ route('scientific-profiles.research.edit') }}" class="btn btn-primary">
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
                <p class="text-muted mb-4">Hoạt động đề tài - dự án</p>
                <div class="vstack gap-3 small text-start text-muted">
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-collection"></i>
                        <div>
                            <div class="fw-semibold">Tổng số đề tài/dự án</div>
                            <div>{{ $projects->count() }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-cash-coin"></i>
                        <div>
                            <div class="fw-semibold">Tổng kinh phí</div>
                            <div>{{ $totalBudget ? number_format($totalBudget, 0, ',', '.') . ' triệu' : '—' }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-start gap-2">
                        <i class="bi bi-calendar-week"></i>
                        <div>
                            <div class="fw-semibold">Cập nhật gần nhất</div>
                            <div>{{ optional($info->updated_at)->format('d/m/Y') ?? '—' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Danh sách đề tài - dự án</h4>
                <span class="badge text-bg-light text-dark">{{ $projects->count() }} mục</span>
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
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($projects as $index => $record)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $record->from_period ?? '—' }}</td>
                                    <td>{{ $record->to_period ?? '—' }}</td>
                                    <td>{{ $record->project_name ?? '—' }}</td>
                                    <td>{{ $record->project_type ?? '—' }}</td>
                                    <td>{{ $record->role ?? '—' }}</td>
                                    <td>{{ $record->budget_million_vnd ? number_format($record->budget_million_vnd, 0, ',', '.') : '—' }}</td>
                                    <td>{{ $record->status ?? '—' }}</td>
                                    <td>{{ $record->notes ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4 text-muted">Chưa cập nhật thông tin đề tài - dự án.</td>
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