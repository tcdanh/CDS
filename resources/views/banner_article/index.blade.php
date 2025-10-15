@extends('layouts.app')

@section('title', 'Danh sách Banner')

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
    <div class="d-flex justify-content-between mb-3">
        <h4>Danh sách Banner</h4>
        @if(in_array(Auth::user()->role_id, [1, 2]))
            <a href="{{ route('banner_article.create') }}" class="btn btn-primary">+ Tạo mới</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover">
        <thead class="thead-dark">
            <tr>
                <th>#</th>
                <th>Tiêu đề</th>
                <th>Hình ảnh</th>
                <th>Người tạo</th>
                <th>Link</th>
                <th>Ngày tạo</th>
                @if(in_array(Auth::user()->role_id, [1, 2]))
                    <th>Hành động</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($banners as $index => $banner)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $banner->tittle }}</td>
                    <td>
                        <img src="{{ asset('images/banner_article/' . $banner->hinhanh) }}" alt="banner" width="120">
                    </td>
                    <td>{{ $banner->user->name ?? 'N/A' }}</td>
                    <td>
                        @if ($banner->link)
                            <a href="{{ $banner->link }}" target="_blank">Xem</a>
                        @endif
                    </td>
                    <td>{{ $banner->created_at->format('d/m/Y') }}</td>

                    @if(in_array(Auth::user()->role_id, [1, 2]))
                    <td class="text-nowrap">
                        <a href="{{ route('banner_article.edit', $banner->id) }}" class="btn btn-sm btn-warning">
                        <i class="fas fa-edit"></i> Sửa
                        </a>

                        <form action="{{ route('banner_article.destroy', $banner->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xoá?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                            <i class="fas fa-trash"></i> Xoá
                            </button>
                        </form>
                    </td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $banners->links() }} {{-- pagination --}}
</div>
@endsection
