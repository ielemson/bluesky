@extends("layouts.admin")

@section('title', 'Products Management')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Products Management</h1>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fa fa-plus"></i> Add New Product
        </a>
    </div>

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Search</label>
                            <input type="text" name="search" class="form-control" 
                                   value="{{ request('search') }}" placeholder="Name, SKU, Barcode...">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Category</label>
                            <select name="category_id" class="form-control">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                        {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control">
                                <option value="">All Status</option>
                                <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Featured</label>
                            <select name="is_featured" class="form-control">
                                <option value="">All</option>
                                <option value="1" {{ request('is_featured') == '1' ? 'selected' : '' }}>Featured</option>
                                <option value="0" {{ request('is_featured') == '0' ? 'selected' : '' }}>Not Featured</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group" style="margin-top: 32px;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                <i class="fa fa-sync"></i> Reset
                            </a>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">All Products</h6>
            <div class="bulk-actions">
                <select id="bulkAction" class="form-control form-control-sm d-inline-block w-auto">
                    <option value="">Bulk Actions</option>
                    <option value="activate">Activate</option>
                    <option value="deactivate">Deactivate</option>
                    <option value="feature">Feature</option>
                    <option value="unfeature">Unfeature</option>
                    <option value="delete">Delete</option>
                </select>
                <button id="applyBulkAction" class="btn btn-sm btn-primary">Apply</button>
            </div>
        </div>
        <div class="card-body">
            @if($products->count() > 0)
                <div class="table-responsive">
                   <table class="table table-bordered" id="productsTable" width="100%" cellspacing="0">
    <thead>
        <tr>
            <th width="30"><input type="checkbox" id="selectAll"></th>
            <th>Image</th>
            <th>Product Info</th>
            <th>Category</th>
            <th>Price</th>
            <th>Stock</th>
            <th>Vendors</th>
            <th>Status</th>
            <th>Featured</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @foreach($products as $product)
        <tr data-product-id="{{ $product->id }}">

            <!-- Select -->
            <td><input type="checkbox" class="product-checkbox" value="{{ $product->id }}"></td>

            <!-- Product Image -->
            <td>
                @if($product->primaryImage)
                    <img src="{{ asset($product->primaryImage->image_path) }}"
                         alt="{{ $product->name }}"
                         class="img-thumbnail"
                         style="width: 60px; height: 60px; object-fit: cover;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center"
                         style="width: 60px; height: 60px;">
                        <i class="fa fa-image text-muted"></i>
                    </div>
                @endif
            </td>

            <!-- Product Info -->
            <td>
                <strong>{{ $product->name }}</strong>
                <br>
                <small class="text-muted">SKU: {{ $product->sku ?? '—' }}</small>

                @if($product->barcode)
                    <br><small class="text-muted">Barcode: {{ $product->barcode }}</small>
                @endif
            </td>

            <!-- Category -->
            <td>
                <span class="badge badge-info">
                    {{ $product->category->name ?? 'N/A' }}
                </span>
            </td>

            <!-- Price -->
            <td>
                <strong>${{ number_format($product->price, 2) }}</strong>

                @if($product->compare_price)
                    <br>
                    <small class="text-danger"><s>${{ number_format($product->compare_price, 2) }}</s></small>
                @endif
            </td>

            <!-- Stock -->
            <td>
                <span class="badge {{ $product->stock_quantity > 0 ? 'badge-success' : 'badge-danger' }}">
                    {{ $product->stock_quantity }} in stock
                </span>
            </td>

            <!-- Vendors Count -->
            <td>
                <span class="badge badge-primary">
              {{ $product->vendor_count }}
                    Vendor{{ $product->vendor_count > 1 ? '' : 's' }}
                </span>
            </td>

            <!-- Status -->
            <td>
                <button type="button"
                        class="btn btn-sm btn-status status-toggle 
                            {{ $product->status == 'active' ? 'btn-success' : 
                               ($product->status == 'draft' ? 'btn-secondary' : 'btn-warning') }}"
                        data-product-id="{{ $product->id }}"
                        data-current-status="{{ $product->status }}">
                    {{ ucfirst($product->status) }}
                </button>
            </td>

            <!-- Featured Toggle -->
            <td>
                <button type="button"
                        class="btn btn-sm featured-toggle 
                            {{ $product->is_featured ? 'btn-warning' : 'btn-secondary' }}"
                        data-product-id="{{ $product->id }}"
                        data-current-featured="{{ $product->is_featured }}"
                        title="{{ $product->is_featured ? 'Unfeature' : 'Feature' }}">
                    <i class="fa fa-star"></i>
                </button>
            </td>

            <!-- Created Date -->
            <td><small>{{ $product->created_at->format('M d, Y') }}</small></td>

            <!-- Actions -->
            <td>
                <div class="btn-group btn-group-sm">
                    <a href="{{ route('admin.products.edit', $product->id) }}"
                       class="btn btn-info" title="Edit">
                        <i class="fa fa-edit"></i>
                    </a>
                    <button type="button" class="btn btn-danger delete-product"
                            data-product-id="{{ $product->id }}"
                            data-product-name="{{ $product->name }}"
                            title="Delete">
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
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
                    </div>
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                    <h4>No Products Found</h4>
                    <p class="text-muted">Get started by creating your first product.</p>
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Create Product
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
$(document).ready(function() {
    // Initialize Toastify
    function showToast(message, type = 'success') {
        const background = type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8';
        
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: background,
            stopOnFocus: true,
        }).showToast();
    }

    // Toggle product status with AJAX
    $('.status-toggle').on('click', function() {
        const button = $(this);
        const productId = button.data('product-id');
        const currentStatus = button.data('current-status');
        
        // Determine new status
        let newStatus;
        if (currentStatus === 'draft') {
            newStatus = 'active';
        } else if (currentStatus === 'active') {
            newStatus = 'inactive';
        } else {
            newStatus = 'active';
        }

        // Show loading state
        const originalText = button.text();
        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

      
    });

   $(document).ready(function() {
    // Initialize Toastify
    function showToast(message, type = 'success') {
        const background = type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8';
        
        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: background,
            stopOnFocus: true,
        }).showToast();
    }

    // Toggle product status with AJAX
    $('.status-toggle').on('click', function() {
        const button = $(this);
        const productId = button.data('product-id');
        const currentStatus = button.data('current-status');
        
        // Determine new status
        let newStatus;
        if (currentStatus === 'draft') {
            newStatus = 'active';
        } else if (currentStatus === 'active') {
            newStatus = 'inactive';
        } else {
            newStatus = 'active';
        }

        // Show loading state
        const originalText = button.text();
        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        // CORRECTED: Use proper route construction
        $.ajax({
            url: `/admin/products/${productId}/status`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                status: newStatus
            },
            success: function(response) {
                // Update button appearance
                button.removeClass('btn-success btn-secondary btn-warning');
                
                if (response.new_status === 'active') {
                    button.addClass('btn-success');
                } else if (response.new_status === 'draft') {
                    button.addClass('btn-secondary');
                } else {
                    button.addClass('btn-warning');
                }
                
                // Update button text and data
                button.text(response.new_status.charAt(0).toUpperCase() + response.new_status.slice(1));
                button.data('current-status', response.new_status);
                
                // Show success message
                showToast(response.message, 'success');
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                showToast('Error updating product status', 'error');
            },
            complete: function() {
                button.prop('disabled', false);
            }
        });
    });

    // Toggle featured status with AJAX
    $('.featured-toggle').on('click', function() {
        const button = $(this);
        const productId = button.data('product-id');
        const currentFeatured = button.data('current-featured');

        // Show loading state
        button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        // CORRECTED: Use proper route construction
        $.ajax({
            url: `/admin/products/${productId}/toggle-featured`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Update button appearance
                button.removeClass('btn-warning btn-secondary');
                
                if (response.is_featured) {
                    button.addClass('btn-warning');
                    button.attr('title', 'Unfeature');
                } else {
                    button.addClass('btn-secondary');
                    button.attr('title', 'Feature');
                }
                
                // Update button data
                button.data('current-featured', response.is_featured);
                
                // Show success message
                showToast(response.message, 'success');
            },
            error: function(xhr) {
                console.error('Error:', xhr);
                showToast('Error updating featured status', 'error');
            },
            complete: function() {
                button.prop('disabled', false);
                button.html('<i class="fa fa-star"></i>');
            }
        });
    });

    // Bulk actions
    $('#selectAll').on('change', function() {
        $('.product-checkbox').prop('checked', this.checked);
    });

    $('#applyBulkAction').on('click', function() {
        const action = $('#bulkAction').val();
        const selectedIds = $('.product-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (!action) {
            showToast('Please select an action', 'error');
            return;
        }

        if (selectedIds.length === 0) {
            showToast('Please select at least one product', 'error');
            return;
        }

        Swal.fire({
            title: 'Are you sure?',
            text: `You are about to ${action} ${selectedIds.length} product(s)`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, proceed!'
        }).then((result) => {
            if (result.isConfirmed) {
                // CORRECTED: Use proper route
                $.ajax({
                    url: '/admin/products/bulk-actions',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        action: action,
                        ids: selectedIds
                    },
                    success: function(response) {
                        showToast(response.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        showToast(xhr.responseJSON?.message || 'Error performing bulk action', 'error');
                    }
                });
            }
        });
    });

    // Delete product
    $('.delete-product').on('click', function() {
        const productId = $(this).data('product-id');
        const productName = $(this).data('product-name');
        
        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete <strong>${productName}</strong>. This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                const button = $(this);
                button.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

                // CORRECTED: Use proper route
                $.ajax({
                    url: `/admin/products/${productId}`,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        showToast(response.message, 'success');
                        // Remove the row from table
                        $(`tr[data-product-id="${productId}"]`).fadeOut(300, function() {
                            $(this).remove();
                        });
                    },
                    error: function(xhr) {
                        showToast(xhr.responseJSON?.message || 'Error deleting product', 'error');
                        button.prop('disabled', false).html('<i class="fa fa-trash"></i>');
                    }
                });
            }
        });
    });
});
});
</script>
@endpush