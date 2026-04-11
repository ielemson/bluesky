@extends("layouts.admin")

@section('title', 'Order Details')

@section('content')
<div class="container-fluid">

    <!-- Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 text-gray-800">Order Details</h1>

        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="row">

        <!-- LEFT: Order + Customer -->
        <div class="col-lg-8">

            <!-- Order Info -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong>Order Information</strong>
                </div>

                <div class="card-body">
                    <div class="row">

                        <div class="col-md-6 mb-3">
                            <label>Order Number</label>
                            <p><strong>{{ $order->order_number }}</strong></p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Status</label><br>
                            <span class="badge badge-info">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Order Date</label>
                            <p>{{ \Carbon\Carbon::parse($order->order_date)->format('M d, Y') }}</p>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Scheduled</label>
                            <p>
                                {{ $order->is_scheduled ? 'Yes' : 'No' }}
                                @if($order->scheduled_for)
                                    <br><small>{{ $order->scheduled_for->format('M d, Y') }}</small>
                                @endif
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Customer Info -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong>Customer Information</strong>
                </div>

                <div class="card-body">
                    <p><strong>Name:</strong> {{ $order->customer_name }}</p>
                    <p><strong>Phone:</strong> {{ $order->customer_phone }}</p>
                    <p><strong>Address:</strong> {{ $order->customer_address }}</p>
                </div>
            </div>

            <!-- Order Items -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong>Order Items</strong>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Qty</th>
                                    <th>Total</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($order->items as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $item->name }}</strong>
                                        </td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td>{{ $item->quantity }}</td>
                                        <td>
                                            <strong>${{ number_format($item->total, 2) }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- RIGHT: Summary -->
        <div class="col-lg-4">

            <!-- Vendor -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong>Vendor</strong>
                </div>

                <div class="card-body">
                    <p>
                        <span class="badge badge-info">
                            {{ $order->vendor->store_name ?? 'N/A' }}
                        </span>
                    </p>
                </div>
            </div>

            <!-- Payment -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong>Payment</strong>
                </div>

                <div class="card-body">
                    <p>
                        <strong>Status:</strong>
                        <span class="badge badge-success">
                            {{ ucfirst($order->payment_status ?? 'pending') }}
                        </span>
                    </p>

                    <p>
                        <strong>Method:</strong>
                        {{ ucfirst($order->payment_method ?? 'N/A') }}
                    </p>
                </div>
            </div>

            <!-- Summary -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong>Order Summary</strong>
                </div>

                <div class="card-body">

                    <p>
                        <strong>Total Items:</strong>
                        {{ $order->items->count() }}
                    </p>

                    <hr>

                    <h4>
                        Total: ${{ number_format($order->total_amount, 2) }}
                    </h4>

                </div>
            </div>

        </div>

    </div>

</div>
@endsection