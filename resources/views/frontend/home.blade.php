@extends('frontend.welcome')

@section('title', 'home')

@section('content')
  <div class="hero_area">
    @include('frontend.header')
    <!-- slider section -->
    @include('frontend.banner')
  </div>
  <!-- Dich vu section -->
  <section id="Services" class="furniture_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>
          Các Dịch vụ
        </h2>
        <p>
          VNU-iLEAD cung cấp các khoá đào tạo nâng cao năng lực lãnh đạo và các dịch vụ tư vấn, nghiên cứu trong lĩnh vực quản trị đại học, giáo dục, quản lý, phát triển nguòn nhân lực và đổi mới sáng tạo.
        </p>
      </div>
      <div class="row">
        <div class="col-md-6 col-lg-4">
          <div class="box">
            <div class="img-box">
              <img src="images/skill-leaders.png" alt="">
            </div>
            <div class="detail-box">
              <h5>
                Kỹ năng chuyên nghiệp
              </h5>
              <div class="price_box">
                <h6 class="price_heading">
                  <span></span> Bồi dưỡng các kỹ năng, ...
                </h6>
                <a href="">
                  Tìm hiểu
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="box">
            <div class="img-box">
              <img src="images/tiengAnh.png" alt="">
            </div>
            <div class="detail-box">
              <h5>
                Nâng cao năng lực tiếng Anh
              </h5>
              <div class="price_box">
                <h6 class="price_heading">
                  <span></span> Bồi dưỡng ngoại ngữ ...
                </h6>
                <a href="">
                  Tìm hiểu
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="box">
            <div class="img-box">
              <img src="images/cds_ai.png" alt="">
            </div>
            <div class="detail-box">
              <h5>
                Chuyển đổi số, AI
              </h5>
              <div class="price_box">
                <h6 class="price_heading">
                  <span></span>Ứng dụng AI, CĐS ...
                </h6>
                <a href="">
                  Tìm hiểu
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="box">
            <div class="img-box">
              <img src="images/Innovation.png" alt="">
            </div>
            <div class="detail-box">
              <h5>
                Đổi mới sáng tạo
              </h5>
              <div class="price_box">
                <h6 class="price_heading">
                  <span></span>Chương trình Innovation ...
                </h6>
                <a href="">
                  Tìm hiểu
                </a>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="box">
            <div class="img-box">
              <img src="images/tuvan.png" alt="">
            </div>
            <div class="detail-box">
              <h5>
                Tư vấn, nghiên cứu
              </h5>
              <div class="price_box">
                <h6 class="price_heading">
                  <span></span>Dịch vụ tư vấn, NCKH ...
                </h6>
                <a href="">
                  Tìm hiểu
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- end dịch vu section -->

  <!-- about section -->
  <section id="Intro" class="about_section layout_padding long_section">
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="img-box">
            <img src="images/ilead_about.png" alt="">
          </div>
        </div>
        @if ($intro)
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                VNU-iLEAD
              </h2>
            </div>
            <p>
            {!! $intro->short_description !!}
            </p>
            <a href="{{ route('about.index_front') }}">
              Đọc thêm
            </a>
          </div>
        </div>
        @endif
      </div>
    </div>
  </section>
  <!-- end about section -->

  <!-- blog section -->
  <section id="Tintuc" class="blog_section layout_padding">
    <div class="container">
      <div class="heading_container">
        <h2>
          Tin tức
        </h2>
      </div>
      <div class="row">
        @foreach($news as $i=> $new)
          <div class="col-md-6 col-lg-4 mx-auto">
            <div class="box">
              <div class="img-box">
                <img src="{{ asset('images/news/' .$new->image) }}" alt="">
              </div>
              <div class="detail-box">
                <h5>
                  {{ $new->title }}
                </h5>
                <p>
                  {{ Str::limit(strip_tags($new->content), 100) }}             
                </p>
                <a href="{{ route('news.public', $new->slug) }}">
                  Đọc thêm
                </a>
              </div>
            </div>
          </div>
        @endforeach
        
      </div>
    </div>
  </section>

  <!-- end blog section -->

  <!-- client section -->
  <section class="client_section layout_padding-bottom">
    <div class="container">
      <div class="heading_container">
        <h2>
          Đối tác 
        </h2>
      </div>
      <div id="carouselExample2Controls" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
          <div class="carousel-item active">
            <div class="row">
              <div class="col-md-11 col-lg-10 mx-auto">
                <div class="box">
                  <div class="img-box">
                    <img src="images/SMU.jpg" alt="" />
                  </div>
                  <div class="detail-box">
                    <div class="name">
                      <i class="fa fa-quote-left" aria-hidden="true"></i>
                      <h6>
                        Singapore Management University (SMU)
                      </h6>
                    </div>
                    <p>
                    SMU là trường đại học công lập ra đời thứ 3 (trong 6 trường đại học công lập hiện nay) tại Singapore. 
                      Trường được thành lập với mục tiêu đào tạo chuyên biệt về QUẢN LÝ và THƯƠNG MẠI - một trong những lĩnh vực đào tạo nổi tiếng ở Singapore.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="carousel-item">
            <div class="row">
              <div class="col-md-11 col-lg-10 mx-auto">
                <div class="box">
                  <div class="img-box">
                    <img src="images/dongnai.jpg" alt="" />
                  </div>
                  <div class="detail-box">
                    <div class="name">
                      <i class="fa fa-quote-left" aria-hidden="true"></i>
                      <h6>
                        Đồng Nai
                      </h6>
                    </div>
                    <p>
                    Tỉnh Đồng Nai nằm trong vùng kinh tế trọng điểm Nam bộ, có diện tích tự nhiên là 5.907,2 km²- vùng kinh tế phát triển và năng động nhất cả nước.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="carousel_btn-container">
          <a class="carousel-control-prev" href="#carouselExample2Controls" role="button" data-slide="prev">
            <i class="fa fa-long-arrow-left" aria-hidden="true"></i>
            <span class="sr-only">Previous</span>
          </a>
          <a class="carousel-control-next" href="#carouselExample2Controls" role="button" data-slide="next">
            <i class="fa fa-long-arrow-right" aria-hidden="true"></i>
            <span class="sr-only">Next</span>
          </a>
        </div>
      </div>
    </div>
  </section>
@endsection