@extends('layouts.admin')

@section('title', 'Edit User - ' . $user->name)

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit User</h1>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Back to Users
        </a>
    </div>

    <!-- Edit Form -->
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" id="userForm">
        @csrf
        @method('PUT')
        
        <div class="row">
            <!-- Left Column - Basic Information -->
            <div class="col-lg-8">
                <!-- Basic Information Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Basic Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Full Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" 
                                           placeholder="Enter full name" required>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nickname">Nickname</label>
                                    <input type="text" class="form-control @error('nickname') is-invalid @enderror" 
                                           id="nickname" name="nickname" value="{{ old('nickname', $user->nickname) }}" 
                                           placeholder="Enter nickname (optional)">
                                    @error('nickname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email Address *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" 
                                           placeholder="Enter email address" required>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact">Contact Number</label>
                                    <input type="text" class="form-control @error('contact') is-invalid @enderror" 
                                           id="contact" name="contact" value="{{ old('contact', $user->contact) }}" 
                                           placeholder="Enter contact number">
                                    @error('contact')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                           id="password" name="password" 
                                           placeholder="Leave empty to keep current password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                    <small class="form-text text-muted">
                                        Leave empty to keep current password.
                                    </small>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" 
                                           id="password_confirmation" name="password_confirmation" 
                                           placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Settings -->
            <div class="col-lg-4">
                <!-- Settings Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Settings</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="customer_id">Customer ID</label>
                            <input type="text" class="form-control @error('customer_id') is-invalid @enderror" 
                                   id="customer_id" name="customer_id" value="{{ old('customer_id', $user->customer_id) }}" 
                                   placeholder="Customer ID">
                            @error('customer_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" 
                                       id="is_vendor" name="is_vendor" value="1"
                                       {{ old('is_vendor', $user->is_vendor) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_vendor">
                                    This user is a vendor
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                Vendors can manage products and orders.
                            </small>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" 
                                       id="email_verified" name="email_verified" value="1"
                                       {{ old('email_verified', $user->email_verified_at) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="email_verified">
                                    Mark email as verified
                                </label>
                            </div>
                            <small class="form-text text-muted">
                                User won't need to verify their email address.
                            </small>
                        </div>
                    </div>
                </div>

                <!-- User Info Card -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">User Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="small">
                            <p><strong>Created:</strong> {{ $user->created_at->format('M d, Y') }}</p>
                            <p><strong>Last Updated:</strong> {{ $user->updated_at->format('M d, Y') }}</p>
                            <p><strong>Email Status:</strong> {!! $user->status_badge !!}</p>
                            <p><strong>User Type:</strong> {!! $user->type_badge !!}</p>
                            @if($user->email_verified_at)
                                <p><strong>Verified:</strong> {{ $user->email_verified_at->format('M d, Y') }}</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Actions Card -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-block mb-2">
                            <i class="fa fa-save"></i> Update User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary btn-block mb-2">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                        
                        <hr>
                        
                        <div class="text-center">
                            <a href="" class="btn btn-info btn-sm mr-2">
                                <i class="fa fa-eye"></i> View
                            </a>
                            <button type="button" class="btn btn-danger btn-sm" id="deleteUserBtn">
                                <i class="fa fa-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
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
                Are you sure you want to delete the user "<strong>{{ $user->name }}</strong>"? 
                This action cannot be undone.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete User</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Delete user
    $('#deleteUserBtn').on('click', function() {
        $('#deleteModal').modal('show');
    });
});
</script>
@endpush