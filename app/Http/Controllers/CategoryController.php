<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;


class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = Category::with(['parent', 'children', 'products'])
            ->root()
            ->orderBy('name')
            ->get();
            
        return view('admin.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $parentCategories = Category::root()->active()->get();
        return view('admin.category.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'parent_id' => 'nullable|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string'
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('categories', 'public');
        }

        // Set default values
        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(Category $category)
    {
        $parentCategories = Category::root()->active()
            ->where('id', '!=', $category->id)
            ->get();
            
        return view('admin.category.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category in storage.
     */

public function update(Request $request, Category $category)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
        'parent_id' => 'nullable|exists:categories,id',
        'description' => 'nullable|string',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        'is_active' => 'nullable|boolean',
        'is_featured' => 'nullable|boolean',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string',
    ]);

    $validated['slug'] = Str::slug($validated['name']);
    $validated['is_active'] = $request->has('is_active');
    $validated['is_featured'] = $request->has('is_featured');

    if ($request->hasFile('image')) {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $validated['image'] = $request->file('image')->store('categories', 'public');
    }

    $category->update($validated);

    return redirect()
        ->route('admin.categories.index')
        ->with('success', 'Category updated successfully.');
}

    /**
     * Remove the specified category from storage.
     */
    public function destroy(Category $category)
    {
        // Check if category has products
        if ($category->products()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with associated products.');
        }

        // Check if category has children
        if ($category->children()->exists()) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with subcategories. Please delete subcategories first.');
        }

        // Delete image
        if ($category->image) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Category deleted successfully.');
    }

    /**
     * Update category status (Active/Inactive)
     */
    public function updateStatus(Category $category)
    {
        $category->update([
            'is_active' => !$category->is_active
        ]);

        $status = $category->is_active ? 'activated' : 'deactivated';

        return response()->json([
            'success' => true,
            'message' => "Category {$status} successfully.",
            'is_active' => $category->is_active
        ]);
    }

    /**
     * Get subcategories for a category (AJAX)
     */
    public function getSubcategories($id)
    {
        $category = Category::with('children')->findOrFail($id);
        
        $html = '';
        if ($category->children->count() > 0) {
            $html .= '<ul class="list-group">';
            foreach ($category->children as $subcategory) {
                $html .= '
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>' . $subcategory->name . '</strong>
                        <br>
                        <small class="text-muted">' . ($subcategory->description ? Str::limit($subcategory->description, 100) : 'No description') . '</small>
                    </div>
                    <div>
                        <span class="badge badge-primary mr-2">' . ($subcategory->products->count() ?? 0) . ' products</span>
                        <a href="' . route('admin.categories.edit', $subcategory->id) . '" class="btn btn-sm btn-info">
                            <i class="fas fa-edit"></i>
                        </a>
                    </div>
                </li>';
            }
            $html .= '</ul>';
        } else {
            $html = '<div class="alert alert-info">No subcategories found for this category.</div>';
        }
        
        return $html;
    }

    /**
     * Get categories for dropdown (AJAX)
     */
    public function getDropdownList()
    {
        $categories = Category::active()
            ->root()
            ->with('children')
            ->orderBy('name')
            ->get();

        $data = [];
        
        foreach ($categories as $category) {
            $data[] = [
                'id' => $category->id,
                'text' => $category->name,
                'children' => $category->children->map(function ($child) {
                    return [
                        'id' => $child->id,
                        'text' => '-- ' . $child->name
                    ];
                })->toArray()
            ];
        }

        return response()->json($data);
    }

    /**
     * Handle bulk actions
     */
    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'ids' => 'required|array',
            'ids.*' => 'exists:categories,id'
        ]);

        $categories = Category::whereIn('id', $request->ids)->get();

        switch ($request->action) {
            case 'activate':
                $categories->each->update(['is_active' => true]);
                $message = 'Selected categories activated successfully.';
                break;

            case 'deactivate':
                $categories->each->update(['is_active' => false]);
                $message = 'Selected categories deactivated successfully.';
                break;

            case 'delete':
                foreach ($categories as $category) {
                    // Check if category can be deleted
                    if ($category->products()->exists() || $category->children()->exists()) {
                        return response()->json([
                            'success' => false,
                            'message' => "Cannot delete category '{$category->name}' because it has associated products or subcategories."
                        ], 422);
                    }
                    
                    // Delete image
                    if ($category->image) {
                        Storage::disk('public')->delete($category->image);
                    }
                    
                    $category->delete();
                }
                $message = 'Selected categories deleted successfully.';
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message
        ]);
    }

    /**
     * API endpoint for categories (for AJAX calls)
     */
    public function apiIndex()
    {
        $categories = Category::active()
            ->with('children')
            ->root()
            ->orderBy('name')
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->name,
                    'slug' => $category->slug,
                    'image' => $category->image ? asset('storage/' . $category->image) : null,
                    'children' => $category->children->map(function ($child) {
                        return [
                            'id' => $child->id,
                            'name' => $child->name,
                            'slug' => $child->slug,
                            'image' => $child->image ? asset('storage/' . $child->image) : null,
                        ];
                    })
                ];
            });

        return response()->json($categories);
    }

    /**
     * API endpoint for single category
     */
    public function apiShow(Category $category)
    {
        return response()->json([
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'image' => $category->image ? asset('storage/' . $category->image) : null,
            'is_active' => $category->is_active,
            'is_featured' => $category->is_featured,
            'parent' => $category->parent ? [
                'id' => $category->parent->id,
                'name' => $category->parent->name
            ] : null,
            'products_count' => $category->products->count(),
            'children_count' => $category->children->count()
        ]);
    }

      /**
     * Get categories for navigation
     */
    public function getNavigationCategories()
    {
        // Get main categories (level 1) with their children
        $categories = Category::active()
            ->root()
            ->with(['children' => function($query) {
                $query->active()->withCount('products');
            }])
            ->withCount('products')
            ->orderBy('name')
            ->get();

        return $categories;
    }

    /**
     * Get featured categories for mega menu
     */
    public function getFeaturedCategories()
    {
        return Category::active()
            ->featured()
            ->withCount('products')
            ->orderBy('name')
            ->take(8)
            ->get();
    }

}
