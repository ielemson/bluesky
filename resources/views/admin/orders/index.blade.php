@extends("layouts.admin")

@section('title', 'Orders Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Orders Management</h1>
        <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Create Admin Order
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.orders.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Search</label>
                            <input
                                type="text"
                                name="search"
                                class="form-control"
                                value="{{ request('search') }}"
                                placeholder="Order No, customer name, email, phone...">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Vendor Shop</label>
                            <select name="vendor_id" class="form-control">
                                <option value="">All Shops</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}"
                                        {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                        {{ $vendor->store_name ?? $vendor->business_name ?? 'Vendor Shop' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Order Status</label>
                            <select name="order_status" class="form-control">
                                <option value="">All Status</option>
                                <option value="pending" {{ request('order_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ request('order_status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="shipped" {{ request('order_status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                                <option value="delivered" {{ request('order_status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                                <option value="completed" {{ request('order_status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ request('order_status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                <option value="refunded" {{ request('order_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Payment Status</label>
                            <select name="payment_status" class="form-control">
                                <option value="">All Payments</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                                <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group" style="margin-top: 32px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                                <i class="fa fa-sync"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Admin Orders</h6>
        </div>

        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="ordersTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Order No.</th>
                                <th>Customer</th>
                                <th>Vendor Shop</th>
                                <th>Items</th>
                                <th>Amount</th>
                                <th>Payment</th>
                                <th>Order Status</th>
                                <th>Created By</th>
                                <th>Ordered</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($orders as $order)
                                <tr data-order-id="{{ $order->id }}">
                                    <!-- Order Number -->
                                    <td>
                                        <strong>{{ $order->order_number }}</strong>
                                        @if($order->order_source)
                                            <br>
                                            <small class="text-muted text-uppercase">
                                                Source: {{ $order->order_source }}
                                            </small>
                                        @endif
                                    </td>

                                    <!-- Customer -->
                                    <td>
                                        <strong>{{ $order->customer_name }}</strong>
                                        <br>
                                        <small class="text-muted">{{ $order->customer_email ?? 'No email' }}</small>
                                        <br>
                                        <small class="text-muted">{{ $order->customer_phone }}</small>
                                    </td>

                                    <!-- Vendor -->
                                    <td>
                                        <span class="badge badge-info">
                                            {{ $order->vendor->store_name ?? $order->vendor->business_name ?? 'N/A' }}
                                        </span>
                                    </td>

                                    <!-- Items -->
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $order->items->count() }} item{{ $order->items->count() > 1 ? 's' : '' }}
                                        </span>
                                    </td>

                                    <!-- Amount -->
                                    <td>
                                        <strong>${{ number_format($order->total_amount, 2) }}</strong>
                                        <br>
                                        <small class="text-muted">
                                            Subtotal: ${{ number_format($order->subtotal, 2) }}
                                        </small>
                                    </td>

                                    <!-- Payment Status -->
                                    <td>
                                        @php
                                            $paymentClass = match($order->payment_status) {
                                                'paid' => 'success',
                                                'failed' => 'danger',
                                                'refunded' => 'warning',
                                                default => 'secondary',
                                            };
                                        @endphp

                                        <span class="badge badge-{{ $paymentClass }}">
                                            {{ ucfirst($order->payment_status) }}
                                        </span>

                                        @if($order->payment_method)
                                            <br>
                                            <small class="text-muted">{{ ucfirst($order->payment_method) }}</small>
                                        @endif
                                    </td>

                                    <!-- Order Status -->
                                    <td>
                                        @php
                                            $statusClass = match($order->order_status) {
                                                'pending' => 'secondary',
                                                'processing' => 'info',
                                                'shipped' => 'primary',
                                                'delivered' => 'warning',
                                                'completed' => 'success',
                                                'cancelled' => 'danger',
                                                'refunded' => 'dark',
                                                default => 'secondary',
                                            };
                                        @endphp

                                        <span class="badge badge-{{ $statusClass }}">
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>

                                    <!-- Created By -->
                                    <td>
                                        {{ $order->creator->name ?? 'System' }}
                                    </td>

                                    <!-- Ordered Date -->
                                    <td>
                                        <small>
                                            {{ optional($order->ordered_at)->format('M d, Y h:i A') ?? $order->created_at->format('M d, Y h:i A') }}
                                        </small>
                                    </td>

                                    <!-- Actions -->
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.orders.show', $order->id) }}"
                                               class="btn btn-info"
                                               title="View Order">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} entries
                    </div>
                    {{ $orders->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                    <h4>No Orders Found</h4>
                    <p class="text-muted">No admin-created orders match the current filter.</p>
                    <a href="{{ route('admin.orders.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Create Admin Order
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection