@extends('layouts.app')

@section('title', 'Cập nhật đề tài - dự án')

@section('content')
<div class="row g-4 align-items-start">
    <div class="col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold">Hướng dẫn</h5>
                <p class="text-muted small">Chỉ cập nhật các thông tin liên quan đến quá trình tham gia đề tài, dự án khoa học.
                    Các mục khác trong hồ sơ sẽ được giữ nguyên.</p>
                <a href="{{ route('scientific-profiles.research') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-arrow-left-short me-1"></i>
                    Quay lại đề tài - dự án
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Chỉnh sửa đề tài - dự án</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('scientific-profiles.update') }}" method="post">
                    @include('scientific_profiles.partials.teach-research-form', ['mode' => 'research'])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection