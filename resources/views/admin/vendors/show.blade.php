@extends('layouts.admin')

@section('title', 'Vendor Profile')

@section('content')
    <div class="container-full">
        @include('admin.partials.page_header', [
            'header1' => 'Vendors',
            'header2' => 'Vendor Profile',
        ])

        <!-- Main content -->
        <section class="content">
            <div class="row">
                {{-- LEFT SIDE: Profile & quick stats --}}
                <div class="col-xl-4 col-lg-5">
                    {{-- Profile card --}}
                    <div class="card text-center">
                        <div class="card-body">
                            {{-- Avatar / Store logo --}}
                            <img src="{{ $vendor->store_logo ? asset($vendor->store_logo) : asset('images/avatar/default.png') }}"
                                class="bg-light rounded-circle avatar-lg img-thumbnail" alt="profile-image">

                            <h4 class="mb-0 mt-2">{{ $vendor->user->display_name }}</h4>
                            <p class="text-muted fs-14">
                                {{ $vendor->store_name ?? 'Vendor' }}
                            </p>

                            <a href="{{ route('admin.vendors.active') }}" class="btn btn-primary btn-sm mb-2">
                                Back to Vendors
                            </a>

                            <div class="text-start mt-3">
                                <p class="header-title text-info mb-2"><strong>About Vendor :</strong></p>

                                <p class="text-muted mb-2">
                                    <strong class="text-info">Full Name :</strong>
                                    <span class="ms-2">{{ $vendor->user->name }}</span>
                                </p>

                                <p class="text-muted mb-2">
                                    <strong class="text-info">Contact Person :</strong>
                                    <span class="ms-2">{{ $vendor->contact_person ?? 'N/A' }}</span>
                                </p>

                                <p class="text-muted mb-2">
                                    <strong class="text-info">Mobile :</strong>
                                    <span class="ms-2">{{ $vendor->user->contact ?? 'N/A' }}</span>
                                </p>

                                <p class="text-muted mb-2">
                                    <strong class="text-info">Email :</strong>
                                    <span class="ms-2">{{ $vendor->user->email }}</span>
                                </p>

                                <p class="text-muted mb-2">
                                    <strong class="text-info">Business :</strong>
                                    <span class="ms-2">{{ $vendor->main_business ?? 'N/A' }}</span>
                                </p>

                                <p class="text-muted mb-1">
                                    <strong class="text-info">Status :</strong>
                                    <span class="ms-2">
                                        <span
                                            class="badge badge-{{ $vendor->status === 'approved' ? 'success' : ($vendor->status === 'rejected' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($vendor->status) }}
                                        </span>
                                    </span>
                                </p>
                            </div>
                        </div> <!-- end card-body -->
                    </div> <!-- end card -->

                    {{-- Quick stats card --}}
                    <div class="card">
                        <div class="card-body">
                            <h4 class="header-title mb-3">Quick Stats</h4>

                            <div class="mb-2">
                                <span class="text-muted">Total Orders:</span>
                                <span class="float-end font-weight-bold">
                                    {{ $vendor->user->orders->count() }}
                                </span>
                            </div>

                            <div class="mb-2">
                                <span class="text-muted">Wallet Balance:</span>
                                <span class="float-end font-weight-bold">
                                    @if ($vendor->user->wallet)
                                        {{ number_format($vendor->user->wallet->balance, 2) }}
                                    @else
                                        0.00
                                    @endif
                                </span>
                            </div>

                            <div class="mb-2">
                                <span class="text-muted">Email Verified:</span>
                                <span class="float-end font-weight-bold">
                                    {{ ucfirst($vendor->user->verification_status) }}
                                </span>
                            </div>

                            <div class="mb-0">
                                <span class="text-muted">Applied:</span>
                                <span class="float-end font-weight-bold">
                                    {{ $vendor->created_at->format('M d, Y H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDE: Tabs for Store / Products / Orders / Wallet / Settings --}}
                <div class="col-xl-8 col-lg-7">
                    <div class="card">
                        <div class="card-body">
                            {{-- Tabs --}}
                            <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                                <li class="nav-item">
                                    <a href="#store" data-bs-toggle="tab" class="nav-link rounded-0 active">
                                        Store
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#products" data-bs-toggle="tab" class="nav-link rounded-0">
                                        Products
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#orders" data-bs-toggle="tab" class="nav-link rounded-0">
                                        Orders
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#wallet" data-bs-toggle="tab" class="nav-link rounded-0">
                                        Wallet
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="#settings" data-bs-toggle="tab" class="nav-link rounded-0">
                                        Settings
                                    </a>
                                </li>
                            </ul>

                            <div class="tab-content">
                                {{-- Store tab --}}
                                <div class="tab-pane show active" id="store">
                                    <h5 class="text-uppercase mb-3">
                                        <i class="mdi mdi-store me-1"></i> Store Information
                                    </h5>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>Store Name:</strong> {{ $vendor->store_name }}</p>
                                            <p><strong>Contact Person:</strong> {{ $vendor->contact_person }}</p>
                                            <p><strong>ID Number:</strong> {{ $vendor->id_number }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>Business Type:</strong> {{ $vendor->main_business }}</p>
                                            <p><strong>Address:</strong> {{ $vendor->address }}</p>
                                            <p><strong>Invite Code:</strong> {{ $vendor->invite_code ?? 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <h6 class="mt-4 mb-2"><i class="mdi mdi-id-card me-1"></i> ID Card Images</h6>
                                    <div class="row">
                                        <div class="col-md-6 text-center mb-3">
                                            <p class="mb-1"><strong>Front</strong></p>
                                            @if ($vendor->idcard_front)
                                                <img src="{{ asset($vendor->idcard_front) }}" alt="ID Front"
                                                    class="img-fluid rounded bg-light"
                                                    style="max-height:180px;object-fit:cover;">
                                            @else
                                                <span class="badge badge-secondary">No front image</span>
                                            @endif
                                        </div>
                                        <div class="col-md-6 text-center mb-3">
                                            <p class="mb-1"><strong>Back</strong></p>
                                            @if ($vendor->idcard_back)
                                                <img src="{{ asset($vendor->idcard_back) }}" alt="ID Back"
                                                    class="img-fluid rounded bg-light"
                                                    style="max-height:180px;object-fit:cover;">
                                            @else
                                                <span class="badge badge-secondary">No back image</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Products tab (VendorProduct + Product) --}}
                                {{-- Products tab --}}
                                <div class="tab-pane" id="products">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h5 class="text-uppercase mb-0">
                                            <i class="mdi mdi-package-variant-closed me-1"></i>
                                            Vendor Products
                                            <span class="badge badge-info">
                                                {{ $vendor->vendor_products_count ?? ($vendor->vendorProducts->count() ?? 0) }}
                                            </span>
                                        </h5>

                                        <a href="{{ route('admin.vendors.products', $vendor->id) }}"
                                            class="btn btn-sm btn-outline-primary">
                                            View All Vendor Products
                                        </a>
                                    </div>

                                    @php
                                        // show a small subset here if you want, e.g. latest 10
                                        $vendorProducts = ($vendor->vendorProducts ?? collect())->take(10);
                                    @endphp

                                    @if ($vendorProducts->count())
                                        {{-- existing table here --}}
                                    @else
                                        <p class="text-muted">No products assigned to this vendor yet.</p>
                                    @endif
                                </div>


                                {{-- Orders tab --}}
                                <div class="tab-pane" id="orders">
                                    <h5 class="text-uppercase mb-3">
                                        <i class="mdi mdi-cart me-1"></i> Recent Orders
                                    </h5>

                                    @php
                                        $orders = $vendor->user->orders->sortByDesc('created_at')->take(10);
                                    @endphp

                                    @if ($orders->count())
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-sm mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Order No</th>
                                                        <th>Total</th>
                                                        <th>Status</th>
                                                        <th>Date</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($orders as $order)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}</td>
                                                            <td>{{ $order->order_number }}</td>
                                                            <td>{{ number_format($order->total_amount, 2) }}</td>
                                                            <td>
                                                                <span class="badge badge-info">
                                                                    {{ ucfirst($order->status) }}
                                                                </span>
                                                            </td>
                                                            <td>{{ $order->created_at->format('M d, Y H:i') }}</td>
                                                            <td>
                                                                <a href="{{ route('admin.orders.show', $order->id) }}"
                                                                    class="btn btn-primary btn-xs">
                                                                    View
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @else
                                        <p class="text-muted">No orders found for this vendor.</p>
                                    @endif
                                </div>

                                {{-- Wallet tab --}}
                                <div class="tab-pane" id="wallet">
                                    <h5 class="text-uppercase mb-3">
                                        <i class="mdi mdi-wallet me-1"></i> Wallet Information
                                    </h5>

                                    @if ($vendor->user->wallet)
                                        <p>
                                            <strong>Current Balance:</strong>
                                            {{ number_format($vendor->user->wallet->balance, 2) }}
                                        </p>
                                        <p>
                                            <strong>Currency:</strong>
                                            {{ $vendor->user->wallet->currency ?? '$' }}
                                        </p>
                                        <p>
                                            <strong>Last Updated:</strong>
                                            {{ $vendor->user->wallet->updated_at->format('M d, Y H:i') }}
                                        </p>

                                        <a href="" class="btn btn-sm btn-outline-primary">
                                            View Wallet Details
                                        </a>
                                    @else
                                        <p class="text-muted">Wallet not created for this vendor.</p>
                                    @endif
                                </div>

                                {{-- Settings / Admin actions tab --}}
                                <div class="tab-pane" id="settings">
                                    <h5 class="mb-4 text-uppercase">
                                        <i class="mdi mdi-account-circle me-1"></i> Admin Actions
                                    </h5>

                                    <p><strong>Current Status:</strong> {{ ucfirst($vendor->status) }}</p>

                                    <div class="d-flex flex-wrap gap-2">
                                        {{-- Approve via normal POST (if needed) --}}
                                        <form action="{{ url("/admin/vendors/{$vendor->id}/approve") }}" method="POST"
                                            class="mr-2 d-inline-block">
                                            @csrf
                                            <button type="submit" class="btn btn-success">
                                                Approve Vendor
                                            </button>
                                        </form>

                                        {{-- Approve / Reject with SweetAlert JS helper (reuse from your list page) --}}
                                        <button type="button" class="btn btn-success"
                                            onclick="approveApplication({{ $vendor->id }}, '{{ $vendor->store_name }}')">
                                            Approve (Dialog)
                                        </button>

                                        <button type="button" class="btn btn-danger"
                                            onclick="rejectApplication({{ $vendor->id }}, '{{ $vendor->store_name }}')">
                                            Reject Vendor
                                        </button>
                                    </div>
                                </div>
                            </div> {{-- end tab-content --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
