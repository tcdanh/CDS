@extends('frontend.welcome')

@section('title', 'index')

@section('content')
    <!--div class="hero_area">
        @include('frontend.header')    
    </div-->
    <!-- about section -->
    <section id="Intro" class="about_section layout_padding long_section">
        <div class="container">
            <div class="row align-items-start">
                <!-- Cột 1: Ban lãnh đạo -->
                <!--div class="col-md-6"-->
                <div class="col-12 col-md-6 mb-4">
                    <div class="img-box text-center mb-3">
                        <img src="images/ilead_about.png" alt="" class="img-fluid">
                    </div>
                    <div class="detail-box">
                        <div class="heading_container">
                            <br>
                            <!--h3 style="border-bottom: 2px solid #333; display: inline-block; padding-bottom: 5px;"-->
                            <h3 style="border-bottom: 3px solid #007bff; display: inline-block; padding-bottom: 4px;"> 
                                Ban lãnh đạo iLEAD
                            </h3>
                            <br>
                        </div>
                        <div class="row">
                            @foreach($intro->structures as $structure)
                                <!--div class="col-md-6 d-flex align-items-start mb-4"-->
                                <div class="col-12 d-flex align-items-start mb-4">
                                    <!-- Cột ảnh bên trái -->
                                    <!--div class="me-3"-->
                                    <div class="me-4 flex-shrink-0">
                                        @if ($structure->image)
                                            <img src="{{ asset('images/intros/' . $structure->image) }}" alt="{{ $structure->name }}" class="img-fluid rounded shadow-sm" style="width: 150px;">
                                        @else
                                            <img src="{{ asset('images/avatars/default_avatar.jpeg') }}" alt="No Image" class="img-fluid rounded shadow-sm" style="width: 150px;">
                                        @endif
                                    </div>
                                    <div class="me-3 flex-shrink-0">
                                        <p>&nbsp;&nbsp;</p>
                                    </div>
                                    <!-- Cột thông tin bên phải -->
                                    <div class="d-flex flex-column justify-content-center ps-2">
                                        <h5 class="mb-1" >
                                            <i class=" me-2 text-primary"></i> {{ $structure->name }}
                                        </h5>
                                        <p class="mb-1 small"> 
                                            <i class="fa fa-play me-2 text-secondary"></i> {{ $structure->position }}
                                        </p>
                                        <p class="mb-1 small text-muted">
                                            <i class="fa fa-play me-2"></i> {{ $structure->description }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <!-- Cột 2: Về VNU-iLEAD -->
                @if ($intro)
                    <!--div class="col-md-6"-->
                    <div class="col-12 col-md-6">
                        <div class="detail-box">
                            <div class="heading_container">
                                <h2> 
                                    Về VNU-iLEAD
                                </h2>
                            </div>
                            <p>
                                {!! $intro->short_description !!}
                            </p>
                            <div class="text-center">
                                <!--img src="images/vnu-ilead-logo.png" width="150" alt="Logo" -->
                                @if ($intro->image)
                                    <img src="{{ asset('images/intros/' . $intro->image) }}" width="150" alt="Logo"  >
                                @endif
                            </div>
                            <p>
                                <h3>Tầm nhìn</h3>
                            </p>
                            <p>
                                {!! $intro->vision !!}
                            </p>
                            <p>
                                <h3>Nhiệm vụ</h3>
                            </p>
                            <p>
                                {!! $intro->mission !!}
                            </p>
                            <p>
                                <h3>Mục tiêu</h3>
                            </p>
                            <p>
                                {!! $intro->goals !!}
                            </p>
                        </div>
                    </div>
                @endif
                <div class="heading_container">
                    <h3 style="border-bottom: 3px solid #007bff; display: inline-block; padding-bottom: 4px;"> 
                        Thành tựu đạt được
                    </h3>
                    <br>
                    <ul>
                        @foreach ($intro->achievements->sortBy('thoigian') as $achievement)
                            <li>
                                <strong>{{ $achievement->thoigian }}</strong> - 
                                {{ $achievement->type }}: {{ $achievement->description }}
                            </li>
                        @endforeach
                    </ul>
                    <p> Đang cập nhật </p>
                </div>
            </div>
        </div>
  </section>
  <!-- end about section -->
@endsection