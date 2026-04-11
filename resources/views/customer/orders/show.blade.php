@extends('layouts.customer')

@section('content')
@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => auth()->user()->nickname ?? auth()->user()->name,
        'header_2' => 'Order Details'
    ])
@endsection

<section class="content">
    <div class="row mb-4">
        <div class="col-md-4 col-12">
            <div class="box box-body">
                <h6 class="mb-30">
                    <span class="text-uppercase">Order Number</span>
                    <span class="float-right">
                        <a class="btn btn-xs btn-primary" href="{{ route('vendor.orders.index') }}">Back</a>
                    </span>
                </h6>

                <p class="font-size-22 mb-0">
                    {{ $order->order_number ?? ('#' . $order->id) }}
                </p>

                <div class="progress progress-xxs mt-10 mb-0">
                    <div class="progress-bar bg-primary" role="progressbar" style="width: 100%; height: 4px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="box box-body">
                <h6 class="mb-30">
                    <span class="text-uppercase">Order Status</span>
                </h6>

                @php
                    $statusClass = match($order->order_status) {
                        'completed' => 'success',
                        'processing' => 'info',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        'scheduled' => 'primary',
                        default => 'secondary',
                    };
                @endphp

                <p class="font-size-22 mb-0">
                    <span class="badge badge-{{ $statusClass }}">
                        {{ ucfirst($order->order_status ?? 'pending') }}
                    </span>
                </p>

                <div class="progress progress-xxs mt-10 mb-0">
                    <div class="progress-bar bg-{{ $statusClass }}" role="progressbar" style="width: 100%; height: 4px;"></div>
                </div>
            </div>
        </div>

        <div class="col-md-4 col-12">
            <div class="box box-body">
                <h6 class="mb-30">
                    <span class="text-uppercase">Order Date</span>
                </h6>

                <p class="font-size-22 mb-0">
                    {{ optional($order->created_at)->format('d M, Y') }}
                </p>
                <small class="text-muted">
                    {{ optional($order->created_at)->format('h:i A') }}
                </small>

                <div class="progress progress-xxs mt-10 mb-0">
                    <div class="progress-bar bg-dark" role="progressbar" style="width: 100%; height: 4px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Customer / Delivery Info --}}
        <div class="col-lg-4 col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Customer Information</h4>
                </div>
                <div class="box-body">
                    <p class="mb-2"><strong>Name:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-2"><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                    <p class="mb-2"><strong>Address:</strong> {{ $order->customer_address }}</p>
                    @if(!empty($order->scheduled_for))
                        <p class="mb-2">
                            <strong>Scheduled For:</strong>
                            {{ \Carbon\Carbon::parse($order->scheduled_for)->format('d M, Y h:i A') }}
                        </p>
                    @endif
                    @if(!empty($order->released_at))
                        <p class="mb-0">
                            <strong>Released At:</strong>
                            {{ \Carbon\Carbon::parse($order->released_at)->format('d M, Y h:i A') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Vendor Info --}}
        <div class="col-lg-4 col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Vendor Information</h4>
                </div>
                <div class="box-body">
                    <p class="mb-2"><strong>Store:</strong> {{ $order->vendor->store_name ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Contact Person:</strong> {{ $order->vendor->contact_person ?? 'N/A' }}</p>
                    <p class="mb-2"><strong>Business:</strong> {{ $order->vendor->main_business ?? 'N/A' }}</p>
                    <p class="mb-0"><strong>Address:</strong> {{ $order->vendor->address ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

        {{-- Summary --}}
        <div class="col-lg-4 col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Payment Summary</h4>
                </div>
                <div class="box-body">
                    <p class="d-flex justify-content-between mb-2">
                        <span>Subtotal</span>
                        <strong>${{ number_format($subtotal, 2) }}</strong>
                    </p>
                    <p class="d-flex justify-content-between mb-2">
                        <span>Shipping</span>
                        <strong>${{ number_format($shipping, 2) }}</strong>
                    </p>
                    <p class="d-flex justify-content-between mb-2">
                        <span>Tax</span>
                        <strong>${{ number_format($tax, 2) }}</strong>
                    </p>
                    <p class="d-flex justify-content-between mb-2">
                        <span>Discount</span>
                        <strong>${{ number_format($discount, 2) }}</strong>
                    </p>
                    <hr>
                    <p class="d-flex justify-content-between mb-0">
                        <span><strong>Total</strong></span>
                        <strong class="text-primary">${{ number_format($total, 2) }}</strong>
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-header with-border">
                    <h4 class="box-title">Order Items</h4>
                </div>
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover product-order">
                            <thead>
                                <tr class="bg-secondary">
                                    <th>Photo</th>
                                    <th>Product</th>
                                    <th>SKU</th>
                                    <th>Category</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($order->items as $item)
                                    @php
                                        $product = $item->product;
                                        $image = optional(optional($product)->images)->first();
                                        $lineTotal = (float) $item->price * (int) $item->quantity;
                                    @endphp
                                    <tr>
                                        <td>
                                            @if($image)
                                                <img src="{{ asset($image->image_path) }}"
                                                     alt="{{ $product->name ?? 'Product image' }}"
                                                     width="70"
                                                     class="rounded">
                                            @else
                                                <img src="https://via.placeholder.com/70x70?text=No+Image"
                                                     alt="No image"
                                                     width="70"
                                                     class="rounded">
                                            @endif
                                        </td>
                                        <td>
                                            <strong>{{ $product->name ?? 'N/A' }}</strong>
                                        </td>
                                        <td>{{ $product->sku ?? 'N/A' }}</td>
                                        <td>{{ $product->category->name ?? 'N/A' }}</td>
                                        <td>${{ number_format((float) $item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td><strong>${{ number_format($lineTotal, 2) }}</strong></td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            <div class="alert alert-info mb-0">
                                                <i class="mdi mdi-information-outline"></i>
                                                No items found for this order.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection