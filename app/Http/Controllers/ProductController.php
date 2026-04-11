<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Vendor;
use App\Models\VendorProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the products.
     */
//     public function index(Request $request)
// {
//     $query = Product::with(['category', 'images', 'vendors'])
//         ->withCount(['vendors' => function($query) {
//             $query->where('is_active', true);
//         }]);

//     // Search filter
//     if ($request->has('search') && $request->search) {
//         $search = $request->search;
//         $query->where(function($q) use ($search) {
//             $q->where('name', 'like', "%{$search}%")
//               ->orWhere('sku', 'like', "%{$search}%")
//               ->orWhere('barcode', 'like', "%{$search}%");
//         });
//     }

//     // Category filter
//     if ($request->has('category_id') && $request->category_id) {
//         $query->where('category_id', $request->category_id);
//     }

//     // Status filter
//     if ($request->has('status') && $request->status) {
//         $query->where('status', $request->status);
//     }

//     // Featured filter
//     if ($request->has('is_featured') && $request->is_featured !== '') {
//         $query->where('is_featured', $request->is_featured);
//     }

//     $products = $query->latest()->paginate(10);
//     $categories = Category::active()->get();

//     return view('admin.products.index', compact('products', 'categories'));
// }

// public function index(Request $request)
// {
//     $query = Product::with(['category', 'images'])
//                     ->withCount('activeVendors'); // counts vendors via vendor_products

//     // Search filter
//     if ($request->filled('search')) {
//         $search = $request->search;
//         $query->where(function($q) use ($search) {
//             $q->where('name', 'like', "%{$search}%")
//               ->orWhere('sku', 'like', "%{$search}%")
//               ->orWhere('barcode', 'like', "%{$search}%");
//         });
//     }

//     // Category filter
//     if ($request->filled('category_id')) {
//         $query->where('category_id', $request->category_id);
//     }

//     // Status filter
//     if ($request->filled('status')) {
//         $query->where('status', $request->status);
//     }

//     // Featured filter
//     if ($request->filled('is_featured')) {
//         $query->where('is_featured', $request->is_featured);
//     }

//     $products = $query->latest()->paginate(10);
//     $categories = Category::active()->get();

//     return view('admin.products.index', compact('products', 'categories'));
// }


// public function index(Request $request)
// {
//     $query = Product::with(['category', 'images'])
//                     ->withCount('activeVendors'); // counts active vendors

//     // Search filter
//     if ($request->filled('search')) {
//         $search = $request->search;
//         $query->where(function($q) use ($search) {
//             $q->where('name', 'like', "%{$search}%")
//               ->orWhere('sku', 'like', "%{$search}%")
//               ->orWhere('barcode', 'like', "%{$search}%");
//         });
//     }

//     // Category filter
//     if ($request->filled('category_id')) {
//         $query->where('category_id', $request->category_id);
//     }

//     // Status filter
//     if ($request->filled('status')) {
//         $query->where('status', $request->status);
//     }

//     // Featured filter
//     if ($request->filled('is_featured')) {
//         $query->where('is_featured', $request->is_featured);
//     }

//     $products = $query->latest()->paginate(10);
//     $categories = Category::active()->get();

//     return view('admin.products.index', compact('products', 'categories'));
// }

public function index(Request $request)
{
    $query = Product::with(['category', 'images'])
        ->withCount([
            'activeVendors as vendor_count' // alias for clarity
        ]);

    // Search Filter
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('sku', 'like', "%{$search}%")
              ->orWhere('barcode', 'like', "%{$search}%");
        });
    }

    // Category Filter
    if ($request->filled('category_id')) {
        $query->where('category_id', $request->category_id);
    }

    // Status Filter
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }

    // Featured Filter
    if ($request->filled('is_featured')) {
        $query->where('is_featured', $request->is_featured);
    }

    $products = $query->latest()->paginate(10);
    $categories = Category::active()->get();

    return view('admin.products.index', compact('products', 'categories'));
}


    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::active()->get();
        return view('admin.products.create', compact('categories'));
    }

   /**
 * Store a newly created product in storage.
 */
