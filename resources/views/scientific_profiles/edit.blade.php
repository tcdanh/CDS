@extends('layouts.app')

@section('title', 'Cập nhật thông tin cá nhân')

@section('content')
<div class="row g-4 align-items-start">
    <div class="col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold">Hướng dẫn</h5>
                <p class="text-muted small">Cập nhật đầy đủ các thông tin cá nhân để hiển thị trong trang hồ sơ của bạn. Những trường không có thông tin có thể để trống.</p>
                <a href="{{ route('scientific-profiles.show') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-arrow-left-short me-1"></i>
                    Quay lại trang lý lịch
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Chỉnh sửa thông tin</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('scientific-profiles.update') }}" method="post" enctype="multipart/form-data">
                    @include('scientific_profiles.partials.form')
                </form>
            </div>
        </div>
    </div>
</div>
@endsection