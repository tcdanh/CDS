@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row">
              <div class="col-sm-6"><h3 class="mb-0">Dashboard</h3></div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                </ol>
              </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->
    <!--div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header"><h3 class="card-title">Dashboard</h3></div>
                <div class="card-body">
                    <p>You’re logged in!</p>
                </div>
            </div>
        </div>
    </div-->
    
    <!-- Dashboard 1 -->
    @include('dashboard.dashboard1', ['projectCount' => $projectCount ?? 0, 'personalCount' => $personalCount ?? 0])
    
@endsection
