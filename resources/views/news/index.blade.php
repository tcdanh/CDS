@extends('layouts.app')

@section('title', 'Quản lý Tin tức')

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
                  <li class="breadcrumb-item active" aria-current="page">Tin tức</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
        </div>
    <!--end::Container-->
</div>
<!--end::App Content Header-->
<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h3>Danh sách tin tức</h3>
        <a href="{{ route('admin.news.create') }}" class="btn btn-primary mb-3">+ Thêm tin mới</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Tiêu đề</th>
                <th>Hình ảnh</th>
                <th>Slug</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($news as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->title }}</td>
                    <td>
                        @if ($item->image)
                            <img src="{{ asset('images/news/' . $item->image) }}" width="100" alt="image">
                        @else
                            <span class="text-muted">Không có ảnh</span>
                        @endif
                    </td>
                    <td>{{ $item->slug }}</td>
                    <td>
                        <a href="{{ route('admin.news.edit', $item->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('admin.news.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá tin này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Chưa có tin tức nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $news->links() }} {{-- pagination --}}
</div>
@endsection
