@extends('layouts.app')

@section('title', $product->name)

@section('home_')

    <!-- START HEADER -->
    @include('frontend.partials.page-header')
    <!-- END HEADER -->

    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- STRART CONTAINER -->
            @include('frontend.partials.bread-crumb', [
                'header_1' => gtrans('Product Detail'),
                'header_2' => gtrans('Product'),
            ])
        </div>
        <!-- END CONTAINER-->
    </div>
    <!-- END SECTION BREADCRUMB -->

    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- START SECTION SHOP -->
        <div class="section">
            <div class="container">
                <div class="row">

                    {{-- LEFT: PRODUCT IMAGES --}}
                    <div class="col-lg-6 col-md-6 mb-4 mb-md-0">
                        <div class="product-image">

                            {{-- Main Product Image --}}
                            <div class="product_img_box">
                                <img id="product_img" src="{{ asset($product->images->first()->image_path) }}"
                                    data-zoom-image="{{ asset($product->images->first()->image_path) }}"
                                    alt="{{ $product->images->first()->alt_text ?? $product->name }}" />

                                <a href="#" class="product_img_zoom" title="{{ gtrans('Zoom') }}">
                                    <span class="linearicons-zoom-in"></span>
                                </a>
                            </div>

                            {{-- Thumbnails --}}
                            <div id="pr_item_gallery" class="product_gallery_item slick_slider" data-slides-to-show="4"
                                data-slides-to-scroll="1" data-infinite="false">

                                @foreach ($product->images as $img)
                                    <div class="item">
                                        <a href="#" class="product_gallery_item" data-image="{{ $img->image_path }}"
                                            data-zoom-image="{{ $img->image_path }}">
                                            <img src="{{ asset($img->image_path) }}"
                                                alt="{{ $img->alt_text ?? $product->name }}">
                                        </a>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>

                    {{-- RIGHT: PRODUCT DETAILS --}}
                    <div class="col-lg-6 col-md-6">
                        <div class="pr_detail">

                            <div class="product_description">

                                {{-- Product Title --}}
                                <h4 class="product_title">{{ gtrans($product->name) }}</h4>

                                {{-- Pricing --}}
                                <div class="product_price">
                                    <span class="price">
                                        ${{ number_format($vendorProduct->vendor_price ?? $product->price) }}
                                    </span>

                                    @if ($product->compare_price)
                                        <del>${{ number_format($product->compare_price) }}</del>
                                        <div class="on_sale">
                                            <span>
                                                {{ round((($product->compare_price - ($vendorProduct->vendor_price ?? $product->price)) / $product->compare_price) * 100) }}%
                                                {{ gtrans('Off') }}
                                            </span>
                                        </div>
                                    @endif
                                </div>

                                {{-- Rating (static or replace with dynamic rating later) --}}
                                <div class="rating_wrap">
                                    <div class="rating">
                                        <div class="product_rate" style="width:80%"></div>
                                    </div>
                                    <span class="rating_num">(0)</span>
                                </div>

                                {{-- Short Description --}}
                                <div class="pr_desc">
                                    <p>{{ gtrans($product->short_description) }}</p>
                                </div>

                                {{-- Product Info --}}
                                <div class="product_sort_info">
                                    <ul>
                                        @if ($product->warranty)
                                            <li>
                                                <i class="linearicons-shield-check"></i>
                                                {{ gtrans('Warranty') }}:
                                                {{ gtrans($product->warranty) }}
                                            </li>
                                        @endif

                                        <li>
                                            <i class="linearicons-sync"></i>
                                            {{ gtrans('7-Day Return Policy') }}
                                        </li>
                                        <li>
                                            <i class="linearicons-bag-dollar"></i>
                                            {{ gtrans('Pay on Delivery (If Available)') }}
                                        </li>
                                    </ul>
                                </div>

                            </div>

                            <hr />

                            {{-- Add to Cart --}}
                            <div class="cart_extra">
                                <div class="cart-product-quantity">
                                    <div class="quantity">
                                        <input type="button" value="-" class="minus">
                                        <input type="text" name="quantity" value="1" class="qty">
                                        <input type="button" value="+" class="plus">
                                    </div>
                                </div>
                                <div class="cart_btn">
                                    <button class="btn btn-fill-out btn-addtocart" type="button">
                                        <i class="icon-basket-loaded"></i>
                                        {{ gtrans('Add to cart') }}
                                    </button>
                                    <a class="add_compare" href="#"><i class="icon-shuffle"></i></a>
                                    <a class="add_wishlist" href="#"><i class="icon-heart"></i></a>
                                </div>
                            </div>

                            <hr />

                            {{-- Product Meta --}}
                            <ul class="product-meta">
                                <li>
                                    {{ gtrans('SKU') }}:
                                    <a href="#">{{ $product->sku ?? gtrans('N/A') }}</a>
                                </li>
                                <li>
                                    {{ gtrans('Category') }}:
                                    <a
                                        href="#">{{ gtrans(optional($product->category)->name ?? gtrans('Uncategorized')) }}</a>
                                </li>
                                <li>
                                    {{ gtrans('Brand') }}:
                                    <a href="#">{{ $product->brand ? gtrans($product->brand) : gtrans('N/A') }}</a>
                                </li>
                            </ul>

                            {{-- Share --}}
                            <div class="product_share">
                                <span>{{ gtrans('Share') }}:</span>
                                <ul class="social_icons">
                                    <li><a href="#"><i class="ion-social-facebook"></i></a></li>
                                    <li><a href="#"><i class="ion-social-twitter"></i></a></li>
                                    <li><a href="#"><i class="ion-social-instagram-outline"></i></a></li>
                                </ul>
                            </div>

                        </div>
                    </div>

                </div>

                {{-- Description / Additional Info / Reviews (first block) --}}
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="tab-style3">
                            <ul class="nav nav-tabs" role="tablist">

                                <li class="nav-item">
                                    <a class="nav-link active" data-bs-toggle="tab" href="#Description">
                                        {{ gtrans('Description') }}
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#Additional-info">
                                        {{ gtrans('Additional Info') }}
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab" href="#Reviews">
                                        {{ gtrans('Reviews') }} (0)
                                    </a>
                                </li>

                            </ul>

                            <div class="tab-content shop_info_tab">

                                <div class="tab-pane fade show active" id="Description">
                                    {!! $product->description !!}
                                </div>

                                <div class="tab-pane fade" id="Additional-info">
                                    <table class="table table-bordered">
                                        <tr>
                                            <td>{{ gtrans('Weight') }}</td>
                                            <td>{{ $product->weight ?? gtrans('N/A') }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ gtrans('Dimensions') }}</td>
                                            <td>
                                                {{ $product->length }} x {{ $product->width }} x
                                                {{ $product->height }} cm
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ gtrans('Condition') }}</td>
                                            <td>{{ $product->condition ?? gtrans('N/A') }}</td>
                                        </tr>
                                    </table>
                                </div>

                                <div class="tab-pane fade" id="Reviews">
                                    <p>{{ gtrans('No reviews yet.') }}</p>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- SECOND SECTION (you already partially localized) --}}
        <div class="section">
            <div class="container">
                <div class="row">

                    {{-- LEFT: PRODUCT IMAGES (already localized Zoom above) --}}
                    <div class="col-lg-6 col-md-6 mb-4 mb-md-0">
                        <div class="product-image">

                            <div class="product_img_box">
                                @php
                                    $firstImage = $product->images->first();
                                @endphp

                                @if ($firstImage)
                                    <img id="product_img" src="{{ asset($firstImage->image_path) }}"
                                        data-zoom-image="{{ asset($firstImage->image_path) }}"
                                        alt="{{ $firstImage->alt_text ?? $product->name }}" />

                                    <a href="#" class="product_img_zoom" title="{{ gtrans('Zoom') }}">
                                        <span class="linearicons-zoom-in"></span>
                                    </a>
                                @endif
                            </div>

                            <div id="pr_item_gallery" class="product_gallery_item slick_slider" data-slides-to-show="4"
                                data-slides-to-scroll="1" data-infinite="false">

                                @foreach ($product->images as $img)
                                    <div class="item">
                                        <a href="#" class="product_gallery_item"
                                            data-image="{{ $img->image_path }}"
                                            data-zoom-image="{{ $img->image_path }}">
                                            <img src="{{ asset($img->image_path) }}"
                                                alt="{{ $img->alt_text ?? $product->name }}">
                                        </a>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                    </div>

                    {{-- Description / Additional Info / Reviews (second block) --}}
                    <div class="row mt-5">
                        <div class="col-12">
                            <div class="tab-style3">

                                <ul class="nav nav-tabs" role="tablist">

                                    <li class="nav-item">
                                        <a class="nav-link active" data-bs-toggle="tab" href="#Description">
                                            {{ gtrans('Description') }}
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#Additional-info">
                                            {{ gtrans('Additional Info') }}
                                        </a>
                                    </li>

                                    <li class="nav-item">
                                        <a class="nav-link" data-bs-toggle="tab" href="#Reviews">
                                            {{ gtrans('Reviews') }} (0)
                                        </a>
                                    </li>

                                </ul>

                                <div class="tab-content shop_info_tab">

                                    {{-- Description Tab --}}
                                    <div class="tab-pane fade show active" id="Description">
                                        {!! $product->description !!}
                                    </div>

                                    {{-- Additional Info Tab --}}
                                    <div class="tab-pane fade" id="Additional-info">
                                        <table class="table table-bordered">
                                            <tr>
                                                <td>{{ gtrans('Weight') }}</td>
                                                <td>{{ $product->weight ?? gtrans('N/A') }}</td>
                                            </tr>
                                            <tr>
                                                <td>{{ gtrans('Dimensions') }}</td>
                                                <td>
                                                    {{ $product->length }} x {{ $product->width }} x
                                                    {{ $product->height }} cm
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>{{ gtrans('Condition') }}</td>
                                                <td>{{ $product->condition ?? gtrans('N/A') }}</td>
                                            </tr>
                                        </table>
                                    </div>

                                    {{-- Reviews Tab --}}
                                    <div class="tab-pane fade" id="Reviews">
                                        <p>{{ gtrans('No reviews yet.') }}</p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- END SECTION SHOP -->
        </div>

        @include('components.login-form')
        @include('components.register-form')
    @endsection
