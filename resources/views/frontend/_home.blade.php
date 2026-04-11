@extends('layouts.app')
@section('title', 'Home')
@section('home_')
    <!-- START HEADER -->
    @include('partials.header')
    <!-- END HEADER -->
    <!-- START SECTION BANNER -->
    @include('partials.slider')
    <!-- END SECTION BANNER -->
@endsection
@section('content')

    <!-- END SECTION BANNER -->
    {{-- <div class="section small_pt small_pb">
        <div class="custom-container">
            <div class="row">
                @if ($featuredVendorProducts->count())
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="heading_tab_header">
                                    <div class="heading_s2">
                                        <h4>{{ gtrans('Featured Products ') }}</h4>
                                    </div>
                                    <div class="view_all">
                                        <a href="{{ route('page.products.shop') }}" class="text_default"><i
                                                class="linearicons-power"></i> <span>View All</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
                                    data-loop="true" data-margin="20"
                                    data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "4"}}'>
                                    @foreach ($featuredVendorProducts as $vendorProduct)
                                        @include('components.product-card', ['product' => $vendorProduct])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                @if ($newArrivalVendorProducts->count())
                    <div class="col-xl-12">
                        <div class="row">
                            <div class="col-12">
                                <div class="heading_tab_header">
                                    <div class="heading_s2">
                                        <h4> {{ gtrans('New Arrival Products ') }}</h4>
                                    </div>
                                    <div class="view_all">
                                        <a href="{{ route('page.products.shop') }}" class="text_default"><i
                                                class="linearicons-power"></i> <span>View All</span></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="product_slider carousel_slider owl-carousel owl-theme dot_style1"
                                    data-loop="true" data-margin="20"
                                    data-responsive='{"0":{"items": "1"}, "481":{"items": "2"}, "768":{"items": "3"}, "991":{"items": "4"}}'>
                                    @foreach ($newArrivalVendorProducts as $vendorProduct)
                                        @include('components.product-card', ['product' => $vendorProduct])
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div> --}}

    <div class="section small_pt small_pb">
        <div class="custom-container">
            <div class="row">
                {{-- Categories --}}


                {{-- Products --}}
                <div class="col-lg-12">
                    <div class="row">
                        <div class="col-12">
                            <div class="heading_tab_header">
                                <div class="heading_s2">
                                    <h4>{{ gtrans('Products') }}</h4>
                                </div>
                                <div class="view_all">
                                    <a href="{{ route('page.products.shop') }}" class="text_default">
                                        <i class="linearicons-power"></i>
                                        <span>{{ gtrans('View All') }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row shop_container">
                        @forelse($products as $product)
                            <div class="col-md-3 col-sm-4 mb-4">
                                @include('components.product-card', ['product' => $product])
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info">
                                    {{ gtrans('No products found.') }}
                                </div>
                            </div>
                        @endforelse
                    </div>

                    @if (method_exists($products, 'links'))
                        <div class="mt-4">
                            {{ $products->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @include('components.login-form')
    @include('components.register-form')
@endsection
