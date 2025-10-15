@extends('frontend.welcome')

@section('title', 'index')

@section('content')
    <!--div class="hero_area">
        @include('frontend.header')    
    </div-->
    <section id="Tintuc" class="blog_section layout_padding long_section">
        <div class="container py-4">
            <div class="row align-items-star">
                <!-- Cột trái: Nội dung chi tiết tin -->
                <div class="col-md-8 mb-4">
                    <h2>{{ $news->title }}</h2>
                    @if ($news->image)
                        <img src="{{ asset('images/news/' . $news->image) }}" alt="{{ $news->title }}" class="img-fluid mb-3">
                    @endif
                    <div>{!! $news->content !!}</div>
                </div>

                <!-- Cột phải: Khóa học sắp khai giảng + Tin mới -->
            <div class="col-md-4">
                <!-- Thông báo khóa học -->
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-info text-white" >
                        <strong>🎓 Khóa học sắp khai giảng</strong>
                    </div>
                    <div class="card-body">
                        <ul class="list-unstyled">
                            <li class="mb-2">🔹 Lãnh đạo chuyển đổi số - 15/07</li>
                            <li class="mb-2">🔹 Kỹ năng quản trị đổi mới - 25/07</li>
                            <li class="mb-2">🔹 Công nghệ AI & ứng dụng - 01/08</li>
                        </ul>
                        
                        <a href="{{ url('/courses') }}" class="btn btn-sm btn-outline-info">Đăng ký</a>
                        
                    </div>
                </div>

            </div>
        </div>
    </section>
  <!-- end tintuc section -->
@endsection
