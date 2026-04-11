@extends('layouts.customer')

@section('content')
@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => auth()->user()->nickname ?? auth()->user()->name,
        'header_2' => 'Orders'
    ])
@endsection

<section class="content">
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-body">
                    <form action="{{ route('vendor.orders.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4 mb-2">
                                <input type="text"
                                       name="search"
                                       class="form-control"
                                       placeholder="Search by order no, customer name, phone..."
                                       value="{{ request('search') }}">
                            </div>

                            <div class="col-md-2 mb-2">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                    <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                </select>
                            </div>

                            <div class="col-md-2 mb-2">
                                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                            </div>

                            <div class="col-md-2 mb-2">
                                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                            </div>

                            <div class="col-md-2 mb-2">
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="mdi mdi-magnify"></i> Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="mt-3 text-right">
                        <small class="text-muted">
                            Showing {{ $orders->count() }} of {{ $orders->total() }} orders
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="box">
                <div class="box-body">
                    <div class="table-responsive">
                        <table class="table table-hover no-wrap product-order">
                            <thead>
                                <tr class="bg-secondary">
                                    <th>Customer</th>
                                    <th>Order No</th>
                                    <th>Photo</th>
                                    <th>Items</th>
                                    <th>Quantity</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th width="120">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    @php
                                        $firstItem = $order->items->first();
                                        $firstProduct = optional($firstItem)->product;
                                        $firstImage = optional(optional($firstProduct)->images)->first();
                                        $totalQty = $order->items->sum('quantity');
                                    @endphp

                                    <tr>
                                        <td>
                                            <strong>{{ $order->customer_name }}</strong><br>
                                            <small class="text-muted">{{ $order->customer_phone }}</small>
                                        </td>

                                        <td>
                                            {{ $order->order_number ?? ('#' . $order->id) }}
                                        </td>

                                        <td>
                                            @if($firstImage)
                                                <img src="{{ asset($firstImage->image_path) }}"
                                                     alt="{{ $firstProduct->name ?? 'Product image' }}"
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
                                            @if($order->items->count() > 0)
                                                <strong>{{ optional($firstProduct)->name }}</strong>
                                                @if($order->items->count() > 1)
                                                    <br>
                                                    <small class="text-muted">
                                                        +{{ $order->items->count() - 1 }} more item(s)
                                                    </small>
                                                @endif
                                            @else
                                                <span class="text-muted">No items</span>
                                            @endif
                                        </td>

                                        <td>{{ $totalQty }}</td>

                                        <td>
                                            {{ optional($order->created_at)->format('d M Y') }}<br>
                                            <small class="text-muted">{{ optional($order->created_at)->format('h:i A') }}</small>
                                        </td>

                                        <td>
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

                                            <span class="badge badge-{{ $statusClass }}">
                                                {{ ucfirst($order->order_status ?? 'pending') }}
                                            </span>
                                        </td>

                                        <td>
                                            <a href="{{ route('vendor.orders.show', $order) }}"
                                               class="btn btn-sm btn-info"
                                               data-toggle="tooltip"
                                               title="View Order">
                                                <i class="mdi mdi-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">
                                            <div class="alert alert-info mb-0">
                                                <i class="mdi mdi-information-outline"></i>
                                                No orders found.
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($orders->hasPages())
                        <div class="mt-4 d-flex justify-content-center">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
@endsection