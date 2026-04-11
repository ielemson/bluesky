<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\VendorProduct;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorProductController extends Controller
{
    /**
     * Display products available for vendors to add to their listing
     */
    public function index(Request $request)
    {
        // Get the selected category from request or default to all
        $categoryId = $request->get('category_id');

        // Start with products available for vendors
        $products = Product::where('is_available_for_vendors', true)
            ->where('status', 'active') // Assuming you have a status field
            ->with(['images' => function ($query) {
                $query->where('is_primary', true)->orWhere('sort_order', 0);
            }]);

        // Filter by category if selected
        if ($categoryId) {
            $products->where('category_id', $categoryId);
        }

        $products = $products->paginate(12);

        // Get all active categories for filter
        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view("customer.products.addlisting", compact('products', 'categories', 'categoryId'));
    }

    /**
     * Show products by category
     */
    public function byCategory(Category $category)
    {
        $products = Product::where('is_available_for_vendors', true)
            ->where('category_id', $category->id)
            ->where('status', 'active')
            ->with(['images' => function ($query) {
                $query->where('is_primary', true)->orWhere('sort_order', 0);
            }])
            ->paginate(12);

        $categories = Category::where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('customer.products.index', compact('products', 'categories', 'category'));
    }

    /**
     * Add product to vendor's listing
     */
    // public function addToListing(Request $request)
    // {
    //     $request->validate([
    //         'product_id' => 'required|exists:products,id'
    //     ]);

    //     try {
    //         $product = Product::findOrFail($request->product_id);
    //         $vendorId = Auth::id();

    //         // Check if product is available for vendors
    //         if (!$product->is_available_for_vendors) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'This product is not available for vendor listing.'
    //             ], 403);
    //         }

    //         // Check if vendor already has this product in their listing
    //         $existingListing = VendorProduct::where('vendor_id', $vendorId)
    //             ->where('product_id', $product->id)
    //             ->first();

    //         if ($existingListing) {
    //             return response()->json([
    //                 'success' => false,
    //                 'message' => 'This product is already in your listing.'
    //             ], 409);
    //         }

    //         // Create vendor listing
    //         VendorProduct::create([
    //             'vendor_id' => $vendorId,
    //             'product_id' => $product->id,
    //             'vendor_price' => $product->price, // Default to product price, vendor can change later
    //             'stock_quantity' => 0, // Vendor starts with 0 stock
    //             'is_active' => true
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Product added to your listing successfully!',
    //             'product_name' => $product->name
    //         ]);
    //     } catch (\Exception $e) {
    //         \Log::error('Error adding product to vendor listing: ' . $e->getMessage());

    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error adding product to listing. Please try again.'
    //         ], 500);
    //     }
    // }

    public function addToListing(Request $request)
{
    $request->validate([
        'product_id' => 'required|exists:products,id'
    ]);

    try {
        $product = Product::findOrFail($request->product_id);

        $vendor = Vendor::where('user_id', Auth::id())->first();

        if (!$vendor) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor profile not found for this user.'
            ], 404);
        }

        // Check if product is available for vendors
        if (!$product->is_available_for_vendors) {
            return response()->json([
                'success' => false,
                'message' => 'This product is not available for vendor listing.'
            ], 403);
        }

        // Check if vendor already has this product in their listing
        $existingListing = VendorProduct::where('vendor_id', $vendor->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingListing) {
            return response()->json([
                'success' => false,
                'message' => 'This product is already in your listing.'
            ], 409);
        }

        // Create vendor listing
        VendorProduct::create([
            'vendor_id' => $vendor->id,
            'product_id' => $product->id,
            'vendor_price' => $product->price,
            'stock_quantity' => $product->stock_quantity,
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Product added to your listing successfully!',
            'product_name' => $product->name
        ]);
    } catch (\Exception $e) {
        \Log::error('Error adding product to vendor listing', [
            'message' => $e->getMessage(),
            'user_id' => Auth::id(),
            'product_id' => $request->product_id
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Error adding product to listing. Please try again.'
        ], 500);
    }
}

    /**
     * Display vendor's own listings
     */
   public function myListings(Request $request)
{
    $vendor = Vendor::where('user_id', Auth::id())->first();

    if (!$vendor) {
        return redirect()->back()->with('error', 'Vendor profile not found.');
    }

    $listings = VendorProduct::where('vendor_id', $vendor->id)
        ->with([
            'product' => function ($query) {
                $query->with([
                    'images' => function ($imgQuery) {
                        $imgQuery->where('is_primary', true)
                            ->orWhere('sort_order', 0)
                            ->orderBy('is_primary', 'desc')
                            ->orderBy('sort_order', 'asc');
                    },
                    'category'
                ]);
            }
        ])
        ->latest()
        ->paginate(12);

    $categories = Category::where('is_active', true)
        ->orderBy('name')
        ->get();

    return view('customer.products.productlisting', compact('listings', 'categories'));
}

    /**
     * Remove a product from vendor's listing
     */
    public function removeFromListing($id)
    {
        try {
            $listing = VendorProduct::where('vendor_id', Auth::id())
                ->where('id', $id)
                ->firstOrFail();

            $productName = $listing->product->name;
            $listing->delete();

            return response()->json([
                'success' => true,
                'message' => '"' . $productName . '" has been removed from your listing.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error removing product from listing: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update vendor listing (price, stock, status)
     */
    // public function updateListing(Request $request, $id)
    // {
    //     $request->validate([
    //         'vendor_price' => 'required|numeric|min:0',
    //         'stock_quantity' => 'required|integer|min:0',
    //         'is_active' => 'boolean'
    //     ]);

    //     try {
    //         $listing = VendorProduct::where('vendor_id', Auth::id())
    //             ->where('id', $id)
    //             ->firstOrFail();

    //         $listing->update([
    //             'vendor_price' => $request->vendor_price,
    //             'stock_quantity' => $request->stock_quantity,
    //             'is_active' => $request->is_active ?? true
    //         ]);

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Listing updated successfully!'
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Error updating listing: ' . $e->getMessage()
    //         ], 500);
    //     }
    // }


// public function updateListing(Request $request, $id)
// {
//     // Validate incoming data
//     $request->validate([
//         'vendor_price'   => 'required|numeric|min:0',
//         'stock_quantity' => 'required|integer|min:0',
//         'is_active'      => 'required|in:0,1',
//     ]);

//     // Get listing owned by this vendor
//     $listing = VendorProduct::where('id', $id)
//         ->where('vendor_id', auth()->id())
//         ->first();

//     if (!$listing) {
//         return response()->json([
//             'success' => false,
//             'message' => 'Listing not found or not authorized.'
//         ], 404);
//     }

//     // Update listing
//     $listing->update([
//         'vendor_price'   => $request->vendor_price,
//         'stock_quantity' => $request->stock_quantity,
//         'is_active'      => $request->is_active,
//     ]);

//     return response()->json([
//         'success' => true,
//         'message' => 'Listing updated successfully.',
//         'data'    => $listing
//     ]);
// }


  /**
     * Update vendor listing (price, stock, status)
     */
    // public function updateListing(Request $request, $id)
    // {
    //     // Validation
    //     $validated = $request->validate([
    //         'vendor_price'   => 'required|numeric|min:0.01',
    //         'stock_quantity' => 'required|numeric|min:0',
    //         'is_active'      => 'required|in:0,1',
    //     ]);

    //     $listing = VendorProduct::findOrFail($id);

    //     // Update fields
    //     $listing->vendor_price   = $validated['vendor_price'];
    //     $listing->stock_quantity = $validated['stock_quantity'];
    //     $listing->is_active      = $validated['is_active'];
    //     $listing->save();

    //     return response()->json([
    //         "success" => true,
    //         "message" => "Vendor listing updated successfully.",
    //         "listing" => $listing
    //     ]);
    // }


    /**
     * Toggle Active / Inactive
     */
    // public function updateStatus(Request $request, $id)
    // {
    //     $request->validate([
    //         'is_active' => 'required|in:0,1'
    //     ]);

    //     $listing = VendorProduct::findOrFail($id);
    //     $listing->is_active = $request->is_active;
    //     $listing->save();

    //     return response()->json([
    //         "success" => true,
    //         "message" => $listing->is_active ? "Listing activated." : "Listing deactivated.",
    //         "status"  => $listing->is_active
    //     ]);
    // }

    public function removeListing(Request $request, $id)
{
    // Ensure listing belongs to this vendor
    $listing = VendorProduct::where('id', $id)
        ->where('vendor_id', auth()->id())
        ->first();

    if (!$listing) {
        return response()->json([
            'success' => false,
            'message' => 'Listing not found or not authorized.',
        ], 404);
    }

    // Either delete or detach from vendor listing table, depending on your structure
    $listing->delete();

    return response()->json([
        'success' => true,
        'message' => 'Product removed from your listing successfully.',
    ]);
}
}