public function store(Request $request)
{
    DB::beginTransaction();

    try {
        $validated = $this->validateProduct($request);

        // Generate slug
        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        // Handle published_at
        if ($validated['status'] === 'active' && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        // Handle boolean fields
        $booleanFields = [
            'track_quantity',
            'allow_backorder',
            'is_available_for_vendors',
            'is_featured',
            'is_new_arrival',
            'is_hot_selling',
            'is_best_seller',
            'is_trending',
            'is_clearance',
            'is_back_in_stock',
            'is_pre_order',
            'is_flash_sale',
            'has_free_shipping',
            'is_eco_friendly',
            'is_sustainable',
            'is_handmade',
            'is_customizable',
            'is_virtual',
            'is_downloadable'
        ];

        foreach ($booleanFields as $field) {
            $validated[$field] = $request->has($field);
        }

        // Create product
        $product = Product::create($validated);

        // Handle images
        if ($request->hasFile('images')) {
            $this->handleProductImages($product, $request->file('images'));
        }

        DB::commit();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');

    } catch (\Illuminate\Validation\ValidationException $e) {
        DB::rollBack();
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
            
    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Product creation error: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Error creating product: ' . $e->getMessage())
            ->withInput();
    }
}

/**
 * Validate product data.
 */
private function validateProduct(Request $request, $productId = null)
{
    $rules = [
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|string',
        'short_description' => 'nullable|string|max:500',
        'price' => 'required|numeric|min:0',
        'compare_price' => 'nullable|numeric|min:0',
        'cost_price' => 'nullable|numeric|min:0',
        'stock_quantity' => 'required|integer|min:0',
        'sku' => 'required|string|unique:products,sku' . ($productId ? ",{$productId}" : ''),
        'barcode' => 'nullable|string',
        'weight' => 'nullable|numeric|min:0',
        'length' => 'nullable|numeric|min:0',
        'width' => 'nullable|numeric|min:0',
        'height' => 'nullable|numeric|min:0',
        'status' => 'required|in:draft,active,inactive,archived',
        'is_featured' => 'boolean',
        'is_available_for_vendors' => 'boolean',
        'published_at' => 'nullable|date',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
        'meta_keywords' => 'nullable|string',
        'brand' => 'nullable|string|max:255',
        'model' => 'nullable|string|max:255',
        'warranty' => 'nullable|string|max:255',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'track_quantity' => 'boolean',
        'allow_backorder' => 'boolean',
        'low_stock_threshold' => 'integer|min:0',
        'is_virtual' => 'boolean',
        'is_downloadable' => 'boolean',
        
        // Product Attributes
        'is_new_arrival' => 'boolean',
        'is_hot_selling' => 'boolean',
        'is_best_seller' => 'boolean',
        'is_trending' => 'boolean',
        'is_clearance' => 'boolean',
        'is_back_in_stock' => 'boolean',
        'is_pre_order' => 'boolean',
        'is_flash_sale' => 'boolean',
        'has_free_shipping' => 'boolean',
        'is_eco_friendly' => 'boolean',
        'is_sustainable' => 'boolean',
        'is_handmade' => 'boolean',
        'is_customizable' => 'boolean',
        'condition' => 'required|in:new,refurbished,used',
        'sale_start_date' => 'nullable|date',
        'sale_end_date' => 'nullable|date|after_or_equal:sale_start_date',
        'sale_percentage' => 'nullable|integer|min:1|max:100',
    ];

    // Add custom validation for compare_price
    $request->validate($rules, [
        'compare_price.gt' => 'Compare price must be greater than regular price.',
    ]);

    $validated = $request->validate($rules);

    // Custom validation for compare_price
    if (isset($validated['compare_price']) && $validated['compare_price'] > 0) {
        if ($validated['compare_price'] <= $validated['price']) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'compare_price' => 'Compare price must be greater than regular price to show discount.',
            ]);
        }
    }

    return $validated;
}

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::active()->get();
        return view("admin.products.edit", compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        DB::beginTransaction();

        try {
            $validated = $this->validateProduct($request, $product->id);

            // Update slug if name changed
            if ($validated['name'] !== $product->name) {
                $validated['slug'] = $this->generateUniqueSlug($validated['name'], $product->id);
            }

            // Handle published_at
            if ($validated['status'] === 'active' && empty($validated['published_at'])) {
                $validated['published_at'] = now();
            } elseif ($validated['status'] !== 'active') {
                $validated['published_at'] = null;
            }

            // Update product
            $product->update($validated);

            // Handle new images
            if ($request->hasFile('images')) {
                $this->handleProductImages($product, $request->file('images'));
            }

            // Handle image reordering
            if ($request->has('image_order')) {
                $this->reorderImages($product, $request->image_order);
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error updating product: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();

        try {
            // Check if product is used by vendors
            if ($product->vendors()->exists()) {
                return redirect()->back()
                    ->with('error', 'Cannot delete product that is being used by vendors.');
            }

            // Delete images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
                $image->delete();
            }

            $product->delete();

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'Product deleted successfully.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    /**
     * Update product status.
     */
    // public function updateStatus(Product $product)
    // {
    //     $newStatus = $product->status === 'active' ? 'inactive' : 'active';
        
    //     $product->update([
    //         'status' => $newStatus,
    //         'published_at' => $newStatus === 'active' ? now() : null
    //     ]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => "Product {$newStatus} successfully.",
    //         'status' => $newStatus
    //     ]);
    // }

    // In ProductController

/**
 * Update product status
 */
public function updateStatus(Request $request, Product $product)
{
    $validated = $request->validate([
        'status' => 'required|in:draft,active,inactive'
    ]);

    $product->update(['status' => $validated['status']]);

    return response()->json([
        'message' => 'Product status updated successfully',
        'new_status' => $product->status
    ]);
}



    /**
     * Toggle featured status.
     */
    public function toggleFeatured(Product $product)
    {
        $product->update([
            'is_featured' => !$product->is_featured
        ]);

        $status = $product->is_featured ? 'featured' : 'unfeatured';

        return response()->json([
            'success' => true,
            'message' => "Product {$status} successfully.",
            'is_featured' => $product->is_featured
        ]);
    }

    /**
     * Delete product image.
     */
    public function deleteImage(ProductImage $image)
    {
        // Check if image belongs to product
        if ($image->product_id !== request('product_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return response()->json([
            'success' => true,
            'message' => 'Image deleted successfully.'
        ]);
    }

    /**
     * Set primary image.
     */
    public function setPrimaryImage(Product $product, ProductImage $image)
    {
        // Ensure image belongs to product
        if ($image->product_id !== $product->id) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized action.'
            ], 403);
        }

        $product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Primary image set successfully.'
        ]);
    }

    /**
     * Generate unique slug for product.
     */
    private function generateUniqueSlug($name, $productId = null)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (Product::where('slug', $slug)
            ->when($productId, function($query) use ($productId) {
                $query->where('id', '!=', $productId);
            })
            ->exists()) {
            $slug = $originalSlug . '-' . $counter++;
        }

        return $slug;
    }

    /**
     * Handle product image uploads.
     */
