@extends('layouts.app')
@section('title', gtrans('Product Details'))

@section("home_")
@include("partials._general_header")

<div class="page-header breadcrumb-wrap">
    <div class="container">
        <div class="breadcrumb">
            <a href="{{ route('home') }}" rel="nofollow">{{ gtrans('Home') }}</a>
            <span></span>
            <a href="javascript:;">{{ gtrans('Shop') }}</a>

            @if ($selectedCategory)
                <span></span> {{ gtrans($selectedCategory->name) }}
            @endif
        </div>
    </div>
</div>

<section class="mt-50 mb-50">
    <div class="container">
        <div class="row">

            <div class="col-lg-12">
                <div class="shop-product-fillter">
                    <div class="totall-product">
                        <p>
                            @if ($selectedCategory)
                                {{ gtrans('Showing') }}
                                <strong class="text-brand">{{ $products->total() }}</strong>
                                {{ gtrans('item(s) in') }}
                                <strong>{{ gtrans($selectedCategory->name) }}</strong>
                            @else
                                {{ gtrans('We found') }}
                                <strong class="text-brand">{{ $products->total() }}</strong>
                                {{ gtrans('item(s) for you!') }}
                            @endif
                        </p>
                    </div>

                    <div class="sort-by-product-area">
                        <div class="sort-by-cover">
                            <div class="sort-by-product-wrap">
                                <div class="sort-by">
                                    <span><i class="fi-rs-apps"></i>{{ gtrans('Per Page:') }}</span>
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
                                $productUrl = route('page.products.show', ['slug' => $product->slug]);
                                $inStock = ($product->stock_quantity ?? 0) > 0;
                            @endphp

                            <div class="col-lg-4 col-md-4 col-sm-6 col-6">
                                <div class="product-cart-wrap mb-30">
                                    <div class="product-img-action-wrap">
                                        <div class="product-img product-img-zoom">
                                            <a href="{{ $productUrl }}">
                                                <img class="default-img" src="{{ $defaultImage }}" alt="{{ $product->name }}">
                                                <img class="hover-img" src="{{ $hoverImage }}" alt="{{ $product->name }}">
                                            </a>
                                        </div>

                                        <div class="product-action-1">
                                            <a aria-label="{{ gtrans('Quick view') }}"
                                               class="action-btn hover-up"
                                               href="{{ $productUrl }}">
                                                <i class="fi-rs-search"></i>
                                            </a>

                                            @guest
                                                <a aria-label="{{ gtrans('Login') }}"
                                                   class="action-btn hover-up"
                                                   href="javascript:;"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#loginModal"
                                                   title="{{ gtrans('Login to continue') }}">
                                                    <i class="fi-rs-heart"></i>
                                                </a>
                                            @else
                                                <a aria-label="{{ gtrans('Add To Wishlist') }}"
                                                   class="action-btn hover-up"
                                                   href="javascript:;">
                                                    <i class="fi-rs-heart"></i>
                                                </a>
                                            @endguest
                                        </div>

                                        @if (!is_null($oldPrice) && $oldPrice > $price && $oldPrice > 0)
                                            @php
                                                $discount = round((($oldPrice - $price) / $oldPrice) * 100);
                                            @endphp
                                            <div class="product-badges product-badges-position product-badges-mrg">
                                                <span class="sale">-{{ $discount }}%</span>
                                            </div>
                                        @elseif(!$inStock)
                                            <div class="product-badges product-badges-position product-badges-mrg">
                                                <span class="hot">{{ gtrans('Sold Out') }}</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="product-content-wrap">
                                        <div class="product-category">
                                            <a href="javascript:;">
                                                {{ gtrans($product->category->name ?? 'Uncategorized') }}
                                            </a>
                                        </div>

                                        <h2>
                                            <a href="{{ $productUrl }}">
                                                {{ gtrans($product->name) }}
                                            </a>
                                        </h2>

                                        @if (!empty($product->short_description))
                                            <p class="font-small text-muted mb-5">
                                                {{ gtrans(\Illuminate\Support\Str::limit($product->short_description, 55)) }}
                                            </p>
                                        @endif

                                        <div class="product-price">
                                            <span>${{ number_format($price, 2) }}</span>

                                            @if (!is_null($oldPrice) && $oldPrice > $price)
                                                <span class="old-price">${{ number_format($oldPrice, 2) }}</span>
                                            @endif
                                        </div>

                                        <div class="product-action-1 show">
                                            @if($inStock)
                                                <a aria-label="{{ gtrans('Add To Cart') }}"
                                                   class="action-btn hover-up add-to-cart-link"
                                                   href="javascript:;"
                                                   data-product-id="{{ $product->id }}">
                                                    <i class="fi-rs-shopping-bag-add"></i>
                                                </a>
                                            @else
                                                <a aria-label="{{ gtrans('Out of Stock') }}"
                                                   class="action-btn hover-up disabled"
                                                   href="javascript:;"
                                                   aria-disabled="true"
                                                   style="opacity:.5; pointer-events:none;">
                                                    <i class="fi-rs-shopping-bag-add"></i>
                                                </a>
                                            @endif

                                            @guest
                                                <a aria-label="{{ gtrans('Login') }}"
                                                   class="action-btn hover-up"
                                                   href="javascript:;"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#loginModal"
                                                   title="{{ gtrans('Login') }}">
                                                    <i class="fi-rs-user"></i>
                                                </a>
                                            @endguest
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="pagination-area mt-15 mb-sm-5 mb-lg-0">
                        {{ $products->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <h4 class="mb-2">{{ gtrans('No products found') }}</h4>
                        <p class="text-muted">
                            @if ($selectedCategory)
                                {{ gtrans('There are no products available under') }} {{ gtrans($selectedCategory->name) }} {{ gtrans('at the moment.') }}
                            @else
                                {{ gtrans('No products are available at the moment.') }}
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const setCartCount = (count) => {
        document.querySelectorAll('.cart_count').forEach(el => {
            el.textContent = count;
        });
    };

    const setCartDropdown = (html) => {
        document.querySelectorAll('.cart_box').forEach(el => {
            el.innerHTML = html;
        });
    };

    const showToast = (message, success = true) => {
        Toastify({
            text: message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            style: {
                background: success
                    ? "linear-gradient(to right, #00b09b, #96c93d)"
                    : "linear-gradient(to right, #ff5f6d, #ffc371)"
            }
        }).showToast();
    };

    document.body.addEventListener("click", async function (e) {
        const link = e.target.closest(".add-to-cart-link");
        if (!link || link.classList.contains('disabled')) return;

        e.preventDefault();

        try {
            const response = await fetch("{{ route('cart.add.ajax') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({
                    product_id: link.dataset.productId,
                    quantity: 1
                })
            });

            const data = await response.json();

            if (!response.ok || !data.status) {
                showToast(data.message || "{{ gtrans('Unable to add to cart.') }}", false);
                console.error(data);
                return;
            }

            showToast(data.message || "{{ gtrans('Product added to cart!') }}");
            setCartCount(data.cart_count);
            setCartDropdown(data.cart_dropdown);

        } catch (error) {
            console.error(error);
            showToast("{{ gtrans('Something went wrong.') }}", false);
        }
    });
});
</script>
@endpush
@endsection