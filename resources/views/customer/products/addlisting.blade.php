@extends('layouts.customer')
@section('content')
@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => Auth()->user()->nickname,
        'header_2' => 'Products'])
@endsection

<section class="content">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <h6 class="card-title">Filter by Category</h6>
                    <div class="row">
                        <div class="col-md-6">
                            <form action="" method="GET">
                                <div class="form-group">
                                    <select name="category_id" class="form-control" onchange="this.form.submit()">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ isset($categoryId) && $categoryId == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-right">
                            <small class="text-muted">
                                Showing {{ $products->count() }} of {{ $products->total() }} products
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   <div class="row">
    @forelse($products as $product)
        <div class="col-12 col-lg-6 col-xl-4">
            <div class="box">
                <div class="box-body">

                    {{-- Product Image --}}
                    <div class="product-img">
                        @if ($product->images->count() > 0)
                            <img src="{{ asset($product->images->first()->image_path) }}"
                                 alt="{{ $product->images->first()->alt_text ?? $product->name }}"
                                 class="img-fluid"
                                 style="width: 100%; height: 220px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/300x220?text=No+Image"
                                 class="img-fluid"
                                 style="width: 100%; height: 220px; object-fit: cover;">
                        @endif
                    </div>

                    {{-- Product Content --}}
                    <div class="product-text position-relative">
                        <div class="pro-img-overlay">
                            <a href="javascript:void(0)"
                               class="btn btn-info btn-icon-circle add-to-listing"
                               data-product-id="{{ $product->id }}"
                               data-product-name="{{ $product->name }}">
                                <i class="mdi mdi-cart-plus"></i>
                            </a>
                        </div>

                        <h3 class="box-title mb-0">
                            {{ Str::limit($product->name, 50) }}
                        </h3>

                        <small class="text-muted db">
                            {{ Str::limit($product->short_description ?? $product->description, 80) }}
                        </small>
                    </div>

                    {{-- Footer --}}
                    <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">
                        <h4 class="text-blue mb-0" style="font-weight: 600;">
                            ${{ number_format($product->cost_price ?? $product->price, 0) }}
                        </h4>

                        <button class="btn btn-sm btn-primary add-to-listing"
                                data-product-id="{{ $product->id }}"
                                data-product-name="{{ $product->name }}">
                            <i class="mdi mdi-cart-plus"></i> Add
                        </button>
                    </div>

                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="alert alert-info text-center">
                <i class="mdi mdi-information-outline"></i>
                No products available for vendors at the moment.
            </div>
        </div>
    @endforelse
</div>

{{-- ✅ Pagination ALWAYS OUTSIDE --}}
@if ($products->hasPages())
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-center">
                {{ $products->onEachSide(1)->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
@endif
</section>
@push('styles')
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush
@push('scripts')
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Add to listing button click
        $('.add-to-listing').on('click', function() {
            const productId = $(this).data('product-id');
            const productName = $(this).data('product-name');
            const button = $(this);

            // Show loading state on button
            const originalHtml = button.html();
            button.prop('disabled', true).html('<i class="mdi mdi-loading mdi-spin"></i>');

            $.ajax({
                url: '{{ route('vendor.listing.add') }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        // Show SweetAlert success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: response.message,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'OK'
                        }).then((result) => {
                            // Update button to show added state
                            button.removeClass('btn-success').addClass('btn-secondary')
                                .html('<i class="mdi mdi-check"></i>')
                                .prop('disabled', true)
                                .attr('title', 'Already in your listing')
                                .tooltip('dispose').tooltip();
                        });
                    } else {
                        // Show SweetAlert error message
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message,
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'OK'
                        });

                        // Reset button
                        button.prop('disabled', false).html(originalHtml);
                    }
                },
                error: function(xhr) {
                    let message = 'An error occurred while adding the product. Please try again.';

                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }

                    // Show SweetAlert error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });

                    // Reset button
                    button.prop('disabled', false).html(originalHtml);
                }
            });
        });
    </script>
@endpush

@endsection