@extends('layouts.customer')

@section('content')
@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => Auth()->user()->nickname,
        'header_2' => 'Products',
    ])
@endsection

<section class="content">
    <div class="row mb-4">
        <div class="col-md-4 col-12">
            <div class="box box-body">
                <h6 class="mb-30">
                    <span class="text-uppercase">Total Listings</span>
                    <span class="float-right"><a class="btn btn-xs btn-primary" href="#">View</a></span>
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
                    <span class="float-right"><a class="btn btn-xs btn-primary" href="#">View</a></span>
                </h6>

                <p class="font-size-26">{{ $listings->where('is_active', true)->count() }}</p>

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
                    <span class="float-right"><a class="btn btn-xs btn-primary" href="#">View</a></span>
                </h6>

                <p class="font-size-26">{{ $listings->where('stock_quantity', '>', 0)->count() }}</p>

                <div class="progress progress-xxs mt-0 mb-10">
                    <div class="progress-bar bg-danger" role="progressbar" style="width: 35%; height: 4px;"
                        aria-valuenow="35" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

            </div>
        </div>
    </div>

  <div class="row">
    @forelse($listings as $listing)
        <div class="col-12 col-lg-6 col-xl-4">
            <div class="box" data-listing-id="{{ $listing->id }}">
                <div class="box-body">
                    <div class="product-img position-relative">
                        @if ($listing->product->images->count() > 0)
                            <img src="{{ asset($listing->product->images->first()->image_path) }}"
                                 alt="{{ $listing->product->images->first()->alt_text ?? $listing->product->name }}"
                                 class="img-fluid"
                                 style="width: 100%; height: 220px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/300x220?text=No+Image"
                                 alt="No image available"
                                 class="img-fluid"
                                 style="width: 100%; height: 220px; object-fit: cover;">
                        @endif
                    </div>

                    <div class="product-text">
                        {{-- <div class="pro-img-overlay">
                            <a href="javascript:void(0)"
                               class="btn btn-info btn-icon-circle"
                               title="Listing ID #{{ $listing->id }}">
                                <i class="mdi mdi-information-outline"></i>
                            </a>

                            <a href="javascript:void(0)"
                               class="btn btn-danger btn-icon-circle remove-listing"
                               data-listing-id="{{ $listing->id }}"
                               data-product-name="{{ $listing->product->name }}"
                               title="Remove listing">
                                <i class="mdi mdi-delete"></i>
                            </a>
                        </div> --}}

                        {{-- <h2 class="pro-price text-blue">
                            ${{ number_format($listing->vendor_price, 2) }}
                        </h2> --}}

                        <h3 class="box-title mb-0">{{ $listing->product->name }}</h3>

                        <small class="text-muted db">
                            {{ Str::limit($listing->product->short_description, 60) ?: 'No short description available.' }}
                        </small>
                    </div>

                    <div class="product-base-info mt-3 pt-3 border-top">
                        <small class="text-muted d-block mb-1">
                            <i class="mdi mdi-tag-outline"></i>
                            Base Price: ${{ number_format($listing->product->price, 2) }}
                        </small>

                        <small class="text-muted d-block mb-1">
                            <i class="mdi mdi-barcode"></i>
                            SKU: {{ $listing->product->sku ?? 'N/A' }}
                        </small>

                        <small class="text-muted d-block mb-1">
                            <i class="mdi mdi-package-variant"></i>
                            Category: {{ $listing->product->category->name ?? 'N/A' }}
                        </small>

                        <small class="text-muted d-block mb-1">
                            <i class="mdi mdi-library-plus"></i>
                            Stock: {{ $listing->stock_quantity }}
                        </small>

                        <small class="text-muted d-block">
                            <i class="mdi mdi-checkbox-marked-circle-outline"></i>
                            Status:
                            <span class="{{ $listing->is_active ? 'text-success' : 'text-danger' }}">
                                {{ $listing->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </small>
                    </div>

                    <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                        <span class="text-muted small">Listing ID: #{{ $listing->id }}</span>

                        <button class="btn btn-sm btn-outline-danger remove-listing"
                                type="button"
                                data-listing-id="{{ $listing->id }}"
                                data-product-name="{{ $listing->product->name }}">
                            <i class="mdi mdi-delete"></i> Remove
                        </button>
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
                    <p class="text-muted">You haven't added any products to your listing yet.</p>
                    <a href="{{ route('customer.products.index') }}" class="btn btn-primary">
                        <i class="mdi mdi-plus"></i> Add Products to Listing
                    </a>
                </div>
            </div>
        </div>
    @endforelse
    
    <div class="row mt-4">
    <div class="col-12">
        @if ($listings->hasPages())
            <div class="d-flex justify-content-center">
                {{ $listings->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        @endif
    </div>
</div>
</div>
</section>

@endsection

@push('styles')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {

            // ------------------------------------------------------
            // REMOVE LISTING (ONLY ACTION ALLOWED)
            // ------------------------------------------------------
            $(document).on('click', '.remove-listing', function () {
                const listingId = $(this).data('listing-id');
                const productName = $(this).data('product-name');

                Swal.fire({
                    title: "Remove Listing?",
                    html: `You are about to remove <b>${productName}</b> from your store.`,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Yes, remove it",
                    cancelButtonText: "Cancel",
                    confirmButtonColor: "#d33"
                }).then(result => {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: "{{ url('vendor/listing') }}/" + listingId + "/remove",
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'POST'
                        },
                        processData: true,
                        contentType: 'application/x-www-form-urlencoded; charset=UTF-8',
                        beforeSend: () => {
                            Swal.fire({
                                title: "Removing...",
                                allowOutsideClick: false,
                                didOpen: Swal.showLoading
                            });
                        },
                        success: function (response) {
                            if (response.success) {
                                Swal.fire("Removed!", response.message, "success");

                                // Fade out card
                                $('.box[data-listing-id="' + listingId + '"]').fadeOut(300, function () {
                                    $(this).remove();

                                    // If no listings left, reload to show empty state
                                    if ($('.box[data-listing-id]').length === 0) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire("Error!", response.message || "Unable to remove listing.", "error");
                            }
                        },
                        error: function () {
                            Swal.fire("Error!", "Something went wrong.", "error");
                        }
                    });
                });
            });

        });
    </script>
@endpush
