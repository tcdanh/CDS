@extends('layouts.app')

@section('title', 'Chỉnh sửa Banner')

@section('content')
<!--begin::App Content Header-->
<div class="app-content-header">
    <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">News Management</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Banner article</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
        </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->
<div class="container py-4">
    <div class="card card-primary">
        <div class="card-header"><h3 class="card-title">Chỉnh sửa Banner</h3></div>
        <div class="card-body">
            <form method="POST" action="{{ route('banner_article.update', $banner->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group mb-3">
                    <label>Tiêu đề (tối đa 30 từ)</label>
                    <input type="text" name="tittle" class="form-control" required maxlength="255" value="{{ old('tittle', $banner->tittle) }}">
                </div>

                <div class="form-group mb-3">
                    <label>Mô tả tiếng Anh</label>
                    <textarea name="mota_en" class="form-control" rows="3" maxlength="1000" required>{{ old('mota_en', $banner->mota_en) }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label>Mô tả tiếng Việt</label>
                    <textarea name="mota_vn" class="form-control" rows="3" maxlength="1000" required>{{ old('mota_vn', $banner->mota_vn) }}</textarea>
                </div>

                <div class="form-group mb-3">
                    <label>Hình ảnh hiện tại</label><br>
                    <img src="{{ asset('images/banner_article/' . $banner->hinhanh) }}" alt="banner" width="200">
                </div>

                <div class="form-group mb-3">
                    <label>Thay hình ảnh mới (nếu muốn)</label>
                    <input type="file" name="hinhanh" accept="image/*" class="form-control">
                </div>

                <div class="form-group mb-3">
                    <label>Link đến bài viết (nếu có)</label>
                    <input type="url" name="link" class="form-control" value="{{ old('link', $banner->link) }}">
                </div>

                <button type="submit" class="btn btn-success">Cập nhật</button>
                <a href="{{ route('banner_article.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>
@endsection