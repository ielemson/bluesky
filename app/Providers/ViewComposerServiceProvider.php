<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Category;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Share categories with all views
        View::composer('*', function ($view) {
            $categories = Category::active()
                ->root()
                ->with(['children' => function($query) {
                    $query->active()->withCount('products');
                }])
                ->withCount('products')
                ->orderBy('name')
                ->get();

            $featuredCategories = Category::active()
                ->featured()
                ->withCount('products')
                ->orderBy('name')
                ->take(8)
                ->get();

            $view->with([
                'navigationCategories' => $categories,
                'featuredCategories' => $featuredCategories
            ]);
        });
    }
}