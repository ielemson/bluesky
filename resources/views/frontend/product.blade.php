@extends('layouts.app')
@section('title', $product->name)
@section("home_")
 @include("partials._general_header")   
    @php
        $images = $product->images ?? collect();
        $primaryImage = $images->where('is_primary', true)->sortBy('sort_order')->first();
        $galleryImages = $images->count() ? $images : collect([
            (object) [
                'image_path' => 'https://picsum.photos/seed/' . $product->slug . '/800/900',
                'alt_text' => $product->name,
                'is_primary' => true,
                'sort_order' => 1,
            ]
        ]);

        $mainImage = $primaryImage?->image_path ?? $galleryImages->first()->image_path ?? 'https://picsum.photos/seed/placeholder/800/900';

        $price = $product->price ?? 0;
        $comparePrice = $product->compare_price ?? null;
        $discount = null;

        if (!empty($comparePrice) && $comparePrice > $price) {
            $discount = round((($comparePrice - $price) / $comparePrice) * 100);
        }
    @endphp

    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="javascript:;" rel="nofollow">Home</a>
                @if ($product->category)
                    <span></span>
                    <a href="javascript:;">{{ $product->category->name }}</a>
                @endif
                <span></span> {{ $product->name }}
            </div>
        </div>
    </div>

    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-detail accordion-detail">
                        <div class="row mb-50">
                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="detail-gallery">
                                    <span class="zoom-icon"><i class="fi-rs-search"></i></span>

                                    {{-- MAIN SLIDES --}}
                                    <div class="product-image-slider">
                                        @foreach ($galleryImages as $image)
                                            <figure class="border-radius-10">
                                                <img src="{{ $image->image_path }}"
                                                     alt="{{ $image->alt_text ?? $product->name }}">
                                            </figure>
                                        @endforeach
                                    </div>

                                    {{-- THUMBNAILS --}}
                                    <div class="slider-nav-thumbnails pl-15 pr-15">
                                        @foreach ($galleryImages as $image)
                                            <div>
                                                <img src="{{ $image->image_path }}"
                                                     alt="{{ $image->alt_text ?? $product->name }}">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12 col-xs-12">
                                <div class="detail-info">
                                    @if ($product->is_new_arrival)
                                        <span class="stock-status out-stock mb-10">New Arrival</span>
                                    @elseif($product->is_hot_selling)
                                        <span class="stock-status in-stock mb-10">Hot Selling</span>
                                    @elseif($product->is_best_seller)
                                        <span class="stock-status in-stock mb-10">Best Seller</span>
                                    @endif

                                    <h2 class="title-detail">{{ $product->name }}</h2>

                                    <div class="product-detail-rating">
                                        <div class="pro-details-brand">
                                            <span>
                                                Brand:
                                                <a href="javascript:;">{{ $product->brand ?: 'N/A' }}</a>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="clearfix product-price-cover">
                                        <div class="product-price primary-color float-left">
                                            <ins>
                                                <span class="text-brand">${{ number_format($price, 2) }}</span>
                                            </ins>

                                            @if (!empty($comparePrice) && $comparePrice > $price)
                                                <ins>
                                                    <span class="old-price font-md ml-15">${{ number_format($comparePrice, 2) }}</span>
                                                </ins>
                                            @endif

                                            @if ($discount)
                                                <span class="save-price font-md color3 ml-15">{{ $discount }}% Off</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="bt-1 border-color-1 mt-15 mb-15"></div>

                                    <div class="short-desc mb-30">
                                        <p>
                                            {{ $product->short_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 180) }}
                                        </p>
                                    </div>

                                    <div class="product_sort_info font-xs mb-30">
                                        <ul>
                                            @if ($product->warranty)
                                                <li class="mb-10">
                                                    <i class="fi-rs-crown mr-5"></i> {{ $product->warranty }}
                                                </li>
                                            @endif

                                            @if ($product->has_free_shipping)
                                                <li class="mb-10">
                                                    <i class="fi-rs-shuffle mr-5"></i> Free Shipping Available
                                                </li>
                                            @endif

                                            @if ($product->allow_backorder)
                                                <li class="mb-10">
                                                    <i class="fi-rs-refresh mr-5"></i> Backorder Allowed
                                                </li>
                                            @endif

                                            <li>
                                                <i class="fi-rs-credit-card mr-5"></i> Secure Checkout Available
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="bt-1 border-color-1 mt-30 mb-30"></div>

                                    <div class="detail-extralink">
                                        <div class="detail-qty border radius">
                                            <a href="javascript:;" class="qty-down"><i class="fi-rs-angle-small-down"></i></a>
                                            <span class="qty-val">1</span>
                                            <a href="javascript:;" class="qty-up"><i class="fi-rs-angle-small-up"></i></a>
                                        </div>

                                        <div class="product-extra-link2">
                                            <button type="button" class="button button-add-to-cart">
                                                Add to cart
                                            </button>
                                            <a aria-label="Add To Wishlist" class="action-btn hover-up" href="javascript:;">
                                                <i class="fi-rs-heart"></i>
                                            </a>
                                          
                                        </div>
                                    </div>

                                    <ul class="product-meta font-xs color-grey mt-50">
                                        @if ($product->sku)
                                            <li class="mb-5">SKU: <a href="javascript:;">{{ $product->sku }}</a></li>
                                        @endif

                                        @if ($product->category)
                                            <li class="mb-5">
                                                Category:
                                                <a href="javascript:;">{{ $product->category->name }}</a>
                                            </li>
                                        @endif

                                        <li>
                                            Availability:
                                            @if (($product->stock_quantity ?? 0) > 0)
                                                <span class="in-stock text-success ml-5">
                                                    {{ $product->stock_quantity }} Item(s) In Stock
                                                </span>
                                            @else
                                                <span class="text-danger ml-5">Out of Stock</span>
                                            @endif
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-lg-10 m-auto entry-main-content">
                                <h2 class="section-title style-1 mb-30">Description</h2>
                                <div class="description mb-50">
                                    <p>{!! nl2br(e($product->description ?: 'No detailed description available for this product yet.')) !!}</p>
                                </div>

                                <h3 class="section-title style-1 mb-30">Additional info</h3>
                                <table class="font-md mb-30">
                                    <tbody>
                                        @if ($product->brand)
                                            <tr>
                                                <th>Brand</th>
                                                <td><p>{{ $product->brand }}</p></td>
                                            </tr>
                                        @endif

                                        @if ($product->model)
                                            <tr>
                                                <th>Model</th>
                                                <td><p>{{ $product->model }}</p></td>
                                            </tr>
                                        @endif

                                        @if ($product->condition)
                                            <tr>
                                                <th>Condition</th>
                                                <td><p>{{ ucfirst($product->condition) }}</p></td>
                                            </tr>
                                        @endif

                                        @if ($product->weight)
                                            <tr>
                                                <th>Weight</th>
                                                <td><p>{{ $product->weight }}</p></td>
                                            </tr>
                                        @endif

                                        @if ($product->length || $product->width || $product->height)
                                            <tr>
                                                <th>Dimensions</th>
                                                <td>
                                                    <p>
                                                        {{ $product->length ?: 0 }} ×
                                                        {{ $product->width ?: 0 }} ×
                                                        {{ $product->height ?: 0 }}
                                                    </p>
                                                </td>
                                            </tr>
                                        @endif

                                        @if ($product->barcode)
                                            <tr>
                                                <th>Barcode</th>
                                                <td><p>{{ $product->barcode }}</p></td>
                                            </tr>
                                        @endif

                                        @if ($product->warranty)
                                            <tr>
                                                <th>Warranty</th>
                                                <td><p>{{ $product->warranty }}</p></td>
                                            </tr>
                                        @endif

                                        <tr>
                                            <th>Featured</th>
                                            <td><p>{{ $product->is_featured ? 'Yes' : 'No' }}</p></td>
                                        </tr>

                                        <tr>
                                            <th>Free Shipping</th>
                                            <td><p>{{ $product->has_free_shipping ? 'Yes' : 'No' }}</p></td>
                                        </tr>

                                        <tr>
                                            <th>Eco Friendly</th>
                                            <td><p>{{ $product->is_eco_friendly ? 'Yes' : 'No' }}</p></td>
                                        </tr>

                                        <tr>
                                            <th>Sustainable</th>
                                            <td><p>{{ $product->is_sustainable ? 'Yes' : 'No' }}</p></td>
                                        </tr>

                                        <tr>
                                            <th>Handmade</th>
                                            <td><p>{{ $product->is_handmade ? 'Yes' : 'No' }}</p></td>
                                        </tr>

                                        <tr>
                                            <th>Customizable</th>
                                            <td><p>{{ $product->is_customizable ? 'Yes' : 'No' }}</p></td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="social-icons single-share">
                                    <ul class="text-grey-5 d-inline-block">
                                        <li><strong class="mr-10">Share this:</strong></li>
                                        <li class="social-facebook"><a href="javascript:;"><img src="assets/imgs/theme/icons/icon-facebook.svg" alt=""></a></li>
                                        <li class="social-twitter"><a href="javascript:;"><img src="assets/imgs/theme/icons/icon-twitter.svg" alt=""></a></li>
                                        <li class="social-instagram"><a href="javascript:;"><img src="assets/imgs/theme/icons/icon-instagram.svg" alt=""></a></li>
                                        <li class="social-linkedin"><a href="javascript:;"><img src="assets/imgs/theme/icons/icon-pinterest.svg" alt=""></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Optional related products area placeholder --}}
                        <div class="row mt-60">
                            <div class="col-12">
                                <h3 class="section-title style-1 mb-30">Related products</h3>
                                <div class="text-muted">
                                    Related products can be loaded here based on category.
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
