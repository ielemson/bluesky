@extends('layouts.admin')

@section('title', 'Vendor Products')

@section('content')
@section('content_header')
    @include('admin.partials.page_header', [
        'header1' => 'Vendors',
        'header2' => $vendor->store_name . ' Products',
    ])
@endsection

<section class="content">
    {{-- Top stats row --}}
    <div class="row mb-4">
        <div class="col-md-4 col-12">
            <div class="box box-body">
                <h6 class="mb-30">
                    <span class="text-uppercase">Total Listings</span>
                    <span class="float-right">
                        {{-- optional: scroll to grid --}}
                        <a class="btn btn-xs btn-primary" href="#vendor-products-grid">View</a>
                    </span>
                </h6>
                <p class="font-size-26">{{ $listings->total() }}</p>
                <div class="progress progress-xxs mt-0 mb-10">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 35%; height: 4px;"
                        aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="box box-body">
                <h6 class="mb-30">
                    <span class="text-uppercase">Active Listings</span>
                    <span class="float-right">
                        <a class="btn btn-xs btn-primary"
                            href="{{ request()->fullUrlWithQuery(['filter' => 'active']) }}">
                            View
                        </a>
                    </span>
                </h6>
                <p class="font-size-26">
                    {{ $listings->where('is_active', true)->count() }}
                </p>
                <div class="progress progress-xxs mt-0 mb-10">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 35%; height: 4px;"
                        aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="box box-body">
                <h6 class="mb-30">
                    <span class="text-uppercase">In Stock</span>
                    <span class="float-right">
                        <a class="btn btn-xs btn-primary"
                            href="{{ request()->fullUrlWithQuery(['filter' => 'in_stock']) }}">
                            View
                        </a>
                    </span>
                </h6>
                <p class="font-size-26">
                    {{ $listings->where('stock_quantity', '>', 0)->count() }}
                </p>
                <div class="progress progress-xxs mt-0 mb-10">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 35%; height: 4px;"
                        aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Products grid --}}
    <div class="row" id="vendor-products-grid">
        @forelse($listings as $listing)
            <div class="col-12 col-lg-6 col-xl-4">
                <div class="box" data-listing-id="{{ $listing->id }}">
                    <div class="box-body">
                        <div class="product-img">
                            @php
                                $product = $listing->product;
                                $firstImage = $product?->images->first();
                            @endphp

                            @if ($firstImage)
                                <img src="{{ asset($firstImage->image_path) }}"
                                    alt="{{ $firstImage->alt_text ?? $product->name }}" class="img-fluid rounded"
                                    style="max-height: 200px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/300x200?text=No+Image" alt="No image available"
                                    class="img-fluid rounded" style="max-height: 200px; object-fit: cover;">
                            @endif
                        </div>

                        <div class="product-text">
                            <div class="pro-img-overlay">
                                {{-- Admin view; could add manage/edit here if needed --}}
                                <a href="{{ route('admin.vendors.products', [
                                    'vendor' => $vendor->id,
                                    'vendorProduct' => $listing->id,
                                ]) }}"
                                    class="btn btn-info btn-icon-circle" type="button">
                                    <i class="mdi mdi-eye"></i>
                                </a>
                            </div>

                            {{-- <h2 class="pro-price text-blue" style="font-size: 1.5rem">
                                ${{ number_format($listing->vendor_price, 0) }}
                            </h2> --}}
                            <h3 class="box-title mb-0">{{ $product->name }}</h3>
                            <small class="text-muted db">
                                {{ Str::limit($product->short_description, 50) }}
                            </small>
                        </div>

                        <div class="product-base-info mt-2 pt-2 border-top">
                            <small class="text-muted d-block">
                                <i class="mdi mdi-tag-outline"></i>
                                Base Price: ${{ number_format($product->price, 2) }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="mdi mdi-barcode"></i>
                                SKU: {{ $product->sku ?? 'N/A' }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="mdi mdi-package-variant"></i>
                                Category: {{ $product->category->name ?? 'N/A' }}
                            </small>
                            <small class="text-muted d-block">
                                <i class="mdi mdi-library-plus"></i>
                                Stock: <span>{{ $listing->stock_quantity }}</span>
                            </small>
                            <small class="text-muted d-block">
                                <i class="mdi mdi-checkbox-marked-circle-outline"></i>
                                Status:
                                <span class="{{ $listing->is_active ? 'text-success' : 'text-danger' }}">
                                    {{ $listing->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </small>
                        </div>

                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted small">
                                    Listing ID: #{{ $listing->id }}
                                </span>
                                {{-- Example admin delete, if you want --}}
                                {{-- <button class="btn btn-sm btn-outline-danger" type="button">
                                    <i class="mdi mdi-delete"></i> Remove
                                </button> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body text-center py-5">
                        <i class="mdi mdi-format-list-bulleted mdi-48px text-muted mb-3"></i>
                        <h4 class="text-muted">No Listings Yet</h4>
                        <p class="text-muted">
                            This vendor has no products listed yet.
                        </p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if ($listings instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="mt-4">
            {{ $listings->links() }}
        </div>
    @endif
</section>
@endsection
