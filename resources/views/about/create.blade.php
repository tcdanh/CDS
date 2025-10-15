@extends('layouts.app')

@section('title', 'Tạo Giới thiệu mới')

@section('content')
<div class="container py-4">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tạo Giới thiệu</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('about.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label>Giới thiệu ngắn</label>
                    <textarea name="short_description" class="form-control" rows="3" required>{{ old('short_description') }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Tầm nhìn</label>
                    <textarea name="vision" class="form-control" required>{{ old('vision') }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Sứ mạng</label>
                    <textarea name="mission" class="form-control" required>{{ old('mission') }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Mục tiêu</label>
                    <textarea name="goals" class="form-control" required>{{ old('goals') }}</textarea>
                </div>

                <div class="mb-3">
                    <label>Ảnh đại diện (tuỳ chọn)</label>
                    <input type="file" name="image" class="form-control">
                </div>

                <button type="submit" class="btn btn-success">Lưu</button>
                <a href="{{ route('about.index') }}" class="btn btn-secondary">Huỷ</a>
            </form>
        </div>
    </div>
</div>
@endsection
