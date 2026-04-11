@extends('layouts.app')
@section('title', 'Home')

@section('home_')

@section('_header')
    <header class="header-area header-style-3 header-height-2">

        {{-- TOP HEADER --}}
        @include('partials.topheader')

        {{-- MIDDLE HEADER --}}
        @include('partials.header')

        @include('partials.mobile_header')
    </header>
@endsection
@include('partials.home_slider')

<section class="popular-categories section-padding mt-15">
    <div class="container wow fadeIn animated">
        <h3 class="section-title mb-20">
            <span>Popular</span> Categories
        </h3>
        <div class="carausel-6-columns-cover position-relative">

            <div class="slider-arrow slider-arrow-2 carausel-6-columns-arrow" id="carausel-6-columns-arrows"></div>

            <div class="carausel-6-columns" id="carausel-6-columns">

                @foreach ($topCategories as $category)
                    <div class="card-1">

                        <figure class="img-hover-scale overflow-hidden">
                            <a href="{{ route('page.products.category', $category->slug ?? $category->id) }}">
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}">
                            </a>
                        </figure>

                        <h5>
                            <a href="{{ route('page.products.category', $category->slug ?? $category->id) }}">
                                {{ $category->name }}
                            </a>
                        </h5>

                    </div>
                @endforeach

            </div>

        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container wow fadeIn animated">
        <h3 class="section-title mb-20"><span>Hot Selling</span> Products</h3>

        <div class="carausel-6-columns-cover position-relative">
            <div class="slider-arrow slider-arrow-2 carausel-6-columns-arrow" id="carausel-6-columns-2-arrows"></div>

            <div class="carausel-6-columns carausel-arrow-center" id="carausel-6-columns-2">
                @forelse ($hotSellingProducts as $product)
                    @php
                        $images = $product->images ?? collect();
                        $primaryImage = $images->where('is_primary', true)->first() ?? $images->first();
                        $hoverImage = $images->where('is_primary', false)->first();
                        $productUrl = route('page.products.show', ['slug' => $product->slug]);
                        $fallbackImage = asset('assets/imgs/shop/product-placeholder.jpg');
                    @endphp

                    <div class="product-cart-wrap small hover-up">
                        <div class="product-img-action-wrap">
                            <div class="product-img product-img-zoom">
                                <a href="{{ $productUrl }}">
                                    <img class="default-img"
                                        src="{{ $primaryImage ? asset($primaryImage->image_path) : $fallbackImage }}"
                                        alt="{{ $primaryImage->alt_text ?? $product->name }}">

                                    <img class="hover-img"
                                        src="{{ $hoverImage ? asset($hoverImage->image_path) : ($primaryImage ? asset($primaryImage->image_path) : $fallbackImage) }}"
                                        alt="{{ $hoverImage->alt_text ?? $product->name }}">
                                </a>
                            </div>

                            <div class="product-action-1">
                                <a aria-label="Quick view" class="action-btn small hover-up popup-ajax" href="#"
                                    data-product-id="{{ $product->id }}">
                                    <i class="fi-rs-eye"></i>
                                </a>

                                <a aria-label="Add To Wishlist" class="action-btn small hover-up" href="#"
                                    data-product-id="{{ $product->id }}">
                                    <i class="fi-rs-heart"></i>
                                </a>


                            </div>

                            <div class="product-badges product-badges-position product-badges-mrg">
                                @if ($product->compare_price && $product->compare_price > $product->price)
               
                                @elseif ($product->stock_quantity <= 0)
                                    <span class="hot">{{ gtrans('Sold Out') }}</span>
                                @else
                                    <span class="hot">{{ gtrans('Hot') }}</span>
                                @endif
                            </div>
                        </div>

                        <div class="product-content-wrap">
                            <h2>
                                <a href="{{ $productUrl }}">
                                    {{ gtrans($product->name) }}
                                </a>
                            </h2>

                            <div class="rating-result" title="0%">
                                <span></span>
                            </div>

                            <div class="product-price">
                                <span>${{ number_format($product->price, 2) }}</span>

                                @if ($product->compare_price && $product->compare_price > $product->price)
                                    <span class="old-price">
                                        ${{ number_format($product->compare_price, 2) }}
                                    </span>
                                @endif
                            </div>

                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <p class="text-muted">{{ gtrans('No hot selling products available at the moment.') }}</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</section>

