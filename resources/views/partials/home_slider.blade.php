<section class="home-slider position-relative pt-25 pb-20">
    <div class="container">
        <div class="row">
            <div class="col-lg-9">
                <div class="position-relative">
                    <div class="hero-slider-1 style-3 dot-style-1 dot-style-1-position-1">

                        {{-- Slide 1 --}}
                        <div class="single-hero-slider single-animation-wrap">
                            <div class="container">
                                <div class="slider-1-height-3 slider-animated-1">
                                    <div class="hero-slider-content-2">
                                        <h4 class="animated">Bluesky Mart</h4>
                                        <h2 class="animated fw-900">Best Deals</h2>
                                        <h1 class="animated fw-900 text-brand">Shop Essentials</h1>
                                        <p class="animated">Top quality. Great prices.</p>
                                        <a class="animated btn btn-brush btn-brush-3"
                                           href="{{ route('page.products.shop') }}">
                                            Shop Now
                                        </a>
                                    </div>
                                    <div class="slider-img">
                                        <img src="{{ asset('assets/imgs/slider/slider-4.png') }}" alt="Bluesky Mart Deals">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Slide 2 --}}
                        <div class="single-hero-slider single-animation-wrap">
                            <div class="container">
                                <div class="slider-1-height-3 slider-animated-1">
                                    <div class="hero-slider-content-2">
                                        <h4 class="animated">New In</h4>
                                        <h2 class="animated fw-900">Fresh Picks</h2>
                                        <h1 class="animated fw-900 text-brand">Trending Now</h1>
                                        <p class="animated">Hot items. Don’t miss out.</p>
                                        <a class="animated btn btn-brush btn-brush-3"
                                           href="{{ route('page.products.shop') }}">
                                            Shop Now
                                        </a>
                                    </div>
                                    <div class="slider-img">
                                        <img src="{{ asset('assets/imgs/slider/slider-5.png') }}" alt="Trending Products">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="slider-arrow hero-slider-1-arrow style-3"></div>
                </div>
            </div>

            <!-- ✅ FIX APPLIED HERE -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="banner-img banner-1 wow fadeIn animated home-3">
                    <img class="border-radius-10" src="{{ asset('assets/imgs/banner/banner-5.jpg') }}" alt="Bluesky Mart Accessories">
                    <div class="banner-text">
                        <span>Accessories</span>
                        <h4>Upgrade Your Style</h4>
                        <a href="{{ route('page.products.shop') }}">
                            Shop Now <i class="fi-rs-arrow-right"></i>
                        </a>
                    </div>
                </div>

                <div class="banner-img banner-2 wow fadeIn animated mb-0">
                    <img class="border-radius-10" src="{{ asset('assets/imgs/banner/banner-7.jpg') }}" alt="Bluesky Mart Smart Offer">
                    <div class="banner-text">
                        <span>Hot Deals</span>
                        <h4>Save Big Today</h4>
                        <a href="{{ route('page.products.shop') }}">
                            Shop Now <i class="fi-rs-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>