@extends('layouts.admin')

@section('title', 'Create Product')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create New Product</h1>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Products
            </a>
        </div>

        <!-- Alert Messages -->
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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-triangle"></i> Please fix the following errors:
                <ul class="mb-0 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Create Form -->
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
            @csrf

            <div class="row">
                <!-- Left Column - Basic Information -->
                <div class="col-lg-8">
                    <!-- Basic Information Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Basic Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Product Name *</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}"
                                    placeholder="Enter product name" required>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="category_id">Category *</label>
                                <select class="form-control @error('category_id') is-invalid @enderror" id="category_id"
                                    name="category_id" required>
                                    <option value="">-- Select Category --</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}"
                                            {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="short_description">Short Description</label>
                                <textarea class="form-control @error('short_description') is-invalid @enderror" id="short_description"
                                    name="short_description" rows="3" placeholder="Brief description of the product">{{ old('short_description') }}</textarea>
                                @error('short_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">Max 500 characters. This will be shown in product
                                    listings.</small>
                            </div>

                            <div class="form-group">
                                <label for="description">Full Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                    rows="6" placeholder="Detailed product description">{{ old('description') }}</textarea>
                                @error('description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Pricing Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Pricing</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="price">Price *</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" step="0.01"
                                                class="form-control @error('price') is-invalid @enderror" id="price"
                                                name="price" value="{{ old('price') }}" placeholder="0.00"
                                                min="0" required>
                                        </div>
                                        @error('price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="compare_price">Compare Price</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" step="0.01"
                                                class="form-control @error('compare_price') is-invalid @enderror"
                                                id="compare_price" name="compare_price"
                                                value="{{ old('compare_price') }}" placeholder="0.00" min="0">
                                        </div>
                                        @error('compare_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <small class="form-text text-muted">Original price for showing discount.</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="cost_price">Cost Price</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">$</span>
                                            </div>
                                            <input type="number" step="0.01"
                                                class="form-control @error('cost_price') is-invalid @enderror"
                                                id="cost_price" name="cost_price" value="{{ old('cost_price') }}"
                                                placeholder="0.00" min="0">
                                        </div>
                                        @error('cost_price')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <small class="form-text text-muted">Your cost for profit calculation.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Inventory Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Inventory</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="sku">SKU *</label>
                                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                                            id="sku" name="sku" value="{{ old('sku') }}"
                                            placeholder="Product SKU" required>
                                        @error('sku')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="barcode">Barcode</label>
                                        <input type="text" class="form-control @error('barcode') is-invalid @enderror"
                                            id="barcode" name="barcode" value="{{ old('barcode') }}"
                                            placeholder="Barcode, UPC, ISBN, etc.">
                                        @error('barcode')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="stock_quantity">Stock Quantity *</label>
                                        <input type="number"
                                            class="form-control @error('stock_quantity') is-invalid @enderror"
                                            id="stock_quantity" name="stock_quantity"
                                            value="{{ old('stock_quantity', 0) }}" min="0" required>
                                        @error('stock_quantity')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="low_stock_threshold">Low Stock Threshold</label>
                                        <input type="number"
                                            class="form-control @error('low_stock_threshold') is-invalid @enderror"
                                            id="low_stock_threshold" name="low_stock_threshold"
                                            value="{{ old('low_stock_threshold', 5) }}" min="0">
                                        @error('low_stock_threshold')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status *</label>
                                        <select class="form-control @error('status') is-invalid @enderror" id="status"
                                            name="status" required>
                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft
                                            </option>
                                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>
                                                Active</option>
                                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                                Inactive</option>
                                        </select>
                                        @error('status')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="track_quantity"
                                                name="track_quantity" value="1"
                                                {{ old('track_quantity', true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="track_quantity">
                                                Track Quantity
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="allow_backorder"
                                                name="allow_backorder" value="1"
                                                {{ old('allow_backorder') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="allow_backorder">
                                                Allow Backorder
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input"
                                                id="is_available_for_vendors" name="is_available_for_vendors"
                                                value="1"
                                                {{ old('is_available_for_vendors', true) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_available_for_vendors">
                                                Available for Vendors
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Shipping</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="weight">Weight (kg)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('weight') is-invalid @enderror" id="weight"
                                            name="weight" value="{{ old('weight') }}" placeholder="0.00"
                                            min="0">
                                        @error('weight')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="length">Length (cm)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('length') is-invalid @enderror" id="length"
                                            name="length" value="{{ old('length') }}" placeholder="0.00"
                                            min="0">
                                        @error('length')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="width">Width (cm)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('width') is-invalid @enderror" id="width"
                                            name="width" value="{{ old('width') }}" placeholder="0.00"
                                            min="0">
                                        @error('width')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="height">Height (cm)</label>
                                        <input type="number" step="0.01"
                                            class="form-control @error('height') is-invalid @enderror" id="height"
                                            name="height" value="{{ old('height') }}" placeholder="0.00"
                                            min="0">
                                        @error('height')
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
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="is_virtual"
                                                name="is_virtual" value="1"
                                                {{ old('is_virtual') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_virtual">
                                                Virtual Product
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">No shipping required.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="is_downloadable"
                                                name="is_downloadable" value="1"
                                                {{ old('is_downloadable') ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="is_downloadable">
                                                Downloadable Product
                                            </label>
                                        </div>
                                        <small class="form-text text-muted">Customers can download files.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Attributes Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Product Attributes & Badges</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Status Badges</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="is_new_arrival" name="is_new_arrival" value="1"
                                                        {{ old('is_new_arrival', true) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_new_arrival">
                                                        New Arrival
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_featured"
                                                        name="is_featured" value="1"
                                                        {{ old('is_featured', true) ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_featured">
                                                        Featured Product
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="is_hot_selling" name="is_hot_selling" value="1"
                                                        {{ old('is_hot_selling') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_hot_selling">
                                                        Hot Selling
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="is_best_seller" name="is_best_seller" value="1"
                                                        {{ old('is_best_seller') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_best_seller">
                                                        Best Seller
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_trending"
                                                        name="is_trending" value="1"
                                                        {{ old('is_trending') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_trending">
                                                        Trending
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_clearance"
                                                        name="is_clearance" value="1"
                                                        {{ old('is_clearance') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_clearance">
                                                        Clearance
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_pre_order"
                                                        name="is_pre_order" value="1"
                                                        {{ old('is_pre_order') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_pre_order">
                                                        Pre-Order
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="is_flash_sale" name="is_flash_sale" value="1"
                                                        {{ old('is_flash_sale') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_flash_sale">
                                                        Flash Sale
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="has_free_shipping" name="has_free_shipping" value="1"
                                                        {{ old('has_free_shipping') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="has_free_shipping">
                                                        Free Shipping
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="text-primary mb-3">Product Features</h6>
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="is_eco_friendly" name="is_eco_friendly" value="1"
                                                        {{ old('is_eco_friendly') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_eco_friendly">
                                                        Eco Friendly
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="is_sustainable" name="is_sustainable" value="1"
                                                        {{ old('is_sustainable') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_sustainable">
                                                        Sustainable
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="is_handmade"
                                                        name="is_handmade" value="1"
                                                        {{ old('is_handmade') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_handmade">
                                                        Handmade
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input"
                                                        id="is_customizable" name="is_customizable" value="1"
                                                        {{ old('is_customizable') ? 'checked' : '' }}>
                                                    <label class="custom-control-label" for="is_customizable">
                                                        Customizable
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label for="condition">Product Condition *</label>
                                                <select class="form-control @error('condition') is-invalid @enderror"
                                                    id="condition" name="condition" required>
                                                    <option value="new"
                                                        {{ old('condition', 'new') == 'new' ? 'selected' : '' }}>New
                                                    </option>
                                                    <option value="refurbished"
                                                        {{ old('condition') == 'refurbished' ? 'selected' : '' }}>
                                                        Refurbished</option>
                                                    <option value="used"
                                                        {{ old('condition') == 'used' ? 'selected' : '' }}>Used</option>
                                                </select>
                                                @error('condition')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Sale Settings -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <h6 class="text-primary mb-3">Sale Settings</h6>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sale_percentage">Sale Percentage (%)</label>
                                        <input type="number"
                                            class="form-control @error('sale_percentage') is-invalid @enderror"
                                            id="sale_percentage" name="sale_percentage"
                                            value="{{ old('sale_percentage') }}" placeholder="0" min="1"
                                            max="100">
                                        @error('sale_percentage')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sale_start_date">Sale Start Date</label>
                                        <input type="datetime-local"
                                            class="form-control @error('sale_start_date') is-invalid @enderror"
                                            id="sale_start_date" name="sale_start_date"
                                            value="{{ old('sale_start_date') }}">
                                        @error('sale_start_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="sale_end_date">Sale End Date</label>
                                        <input type="datetime-local"
                                            class="form-control @error('sale_end_date') is-invalid @enderror"
                                            id="sale_end_date" name="sale_end_date" value="{{ old('sale_end_date') }}">
                                        @error('sale_end_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Badge Preview -->
                            <div class="row mt-3">
                                <div class="col-12">
                                    <h6 class="text-primary mb-2">Badge Preview</h6>
                                    <div id="badgePreview" class="d-flex flex-wrap gap-2 p-3 border rounded">
                                        <span class="badge badge-primary">New Arrival</span>
                                        <span class="text-muted">Select attributes to see badges...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SEO Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">SEO Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="meta_title">Meta Title</label>
                                <input type="text" class="form-control @error('meta_title') is-invalid @enderror"
                                    id="meta_title" name="meta_title" value="{{ old('meta_title') }}"
                                    placeholder="Meta title for SEO">
                                @error('meta_title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">Recommended: 50-60 characters</small>
                            </div>

                            <div class="form-group">
                                <label for="meta_description">Meta Description</label>
                                <textarea class="form-control @error('meta_description') is-invalid @enderror" id="meta_description"
                                    name="meta_description" rows="3" placeholder="Meta description for SEO">{{ old('meta_description') }}</textarea>
                                @error('meta_description')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">Recommended: 150-160 characters</small>
                            </div>

                            <div class="form-group">
                                <label for="meta_keywords">Meta Keywords</label>
                                <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror"
                                    id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}"
                                    placeholder="keyword1, keyword2, keyword3">
                                @error('meta_keywords')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <small class="form-text text-muted">Separate keywords with commas</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column - Images & Settings -->
                <div class="col-lg-4">
                    <!-- Images Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Product Images</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="images">Upload Images</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input @error('images') is-invalid @enderror"
                                        id="images" name="images[]" multiple accept="image/*">
                                    <label class="custom-file-label" for="images">Choose files...</label>
                                    @error('images')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <small class="form-text text-muted">
                                    You can select multiple images. First image will be set as primary.
                                </small>
                            </div>

                            <!-- Image Preview Area -->
                            <div id="imagePreview" class="mt-3">
                                <p class="text-muted text-center">No images selected</p>
                            </div>
                        </div>
                    </div>

                    <!-- Settings Card -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Settings</h6>
                        </div>
                        <div class="card-body">
                            {{-- <div class="form-group">
<div class="custom-control custom-checkbox">
<input type="checkbox" class="custom-control-input" 
id="is_featured" name="is_featured" value="1"
{{ old('is_featured') ? 'checked' : '' }}>
<label class="custom-control-label" for="is_featured">
Featured Product
</label>
</div>
<small class="form-text text-muted">
Featured products will be highlighted on the website.
</small>
</div> --}}

                            <div class="form-group">
                                <label for="brand">Brand</label>
                                <input type="text" class="form-control @error('brand') is-invalid @enderror"
                                    id="brand" name="brand" value="{{ old('brand') }}"
                                    placeholder="Product brand">
                                @error('brand')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="model">Model</label>
                                <input type="text" class="form-control @error('model') is-invalid @enderror"
                                    id="model" name="model" value="{{ old('model') }}"
                                    placeholder="Product model">
                                @error('model')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="warranty">Warranty</label>
                                <input type="text" class="form-control @error('warranty') is-invalid @enderror"
                                    id="warranty" name="warranty" value="{{ old('warranty') }}"
                                    placeholder="e.g., 1 Year">
                                @error('warranty')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Actions Card -->
                    <div class="card shadow">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Actions</h6>
                        </div>
                        <div class="card-body">
                            <div class="btn-group" role="group" style="width: 100%;">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Create
                                </button>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('styles')
    <style>
        .image-preview-item {
            position: relative;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            text-align: center;
        }

        .image-preview-item img {
            max-width: 100%;
            max-height: 150px;
            object-fit: cover;
        }

        .image-preview-item .badge-primary {
            position: absolute;
            top: 5px;
            left: 5px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Image preview
            $('#images').on('change', function() {
                const files = this.files;
                const preview = $('#imagePreview');

                preview.empty();

                if (files.length === 0) {
                    preview.html('<p class="text-muted text-center">No images selected</p>');
                    return;
                }

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = function(e) {
                        const isPrimary = i === 0 ? '<span class="badge badge-primary">Primary</span>' :
                            '';
                        preview.append(`
<div class="image-preview-item">
${isPrimary}
<img src="${e.target.result}" alt="Preview">
<p class="small mb-0 mt-2">${file.name}</p>
</div>
`);
                    }

                    reader.readAsDataURL(file);
                }
            });

            // Update file input label
            $('.custom-file-input').on('change', function() {
                let files = $(this).prop('files');
                let fileNames = [];

                for (let i = 0; i < files.length; i++) {
                    fileNames.push(files[i].name);
                }

                $(this).next('.custom-file-label').addClass("selected").html(fileNames.join(', '));
            });

            // Form validation
            $('#productForm').on('submit', function() {
                const price = parseFloat($('#price').val());
                const comparePrice = parseFloat($('#compare_price').val()) || 0;

                if (comparePrice > 0 && comparePrice <= price) {
                    Swal.fire({
                        title: 'Price Warning',
                        text: 'Compare price should be greater than regular price to show discount.',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                    return false;
                }

                return true;
            });

            // Badge preview update
            function updateBadgePreview() {
                const badges = [];
                const badgeClasses = {
                    'is_new_arrival': {
                        label: 'New Arrival',
                        class: 'badge-primary'
                    },
                    'is_featured': {
                        label: 'Featured Product',
                        class: 'badge-primary'
                    },
                    'is_hot_selling': {
                        label: 'Hot Selling',
                        class: 'badge-danger'
                    },
                    'is_best_seller': {
                        label: 'Best Seller',
                        class: 'badge-success'
                    },
                    'is_trending': {
                        label: 'Trending',
                        class: 'badge-info'
                    },
                    'is_clearance': {
                        label: 'Clearance',
                        class: 'badge-warning'
                    },
                    'is_pre_order': {
                        label: 'Pre-Order',
                        class: 'badge-dark'
                    },
                    'is_flash_sale': {
                        label: 'Flash Sale',
                        class: 'badge-danger'
                    },
                    'has_free_shipping': {
                        label: 'Free Shipping',
                        class: 'badge-success'
                    },
                    'is_eco_friendly': {
                        label: 'Eco Friendly',
                        class: 'badge-success'
                    },
                    'is_sustainable': {
                        label: 'Sustainable',
                        class: 'badge-info'
                    },
                    'is_handmade': {
                        label: 'Handmade',
                        class: 'badge-warning'
                    },
                    'is_customizable': {
                        label: 'Customizable',
                        class: 'badge-primary'
                    }
                };

                Object.keys(badgeClasses).forEach(checkboxId => {
                    if ($(`#${checkboxId}`).is(':checked')) {
                        badges.push(badgeClasses[checkboxId]);
                    }
                });

                const preview = $('#badgePreview');
                preview.empty();

                if (badges.length === 0) {
                    preview.html('<span class="text-muted">Select attributes to see badges...</span>');
                } else {
                    badges.forEach(badge => {
                        preview.append(`<span class="badge ${badge.class}">${badge.label}</span>`);
                    });
                }
            }

            // Update badge preview when checkboxes change
            $('input[type="checkbox"]').on('change', updateBadgePreview);

            // Initial badge preview
            updateBadgePreview();
        });
    </script>
@endpush
