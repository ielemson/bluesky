@extends('layouts.admin')

@section('title', 'Create Admin Order')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Create Admin Order</h1>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Back to Orders
            </a>
        </div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Please fix the following errors:</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
        <form action="{{ route('admin.orders.store') }}" method="POST" id="adminOrderForm">
            @csrf
            <div class="row">
                <div class="col-12">
                    <!-- Vendor Selection -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Select Vendor Shop</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label for="vendor_id">Vendor Shop <span class="text-danger">*</span></label>
                                <select name="vendor_id" id="vendor_id"
                                    class="form-control @error('vendor_id') is-invalid @enderror">
                                    <option value="">Select Vendor Shop</option>
                                    @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}"
                                            {{ old('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->store_name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('vendor_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div id="vendorInfoBox" class="alert alert-info d-none mb-0">
                                <strong>Selected Shop:</strong> <span id="selectedVendorName">—</span>
                            </div>
                        </div>
                    </div>

                    <!-- Vendor Products -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Vendor Products</h6>
                            <small class="text-muted">Select a vendor first to load products</small>
                        </div>
                        <div class="card-body">

                            <!-- FILTERS -->
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <input type="text" id="searchInput" class="form-control"
                                        placeholder="Search product...">
                                </div>

                                <div class="col-md-2">
                                    <select id="categoryFilter" class="form-control">
                                        <option value="">All Categories</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-2">
                                    <input type="number" id="minPrice" class="form-control" placeholder="Min price">
                                </div>

                                <div class="col-md-2">
                                    <input type="number" id="maxPrice" class="form-control" placeholder="Max price">
                                </div>

                                <div class="col-md-2">
                                    <select id="sortFilter" class="form-control">
                                        <option value="">Newest</option>
                                        <option value="price_low">Price Low → High</option>
                                        <option value="price_high">Price High → Low</option>
                                        <option value="oldest">Oldest</option>
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" class="btn btn-primary btn-block" id="applyFilters">Go</button>
                                </div>

                            </div>

                            <!-- LOADER -->
                            <div id="productLoader" class="text-center py-4 d-none">
                                <i class="fa fa-spinner fa-spin fa-2x text-primary"></i>
                            </div>

                            <!-- EMPTY -->
                            <div id="productsEmptyState" class="text-center py-5">
                                <h5>No Vendor Selected</h5>
                            </div>

                            <!-- TABLE -->
                            <div id="productsTableWrapper" class="table-responsive d-none">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Image</th>
                                            <th>Product</th>
                                            <th>Price</th>
                                            <th>Stock</th>
                                            <th>Qty</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="vendorProductsTableBody"></tbody>
                                </table>
                            </div>

                            <!-- PAGINATION -->
                            <div id="productsPaginationWrapper" class="d-none mt-3">
                                <div class="d-flex justify-content-between">
                                    <small id="productsPaginationInfo"></small>

                                    <div>
                                        <button class="btn btn-sm btn-outline-primary"
                                            id="productsPrevPageBtn">Prev</button>
                                        <span id="productsPageIndicator"></span>
                                        <button class="btn btn-sm btn-outline-primary"
                                            id="productsNextPageBtn">Next</button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- Selected Items -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Selected Order Items</h6>
                            <span class="badge badge-primary" id="selectedItemsCount">0 item(s)</span>
                        </div>
                        <div class="card-body">
                            <div id="selectedItemsEmptyState" class="text-center py-4">
                                <i class="fa fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h5>No Items Added</h5>
                                <p class="text-muted mb-0">Add products from the vendor list above.</p>
                            </div>

                            <div id="selectedItemsWrapper" class="table-responsive d-none">
                                <table class="table table-bordered" width="100%" cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>Product</th>
                                            <th>Unit Price</th>
                                            <th width="130">Quantity</th>
                                            <th>Line Total</th>
                                            <th width="100">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selectedItemsTableBody"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Details -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Customer Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Customer Name <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_name"
                                            class="form-control @error('customer_name') is-invalid @enderror"
                                            value="{{ old('customer_name') }}">
                                        @error('customer_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Customer Phone <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_phone"
                                            class="form-control @error('customer_phone') is-invalid @enderror"
                                            value="{{ old('customer_phone') }}">
                                        @error('customer_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Customer Email</label>
                                        <input type="email" name="customer_email"
                                            class="form-control @error('customer_email') is-invalid @enderror"
                                            value="{{ old('customer_email') }}">
                                        @error('customer_email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Customer Address</label>
                                        <input type="text" name="customer_address"
                                            class="form-control @error('customer_address') is-invalid @enderror"
                                            value="{{ old('customer_address') }}">
                                        @error('customer_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Customer City</label>
                                        <input type="text" name="customer_city" class="form-control"
                                            value="{{ old('customer_city') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Customer State</label>
                                        <input type="text" name="customer_state" class="form-control"
                                            value="{{ old('customer_state') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Customer Country</label>
                                        <input type="text" name="customer_country" class="form-control"
                                            value="{{ old('customer_country', 'Nigeria') }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label>Customer Zipcode</label>
                                        <input type="text" name="customer_zipcode" class="form-control"
                                            value="{{ old('customer_zipcode') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Shipping Details -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Shipping Details</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label>Shipping Address <span class="text-danger">*</span></label>
                                <textarea name="shipping_address" rows="3"
                                    class="form-control @error('shipping_address') is-invalid @enderror">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Shipping City <span class="text-danger">*</span></label>
                                        <input type="text" name="shipping_city"
                                            class="form-control @error('shipping_city') is-invalid @enderror"
                                            value="{{ old('shipping_city') }}">
                                        @error('shipping_city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Shipping State <span class="text-danger">*</span></label>
                                        <input type="text" name="shipping_state"
                                            class="form-control @error('shipping_state') is-invalid @enderror"
                                            value="{{ old('shipping_state') }}">
                                        @error('shipping_state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Shipping Country <span class="text-danger">*</span></label>
                                        <input type="text" name="shipping_country"
                                            class="form-control @error('shipping_country') is-invalid @enderror"
                                            value="{{ old('shipping_country', 'Nigeria') }}">
                                        @error('shipping_country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-0">
                                        <label>Shipping Zipcode</label>
                                        <input type="text" name="shipping_zipcode" class="form-control"
                                            value="{{ old('shipping_zipcode') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Additional Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Payment Method</label>
                                        <input type="text" name="payment_method" class="form-control"
                                            value="{{ old('payment_method') }}"
                                            placeholder="e.g. cash, bank transfer, card">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Shipping Method</label>
                                        <input type="text" name="shipping_method" class="form-control"
                                            value="{{ old('shipping_method') }}"
                                            placeholder="e.g. dispatch rider, pickup, waybill">
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-0">
                                        <label>Notes</label>
                                        <textarea name="notes" rows="3" class="form-control">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                

                    <!-- Order Summary -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Order Date</h6>
                        </div>
                        <div class="card-body">
                              <div class="row">
    <div class="col-md-4 mb-3">
        <label for="order_date" class="form-label">Order Date</label>
        <input
            type="date"
            name="order_date"
            id="order_date"
            class="form-control @error('order_date') is-invalid @enderror"
            value="{{ old('order_date', isset($order) ? \Carbon\Carbon::parse($order->order_date)->format('Y-m-d') : now()->format('Y-m-d')) }}"
            min="{{ now()->format('Y-m-d') }}"
            max="{{ now()->addDays(14)->format('Y-m-d') }}"
            required
        >
        @error('order_date')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror

        <small class="text-muted">
            Orders can be scheduled up to 14 days ahead. Vendors will only see future orders on the due date.
        </small>
    </div>

    <div class="col-md-8 mb-3">
        <label class="form-label d-block">Release Type</label>
        <div class="form-control bg-light" id="release_type_preview">
            Immediate order
        </div>
    </div>
</div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Order Summary</h6>
                        </div>
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-3">
                                    <div class="d-flex justify-content-between mb-2 mb-md-0">
                                        <span>Subtotal</span>
                                        <strong>$<span id="summarySubtotal">0.00</span></strong>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group mb-md-0">
                                        <label>Shipping Cost</label>
                                        <input type="number" step="0.01" min="0" name="shipping_cost"
                                            id="shipping_cost" class="form-control"
                                            value="{{ old('shipping_cost', 0) }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group mb-md-0">
                                        <label>Tax Amount</label>
                                        <input type="number" step="0.01" min="0" name="tax_amount"
                                            id="tax_amount" class="form-control" value="{{ old('tax_amount', 0) }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="form-group mb-md-0">
                                        <label>Discount Amount</label>
                                        <input type="number" step="0.01" min="0" name="discount_amount"
                                            id="discount_amount" class="form-control"
                                            value="{{ old('discount_amount', 0) }}">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="text-md-right">
                                        <div class="mb-2">
                                            <span><strong>Total:</strong></span>
                                            <strong>$<span id="summaryTotal">0.00</span></strong>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-block" id="submitOrderBtn">
                                            <i class="fa fa-save"></i> Create Order
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Validation / Info -->
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            <h6 class="font-weight-bold text-primary">Instructions</h6>
                            <ul class="pl-3 mb-0">
                                <li>Select one vendor shop.</li>
                                <li>Add products only from that selected shop.</li>
                                <li>Provide shipping information before submitting.</li>
                                <li>Stock will be deducted from the vendor's available quantity.</li>
                            </ul>
                        </div>
                    </div>

                    @if (session('error'))
                        <div class="alert alert-danger shadow">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>

        </form>
    </div>
@endsection

@push('styles')
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <style>
        .product-thumb {
            width: 60px;
            height: 60px;
            object-fit: cover;
        }

        #productsTableWrapper table th,
        #productsTableWrapper table td,
        #selectedItemsWrapper table th,
        #selectedItemsWrapper table td {
            vertical-align: middle;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    

<script>
document.addEventListener('DOMContentLoaded', function() {
    const vendorSelect = document.getElementById('vendor_id');
    const vendorInfoBox = document.getElementById('vendorInfoBox');
    const selectedVendorName = document.getElementById('selectedVendorName');

    const productLoader = document.getElementById('productLoader');
    const productsEmptyState = document.getElementById('productsEmptyState');
    const productsTableWrapper = document.getElementById('productsTableWrapper');
    const vendorProductsTableBody = document.getElementById('vendorProductsTableBody');

    const productsPaginationWrapper = document.getElementById('productsPaginationWrapper');
    const productsPaginationInfo = document.getElementById('productsPaginationInfo');
    const productsPrevPageBtn = document.getElementById('productsPrevPageBtn');
    const productsNextPageBtn = document.getElementById('productsNextPageBtn');
    const productsPageIndicator = document.getElementById('productsPageIndicator');

    const searchInput = document.getElementById('searchInput');
    const categoryFilter = document.getElementById('categoryFilter');
    const minPrice = document.getElementById('minPrice');
    const maxPrice = document.getElementById('maxPrice');
    const sortFilter = document.getElementById('sortFilter');
    const applyFilters = document.getElementById('applyFilters');

    const selectedItemsEmptyState = document.getElementById('selectedItemsEmptyState');
    const selectedItemsWrapper = document.getElementById('selectedItemsWrapper');
    const selectedItemsTableBody = document.getElementById('selectedItemsTableBody');
    const selectedItemsCount = document.getElementById('selectedItemsCount');

    const shippingCostInput = document.getElementById('shipping_cost');
    const taxAmountInput = document.getElementById('tax_amount');
    const discountAmountInput = document.getElementById('discount_amount');

    const summarySubtotal = document.getElementById('summarySubtotal');
    const summaryTotal = document.getElementById('summaryTotal');

    let currentVendorId = null;
    let currentProductsPage = 1;
    let lastProductsPage = 1;

    let selectedItems = {};

    let filters = {
        search: '',
        category_id: '',
        min_price: '',
        max_price: '',
        sort: ''
    };

    function showToast(message, type = 'success') {
        const bg = type === 'success' ? '#28a745' : '#dc3545';

        Toastify({
            text: message,
            duration: 3000,
            gravity: "top",
            position: "right",
            backgroundColor: bg,
            stopOnFocus: true,
        }).showToast();
    }

    function money(val) {
        return parseFloat(val || 0).toFixed(2);
    }

    function updateSummary() {
        let subtotal = 0;

        Object.values(selectedItems).forEach(item => {
            subtotal += parseFloat(item.price) * parseInt(item.quantity);
        });

        const shipping = parseFloat(shippingCostInput?.value || 0);
        const tax = parseFloat(taxAmountInput?.value || 0);
        const discount = parseFloat(discountAmountInput?.value || 0);
        const total = subtotal + shipping + tax - discount;

        if (summarySubtotal) summarySubtotal.textContent = money(subtotal);
        if (summaryTotal) summaryTotal.textContent = money(total);
    }
function updateSelectedItemsTable() {
    if (!selectedItemsTableBody) return;

    const items = Object.values(selectedItems);
    selectedItemsTableBody.innerHTML = '';

    if (items.length === 0) {
        selectedItemsEmptyState?.classList.remove('d-none');
        selectedItemsWrapper?.classList.add('d-none');
        if (selectedItemsCount) selectedItemsCount.textContent = '0 item(s)';
        updateSummary();
        return;
    }

    selectedItemsEmptyState?.classList.add('d-none');
    selectedItemsWrapper?.classList.remove('d-none');
    if (selectedItemsCount) selectedItemsCount.textContent = `${items.length} item(s)`;

    items.forEach((item, index) => {
        const qty = parseInt(item.quantity || 1);
        const price = parseFloat(item.price || 0);
        const lineTotal = price * qty;

        const row = document.createElement('tr');
        row.innerHTML = `
            <td>
                <strong>${item.name}</strong><br>
                <small class="text-muted">SKU: ${item.sku || '—'}</small>

                <input type="hidden" name="items[${index}][product_id]" value="${item.product_id}">
                <input type="hidden" name="items[${index}][vendor_product_id]" value="${item.vendor_product_id}">
            </td>

            <td>
                $${money(price)}
                <input type="hidden" name="items[${index}][price]" value="${price}">
            </td>

            <td>
                <input
                    type="number"
                    min="1"
                    max="${item.stock_quantity}"
                    value="${qty}"
                    name="items[${index}][quantity]"
                    class="form-control form-control-sm selected-item-qty"
                    data-vendor-product-id="${item.vendor_product_id}"
                >
            </td>

            <td><strong>$${money(lineTotal)}</strong></td>

            <td>
                <button
                    type="button"
                    class="btn btn-sm btn-danger remove-selected-item"
                    data-vendor-product-id="${item.vendor_product_id}">
                    <i class="fa fa-trash"></i>
                </button>
            </td>
        `;

        selectedItemsTableBody.appendChild(row);
    });

    bindSelectedItemEvents();
    updateSummary();
}

    function bindSelectedItemEvents() {
        document.querySelectorAll('.selected-item-qty').forEach(input => {
            input.addEventListener('input', function() {
                const id = this.dataset.vendorProductId;
                let qty = parseInt(this.value || 1);

                if (qty < 1) qty = 1;

                const max = parseInt(this.getAttribute('max') || 1);
                if (qty > max) {
                    qty = max;
                    this.value = max;
                    showToast('Quantity adjusted to available stock.', 'error');
                }

                if (selectedItems[id]) {
                    selectedItems[id].quantity = qty;
                    updateSelectedItemsTable();
                }
            });
        });

        document.querySelectorAll('.remove-selected-item').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.vendorProductId;
                delete selectedItems[id];
                updateSelectedItemsTable();
            });
        });
    }

    function hidePagination() {
        productsPaginationWrapper?.classList.add('d-none');
    }

    function renderPagination(p) {
        if (!p || p.last_page <= 1) {
            hidePagination();
            return;
        }

        currentProductsPage = p.current_page;
        lastProductsPage = p.last_page;

        productsPaginationWrapper?.classList.remove('d-none');

        if (productsPaginationInfo) {
            productsPaginationInfo.textContent = `Showing ${p.from} to ${p.to} of ${p.total}`;
        }

        if (productsPageIndicator) {
            productsPageIndicator.textContent = `Page ${p.current_page} of ${p.last_page}`;
        }

        if (productsPrevPageBtn) productsPrevPageBtn.disabled = p.current_page <= 1;
        if (productsNextPageBtn) productsNextPageBtn.disabled = p.current_page >= p.last_page;
    }

    function bindAddProductEvents() {
        document.querySelectorAll('.add-product-btn').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const product = JSON.parse(this.dataset.product.replace(/&apos;/g, "'"));
                const qtyInput = document.getElementById(`qty_${product.vendor_product_id}`);
                let quantity = parseInt(qtyInput?.value || 1);

                if (quantity < 1) quantity = 1;
                if (quantity > product.stock_quantity) {
                    quantity = product.stock_quantity;
                    if (qtyInput) qtyInput.value = quantity;
                }

                if (selectedItems[product.vendor_product_id]) {
                    const newQty = parseInt(selectedItems[product.vendor_product_id].quantity) + quantity;
                    selectedItems[product.vendor_product_id].quantity =
                        newQty > product.stock_quantity ? product.stock_quantity : newQty;
                } else {
                    selectedItems[product.vendor_product_id] = {
                        vendor_product_id: product.vendor_product_id,
                        product_id: product.product_id,
                        name: product.name,
                        sku: product.sku,
                        price: parseFloat(product.price),
                        stock_quantity: parseInt(product.stock_quantity),
                        quantity: quantity
                    };
                }

                updateSelectedItemsTable();
                showToast('Product added to order.', 'success');
            });
        });
    }
function renderProducts(products, pagination) {
    if (!vendorProductsTableBody) return;

    vendorProductsTableBody.innerHTML = '';

    if (!Array.isArray(products) || products.length === 0) {
        productsTableWrapper?.classList.add('d-none');
        productsEmptyState?.classList.remove('d-none');

        if (productsEmptyState) {
            productsEmptyState.innerHTML = `
                <i class="fa fa-box-open fa-3x text-muted mb-3"></i>
                <h5>No Products Found</h5>
                <p class="text-muted mb-0">No matching products found for this vendor.</p>
            `;
        }

        hidePagination();
        return;
    }

    productsTableWrapper?.classList.remove('d-none');
    productsEmptyState?.classList.add('d-none');

    products.forEach(p => {
        const stockQty = parseInt(p.stock_quantity || 0, 10);
        const safeName = p.name || 'Unnamed Product';
        const safeSku = p.sku || '—';
        const safePrice = money(p.price || 0);
        const qtyValue = stockQty > 0 ? 1 : 0;
        const isOutOfStock = stockQty < 1;

        const imageHtml = p.image
            ? `<img src="${p.image}" alt="${safeName}" class="img-thumbnail product-thumb"
                    onerror="this.onerror=null;this.outerHTML='<div class=&quot;bg-light d-flex align-items-center justify-content-center product-thumb&quot;><i class=&quot;fa fa-image text-muted&quot;></i></div>';">`
            : `<div class="bg-light d-flex align-items-center justify-content-center product-thumb">
                    <i class="fa fa-image text-muted"></i>
               </div>`;

        const buttonHtml = isOutOfStock
            ? `<button type="button" class="btn btn-sm btn-secondary" disabled>
                    Out of Stock
               </button>`
            : `<button
                    type="button"
                    class="btn btn-sm btn-primary add-product-btn"
                    data-product='${JSON.stringify(p).replace(/'/g, "&apos;")}'>
                    Add
               </button>`;

        const row = `
            <tr>
                <td>${imageHtml}</td>
                <td>
                    <strong>${safeName}</strong><br>
                    <small class="text-muted">SKU: ${safeSku}</small>
                </td>
                <td>$${safePrice}</td>
                <td>
                    <span class="${isOutOfStock ? 'text-danger' : 'text-success'}">
                        ${stockQty}
                    </span>
                </td>
                <td>
                    <input
                        type="number"
                        min="1"
                        max="${stockQty}"
                        value="${qtyValue}"
                        class="form-control form-control-sm product-qty"
                        id="qty_${p.vendor_product_id}"
                        ${isOutOfStock ? 'disabled' : ''}>
                </td>
                <td>
                    ${buttonHtml}
                </td>
            </tr>
        `;

        vendorProductsTableBody.insertAdjacentHTML('beforeend', row);
    });

    bindAddProductEvents();
    renderPagination(pagination);
}

   async function loadVendorProducts(vendorId, page = 1) {
    if (!vendorId) return;

    productLoader?.classList.remove('d-none');
    productsTableWrapper?.classList.add('d-none');
    productsEmptyState?.classList.add('d-none');

    const params = new URLSearchParams({
        page: page,
        search: filters.search || '',
        category_id: filters.category_id || '',
        min_price: filters.min_price || '',
        max_price: filters.max_price || '',
        sort: filters.sort || ''
    });

    const vendorProductsUrlTemplate = "{{ route('admin.orders.vendor.products', ':vendor') }}";

    try {
        const url = vendorProductsUrlTemplate.replace(':vendor', vendorId) + `?${params.toString()}`;

        const res = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        if (!res.ok) {
            throw new Error(`HTTP error! Status: ${res.status}`);
        }

        const data = await res.json();
        productLoader?.classList.add('d-none');

        if (!data.success) {
            showToast(data.message || 'Failed to load products', 'error');
            productsTableWrapper?.classList.add('d-none');
            productsEmptyState?.classList.remove('d-none');
            hidePagination();
            return;
        }

        currentVendorId = vendorId;
        selectedVendorName.textContent = data.vendor?.store_name || 'Vendor Shop';
        vendorInfoBox?.classList.remove('d-none');

        renderProducts(data.products || [], data.pagination || null);

    } catch (e) {
        productLoader?.classList.add('d-none');
        productsTableWrapper?.classList.add('d-none');
        productsEmptyState?.classList.remove('d-none');
        hidePagination();

        if (productsEmptyState) {
            productsEmptyState.innerHTML = `
                <i class="fa fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <h5>Unable to Load Products</h5>
                <p class="text-muted mb-0">An error occurred while fetching vendor products.</p>
            `;
        }

        console.error('Load Vendor Products Error:', e);
        showToast('Error loading products', 'error');
    }
}

    vendorSelect?.addEventListener('change', function() {
        const vendorId = this.value;
        currentVendorId = vendorId || null;

        selectedItems = {};
        updateSelectedItemsTable();

        filters = {
            search: '',
            category_id: '',
            min_price: '',
            max_price: '',
            sort: ''
        };

        if (searchInput) searchInput.value = '';
        if (categoryFilter) categoryFilter.value = '';
        if (minPrice) minPrice.value = '';
        if (maxPrice) maxPrice.value = '';
        if (sortFilter) sortFilter.value = '';

        if (!vendorId) {
            vendorInfoBox?.classList.add('d-none');
            selectedVendorName.textContent = '—';
            vendorProductsTableBody.innerHTML = '';
            productsTableWrapper?.classList.add('d-none');
            productsEmptyState?.classList.remove('d-none');
            hidePagination();
            return;
        }

        loadVendorProducts(vendorId, 1);
    });

    applyFilters?.addEventListener('click', function(e) {
        e.preventDefault();

        filters.search = searchInput?.value || '';
        filters.category_id = categoryFilter?.value || '';
        filters.min_price = minPrice?.value || '';
        filters.max_price = maxPrice?.value || '';
        filters.sort = sortFilter?.value || '';

        if (!currentVendorId) return;

        loadVendorProducts(currentVendorId, 1);
    });

    let debounce;
    searchInput?.addEventListener('input', function() {
        clearTimeout(debounce);

        debounce = setTimeout(() => {
            if (!currentVendorId) return;
            filters.search = this.value;
            loadVendorProducts(currentVendorId, 1);
        }, 400);
    });

    productsPrevPageBtn?.addEventListener('click', function(e) {
        e.preventDefault();
        if (currentVendorId && currentProductsPage > 1) {
            loadVendorProducts(currentVendorId, currentProductsPage - 1);
        }
    });

    productsNextPageBtn?.addEventListener('click', function(e) {
        e.preventDefault();
        if (currentVendorId && currentProductsPage < lastProductsPage) {
            loadVendorProducts(currentVendorId, currentProductsPage + 1);
        }
    });

    [shippingCostInput, taxAmountInput, discountAmountInput].forEach(input => {
        input?.addEventListener('input', updateSummary);
    });

   document.getElementById('adminOrderForm')?.addEventListener('submit', function(e) {
    if (Object.keys(selectedItems).length === 0) {
        e.preventDefault();
        showToast('Please add at least one product to the order.', 'error');
        return;
    }

    const qtyInputs = document.querySelectorAll('input[name^="items["][name$="[quantity]"]');
    if (!qtyInputs.length) {
        e.preventDefault();
        showToast('Order items are missing from the form.', 'error');
    }
});

    updateSelectedItemsTable();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const orderDateInput = document.getElementById('order_date');
    const preview = document.getElementById('release_type_preview');

    function updateReleasePreview() {
        if (!orderDateInput || !preview) return;

        const selected = orderDateInput.value;
        if (!selected) {
            preview.textContent = 'Immediate order';
            return;
        }

        const today = new Date();
        today.setHours(0, 0, 0, 0);

        const selectedDate = new Date(selected);
        selectedDate.setHours(0, 0, 0, 0);

        if (selectedDate > today) {
            preview.textContent = 'Scheduled order — vendor will receive it on the selected date';
        } else {
            preview.textContent = 'Immediate order';
        }
    }

    orderDateInput.addEventListener('change', updateReleasePreview);
    updateReleasePreview();
});
</script>
@endpush

