@extends('layouts.admin')

@section('title', 'User Details - ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Details</h1>
        <div>
            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary mr-2">
                <i class="fa fa-edit"></i> Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Users
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column - User Information -->
        <div class="col-lg-4">
            <!-- User Profile Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Profile</h6>
                </div>
                <div class="card-body text-center">
                    <!-- User Avatar -->
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" 
                         style="width: 100px; height: 100px;">
                        <span class="text-white font-weight-bold display-4">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>
                    
                    <!-- User Name -->
                    <h4 class="font-weight-bold text-gray-800">{{ $user->name }}</h4>
                    
                    <!-- Nickname -->
                    @if($user->nickname)
                        <p class="text-muted mb-2">
                            <i class="fa fa-at"></i> @ {{ $user->nickname }}
                        </p>
                    @endif
                    
                    <!-- User Type & Status -->
                    <div class="d-flex justify-content-center gap-2 mb-3">
                        {!! $user->type_badge !!}
                        {!! $user->status_badge !!}
                    </div>

                    <!-- Customer ID -->
                    <div class="mb-3">
                        <span class="badge badge-light border">
                            <i class="fa fa-id-card text-muted"></i>
                            {{ $user->customer_id }}
                        </span>
                    </div>

                    <!-- Quick Stats -->
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-right">
                                <div class="h5 mb-0 text-primary">{{ $user->orders->count() }}</div>
                                <small class="text-muted">Orders</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="h5 mb-0 text-info">
                                @if($user->is_vendor && $user->vendor)
                                    Active
                                @else
                                    -
                                @endif
                            </div>
                            <small class="text-muted">Vendor Status</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Contact Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong><i class="fa fa-envelope text-primary mr-2"></i> Email Address</strong>
                        <p class="mb-1">{{ $user->email }}</p>
                        <small class="text-muted">
                            @if($user->email_verified_at)
                                <i class="fa fa-check-circle text-success"></i> Verified on {{ $user->email_verified_at->format('M d, Y') }}
                            @else
                                <i class="fa fa-exclamation-triangle text-warning"></i> Not verified
                            @endif
                        </small>
                    </div>

                    <div class="mb-3">
                        <strong><i class="fa fa-phone text-primary mr-2"></i> Contact Number</strong>
                        <p class="mb-0">
                            @if($user->contact)
                                {{ $user->contact }}
                            @else
                                <span class="text-muted">Not provided</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Account Information Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Account Information</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Member Since</strong>
                        <p class="mb-1">{{ $user->created_at->format('F d, Y') }}</p>
                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                    </div>

                    <div class="mb-3">
                        <strong>Last Updated</strong>
                        <p class="mb-1">{{ $user->updated_at->format('F d, Y') }}</p>
                        <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                    </div>

                    <div class="mb-3">
                        <strong>User Type</strong>
                        <p class="mb-0">
                            @if($user->is_vendor)
                                <span class="badge badge-info">Vendor</span>
                                @if($user->vendor)
                                    <small class="text-muted d-block mt-1">Has vendor profile</small>
                                @else
                                    <small class="text-warning d-block mt-1">No vendor profile created</small>
                                @endif
                            @else
                                <span class="badge badge-primary">Customer</span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column - Vendor & Order Information -->
        <div class="col-lg-8">
            <!-- Vendor Information Card -->
            @if($user->is_vendor)
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Vendor Information</h6>
                    @if($user->vendor)
                        <span class="badge badge-success">Active Vendor</span>
                    @else
                        <span class="badge badge-warning">No Vendor Profile</span>
                    @endif
                </div>
                <div class="card-body">
                    @if($user->vendor)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Store Name</strong>
                                    <p class="mb-1">{{ $user->vendor->store_name }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Business Type</strong>
                                    <p class="mb-1">{{ $user->vendor->main_business }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Contact Person</strong>
                                    <p class="mb-1">{{ $user->vendor->contact_person }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <strong>Vendor Status</strong>
                                    <p class="mb-1">
                                        <span class="badge badge-{{ $user->vendor->status == 'approved' ? 'success' : ($user->vendor->status == 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($user->vendor->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div class="mb-3">
                                    <strong>ID Number</strong>
                                    <p class="mb-1">{{ $user->vendor->id_number }}</p>
                                </div>
                                <div class="mb-3">
                                    <strong>Approval Date</strong>
                                    <p class="mb-1">
                                        @if($user->vendor->approved_at)
                                            {{ $user->vendor->approved_at->format('M d, Y') }}
                                        @else
                                            <span class="text-muted">Not approved</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Vendor Address -->
                        <div class="mt-3 p-3 bg-light rounded">
                            <strong>Store Address</strong>
                            <p class="mb-0 mt-1">{{ $user->vendor->address }}</p>
                        </div>

                        <!-- Vendor Actions -->
                        <div class="mt-3 text-right">
                            <a href="{{ route('admin.vendors.show', $user->vendor->id) }}" class="btn btn-info btn-sm">
                                <i class="fa fa-external-link-alt"></i> View Vendor Details
                            </a>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fa fa-store fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Vendor Profile</h5>
                            <p class="text-muted">This user is marked as a vendor but hasn't created a vendor profile yet.</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Recent Orders Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Recent Orders</h6>
                    <span class="badge badge-primary">{{ $user->orders->count() }} total orders</span>
                </div>
                <div class="card-body">
                    @if($user->orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Order #</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($user->orders->take(5) as $order)
                                    <tr>
                                        <td>
                                            <strong>{{ $order->order_number }}</strong>
                                        </td>
                                        <td>{{ $order->created_at->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge badge-{{ $order->order_status == 'delivered' ? 'success' : ($order->order_status == 'pending' ? 'warning' : 'info') }}">
                                                {{ ucfirst($order->order_status) }}
                                            </span>
                                        </td>
                                        <td>${{ number_format($order->total_amount, 2) }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-info btn-sm">
                                                <i class="fa fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        @if($user->orders->count() > 5)
                            <div class="text-center mt-3">
                                <a href="#" class="btn btn-outline-primary btn-sm">
                                    View All Orders ({{ $user->orders->count() }})
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4">
                            <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">No Orders Yet</h5>
                            <p class="text-muted">This user hasn't placed any orders yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- User Activity Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">User Activity Summary</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="h2 text-primary mb-0">{{ $user->orders->count() }}</div>
                                <div class="text-muted">Total Orders</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="h2 text-success mb-0">
                                    {{ $user->orders->where('order_status', 'delivered')->count() }}
                                </div>
                                <div class="text-muted">Completed</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="h2 text-warning mb-0">
                                    {{ $user->orders->whereIn('order_status', ['pending', 'processing'])->count() }}
                                </div>
                                <div class="text-muted">In Progress</div>
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <div class="border rounded p-3">
                                <div class="h2 text-info mb-0">
                                    @if($user->is_vendor && $user->vendor)
                                        Active
                                    @else
                                        -
                                    @endif
                                </div>
                                <div class="text-muted">Vendor Status</div>
                            </div>
                        </div>
                    </div>

                    <!-- Activity Timeline -->
                    <div class="mt-4">
                        <h6 class="text-primary mb-3">Recent Activity</h6>
                        <div class="timeline">
                            @if($user->orders->count() > 0)
                                @foreach($user->orders->take(3) as $order)
                                <div class="timeline-item mb-3">
                                    <div class="timeline-marker bg-primary"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Order Placed</h6>
                                        <p class="mb-1">Order #{{ $order->order_number }} - ${{ number_format($order->total_amount, 2) }}</p>
                                        <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @endforeach
                            @endif
                            
                            @if($user->is_vendor && $user->vendor)
                                <div class="timeline-item mb-3">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Vendor Profile {{ $user->vendor->status == 'approved' ? 'Approved' : 'Created' }}</h6>
                                        <p class="mb-1">{{ $user->vendor->store_name }}</p>
                                        <small class="text-muted">
                                            @if($user->vendor->approved_at)
                                                {{ $user->vendor->approved_at->diffForHumans() }}
                                            @else
                                                {{ $user->vendor->created_at->diffForHumans() }}
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            @endif

                            <div class="timeline-item">
                                <div class="timeline-marker bg-info"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Account Created</h6>
                                    <p class="mb-1">User registered on the platform</p>
                                    <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Card -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Quick Actions</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary btn-block">
                                <i class="fa fa-edit"></i> Edit User
                            </a>
                        </div>
                        <div class="col-md-6 mb-3">
                            @if($user->is_vendor && $user->vendor)
                                <a href="{{ route('admin.vendors.show', $user->vendor->id) }}" class="btn btn-info btn-block">
                                    <i class="fa fa-store"></i> View Vendor
                                </a>
                            @elseif($user->is_vendor)
                                <button class="btn btn-warning btn-block" disabled>
                                    <i class="fa fa-store"></i> No Vendor Profile
                                </button>
                            @else
                                <button class="btn btn-secondary btn-block" disabled>
                                    <i class="fa fa-store"></i> Not a Vendor
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <form action="{{ route('admin.users.toggle-verification', $user->id) }}" method="POST" class="d-inline w-100">
                                @csrf
                                <button type="submit" class="btn btn-{{ $user->email_verified_at ? 'warning' : 'success' }} btn-block">
                                    <i class="fa {{ $user->email_verified_at ? 'fa-times' : 'fa-check' }}"></i>
                                    {{ $user->email_verified_at ? 'Unverify Email' : 'Verify Email' }}
                                </button>
                            </form>
                        </div>
                        <div class="col-md-6 mb-3">
                            <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteModal">
                                <i class="fa fa-trash"></i> Delete User
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete the user <strong>"{{ $user->name }}"</strong>?</p>
                
                @if($user->orders->count() > 0)
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        This user has {{ $user->orders->count() }} order(s). Deleting this user will also remove all associated orders.
                    </div>
                @endif

                @if($user->vendor)
                    <div class="alert alert-warning">
                        <i class="fa fa-exclamation-triangle"></i>
                        This user has a vendor profile. Please delete the vendor profile first.
                    </div>
                @endif

                <p class="mb-0"><strong>This action cannot be undone.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                @if(!$user->vendor)
                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete User</button>
                    </form>
                @else
                    <button type="button" class="btn btn-danger" disabled>Delete Vendor Profile First</button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}
.timeline-item {
    position: relative;
    margin-bottom: 20px;
}
.timeline-marker {
    position: absolute;
    left: -30px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
}
.timeline-content {
    background: #f8f9fc;
    padding: 15px;
    border-radius: 5px;
    border-left: 3px solid #4e73df;
}
.bg-light-blue {
    background-color: #e3f2fd;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Add any additional JavaScript functionality here if needed
});
</script>
@endpush