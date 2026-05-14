@extends('layouts.customer')

@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => auth()->user()->nickname ?? auth()->user()->name,
        'header_2' => 'Store Orders',
    ])
@endsection

@section('content')
    <section class="content vendor-orders-page">

        <div class="vendor-orders-header mb-3">
            <h3 class="mb-0 font-weight-bold">Store Orders</h3>
        </div>

        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <form action="{{ route('vendor.orders.index') }}" method="GET">
                    <div class="row align-items-end">

                        <div class="col-md-4 mb-2">
                            <label class="small text-muted mb-1">Search</label>
                            <input type="text" name="search" class="form-control"
                                placeholder="Order no, customer, phone, address" value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2 mb-2">
                            <label class="small text-muted mb-1">Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Orders</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                    Waiting for Delivery
                                </option>
                                <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                    Waiting for Receipt
                                </option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>
                                    Processed
                                </option>
                            </select>
                        </div>

                        <div class="col-md-2 mb-2">
                            <label class="small text-muted mb-1">From</label>
                            <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                        </div>

                        <div class="col-md-2 mb-2">
                            <label class="small text-muted mb-1">To</label>
                            <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                        </div>

                        <div class="col-md-2 mb-2">
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="mdi mdi-magnify"></i> Filter
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

        @php
            $tabs = [
                'pending' => 'Waiting for delivery',
                'processing' => 'Waiting for receipt',
                'completed' => 'Completed',
                'refunded' => 'Refund/After-sales',
            ];

            $activeStatus = request('status', 'pending');
        @endphp

        <div class="vendor-order-tabs mb-3">
            @foreach ($tabs as $key => $label)
                <a href="{{ route('vendor.orders.index', array_merge(request()->except('page'), ['status' => $key])) }}"
                    class="vendor-order-tab {{ $activeStatus === $key ? 'active' : '' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="vendor-orders-summary mb-3">
            <small class="text-muted">
                Showing {{ $orders->count() }} of {{ $orders->total() }} orders
            </small>
        </div>

        @forelse($orders as $order)
            @php
                $firstItem = $order->items->first();
                $firstProduct = optional($firstItem)->product;
                $firstImage = optional(optional($firstProduct)->images)->first();

                $totalQty = $order->items->sum('quantity');

                $statusClass = match ($order->order_status) {
                    'completed' => 'success',
                    'processing' => 'primary',
                    'pending' => 'warning',
                    default => 'secondary',
                };

                $imagePath = $firstImage ? asset($firstImage->image_path) : asset('assets/imgs/theme/no-image.png');

                $lumpSum = 0;
                $salesProfit = 0;
                $wholesalePrice = 0;

                foreach ($order->items as $item) {
                    $qty = (int) ($item->quantity ?? 1);
                    $unitPrice = (float) ($item->price ?? 0);

                    $itemSubtotal = $unitPrice * $qty;

                    $salePercentage = (float) (optional(optional($item->vendorProduct)->product)->sale_percentage ?? 0);

                    $itemDiscount = ($itemSubtotal * $salePercentage) / 100;
                    $itemWholesale = $itemSubtotal - $itemDiscount;

                    $lumpSum += $itemSubtotal;
                    $salesProfit += $itemDiscount;
                    $wholesalePrice += $itemWholesale;
                }
            @endphp

            <div class="vendor-order-card">

                <div class="vendor-order-top">
                    <div>
                        <span class="badge badge-{{ $statusClass }}">
                            @switch($order->order_status)
                                @case('pending')
                                    Waiting for Delivery
                                @break

                                @case('processing')
                                    Waiting for Receipt
                                @break

                                @case('completed')
                                    Processed
                                @break

                                @default
                                    {{ ucfirst($order->order_status ?? 'pending') }}
                            @endswitch
                        </span>

                        <span class="ml-2 text-muted">
                            {{ $order->order_number ?? '#' . $order->id }}
                        </span>
                    </div>

                    <small class="text-muted">
                        {{ optional($order->created_at)->format('d M Y, h:i A') }}
                    </small>
                </div>

                <div class="vendor-order-body">

                    <div class="vendor-order-image">
                        <img src="{{ $imagePath }}" alt="{{ $firstProduct->name ?? 'Product image' }}">
                    </div>

                    <div class="vendor-order-info">
                        <h5 class="mb-1">
                            {{ $firstProduct->name ?? 'Order Item' }}
                        </h5>

                        @if ($order->items->count() > 1)
                            <small class="text-muted">
                                +{{ $order->items->count() - 1 }} more item(s)
                            </small>
                        @endif

                        <div class="vendor-order-prices mt-2">
                            <p class="mb-0 text-danger">
                                Lump Sum:
                                <strong>${{ number_format($lumpSum, 2) }}</strong>
                                x{{ $totalQty }}
                            </p>

                            <p class="mb-0 text-danger">
                                Sales Profit:
                                <strong>${{ number_format($salesProfit, 2) }}</strong>
                                x{{ $totalQty }}
                            </p>

                            <p class="mb-0 text-danger">
                                Wholesale Price:
                                <strong>${{ number_format($wholesalePrice, 2) }}</strong>
                                x{{ $totalQty }}
                            </p>
                        </div>
                    </div>

                    <div class="vendor-order-action">
                        <div class="actual-payment">
                            Actual payment:
                            <strong>${{ number_format($wholesalePrice, 2) }}</strong>
                        </div>

                        @if ($order->order_status === 'pending')
                            <button type="button" class="btn btn-primary rounded-pill px-4 mt-2 go-to-shipment-btn"
                                data-url="{{ route('vendor.orders.shipment', $order->id) }}">
                                Go to Shipment
                            </button>
                        @elseif($order->order_status === 'processing')
                            <a href="javascript:;"
                                class="btn btn-warning rounded-pill px-4 mt-2">
                                Awaiting Receipt
                            </a>
                        @elseif($order->order_status === 'completed')
                            <button type="button" class="btn btn-success rounded-pill px-4 mt-2" disabled>
                                Processed
                            </button>
                        @endif
                    </div>

                </div>
            </div>

            @empty
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="mdi mdi-package-variant-closed display-4 text-muted"></i>
                        <h5 class="mt-3">No orders found</h5>
                        <p class="text-muted mb-0">Orders assigned to your store will appear here.</p>
                    </div>
                </div>
            @endforelse

            @if ($orders->hasPages())
                <div class="mt-4 d-flex justify-content-center">
                    {{ $orders->links() }}
                </div>
            @endif

        </section>

        @push('styles')
            <style>
                .vendor-orders-page {
                    background: #f7f7f7;
                    padding-bottom: 30px;
                }

                .vendor-order-tabs {
                    background: #fff;
                    display: grid;
                    grid-template-columns: repeat(4, 1fr);
                    border-radius: 4px;
                    overflow-x: auto;
                }

                .vendor-order-tab {
                    text-align: center;
                    padding: 18px 10px;
                    color: #666;
                    font-size: 16px;
                    font-weight: 500;
                    text-decoration: none;
                    position: relative;
                    white-space: nowrap;
                }

                .vendor-order-tab:hover {
                    text-decoration: none;
                    color: #007bff;
                }

                .vendor-order-tab.active {
                    color: #222;
                    font-weight: 700;
                }

                .vendor-order-tab.active::after {
                    content: "";
                    position: absolute;
                    width: 60px;
                    height: 3px;
                    background: #1687f5;
                    left: 50%;
                    bottom: 0;
                    transform: translateX(-50%);
                    border-radius: 10px;
                }

                .vendor-order-card {
                    background: #fff;
                    border-radius: 8px;
                    margin-bottom: 12px;
                    padding: 18px;
                    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.03);
                }

                .vendor-order-top {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    border-bottom: 1px solid #f1f1f1;
                    padding-bottom: 10px;
                    margin-bottom: 15px;
                }

                .vendor-order-body {
                    display: flex;
                    gap: 18px;
                    align-items: center;
                }

                .vendor-order-image {
                    width: 110px;
                    min-width: 110px;
                    height: 110px;
                    background: #fafafa;
                    border-radius: 6px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    overflow: hidden;
                }

                .vendor-order-image img {
                    width: 100%;
                    height: 100%;
                    object-fit: contain;
                }

                .vendor-order-info {
                    flex: 1;
                }

                .vendor-order-info h5 {
                    color: #111;
                    font-size: 17px;
                    font-weight: 600;
                }

                .vendor-order-prices {
                    color: #d71920;
                    font-size: 15px;
                    line-height: 1.5;
                }

                .vendor-order-action {
                    min-width: 230px;
                    text-align: right;
                }

                .actual-payment {
                    color: #111;
                    font-size: 15px;
                }

                .actual-payment strong {
                    color: #d71920;
                    font-size: 20px;
                }

                @media (max-width: 768px) {
                    .vendor-order-tabs {
                        grid-template-columns: repeat(4, minmax(150px, 1fr));
                    }

                    .vendor-order-body {
                        align-items: flex-start;
                        flex-wrap: wrap;
                    }

                    .vendor-order-action {
                        width: 100%;
                        min-width: 100%;
                        text-align: left;
                        margin-top: 10px;
                    }

                    .vendor-order-image {
                        width: 85px;
                        min-width: 85px;
                        height: 85px;
                    }

                    .vendor-order-info h5 {
                        font-size: 15px;
                    }
                }
            </style>
        @endpush

        @push('scripts')
            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
            <script>
                $(document).on('click', '.go-to-shipment-btn', function() {

                    let button = $(this);
                    let url = button.data('url');

                    let originalText = button.html();

                    Swal.fire({
                        title: 'Process Shipment?',
                        text: 'The shipment amount will be held from your wallet balance.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, proceed'
                    }).then((result) => {

                        if (!result.isConfirmed) {
                            return;
                        }

                        button.prop('disabled', true)
                            .html('<i class="fa fa-spinner fa-spin"></i> Processing...');

                        $.ajax({
                            url: url,
                            type: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },

                            success: function(response) {

                                if (response.success) {

                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Shipment Processed',
                                        text: response.message,
                                        timer: 2500,
                                        showConfirmButton: false
                                    });

                                    setTimeout(function() {
                                        location.reload();
                                    }, 1200);

                                } else {

                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Failed',
                                        text: response.message
                                    });

                                    button.prop('disabled', false)
                                        .html(originalText);
                                }
                            },

                            error: function(xhr) {

                                let message = 'Shipment processing failed.';
                                let fundWalletUrl = null;

                                if (xhr.responseJSON) {

                                    if (xhr.responseJSON.message) {
                                        message = xhr.responseJSON.message;
                                    }

                                    if (xhr.responseJSON.fund_wallet_url) {
                                        fundWalletUrl = xhr.responseJSON.fund_wallet_url;
                                    }
                                }

                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Insufficient Wallet Balance',
                                    text: message,
                                    showCancelButton: true,
                                    confirmButtonText: fundWalletUrl ? 'Fund Wallet' : 'Okay',
                                    cancelButtonText: 'Close',
                                    confirmButtonColor: '#3085d6',
                                    cancelButtonColor: '#d33',
                                }).then((result) => {

                                    if (result.isConfirmed && fundWalletUrl) {
                                        window.location.href = fundWalletUrl;
                                    }

                                });

                                button.prop('disabled', false)
                                    .html(originalText);
                            }
                        });

                    });

                });
            </script>
        @endpush
    @endsection
