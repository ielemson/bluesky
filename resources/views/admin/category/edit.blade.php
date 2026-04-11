@extends('layouts.admin')

@section('content')
@section('title', 'Edit Category')
<div class="container-full">
    <!-- Content Header (Page header) -->
    @include('admin.partials.page_header', ['header1' => 'Categories Management', 'header2' => 'Edit Category'])
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            @include("admin.partials.category_alert")
            <!-- Applications Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pending Applications</h6>
                </div>
                <div class="card-body">
                 <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Category Information</h6>
        </div>
        <div class="card-body">
             <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="row">
                    <div class="col-md-8">
                        <!-- Basic Information -->
                        <div class="form-group">
                            <label for="name">Category Name *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $category->name) }}" 
                                   placeholder="Enter category name" required>
                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="parent_id">Parent Category</label>
                            <select class="form-control @error('parent_id') is-invalid @enderror" 
                                    id="parent_id" name="parent_id">
                                <option value="">-- Select Parent Category --</option>
                                @foreach($parentCategories as $parent)
                                    <option value="{{ $parent->id }}" 
                                        {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}
                                        {{ $parent->id == $category->id ? 'disabled' : '' }}>
                                        {{ $parent->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('parent_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                            <small class="form-text text-muted">
                                Leave empty to make this a main category.
                            </small>
                        </div>

                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="4" 
                                      placeholder="Enter category description">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-4">
                        <!-- Current Image -->
                        <div class="form-group">
                            <label>Current Image</label>
                            @if($category->image)
                                <div class="text-center mb-3">
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         alt="{{ $category->name }}" 
                                         class="img-thumbnail" style="max-height: 200px;">
                                    <div class="mt-2">
                                        <a href="{{ asset('storage/' . $category->image) }}" 
                                           target="_blank" class="btn btn-sm btn-info">
                                            <i class="fa fa-external-link-alt"></i> View Full
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="text-center text-muted py-4 border rounded">
                                    <i class="fa fa-image fa-2x mb-2"></i>
                                    <p class="mb-0">No image uploaded</p>
                                </div>
                            @endif
                        </div>

                        <!-- Image Upload -->
                        <div class="form-group">
                            <label for="image">Update Image</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('image') is-invalid @enderror" 
                                       id="image" name="image" accept="image/*">
                                <label class="custom-file-label" for="image">Choose new file...</label>
                                @error('image')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                Leave empty to keep current image.
                            </small>
                            
                            <!-- New Image Preview -->
                            <div class="mt-3 text-center">
                                <img id="imagePreview" src="#" alt="Image Preview" 
                                     class="img-thumbnail d-none" style="max-height: 150px;">
                            </div>
                        </div>

                        <!-- Settings -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" 
                                       id="is_active" name="is_active" value="1" 
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_active">
                                    Active Category
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" 
                                       id="is_featured" name="is_featured" value="1"
                                       {{ old('is_featured', $category->is_featured) ? 'checked' : '' }}>
                                <label class="custom-control-label" for="is_featured">
                                    Featured Category
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="text-primary mb-3">
                            <i class="fa fa-search"></i> SEO Settings
                        </h6>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="meta_title">Meta Title</label>
                            <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                                   id="meta_title" name="meta_title" 
                                   value="{{ old('meta_title', $category->meta_title) }}"
                                   placeholder="Meta title for SEO">
                            @error('meta_title')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="meta_description">Meta Description</label>
                            <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                      id="meta_description" name="meta_description" rows="2"
                                      placeholder="Meta description for SEO">{{ old('meta_description', $category->meta_description) }}</textarea>
                            @error('meta_description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="row mt-4">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save"></i> Update Category
                        </button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                            <i class="fa fa-times"></i> Cancel
                        </a>
                    </div>
                </div>
            </form>
        </div>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>

@push('scripts')
<script>
$(document).ready(function() {
    // Image preview
    $('#image').on('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#imagePreview').attr('src', e.target.result).removeClass('d-none');
            }
            reader.readAsDataURL(file);
            $('.custom-file-label').text(file.name);
        }
    });

    // Update file input label
    $('.custom-file-input').on('change', function() {
        let fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').addClass("selected").html(fileName);
    });
});
</script>
@endpush

@endsection
