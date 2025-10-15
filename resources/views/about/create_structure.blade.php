@extends('layouts.app')

@section('title', 'Tạo thành viên Ban Lãnh đạo mới')

@section('content')
<div class="container py-4">
    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Tạo thêm thành viên Ban Lãnh đạo</h3>
            
        </div>
        <div class="card-body">
            <form action="{{ route('about.structures.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Truyền intro_id ẩn -->
                <input type="hidden" name="intro_id" value="{{ $intro->id }}">

                <div class="mb-3">
                    <label>Họ và Tên</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                    <!--textarea name="name" class="form-control" rows="3" required>{{ old('name') }}</textarea-->
                </div>

                <div class="mb-3">
                    <label>Vị trí</label>
                    <input type="text" name="position" class="form-control" required value="{{ old('position') }}">
                    
                </div>

                <div class="mb-3">
                    <label>Thông tin</label>
                    <textarea name="description" class="form-control" required>{{ old('description') }}</textarea>
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
