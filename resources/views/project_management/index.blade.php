@extends('layouts.app')

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Project Management</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Project Management</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Danh sách dự án nghiên cứu</h3>
                <a href="{{ route('project-management.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-1"></i>Thêm dự án
                </a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="table-responsive">
                    <table class="table table-bordered table-striped align-middle">
                        <thead class="table-light" style="vertical-align: middle;">
                            <tr>
                                <th class="text-center" style="width: 40px;">#</th>
                                <th>Tên dự án</th>
                                <!--th>Nhóm ngành</th>
                                <th>Loại hình nghiên cứu</th-->
                                <th>Thời gian thực hiện</th>
                                <th style="width: 140px;">Chủ nhiệm/ TK đề tài</th>
                                <!--th>Thư ký khoa học</th-->
                                <th class="text-end">Tổng kinh phí (VND)</th>
                                <th style="width: 100px;">Tình trạng</th>
                                <th style="width: 160px;" class="text-center">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($projects as $index => $project)
                                <tr>
                                    <td class="text-center">{{ $projects->firstItem() + $index }}</td>
                                    <td>
                                        <strong>{{ $project->name_vi }}</strong>
                                        <div class="text-muted small">{{ $project->name_en }}</div>
                                        
                                    </td>
                                    <!--td>{{ $project->industry_group ?? '—' }}</td>
                                    <td>{{ $project->research_type ?? '—' }}</td-->
                                    <td>{{ $project->implementation_time ?? '—' }}</td>
                                    <td>{{ optional($project->principalInvestigator)->full_name ?? '—' }}
                                        <div class="text-muted small">(TK: {{ $project->science_secretary ?? '—' }})</div>
                                    </td>
                                    <!--td>{{ $project->science_secretary ?? '—' }}</td-->
                                    <td class="text-end">
                                        @if (! is_null($project->total_budget))
                                            {{ number_format($project->total_budget, 0, ',', '.') }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td>{{ $project->status ?? '—' }}
                                        @if ($project->notes)
                                            <div class="mt-1 small">({{ Str::limit($project->notes, 80) }})</div>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="#" class="btn btn-sm btn-primary me-1">Xem</a>
                                        <a href="{{ route('project-management.edit', $project) }}" class="btn btn-sm btn-warning me-1">Sửa</a>
                                        <form action="{{ route('project-management.destroy', $project) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xoá dự án này?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center text-muted">Chưa có dự án nào được tạo.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{ $projects->links() }}
            </div>
        </div>
    </div>
</section>
@endsection