private function handleProductImages($product, $images)
{
    $currentImageCount = $product->images()->count();

    foreach ($images as $key => $image) {

        // Generate clean unique filename
        $filename = uniqid() . '_' . str_replace(' ', '_', $image->getClientOriginalName());

        // Destination path inside public/
        $destination = public_path('products');

        // Ensure directory exists
        if (!file_exists($destination)) {
            mkdir($destination, 0777, true);
        }

        // Move file
        $image->move($destination, $filename);

        // Save only the relative path
        $imagePath = 'products/' . $filename;

        // Save to DB
        $product->images()->create([
            'image_path'   => $imagePath,
            'is_primary'   => ($currentImageCount === 0 && $key === 0),
            'sort_order'   => $currentImageCount + $key,
            'alt_text'     => $product->name,
        ]);
    }
}


    /**
     * Reorder product images.
     */
    private function reorderImages($product, $imageOrder)
    {
        foreach ($imageOrder as $index => $imageId) {
            $product->images()
                ->where('id', $imageId)
                ->update(['sort_order' => $index]);
        }
    }

    /**
     * Bulk actions for products.
     */
    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete,feature,unfeature',
            'ids' => 'required|array',
            'ids.*' => 'exists:products,id'
        ]);

        $products = Product::whereIn('id', $request->ids)->get();

        switch ($request->action) {
            case 'activate':
                $products->each(function($product) {
                    $product->update([
                        'status' => 'active',
                        'published_at' => now()
                    ]);
                });
                $message = 'Selected products activated successfully.';
                break;

            case 'deactivate':
                $products->each->update(['status' => 'inactive']);
                $message = 'Selected products deactivated successfully.';
                break;

            case 'feature':
                $products->each->update(['is_featured' => true]);
                $message = 'Selected products featured successfully.';
                break;

            case 'unfeature':
                $products->each->update(['is_featured' => false]);
                $message = 'Selected products unfeatured successfully.';
                break;

            case 'delete':
                foreach ($products as $product) {
                    if ($product->vendors()->exists()) {
                        return response()->json([
                            'success' => false,
                            'message' => "Cannot delete product '{$product->name}' because it is being used by vendors."
                        ], 422);
                    }
                    $product->delete();
                }
                $message = 'Selected products deleted successfully.';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    public function show(Product $product, Vendor $vendor)
{
    $vendorProduct = VendorProduct::with('product', 'vendor.user')
        ->where('product_id', $product->id)
        ->where('vendor_id', $vendor->id)
        ->firstOrFail();

    return view('admin.vendor_products.show', compact('product', 'vendor', 'vendorProduct'));
}
}