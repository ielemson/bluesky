  <!-- Products Grid -->
    <div class="row">
        @forelse($products as $product)
            <div class="col-12 col-lg-6 col-xl-4 mb-4">
                <div class="card shadow-sm h-100">
                    <div class="card-body p-3">
                        <div class="product-img text-center mb-3">
                            @if($product->images->count() > 0)
                                <img src="{{ asset($product->images->first()->image_path) }}" 
                                     alt="{{ $product->images->first()->alt_text ?? $product->name }}"
                                     class="img-fluid rounded" 
                                     style="height:700px; width:700px">
                            @else
                                <img src="https://via.placeholder.com/300x200?text=No+Image" 
                                     alt="No image available"
                                     class="img-fluid rounded"
                                     style="max-height: 200px; object-fit: cover;">
                            @endif
                        </div>
                        <div class="product-text">
                            <div class="pro-img-overlay d-flex justify-content-end mb-2">
                                <button class="btn btn-info btn-sm btn-icon-circle mr-1" 
                                        data-toggle="tooltip" 
                                        title="View Details">
                                    <i class="mdi mdi-eye"></i>
                                </button>
                                <button class="btn btn-success btn-sm btn-icon-circle add-to-listing"
                                        data-product-id="{{ $product->id }}"
                                        data-product-name="{{ $product->name }}"
                                        data-toggle="tooltip"
                                        title="Add to My Listing">
                                    <i class="mdi mdi-plus"></i>
                                </button>
                            </div>
                            <h2 class="pro-price text-blue mb-2">${{ number_format($product->cost_price ?? $product->price, 2) }}</h2>
                            <h5 class="card-title mb-1">{{ Str::limit($product->name, 50) }}</h5>
                            <small class="text-muted db">{{ Str::limit($product->short_description ?? $product->description, 80) }}</small>
                            
                            <!-- Additional product info -->
                            <div class="mt-2 pt-2 border-top">
                                <small class="text-muted">
                                    <i class="mdi mdi-package-variant"></i> 
                                    Stock: {{ $product->stock_quantity ?? 'N/A' }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    <i class="mdi mdi-barcode"></i> 
                                    SKU: {{ $product->sku ?? 'N/A' }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">
                    <i class="mdi mdi-information-outline"></i>
                    No products available for vendors at the moment.
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    @endif