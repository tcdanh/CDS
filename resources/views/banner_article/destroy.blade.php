@extends('layouts.app')

@section('title', 'Xoá Banner')

@section('content')
<div class="container py-4">
    <div class="card border-danger">
        <div class="card-header bg-danger text-white">
            <h4>Xác nhận xoá banner</h4>
        </div>
        <div class="card-body">
            <p>Bạn có chắc chắn muốn xoá banner dưới đây không?</p>

            <ul>
                <li><strong>Tiêu đề:</strong> {{ $banner->tittle }}</li>
                <li><strong>Mô tả EN:</strong> {{ $banner->mota_en }}</li>
                <li><strong>Mô tả VN:</strong> {{ $banner->mota_vn }}</li>
                <li><strong>Link:</strong> {{ $banner->link }}</li>
                <li><strong>Hình ảnh:</strong><br>
                    <img src="{{ asset('images/banner_article/' . $banner->hinhanh) }}" width="200">
                </li>
            </ul>

            <form method="POST" action="{{ route('banner_article.destroy', $banner->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Xoá</button>
                <a href="{{ route('banner_article.index') }}" class="btn btn-secondary">Huỷ</a>
            </form>
        </div>
    </div>
</div>
@endsection
