@extends('layouts.admin')

@section('title', 'Users Management')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Users Management</h1>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="fa fa-plus"></i> Add New User
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Filters Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.users.index') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="search">Search</label>
                                <input type="text" class="form-control" id="search" name="search"
                                    value="{{ request('search') }}" placeholder="Search by name, email, contact...">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="type">User Type</label>
                                <select class="form-control" id="type" name="type">
                                    <option value="">All Types</option>
                                    <option value="vendor" {{ request('type') == 'vendor' ? 'selected' : '' }}>Vendors
                                    </option>
                                    <option value="customer" {{ request('type') == 'customer' ? 'selected' : '' }}>Customers
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="verified">Verification Status</label>
                                <select class="form-control" id="verified" name="verified">
                                    <option value="">All Status</option>
                                    <option value="verified" {{ request('verified') == 'verified' ? 'selected' : '' }}>
                                        Verified</option>
                                    <option value="unverified" {{ request('verified') == 'unverified' ? 'selected' : '' }}>
                                        Unverified</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary btn-block">
                                    <i class="fa fa-filter"></i> Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Users Table Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold text-primary">All Users</h6>
                <div class="dropdown">
                    <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="bulkActionsDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-cog"></i> Bulk Actions
                    </button>
                    <div class="dropdown-menu" aria-labelledby="bulkActionsDropdown">
                        <a class="dropdown-item bulk-action-btn" href="#" data-action="verify">
                            <i class="fa fa-check text-success"></i> Verify Selected
                        </a>
                        <a class="dropdown-item bulk-action-btn" href="#" data-action="unverify">
                            <i class="fa fa-times text-warning"></i> Unverify Selected
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item bulk-action-btn text-danger" href="#" data-action="delete">
                            <i class="fa fa-trash"></i> Delete Selected
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if ($users->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="30">
                                        <input type="checkbox" id="selectAll">
                                    </th>
                                    <th>User</th>
                                    <th>Contact Info</th>
                                    <th>Customer ID</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Registered</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($users as $user)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="user-checkbox" value="{{ $user->id }}">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mr-3"
                                                    style="width: 40px; height: 40px;">
                                                    <span class="text-white font-weight-bold">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <strong>{{ $user->name }}</strong>
                                                    @if ($user->nickname)
                                                        <br>
                                                        <small class="text-muted">@ {{ $user->nickname }}</small>
                                                    @endif
                                                    <br>
                                                    <small class="text-muted">{{ $user->email }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($user->contact)
                                                @php
                                                    // Very loose: treat as phone if it contains only +, digits, spaces, (), or -
                                                    $isPhone = preg_match('/^([0-9\s\-\+\(\)]*)$/', $user->contact);
                                                @endphp

                                                @if ($isPhone)
                                                    <i class="fa fa-phone text-muted mr-1"></i> {{ $user->contact }}
                                                @else
                                                    <i class="fa fa-envelope text-muted mr-1"></i> {{ $user->contact }}
                                                @endif
                                            @else
                                                <span class="text-muted">No contact</span>
                                            @endif
                                        </td>
                                        <td>
                                            <code>{{ $user->customer_id }}</code>
                                        </td>
                                        <td>
                                            {!! $user->type_badge !!}
                                        </td>
                                        <td>
                                            {!! $user->status_badge !!}
                                        </td>
                                        <td>
                                            {{ $user->created_at->format('M d, Y') }}
                                            <br>
                                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm">
                                                <a href="" class="btn btn-info" title="View Details">
                                                    <i class="fa fa-eye"></i>
                                                </a>
                                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                                    class="btn btn-primary" title="Edit">
                                                    <i class="fa fa-edit"></i>
                                                </a>
                                                <button type="button" class="btn btn-warning toggle-verification-btn"
                                                    data-user-id="{{ $user->id }}"
                                                    data-is-verified="{{ $user->email_verified_at ? '1' : '0' }}"
                                                    title="{{ $user->email_verified_at ? 'Unverify' : 'Verify' }}">
                                                    <i
                                                        class="fa {{ $user->email_verified_at ? 'fa-times' : 'fa-check' }}"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger delete-user-btn"
                                                    data-user-id="{{ $user->id }}"
                                                    data-user-name="{{ $user->name }}" title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div class="text-muted">
                            Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }}
                            entries
                        </div>
                        {{ $users->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fa fa-users fa-3x text-muted mb-3"></i>
                        <h4>No Users Found</h4>
                        <p class="text-muted">Get started by creating your first user.</p>
                        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                            <i class="fa fa-plus"></i> Create User
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Select all checkbox
            $('#selectAll').on('change', function() {
                $('.user-checkbox').prop('checked', this.checked);
            });

            // Bulk actions
            $('.bulk-action-btn').on('click', function(e) {
                e.preventDefault();
                const action = $(this).data('action');
                const selectedUsers = $('.user-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                if (selectedUsers.length === 0) {
                    Swal.fire('Error', 'Please select at least one user.', 'error');
                    return;
                }

                if (action === 'delete') {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: `You are about to delete ${selectedUsers.length} user(s). This action cannot be undone.`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, delete them!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            performBulkAction(action, selectedUsers);
                        }
                    });
                } else {
                    performBulkAction(action, selectedUsers);
                }
            });

            function performBulkAction(action, userIds) {
                $.ajax({
                    url: '{{ route('admin.users.bulk-actions') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        action: action,
                        ids: userIds
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success').then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function(xhr) {
                        const error = xhr.responseJSON ? xhr.responseJSON.message :
                            'Something went wrong.';
                        Swal.fire('Error', error, 'error');
                    }
                });
            }

            // Toggle verification
            $('.toggle-verification-btn').on('click', function() {
                const userId = $(this).data('user-id');
                const button = $(this);

                $.ajax({
                    url: `{{ url('admin/users') }}/${userId}/toggle-verification`,
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire('Success', response.message, 'success').then(() => {
                                window.location.reload();
                            });
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Failed to update verification status.', 'error');
                    }
                });
            });

            // Delete user
            $('.delete-user-btn').on('click', function() {
                const userId = $(this).data('user-id');
                const userName = $(this).data('user-name');

                Swal.fire({
                    title: 'Are you sure?',
                    html: `You are about to delete <strong>${userName}</strong>. This action cannot be undone.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `{{ url('admin/users') }}/${userId}`;

                        const csrfToken = document.createElement('input');
                        csrfToken.type = 'hidden';
                        csrfToken.name = '_token';
                        csrfToken.value = '{{ csrf_token() }}';

                        const methodField = document.createElement('input');
                        methodField.type = 'hidden';
                        methodField.name = '_method';
                        methodField.value = 'DELETE';

                        form.appendChild(csrfToken);
                        form.appendChild(methodField);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
