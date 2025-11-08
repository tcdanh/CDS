@extends('layouts.app')

@section('title', 'Cập nhật khen thưởng - Kỷ luật')

@section('content')
<div class="row g-4 align-items-start">
    <div class="col-lg-3">
        <div class="card shadow-sm h-100">
            <div class="card-body">
                <h5 class="fw-bold">Hướng dẫn</h5>
                <p class="text-muted small">Chỉ cập nhật các thông tin liên quan đến khen thưởng và kỷ luật. Các mục khác trong hồ sơ sẽ được giữ nguyên.</p>
                <a href="{{ route('scientific-profiles.recognition') }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-arrow-left-short me-1"></i>
                    Quay lại danh sách khen thưởng - kỷ luật
                </a>
            </div>
        </div>
    </div>
    <div class="col-lg-9">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Chỉnh sửa khen thưởng - kỷ luật</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('scientific-profiles.update') }}" method="post">
                    @include('scientific_profiles.partials.compensation-form', ['mode' => 'recognition'])
                </form>
            </div>
        </div>
    </div>
</div>
@endsection