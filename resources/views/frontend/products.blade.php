@extends('layouts.app')
@section('title', 'Product Details')
@section("home_")
 @include("partials._general_header")   
 <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ route("home") }}" rel="nofollow">Home</a>
                <span></span>
                <a href="javascript:;">Shop</a>

                @if ($selectedCategory)
                    <span></span> {{ $selectedCategory->name }}
                @endif
            </div>
        </div>
    </div>

    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                {{-- Sidebar categories --}}
                {{-- <div class="col-lg-3 primary-sidebar sticky-sidebar mb-sm-30">
                    <div class="sidebar-widget widget-category-2 mb-30">
                        <h5 class="section-title style-1 mb-30">Categories</h5>
                        <ul>
                            <li>
                                <a href="javascript:;"
                                   class="{{ !$selectedCategory ? 'active text-brand fw-bold' : '' }}">
                                    All Products
                                </a>
                                <span class="count">{{ $products->total() }}</span>
                            </li>

                            @forelse ($categories as $category)
                                <li class="mb-10">
                                    <a href="javascript:;"
                                       class="{{ $selectedCategory && $selectedCategory->id === $category->id ? 'active text-brand fw-bold' : '' }}">
                                        {{ $category->name }}
                                    </a>
                                    <span class="count">{{ $category->products_count }}</span>

                                    @if ($category->children->count())
                                        <ul class="mt-10 ms-3">
                                            @foreach ($category->children as $child)
                                                <li class="mb-5">
                                                    <a href="javascript:;"
                                                       class="{{ $selectedCategory && $selectedCategory->id === $child->id ? 'active text-brand fw-bold' : '' }}">
                                                        {{ $child->name }}
                                                    </a>
                                                    <span class="count">{{ $child->products_count }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @empty
                                <li>No categories found.</li>
                            @endforelse
                        </ul>
                    </div>
                </div> --}}

                {{-- Product area --}}
                <div class="col-lg-12">
                    <div class="shop-product-fillter">
                        <div class="totall-product">
                            <p>
                                @if ($selectedCategory)
                                    Showing
                                    <strong class="text-brand">{{ $products->total() }}</strong>
                                    item(s) in
                                    <strong>{{ $selectedCategory->name }}</strong>
                                @else
                                    We found
                                    <strong class="text-brand">{{ $products->total() }}</strong>
                                    item(s) for you!
                                @endif
                            </p>
                        </div>

                        <div class="sort-by-product-area">
                            <div class="sort-by-cover">
                                <div class="sort-by-product-wrap">
                                    <div class="sort-by">
                                        <span><i class="fi-rs-apps"></i>Per Page:</span>
                                    </div>
                                    <div class="sort-by-dropdown-wrap">
                                        <span>{{ $products->perPage() }} <i class="fi-rs-angle-small-down"></i></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if ($products->count())
                        <div class="row product-grid-3">
                            @foreach ($products as $product)
                                @php
                                    $defaultImage = $product->images->first()?->image_url
                                        ?? $product->images->first()?->path
                                        ?? asset('assets/imgs/shop/product-placeholder.jpg');

                                    $hoverImage = $product->images->skip(1)->first()?->image_url
                                        ?? $product->images->skip(1)->first()?->path
                                        ?? $defaultImage;

                                    $price = $product->price ?? 0;
                                    $oldPrice = $product->old_price ?? null;
                                @endphp

                                <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                                    <div class="product-cart-wrap mb-30">
                                        <div class="product-img-action-wrap">
                                            <div class="product-img product-img-zoom">
                                                <a href="javascript:;">
                                                    <img class="default-img" src="{{ $defaultImage }}" alt="{{ $product->name }}">
                                                    <img class="hover-img" src="{{ $hoverImage }}" alt="{{ $product->name }}">
                                                </a>
                                            </div>

                                            <div class="product-action-1">
                                                <a aria-label="Quick view"
                                                   class="action-btn hover-up"
                                                   href="javascript:;">
                                                    <i class="fi-rs-search"></i>
                                                </a>
                                                <a aria-label="Add To Wishlist"
                                                   class="action-btn hover-up"
                                                   href="javascript:;">
                                                    <i class="fi-rs-heart"></i>
                                                </a>
                                               
                                            </div>

                                            @if (!is_null($oldPrice) && $oldPrice > $price && $oldPrice > 0)
                                                @php
                                                    $discount = round((($oldPrice - $price) / $oldPrice) * 100);
                                                @endphp
                                                <div class="product-badges product-badges-position product-badges-mrg">
                                                    <span class="sale">-{{ $discount }}%</span>
                                                </div>
                                            @endif
                                        </div>

                                        <div class="product-content-wrap">
                                            <div class="product-category">
                                                <a href="javascript:;">
                                                    {{ $product->category->name ?? 'Uncategorized' }}
                                                </a>
                                            </div>

                                            <h2>
                                                <a href="javascript:;">
                                                    {{ $product->name }}
                                                </a>
                                            </h2>

                                            @if (!empty($product->short_description))
                                                <p class="font-small text-muted mb-5">
                                                    {{ \Illuminate\Support\Str::limit($product->short_description, 55) }}
                                                </p>
                                            @endif

                                            <div class="product-price">
                                                <span>${{ number_format($price, 2) }}</span>

                                                @if (!is_null($oldPrice) && $oldPrice > $price)
                                                    <span class="old-price">${{ number_format($oldPrice, 2) }}</span>
                                                @endif
                                            </div>

                                            <div class="product-action-1 show">
                                                <a aria-label="Add To Cart"
                                                   class="action-btn hover-up"
                                                   href="javascript:;">
                                                    <i class="fi-rs-shopping-bag-add"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="pagination-area mt-15 mb-sm-5 mb-lg-0">
                            {{ $products->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <h4 class="mb-2">No products found</h4>
                            <p class="text-muted">
                                @if ($selectedCategory)
                                    There are no products available under {{ $selectedCategory->name }} at the moment.
                                @else
                                    No products are available at the moment.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

