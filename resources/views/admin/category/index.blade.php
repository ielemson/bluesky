@extends('layouts.admin')

@section('content')
@section('title', 'Category Management')
<div class="container-full">
    <!-- Content Header (Page header) -->
    @include('admin.partials.page_header', ['header1' => 'Categories Management', 'header2' => 'Add New Category'])
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @include("admin.partials.category_alert")
            <!-- Applications Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">List of Categories</h6>
                </div>
                <div class="card-body">
                    @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered" id="categoriesTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Category Name</th>
                                <th>Parent Category</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Featured</th>
                                <th>Products</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" 
                                             alt="{{ $category->name }}" 
                                             class="img-thumbnail" 
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 60px; height: 60px;">
                                            <i class="fa fa-folder text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $category->name }}</strong>
                                    <br>
                                    <small class="text-muted">Slug: {{ $category->slug }}</small>
                                </td>
                                <td>
                                    @if($category->parent)
                                        <span class="badge badge-info">{{ $category->parent->name }}</span>
                                    @else
                                        <span class="badge badge-secondary">Main Category</span>
                                    @endif
                                </td>
                                <td>
                                    @if($category->description)
                                        {{ Str::limit($category->description, 50) }}
                                    @else
                                        <span class="text-muted">No description</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.categories.status', $category->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-toggle-status {{ $category->is_active ? 'btn-success' : 'btn-warning' }}"
                                                title="{{ $category->is_active ? 'Deactivate' : 'Activate' }}">
                                            {{ $category->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @if($category->is_featured)
                                        <span class="badge badge-success">Featured</span>
                                    @else
                                        <span class="badge badge-secondary">Regular</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $category->products_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                           class="btn btn-info" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        
                                        <!-- Subcategories Button -->
                                        @if($category->children->count() > 0)
                                            <a href="#" class="btn btn-secondary view-subcategories" 
                                               data-category-id="{{ $category->id }}"
                                               data-category-name="{{ $category->name }}"
                                               title="View Subcategories">
                                                <i class="fa fa-sitemap"></i>
                                            </a>
                                        @endif

                                        <button type="button" class="btn btn-danger delete-category" 
                                                data-category-id="{{ $category->id }}"
                                                data-category-name="{{ $category->name }}"
                                                title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Display Subcategories -->
                            @foreach($category->children as $subcategory)
                            <tr class="bg-light">
                                <td></td>
                                <td>
                                    @if($subcategory->image)
                                        <img src="{{ asset($subcategory->image) }}" 
                                             alt="{{ $subcategory->name }}" 
                                             class="img-thumbnail" 
                                             style="width: 50px; height: 50px; object-fit: cover;">
                                    @else
                                        <div class="bg-light d-flex align-items-center justify-content-center" 
                                             style="width: 50px; height: 50px;">
                                            <i class="fa fa-folder text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <i class="fa fa-level-down-alt text-muted mr-2"></i>
                                    <strong>{{ $subcategory->name }}</strong>
                                    <br>
                                    <small class="text-muted">Slug: {{ $subcategory->slug }}</small>
                                </td>
                                <td>
                                    <span class="badge badge-info">{{ $category->name }}</span>
                                </td>
                                <td>
                                    @if($subcategory->description)
                                        {{ Str::limit($subcategory->description, 50) }}
                                    @else
                                        <span class="text-muted">No description</span>
                                    @endif
                                </td>
                                <td>
                                    <form action="{{ route('admin.categories.status', $subcategory->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-toggle-status {{ $subcategory->is_active ? 'btn-success' : 'btn-warning' }}"
                                                title="{{ $subcategory->is_active ? 'Deactivate' : 'Activate' }}">
                                            {{ $subcategory->is_active ? 'Active' : 'Inactive' }}
                                        </button>
                                    </form>
                                </td>
                                <td>
                                    @if($subcategory->is_featured)
                                        <span class="badge badge-success">Featured</span>
                                    @else
                                        <span class="badge badge-secondary">Regular</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge badge-primary">{{ $subcategory->products_count ?? 0 }}</span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('admin.categories.edit', $subcategory->id) }}" 
                                           class="btn btn-info" title="Edit">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button type="button" class="btn btn-danger delete-category" 
                                                data-category-id="{{ $subcategory->id }}"
                                                data-category-name="{{ $subcategory->name }}"
                                                title="Delete">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa fa-folder-open fa-3x text-muted mb-3"></i>
                    <h4>No Categories Found</h4>
                    <p class="text-muted">Get started by creating your first category.</p>
                    <a href="{{ route("categories.create") }}" class="btn btn-primary">
                        <i class="fa fa-plus"></i> Create Category
                    </a>
                </div>
            @endif
                </div>
            </div>
        </div>
@include("admin.partials.sub_category_modal")
    </section>
    <!-- /.content -->
</div>

@push('styles')
<style>
.btn-toggle-status {
    min-width: 80px;
    font-size: 0.75rem;
    font-weight: 600;
}
.bg-light tr {
    background-color: #f8f9fa !important;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Delete category confirmation
    $('.delete-category').on('click', function() {
        const categoryId = $(this).data('category-id');
        const categoryName = $(this).data('category-name');
        
        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete <strong>${categoryName}</strong>. This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                // Create delete form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/categories/${categoryId}`;
                
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

    // View subcategories
    $('.view-subcategories').on('click', function(e) {
        e.preventDefault();
        const categoryId = $(this).data('category-id');
        const categoryName = $(this).data('category-name');
        
        $('#subcategoriesModalLabel').text(`Subcategories of ${categoryName}`);
        
        // Load subcategories via AJAX
        $.ajax({
            url: `/admin/categories/${categoryId}/subcategories`,
            type: 'GET',
            success: function(response) {
                $('#subcategoriesContent').html(response);
                $('#subcategoriesModal').modal('show');
            },
            error: function() {
                $('#subcategoriesContent').html(`
                    <div class="alert alert-danger">
                        Failed to load subcategories. Please try again.
                    </div>
                `);
                $('#subcategoriesModal').modal('show');
            }
        });
    });
});
</script>
@endpush

@endsection
