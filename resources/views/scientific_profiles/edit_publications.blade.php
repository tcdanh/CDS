@extends('layouts.app')

@section('title', 'Cập nhật xuất bản & sở hữu trí tuệ')

@section('content')
<div class="row g-4 align-items-start">
    <div class="col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold">Hướng dẫn</h5>
                <p class="text-muted small">Chỉ cập nhật các thông tin liên quan đến công bố khoa học và sở hữu trí tuệ.
                    Các mục khác trong hồ sơ sẽ được giữ nguyên.</p>
                <a href="{{ route('scientific-profiles.publications') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-arrow-left-short me-1"></i>
                    Quay lại xuất bản & SHTT
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Chỉnh sửa xuất bản & SHTT</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('scientific-profiles.update') }}" method="post">
                    @include('scientific_profiles.partials.teach-research-form', ['mode' => 'publications'])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection