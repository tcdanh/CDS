@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Thêm thông tin hợp đồng &amp; tài chính</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('project-management.index') }}">Project Management</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('project-management.show', $project) }}">Chi tiết dự án</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thêm thông tin</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card card-primary card-outline">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">{{ $project->name_vi }}</h3>
                        <a href="{{ route('project-management.show', $project) }}" class="btn btn-outline-secondary">Quay lại</a>
                    </div>
                    <form action="{{ route('project-details.store', $project) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-body">
                            @include('project_management.detail.partials.form')
                        </div>
                        <div class="card-footer d-flex justify-content-end gap-2">
                            <a href="{{ route('project-management.show', $project) }}" class="btn btn-secondary">Huỷ</a>
                            <button type="submit" class="btn btn-primary">Lưu thông tin</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection