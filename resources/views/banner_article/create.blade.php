@extends('layouts.app')

@section('title', 'Create Banner Article')

@section('content')
<div class="container py-4">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Create Banner Article</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('banner_article.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group mb-3">
                    <label>Title (max 30 từ)</label>
                    <input type="text" name="tittle" class="form-control" required maxlength="255">
                </div>

                <div class="form-group mb-3">
                    <label>Mô tả tiếng Anh</label>
                    <textarea name="mota_en" class="form-control" rows="3" maxlength="1000" required></textarea>
                </div>

                <div class="form-group mb-3">
                    <label>Mô tả tiếng Việt</label>
                    <textarea name="mota_vn" class="form-control" rows="3" maxlength="1000" required></textarea>
                </div>

                <div class="form-group mb-3">
                    <label>Hình ảnh (JPG, PNG)</label>
                    <input type="file" name="hinhanh" accept=\"image/*\" class="form-control" required>
                </div>

                <div class="form-group mb-3">
                    <label>Link đến bài viết (nếu có)</label>
                    <input type="url" name="link" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Lưu</button>
                <a href="{{ route('banner_article.index') }}" class="btn btn-secondary">Quay lại</a>
            </form>
        </div>
    </div>
</div>
@endsection
