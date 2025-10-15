@extends('layouts.app')

@section('title', 'Thêm Thành tựu')

@section('content')
<div class="container">
    <h3>Thêm Thành tựu cho: {{ $intro->title }}</h3>
    <form action="{{ route('about.achievements.store') }}" method="POST">
        @csrf
        <input type="hidden" name="intro_id" value="{{ $intro->id }}">

        <!--div class="mb-3">
            <label class="form-label">Tiêu đề</label>
            <input type="text" name="title" class="form-control" required>
        </div-->

        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Năm</label>
            <input type="text" name="thoigian" class="form-control" placeholder="VD: 2025" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Loại thành tựu</label>
            <select name="type" class="form-control" required>
                <option value="Cấp ĐHQG-VNU">Bằng khen cấp ĐHQG-HCM</option>
                <option value="Cấp chính phủ">Bằng khen cấp chính phủ</option>
                <option value="Dự án">Dự án</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Lưu thành tựu</button>
    </form>
</div>
@endsection
