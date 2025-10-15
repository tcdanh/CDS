<!-- slider section -->
<section class="slider_section long_section">
  <div id="customCarousel" class="carousel slide" data-ride="carousel">
    <div class="carousel-inner">
      @foreach($banners as $index => $banner)
      <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
        <div class="container">
          <div class="row">
            <div class="col-md-5">
              <div class="detail-box">
                <h1>{{ $banner->tittle }}</h1>
                <p>{{ $banner->mota_en }}</p>
                <p>{{ $banner->mota_vn }}</p>
                <div class="btn-box">
                  <a href="{{ $banner->link ?? '#Services' }}" class="btn1">
                    Dịch vụ
                  </a>
                  <a href="#Intro" class="btn2">
                    iLEAD
                  </a>
                </div>
              </div>
            </div>
            <div class="col-md-7">
              <div class="img-box">
                <img src="{{ asset('images/banner_article/' . $banner->hinhanh) }}" alt="">
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>

    <ol class="carousel-indicators">
      @foreach($banners as $index => $banner)
      <li data-target="#customCarousel" data-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"></li>
      @endforeach
    </ol>
  </div>
</section>
<!-- end slider section -->
