@extends('layouts.app')

@section('title', 'Giới thiệu')

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
                  <li class="breadcrumb-item active" aria-current="page">About us</li>
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
        <h3>About us (Giới thiệu)</h3>
        @if($intros->isEmpty())
            <a href="{{ route('about.create') }}" class="btn btn-primary">+ Thêm</a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Giới thiệu ngắn</th>
                <th>Tầm nhìn</th>
                <th>Sứ mạng</th>
                <th>Người tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($intros as $index => $intro)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{!! Str::limit($intro->short_description, 30) !!}</td>
                    <td>{!! Str::limit($intro->vision, 20) !!}</td>
                    <td>{!! Str::limit($intro->mission, 20) !!}</td>
                    <td>{{ $intro->user->name ?? 'N/A' }}</td>
                    <td>
                        <a href="{{ route('about.edit', $intro->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                        <form action="{{ route('about.destroy', $intro->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xoá?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @foreach($intros as $intro)
        <!-- Ban lãnh đạo -->
        <div class="d-flex justify-content-between mb-3">
            <h4>Structures (Ban lãnh đạo)</h4>
            <a href="{{ route('about.structures.create', $intro->id) }}" class="btn btn-primary">+ Thêm cấu trúc</a>
        </div> 
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tên</th>
                    <th>Chức vụ</th>
                    <th>Mô tả</th>
                    <th>Hình ảnh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($intro->structures as $sIndex => $structure)
                    <tr>
                        <td>{{ $sIndex + 1 }}</td>
                        <td>{{ $structure->name }}</td>
                        <td>{{ $structure->position }}</td>
                        <td>{!! Str::limit($structure->description, 50) !!}</td>
                        <td>
                            @if($structure->image)
                                <img src="{{ asset('images/intros/' . $structure->image) }}" alt="{{ $structure->name }}" width="60">
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('about.structures.edit', $structure->id) }}" class="btn btn-sm btn-warning">Sửa</a>
                            <form action="{{ route('about.structures.destroy', $structure->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Bạn chắc chắn muốn xoá thành viên này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center">Chưa có thành viên nào được thêm.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @endforeach
    <!-- Thành tựu -->
    <div class="d-flex justify-content-between mb-3">
        <h4>Thành tựu theo mốc thời gian</h4>
        <!-- Nút tạo mới thành tựu -->
        <a href="{{ route('about.achievements.create', ['intro' => $intro->id]) }}" class="btn btn-primary">+ Tạo mới thành tựu</a>
    </div>
    @foreach ($intros as $intro)
        @if ($intro->achievements->isNotEmpty())
            <div class="mt-4">
                <h5>{{ $intro->title }}</h5>
                <ul>
                    @foreach ($intro->achievements->sortBy('thoigian') as $achievement)
                        <li>
                            <strong>{{ $achievement->thoigian }}</strong> - 
                            {{ $achievement->type }}: {{ $achievement->description }}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
    @endforeach
</div>
@endsection