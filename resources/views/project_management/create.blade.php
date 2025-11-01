@extends('layouts.app')

@section('content')
<div class="app-content-header">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6"><h3 class="mb-0">Thêm dự án nghiên cứu</h3></div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('project-management.index') }}">Project Management</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Thêm dự án</li>
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
                    <div class="card-header">
                        <h3 class="card-title">Thông tin dự án</h3>
                    </div>
                    <form action="{{ route('project-management.store') }}" method="POST">
                        @csrf
                        @include('project_management.partials.form', ['project' => null, 'personalInfos' => $personalInfos])
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection