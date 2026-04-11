<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use App\Models\VendorProduct;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index()
{
    $hotSellingProducts = Product::with(['images', 'category'])
        ->active()
        ->where('is_hot_selling', true)
        ->latest()
        ->take(12)
        ->get();

    if ($hotSellingProducts->count() < 12) {
        $existingIds = $hotSellingProducts->pluck('id');

        $extraHotProducts = Product::with(['images', 'category'])
            ->active()
            ->whereNotIn('id', $existingIds)
            ->latest()
            ->take(12 - $hotSellingProducts->count())
            ->get();

        $hotSellingProducts = $hotSellingProducts->concat($extraHotProducts);
    }

    $newArrivalProducts = Product::with(['images', 'category'])
        ->active()
        ->where('is_new_arrival', true)
        ->latest()
        ->take(12)
        ->get();

    if ($newArrivalProducts->count() < 12) {
        $existingIds = $newArrivalProducts->pluck('id');

        $extraNewArrivals = Product::with(['images', 'category'])
            ->active()
            ->whereNotIn('id', $existingIds)
            ->latest()
            ->take(12 - $newArrivalProducts->count())
            ->get();

        $newArrivalProducts = $newArrivalProducts->concat($extraNewArrivals);
    }

    $categories = Category::active()
        ->root()
        ->with(['children' => fn ($q) => $q->active()->withCount('products')])
        ->withCount('products')
        ->orderBy('name')
        ->get();

    $featuredCategories = Category::active()
        ->featured()
        ->withCount('products')
        ->orderBy('name')
        ->take(8)
        ->get();

    $topCategories = Category::active()
        ->whereNull('parent_id')
        ->orderBy('lft')
        ->limit(8)
        ->get();

    return view('frontend.home', compact(
        'hotSellingProducts',
        'newArrivalProducts',
        'categories',
        'featuredCategories',
        'topCategories'
    ));
}

    /**
     * Helper: Get vendor products by attribute
     */
    private function getVendorProductsByAttribute(string $attribute, int $limit = 12)
    {
        return VendorProduct::with([
            'product.images' => fn($q) => $q->where('is_primary', true)->orWhere('sort_order', 0),
            'vendor'
        ])
            ->where('is_active', true)
            ->whereHas(
                'product',
                fn($q) =>
                $q->where($attribute, true)
                    ->where('status', 'active')
                    ->where('published_at', '<=', now())
            )
            ->whereHas(
                'vendor',
                fn($q) =>
                $q->where('status', 'approved') // Only approved vendors
            )
            ->latest()
            ->limit($limit)
            ->get();
    }


    /**
     * Single product page for a specific vendor
     */
    // public function product(string $slug)
    // {
    //     $vendorProduct = VendorProduct::query()
    //         ->where('vendor_id', $vendor)
    //         ->whereHas('product', fn($q) => $q->where('slug', $slug))
    //         ->with([
    //             'product',
    //             'product.images' => fn($q) => $q->orderByDesc('is_primary')->orderBy('sort_order')
    //         ])
    //         ->firstOrFail();

    //     $product = $vendorProduct->product;

    //     return view('frontend.product', compact('product', 'vendorProduct'));
    // }

    /**
     * Shop page with filters
     */
    // public function shop(Request $request)
    // {
    //     $vendorProducts = VendorProduct::with(['product.images', 'vendor'])
    //         ->where('is_active', 1)
    //         ->whereHas('product', fn($q) => $q->where('status', 'active'))
    //         ->whereHas('vendor', fn($q) => $q->where('status', 'approved'))
    //         ->paginate(12);

    //     // Categories for sidebar or filter
    //     $categories = Category::active()
    //         ->root()
    //         ->with(['children' => fn($q) => $q->active()->withCount('products')])
    //         ->withCount('products')
    //         ->orderBy('name')
    //         ->get();

    //     return view('frontend.products', compact('vendorProducts', 'categories'));
    // }

    public function product(string $slug)
{
    $product = Product::query()
        ->with([
            'category',
            'images' => fn ($q) => $q->orderByDesc('is_primary')->orderBy('sort_order'),
        ])
        ->active()
        ->where('slug', $slug)
        ->firstOrFail();

    return view('frontend.product', compact('product'));
}

    public function shop(Request $request, $categorySlug = null)
{
    $categories = Category::active()
        ->root()
        ->with(['children' => fn ($q) => $q->active()->withCount('products')])
        ->withCount('products')
        ->orderBy('name')
        ->get();

    $selectedCategory = null;

    $productsQuery = Product::with(['images', 'category'])
        ->active()
        ->latest();

    if ($categorySlug) {
        $selectedCategory = Category::active()
            ->where('slug', $categorySlug)
            ->firstOrFail();

        $categoryIds = Category::where('id', $selectedCategory->id)
            ->orWhere('parent_id', $selectedCategory->id)
            ->pluck('id');

        $productsQuery->whereIn('category_id', $categoryIds);
    }

    $products = $productsQuery->paginate(12)->withQueryString();

    return view('frontend.products', compact(
        'products',
        'categories',
        'selectedCategory'
    ));
}
}