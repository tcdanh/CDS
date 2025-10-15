@extends('layouts.app')

@section('title', 'Danh sách user')

@section('content_header')
    <h1>Cấu hình hệ thống</h1>
@stop
@section('content')
<div class="card">
        <div class="card-header p-0 pt-1">
            <ul class="nav nav-tabs" id="configTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="tab-user-role" data-toggle="pill" href="#user-role" role="tab" aria-controls="user-role" aria-selected="true">Phân quyền người dùng</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="tab-project-settings" data-toggle="pill" href="#project-settings" role="tab" aria-controls="project-settings" aria-selected="false">Hiệu chỉnh dự án</a>
                </li>
                <!-- Thêm tab khác nếu cần -->
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="configTabsContent">
                <!-- Tab 1: Phân quyền -->
                <div class="tab-pane fade show active" id="user-role" role="tabpanel" aria-labelledby="tab-user-role">
                   @include('admin_setting.roles.partials.index')
                </div>

                <!-- Tab 2: Hiệu chỉnh dự án -->
                <div class="tab-pane fade" id="project-settings" role="tabpanel" aria-labelledby="tab-project-settings">
                    
                </div>
            </div>
        </div>
    </div>
@stop