<section class="product-tabs section-padding wow fadeIn animated">
    <div class="container">
        <div class="tab-header">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="nav-tab-one" data-bs-toggle="tab" data-bs-target="#tab-one"
                        type="button" role="tab" aria-controls="tab-one" aria-selected="true">
                        New discoveries every day
                    </button>
                </li>
            </ul>

            <a href="" class="view-more d-none d-md-flex">
                View More<i class="fi-rs-angle-double-small-right"></i>
            </a>
        </div>

        <div class="tab-content wow fadeIn animated" id="myTabContent">
            <div class="tab-pane fade show active" id="tab-one" role="tabpanel" aria-labelledby="tab-one">
                <div class="row product-grid-4">
                    @forelse ($newArrivalProducts as $product)
                        @php
                            $images = $product->images ?? collect();
                            $primaryImage = $images->where('is_primary', true)->first() ?? $images->first();
                            $hoverImage = $images->where('is_primary', false)->first();
                            $productUrl = route('page.products.show', ['slug' => $product->slug]);
                            $fallbackImage = asset('assets/imgs/shop/product-placeholder.jpg');
                        @endphp

                        <div class="col-lg-3 col-md-4 col-12 col-sm-6">
                            <div class="product-cart-wrap mb-30">
                                <div class="product-img-action-wrap">
                                    <div class="product-img product-img-zoom">
                                        <a href="{{ $productUrl }}">
                                            <img class="default-img"
                                                src="{{ $primaryImage ? asset($primaryImage->image_path) : $fallbackImage }}"
                                                alt="{{ $primaryImage->alt_text ?? $product->name }}">

                                            <img class="hover-img"
                                                src="{{ $hoverImage ? asset($hoverImage->image_path) : ($primaryImage ? asset($primaryImage->image_path) : $fallbackImage) }}"
                                                alt="{{ $hoverImage->alt_text ?? $product->name }}">
                                        </a>
                                    </div>

                                    <div class="product-action-1">
                                        <a aria-label="Quick view" class="action-btn hover-up popup-ajax" href="#"
                                            data-product-id="{{ $product->id }}">
                                            <i class="fi-rs-eye"></i>
                                        </a>

                                        <a aria-label="Add To Wishlist" class="action-btn hover-up" href="#"
                                            data-product-id="{{ $product->id }}">
                                            <i class="fi-rs-heart"></i>
                                        </a>


                                    </div>

                                    <div class="product-badges product-badges-position product-badges-mrg">
                                        @if ($product->compare_price && $product->compare_price > $product->price)
 
                                        @elseif ($product->stock_quantity <= 0)
                                            <span class="hot">{{ gtrans('Sold Out') }}</span>
                                        @else
                                            <span class="new">{{ gtrans('New') }}</span>
                                        @endif
                                    </div>
                                </div>

                                <div class="product-content-wrap">
                                    @if (!empty($product->category?->name))
                                        <div class="product-category">
                                            <a href="">
                                                {{ gtrans($product->category->name) }}
                                            </a>
                                        </div>
                                    @endif

                                    <h2>
                                        <a href="{{ $productUrl }}">
                                            {{ gtrans($product->name) }}
                                        </a>
                                    </h2>

                                    <div class="rating-result" title="0%">
                                        <span></span>
                                    </div>

                                    <div class="product-price">
                                        <span>${{ number_format($product->price, 2) }}</span>

                                        @if ($product->compare_price && $product->compare_price > $product->price)
                                            <span class="old-price">
                                                ${{ number_format($product->compare_price, 2) }}
                                            </span>
                                        @endif
                                    </div>

                                    <div class="product-action-1 show">
                                        <a aria-label="Add To Cart" href="javascript:;"
                                            class="action-btn hover-up add-to-cart-link {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}"
                                            data-product-id="{{ $product->id }}" data-price="{{ $product->price }}"
                                            data-name="{{ $product->name }}" data-slug="{{ $product->slug }}"
                                            data-image="{{ $primaryImage ? (\Illuminate\Support\Str::startsWith($primaryImage->image_path, ['http://', 'https://']) ? $primaryImage->image_path : asset($primaryImage->image_path)) : $fallbackImage }}"
                                            @if ($product->stock_quantity <= 0) aria-disabled="true" @endif>
                                            <i class="fi-rs-shopping-bag-add"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <p class="text-muted">{{ gtrans('No new arrival products available at the moment.') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
