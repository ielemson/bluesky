@extends('layouts.customer')

@section('title', 'My Profile')

@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => Auth::user()->nickname ?: 'My Profile',
        'header_2' => 'Profile',
    ])
@endsection

@section('content')
    <div class="row justify-content-center">

        <div class="col-lg-8 col-12 my-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $user->name ?? 'N/A' }}</h3>
                            <div class="text-muted mb-2">{{ $user->email ?? 'N/A' }}</div>

                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge badge-primary px-3 py-2">
                                    Nickname: {{ $user->nickname ?: 'N/A' }}
                                </span>

                                <span class="badge badge-info px-3 py-2 text-capitalize">
                                    Status: {{ $user->status ?? 'N/A' }}
                                </span>

                                <span class="badge {{ $user->email_verified_at ? 'badge-success' : 'badge-warning' }} px-3 py-2">
                                    {{ $user->email_verified_at ? 'Email Verified' : 'Email Not Verified' }}
                                </span>

                                <span class="badge {{ $user->is_vendor ? 'badge-dark' : 'badge-secondary' }} px-3 py-2">
                                    {{ $user->is_vendor ? 'Vendor Account' : 'Customer Account' }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-3 mt-md-0 text-md-right">
                            <div class="text-muted small">Customer ID</div>
                            <div class="h5 fw-bold mb-0">{{ $user->customer_id ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h4 class="mb-0 fw-bold">Personal Information</h4>
                </div>
                <div class="card-body pt-3">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small mb-1">Full Name</div>
                                <div class="fw-bold">{{ $user->name ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small mb-1">Nickname</div>
                                <div class="fw-bold">{{ $user->nickname ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small mb-1">Email Address</div>
                                <div class="fw-bold">{{ $user->email ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small mb-1">Contact</div>
                                <div class="fw-bold">{{ $user->contact ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small mb-1">Email Verification</div>
                                <div class="fw-bold">
                                    {{ $user->email_verified_at ? $user->email_verified_at->format('d M, Y h:i A') : 'Not Verified' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small mb-1">Joined On</div>
                                <div class="fw-bold">
                                    {{ $user->created_at ? $user->created_at->format('d M, Y h:i A') : 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small mb-1">Last Updated</div>
                                <div class="fw-bold">
                                    {{ $user->updated_at ? $user->updated_at->format('d M, Y h:i A') : 'N/A' }}
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mt-3">
                            <div class="border rounded-3 p-3 h-100">
                                <div class="text-muted small mb-1">Account Type</div>
                                <div class="fw-bold">
                                    {{ $user->is_vendor ? 'Customer / Vendor' : 'Customer Only' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h4 class="mb-0 fw-bold">Shop Details</h4>
                </div>
                <div class="card-body pt-3">
                    @if ($user->is_vendor && $user->vendor)
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="text-muted small mb-1">Store Name</div>
                                    <div class="fw-bold">{{ $user->vendor->store_name ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="text-muted small mb-1">Contact Person</div>
                                    <div class="fw-bold">{{ $user->vendor->contact_person ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="text-muted small mb-1">Main Business</div>
                                    <div class="fw-bold">{{ $user->vendor->main_business ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-6 mt-3">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="text-muted small mb-1">Shop Status</div>
                                    <div class="fw-bold text-capitalize">{{ $user->vendor->status ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="text-muted small mb-1">Address</div>
                                    <div class="fw-bold">{{ $user->vendor->address ?? 'N/A' }}</div>
                                </div>
                            </div>

                            <div class="col-md-12 mt-3">
                                <div class="border rounded-3 p-3 h-100">
                                    <div class="text-muted small mb-1">Invite Code</div>
                                    <div class="fw-bold">{{ $user->vendor->invite_code ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-info mb-0">
                            No shop details available for this customer account.
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4 col-12 my-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                            style="width: 90px; height: 90px; font-size: 30px; font-weight: 700;">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </div>
                    </div>

                    <h4 class="mb-1 fw-bold">{{ $user->name ?? 'N/A' }}</h4>
                    <p class="text-muted mb-3">{{ $user->email ?? 'N/A' }}</p>

                    <div class="row text-center">
                        <div class="col-6 border-right">
                            <div class="small text-muted">Customer ID</div>
                            <div class="fw-bold">{{ $user->customer_id ?? 'N/A' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="small text-muted">Status</div>
                            <div class="fw-bold text-capitalize">{{ $user->status ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">Quick Summary</h5>
                </div>
                <div class="card-body pt-3">
                    <div class="mb-3">
                        <div class="text-muted small">Contact</div>
                        <div class="fw-bold">{{ $user->contact ?? 'N/A' }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small">Nickname</div>
                        <div class="fw-bold">{{ $user->nickname ?? 'N/A' }}</div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small">Email Verified</div>
                        <div class="fw-bold">{{ $user->email_verified_at ? 'Yes' : 'No' }}</div>
                    </div>

                    <div class="mb-0">
                        <div class="text-muted small">Vendor Enabled</div>
                        <div class="fw-bold">{{ $user->is_vendor ? 'Yes' : 'No' }}</div>
                    </div>
                </div>
            </div>

            @if ($user->is_vendor && $user->vendor)
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white border-0 pb-0">
                        <h5 class="mb-0 fw-bold">Shop Snapshot</h5>
                    </div>
                    <div class="card-body pt-3">
                        <div class="mb-3">
                            <div class="text-muted small">Store Name</div>
                            <div class="fw-bold">{{ $user->vendor->store_name ?? 'N/A' }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Business</div>
                            <div class="fw-bold">{{ $user->vendor->main_business ?? 'N/A' }}</div>
                        </div>

                        <div class="mb-3">
                            <div class="text-muted small">Shop Status</div>
                            <div class="fw-bold text-capitalize">{{ $user->vendor->status ?? 'N/A' }}</div>
                        </div>

                        <div class="mb-0">
                            <div class="text-muted small">Address</div>
                            <div class="fw-bold">{{ $user->vendor->address ?? 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection