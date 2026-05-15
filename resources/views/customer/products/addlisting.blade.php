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

            @php
                $productImage =
                    $product->images->count() > 0
                        ? asset($product->images->first()->image_path)
                        : 'https://via.placeholder.com/300x220?text=No+Image';
                $salesPrice = (float) ($product->price ?? 0);
                $salePercentage = (float) ($product->sale_percentage ?? 0);
                $wholesalePrice = ($salePercentage / 100) * $salesPrice;
            @endphp

            <div class="col-12 col-lg-6 col-xl-4">
                <div class="box">
                    <div class="box-body">

                        <div class="product-img">
                            <img src="{{ $productImage }}"
                                alt="{{ $product->images->first()->alt_text ?? $product->name }}"
                                class="img-fluid"
                                style="width: 100%; height: 220px; object-fit: cover;">
                        </div>

                        <div class="product-text position-relative">

                            <div class="pro-img-overlay">
                                <a href="javascript:void(0)"
                                   class="btn btn-info btn-icon-circle add-to-listing"
                                   data-product-id="{{ $product->id }}"
                                   data-product-name="{{ e($product->name) }}"
                                   data-product-image="{{ $productImage }}"
                                   data-sales-price="{{ $salesPrice }}"
                                   data-sale-percentage="{{ $salePercentage }}"
                                   data-wholesale-price="{{ $wholesalePrice }}">
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

                        <div class="mt-3 pt-3 border-top d-flex justify-content-between align-items-center">

                            <h4 class="text-blue mb-0" style="font-weight: 600;">
                                ${{ number_format($salesPrice, 2) }}
                            </h4>

                            <button type="button"
                                    class="btn btn-sm btn-primary add-to-listing"
                                    data-product-id="{{ $product->id }}"
                                    data-product-name="{{ e($product->name) }}"
                                    data-product-image="{{ $productImage }}"
                                    data-sales-price="{{ $salesPrice }}"
                                    data-sale-percentage="{{ $salePercentage }}"
                                    data-wholesale-price="{{ $wholesalePrice }}">
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

@endsection
@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .vendor-listing-modal {
        border-radius: 12px !important;
        padding: 18px 20px !important;
    }

    .listing-confirm-box {
        text-align: left;
    }

    .listing-confirm-header {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .listing-confirm-img {
        width: 90px;
        height: 90px;
        object-fit: cover;
        border-radius: 6px;
        background: #f5f5f5;
    }

    .listing-confirm-title {
        font-size: 20px;
        line-height: 1.5;
        color: #111;
        font-weight: 500;
    }

    .listing-confirm-prices p {
        font-size: 19px;
        color: #333;
        margin-bottom: 18px;
    }

    .listing-confirm-prices span {
        color: #d71920;
        font-weight: 500;
    }

    .vendor-confirm-btn {
        border-radius: 8px !important;
        padding: 10px 26px !important;
        font-size: 17px !important;
    }

    .vendor-cancel-btn {
        border-radius: 8px !important;
        padding: 10px 26px !important;
        font-size: 17px !important;
        color: #333 !important;
        border: 1px solid #ddd !important;
    }
</style>
@endpush
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>
$(document).on('click', '.add-to-listing', function () {

    const button = $(this);

    const productId = button.data('product-id');
    const productName = button.data('product-name') || 'Product';
    const productImage = button.data('product-image');

    const salesPrice = parseFloat(button.data('sales-price')) || 0;
    const salePercentage = parseFloat(button.data('sale-percentage')) || 0;

    /*
    |--------------------------------------------------------------------------
    | WHOLESALE CALCULATION
    |--------------------------------------------------------------------------
    | wholesale is percentage of sales price
    |--------------------------------------------------------------------------
    */
    const wholesalePrice = (salePercentage / 100) * salesPrice;

    const formattedSalesPrice = salesPrice.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    const formattedWholesalePrice = wholesalePrice.toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    Swal.fire({
        width: 560,
        showCancelButton: true,
        confirmButtonText: 'Confirm listing',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#1687f5',
        cancelButtonColor: '#ffffff',
        reverseButtons: true,
        customClass: {
            popup: 'vendor-listing-modal',
            confirmButton: 'vendor-confirm-btn',
            cancelButton: 'vendor-cancel-btn'
        },

        html: `
            <div class="listing-confirm-box">

                <div class="listing-confirm-header">
                    <img src="${productImage}" 
                         alt="${productName}" 
                         class="listing-confirm-img">

                    <div class="listing-confirm-title">
                        ${productName}
                    </div>
                </div>

                <hr>

                <div class="listing-confirm-prices">
                    <p>
                        Sales Price:
                        <span>$${formattedSalesPrice}</span>
                    </p>

                    <p>
                        Wholesale Price:
                        <span>$${formattedWholesalePrice}</span>
                    </p>
                </div>

            </div>
        `,

        preConfirm: () => {

            const originalHtml = button.html();

            button.prop('disabled', true)
                  .html('<i class="mdi mdi-loading mdi-spin"></i>');

            return $.ajax({
                url: '{{ route('vendor.listing.add') }}',
                method: 'POST',
                data: {
                    product_id: productId,
                    _token: '{{ csrf_token() }}'
                }
            })

            .then(function (response) {

                if (!response.success) {

                    button.prop('disabled', false)
                          .html(originalHtml);

                    Swal.showValidationMessage(
                        response.message || 'Unable to add product.'
                    );

                    return false;
                }

                return response;
            })

            .catch(function (xhr) {

                button.prop('disabled', false)
                      .html(originalHtml);

                let message = 'Error adding product to listing. Please try again.';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }

                Swal.showValidationMessage(message);

                return false;
            });
        }
    })

    .then((result) => {

        if (result.isConfirmed && result.value && result.value.success) {

            button.removeClass('btn-primary btn-info')
                  .addClass('btn-secondary')
                  .html('<i class="mdi mdi-check"></i> Added')
                  .prop('disabled', true);

            Swal.fire({
                icon: 'success',
                title: 'Listed',
                text: result.value.message,
                confirmButtonColor: '#1687f5'
            });
        }
    });
});
</script>
@endpush
