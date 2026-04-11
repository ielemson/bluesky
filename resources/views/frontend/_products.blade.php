@extends('layouts.app')

@section('title', 'Product Details')

@section("home_")

<!-- START HEADER -->
@include("frontend.partials.page-header")
<!-- END HEADER -->

<!-- START SECTION BREADCRUMB -->
<div class="breadcrumb_section bg_gray page-title-mini">
    <div class="container"><!-- STRART CONTAINER -->
        @include("frontend.partials.bread-crumb",["header_1"=>"Product Detail",'header_2'=>"Product"])
    </div>
    <!-- END CONTAINER-->
</div>
<!-- END SECTION BREADCRUMB -->

<!-- START MAIN CONTENT -->
<div class="main_content">

<!-- START SECTION SHOP -->
<div class="section">
<!-- START SECTION SHOP -->
<div class="section">
	<div class="container">
    	<div class="row">
			<div class="col-12">
            	<div class="row align-items-center mb-4 pb-1">
                    <div class="col-12">
                        <div class="product_header">
                            <div class="product_header_left">
                                <div class="custom_select">
                                    <select class="form-control form-control-sm">
                                        <option value="order">Default sorting</option>
                                        <option value="popularity">Sort by popularity</option>
                                        <option value="date">Sort by newness</option>
                                        <option value="price">Sort by price: low to high</option>
                                        <option value="price-desc">Sort by price: high to low</option>
                                    </select>
                                </div>
                            </div>
                            <div class="product_header_right">
                            	<div class="products_view">
                                    <a href="javascript:;" class="shorting_icon grid active"><i class="ti-view-grid"></i></a>
                                    <a href="javascript:;" class="shorting_icon list"><i class="ti-layout-list-thumb"></i></a>
                                </div>
                                <div class="custom_select">
                                    <select class="form-control form-control-sm first_null not_chosen">
                                        <option value="">Showing</option>
                                        <option value="9">9</option>
                                        <option value="12">12</option>
                                        <option value="18">18</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> 
                <div class="row shop_container grid">
                    @forelse($vendorProducts as $vendorProduct)
                        @php
                            $product = $vendorProduct->product;
                            $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
                            $hoverImage = $product->images->where('is_primary', false)->first();
                        @endphp
                        <div class="col-lg-3 col-md-4 col-6">
                            <div class="product">
                                <div class="product_img">
                                    <a href="{{ route('page.products.show', ['slug' => $product->slug, 'vendor' => $vendorProduct->vendor_id]) }}">
                                        @if ($primaryImage)
                                            <img src="{{ asset($primaryImage->image_path) }}" alt="{{ $primaryImage->alt_text ?? $product->name }}">
                                        @endif
                                        @if ($hoverImage)
                                            <img class="product_hover_img" src="{{ asset($hoverImage->image_path) }}" alt="{{ $hoverImage->alt_text ?? $product->name }}">
                                        @endif
                                    </a>
                                    @if ($vendorProduct->vendor)
                                        <div class="vendor-badge">
                                            <small class="badge badge-light">Sold by: {{ $vendorProduct->vendor->business_name ?? $vendorProduct->vendor->name }}</small>
                                        </div>
                                    @endif
                                    <div class="product_action_box">
                                        <ul class="list_none pr_action_btn">
                                            <li class="add-to-cart">
                                                <a href="javascript:;"
                                                    class="add-to-cart-link {{ $vendorProduct->stock_quantity <= 0 ? 'disabled' : '' }}"
                                                    data-vendor-product-id="{{ $vendorProduct->id }}" 
                                                    data-product-id="{{ $product->id }}"
                                                    data-vendor-id="{{ $vendorProduct->vendor_id }}"
                                                    data-price="{{ $vendorProduct->vendor_price }}" 
                                                    data-name="{{ $product->name }}"
                                                    data-slug="{{ $product->slug }}"
                                                    data-image="{{ $primaryImage ? asset($primaryImage->image_path) : '' }}">
                                                    <i class="icon-basket-loaded"></i>
                                                    {{ $vendorProduct->stock_quantity <= 0 ? 'Out of Stock' : 'Add To Cart' }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" class="popup-ajax">
                                                    <i class="icon-shuffle"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" class="popup-ajax">
                                                    <i class="icon-magnifier-add"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" data-vendor-product-id="{{ $vendorProduct->id }}">
                                                    <i class="icon-heart"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="product_info">
                                    <h6 class="product_title">
                                        <a href="{{ route('page.products.show', ['slug' => $product->slug, 'vendor' => $vendorProduct->vendor_id]) }}">
                                            {{ $product->name }}
                                        </a>
                                    </h6>
                                    <!-- Vendor Info -->
                                    @if ($vendorProduct->vendor)
                                        <div class="vendor-info mb-2">
                                            <small class="text-muted">
                                                <i class="icon-user"></i>
                                                {{ $vendorProduct->vendor->business_name ?? $vendorProduct->vendor->name }}
                                            </small>
                                        </div>
                                    @endif
                                    <div class="product_price">
                                        <span class="price">${{ number_format($vendorProduct->vendor_price, 2) }}</span>
                                        <!-- Stock Status -->
                                        @if ($vendorProduct->stock_quantity > 0)
                                            <small class="text-success">In Stock ({{ $vendorProduct->stock_quantity }})</small>
                                        @else
                                            <small class="text-danger">Out of Stock</small>
                                        @endif
                                        @if ($product->compare_price && $product->compare_price > $vendorProduct->vendor_price)
                                            <del>${{ number_format($product->compare_price, 2) }}</del>
                                            <div class="on_sale">
                                                <span>{{ calculateDiscountPercentage($product->compare_price, $vendorProduct->vendor_price) }}% Off</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="rating_wrap">
                                        <div class="rating">
                                            <div class="product_rate" style="width:{{ rand(60, 95) }}%"></div>
                                        </div>
                                        <span class="rating_num">({{ rand(5, 50) }})</span>
                                        <!-- Vendor Rating -->
                                        @if ($vendorProduct->vendor && $vendorProduct->vendor->rating)
                                            <small class="text-warning ml-2">
                                                <i class="icon-star"></i> {{ number_format($vendorProduct->vendor->rating, 1) }}
                                            </small>
                                        @endif
                                    </div>
                                    <div class="pr_desc">
                                        <p>{{ $product->short_description ?? Str::limit($product->description, 120) }}</p>
                                    </div>
                                    <div class="pr_switch_wrap">
                                        <div class="product_color_switch">
                                            <!-- Color variants would go here if available -->
                                            <span class="active" data-color="#87554B" style="background-color: rgb(135, 85, 75);"></span>
                                            <span data-color="#333333" style="background-color: rgb(51, 51, 51);"></span>
                                            <span data-color="#DA323F" style="background-color: rgb(218, 50, 63);"></span>
                                        </div>
                                    </div>
                                    <div class="list_product_action_box">
                                        <ul class="list_none pr_action_btn">
                                            <li class="add-to-cart">
                                                <a href="javascript:;"
                                                    class="add-to-cart-link {{ $vendorProduct->stock_quantity <= 0 ? 'disabled' : '' }}"
                                                    data-vendor-product-id="{{ $vendorProduct->id }}" 
                                                    data-product-id="{{ $product->id }}"
                                                    data-vendor-id="{{ $vendorProduct->vendor_id }}"
                                                    data-price="{{ $vendorProduct->vendor_price }}" 
                                                    data-name="{{ $product->name }}"
                                                    data-slug="{{ $product->slug }}"
                                                    data-image="{{ $primaryImage ? asset($primaryImage->image_path) : '' }}">
                                                    <i class="icon-basket-loaded"></i>
                                                    {{ $vendorProduct->stock_quantity <= 0 ? 'Out of Stock' : 'Add To Cart' }}
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" class="popup-ajax">
                                                    <i class="icon-shuffle"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="" class="popup-ajax">
                                                    <i class="icon-magnifier-add"></i>
                                                </a>
                                            </li>
                                            <li>
                                                <a href="#" data-vendor-product-id="{{ $vendorProduct->id }}">
                                                    <i class="icon-heart"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="text-center py-5">
                                <h4>No products found</h4>
                                <p>There are currently no products available.</p>
                            </div>
                        </div>
                    @endforelse
                </div>
        		<div class="row">
                    <div class="col-12">
                        <ul class="pagination mt-3 justify-content-center pagination_style1">
                            {{ $vendorProducts->links() }}
                        </ul>
                    </div>
                </div>
        	</div>
        </div>
    </div>
</div>
<!-- END SECTION SHOP -->
</div>

<!-- END SECTION SHOP -->
</div>
@include("components.login-form")
@include("components.register-form")
@endsection

