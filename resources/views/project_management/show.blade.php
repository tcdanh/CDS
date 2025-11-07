@extends('layouts.app')

@php
    $detail = $project->detail;
    $formatCurrency = fn ($value) => is_null($value) ? '—' : number_format($value, 0, ',', '.');
@endphp

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Chi tiết dự án</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('project-management.index') }}">Project Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Chi tiết dự án</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">{{ $project->name_vi }}</h3>
                <a href="{{ route('project-management.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Quay lại
                </a>
            </div>
            <div class="card-body">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('info'))
                    <div class="alert alert-info">{{ session('info') }}</div>
                @endif

                <div class="mb-4">
                    <h5 class="fw-bold">Thông tin chung</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <tbody>
                                <tr>
                                    <th style="width: 280px;">Tên dự án (VI)</th>
                                    <td>{{ $project->name_vi }}</td>
                                </tr>
                                <tr>
                                    <th>Tên dự án (EN)</th>
                                    <td>{{ $project->name_en }}</td>
                                </tr>
                                <tr>
                                    <th>Nhóm ngành</th>
                                    <td>{{ $project->industry_group ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Loại hình nghiên cứu</th>
                                    <td>{{ $project->research_type ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Thời gian thực hiện</th>
                                    <td>{{ $project->implementation_time ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Chủ nhiệm đề tài</th>
                                    <td>{{ optional($project->principalInvestigator)->full_name ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Thư ký khoa học</th>
                                    <td>{{ $project->science_secretary ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Tổng kinh phí (VNĐ)</th>
                                    <td>{{ is_null($project->total_budget) ? '—' : number_format($project->total_budget, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <th>Tình trạng</th>
                                    <td>{{ $project->status ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th>Ghi chú</th>
                                    <td>{{ $project->notes ?? '—' }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="fw-bold mb-0">Thông tin hợp đồng &amp; tài chính</h5>
                        @if ($detail)
                            <a href="{{ route('project-details.edit', $project) }}" class="btn btn-sm btn-primary">Chỉnh sửa thông tin</a>
                        @endif
                    </div>
                    
                    @if ($detail)
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle">
                                <tbody>
                                    <tr>
                                        <th style="width: 280px;">Số hợp đồng</th>
                                        <td>{{ $detail->contract_number ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Ngày ký hợp đồng</th>
                                        <td>{{ optional($detail->contract_signed_at)->format('d/m/Y') ?? '—' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nơi lưu hợp đồng (file)</th>
                                        <td>
                                            @if ($detail->contract_storage_path)
                                                <a href="{{ route('project-details.download', $project) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="bi bi-download me-1"></i>Tải xuống
                                                </a>
                                                <div class="text-muted small mt-1">{{ basename($detail->contract_storage_path) }}</div>
                                            @else
                                                —
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Tiền công trực tiếp (VNĐ)</th>
                                        <td>{{ $formatCurrency($detail->direct_labor_cost) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tiền vật tư (VNĐ)</th>
                                        <td>{{ $formatCurrency($detail->material_cost) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tiền chi khác (VNĐ)</th>
                                        <td>{{ $formatCurrency($detail->other_cost) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Tiền quản lý nhiệm vụ (VNĐ)</th>
                                        <td>{{ $formatCurrency($detail->management_cost) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Có gia hạn không?</th>
                                        <td>{{ $detail->is_extended ? 'Có' : 'Không' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Thông tin gia hạn</th>
                                        <td>{{ $detail->extension_details ?? '—' }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            Chưa có dữ liệu chi tiết cho dự án này.
                        </div>
                        <a href="{{ route('project-details.create', $project) }}" class="btn btn-primary">Thêm thông tin</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection