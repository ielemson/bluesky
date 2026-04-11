<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Vendor;
use App\Models\VendorProduct;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
         try {
          // Total Users (all users except maybe admins if you track that)
            $totalUsers = User::count();

         // Total Vendors (users with is_vendor = true)
            $totalVendors = User::where('is_vendor', true)->count();
            
            // Total Approved Vendors (from vendors table with status = approved)
            $totalApprovedVendors = VendorProduct::where('is_active', 1)->count();
            
            // Total Pending Vendors
            $totalPendingVendors = VendorProduct::where('is_active', 0)->count();
            
            // Total Products (from main product database)
            $totalProducts = Product::count();
            
            // Total Active Products
            $totalActiveProducts = Product::where('status', 'active')->count();
            
            // Total Active Vendor Listings
            $totalVendorListings = VendorProduct::where('is_active', true)->count();
            
            // Total Vendor Listings with Stock
            $totalVendorListingsWithStock = VendorProduct::where('stock_quantity', '>', 0)->count();
            
            // Total Orders
            $totalOrders = Order::count();
            
            // Completed Orders (assuming 'delivered' means completed)
            $completedOrders = Order::where('order_status', 'delivered')->count();
            
            // Pending Orders
            $pendingOrders = Order::where('order_status', 'pending')->count();
            
            // Processing Orders
            $processingOrders = Order::where('order_status', 'processing')->count();
            
            // Total Revenue (sum of completed orders)
            $totalRevenue = Order::where('order_status', 'delivered')->sum('total_amount');
            
            // This Month Revenue
            $currentMonthRevenue = Order::where('order_status', 'delivered')
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount');
            
            // Today's Revenue
            $todayRevenue = Order::where('order_status', 'delivered')
                ->whereDate('created_at', today())
                ->sum('total_amount');
            
            // Recent Products (last 7 days)
            $recentProducts = Product::where('created_at', '>=', now()->subDays(7))->count();
            
            // Recent Orders (last 7 days)
            $recentOrders = Order::where('created_at', '>=', now()->subDays(7))->count();
            
            // Low Stock Vendor Products (less than or equal to 5)
            $lowStockProducts = VendorProduct::where('stock_quantity', '<=', 5)
                                            ->where('stock_quantity', '>', 0)
                                            ->count();
            
            // Out of Stock Vendor Products
            $outOfStockProducts = VendorProduct::where('stock_quantity', 0)->count();
            
            // Top Selling Products (based on orders - you might need an order_items table for this)
            // For now, we'll use vendor listings with most stock sold (if you track that)
            
            // Average Order Value
            $averageOrderValue = $completedOrders > 0 ? $totalRevenue / $completedOrders : 0;
        
              return view('admin.dashboard', compact(
                'totalUsers',
                'totalVendors',
                'totalApprovedVendors',
                'totalPendingVendors',
                'totalProducts',
                'totalActiveProducts',
                'totalVendorListings',
                'totalVendorListingsWithStock',
                'totalOrders',
                'completedOrders',
                'pendingOrders',
                'processingOrders',
                'totalRevenue',
                'currentMonthRevenue',
                'todayRevenue',
                'recentProducts',
                'recentOrders',
                'lowStockProducts',
                'outOfStockProducts',
                'averageOrderValue'
            ));


        }
         catch (\Exception $e) {
            \Log::error('Admin Dashboard Error: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            
            // Return default values in case of error
            return view('admin.dashboard.index', [
                'totalUsers' => 0,
                'totalVendors' => 0,
                'totalApprovedVendors' => 0,
                'totalPendingVendors' => 0,
                'totalProducts' => 0,
                'totalActiveProducts' => 0,
                'totalVendorListings' => 0,
                'totalVendorListingsWithStock' => 0,
                'totalOrders' => 0,
                'completedOrders' => 0,
                'pendingOrders' => 0,
                'processingOrders' => 0,
                'totalRevenue' => 0,
                'currentMonthRevenue' => 0,
                'todayRevenue' => 0,
                'recentProducts' => 0,
                'recentOrders' => 0,
                'lowStockProducts' => 0,
                'outOfStockProducts' => 0,
                'averageOrderValue' => 0,
            ]);
        }

        
    }

     /**
     * Get dashboard statistics for AJAX requests
     */
    public function getStats()
    {
        try {
            $stats = [
                'total_users' => User::count(),
                'total_vendors' => User::where('is_vendor', true)->count(),
                'total_products' => Product::count(),
                'total_orders' => Order::count(),
                'total_revenue' => Order::where('order_status', 'delivered')->sum('total_amount'),
                'pending_orders' => Order::where('order_status', 'pending')->count(),
                'today_revenue' => Order::where('order_status', 'delivered')
                    ->whereDate('created_at', today())
                    ->sum('total_amount'),
            ];
            
            return response()->json($stats);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch statistics'], 500);
        }
    }
    
    /**
     * Get recent activities for dashboard
     */
    public function getRecentActivities()
    {
        try {
            $recentOrders = Order::with('user')
                ->latest()
                ->take(5)
                ->get();
                
            $recentVendors = Vendor::with('user')
                ->where('status', 'pending')
                ->latest()
                ->take(5)
                ->get();
                
            $lowStockItems = VendorProduct::with(['product', 'vendor.user'])
                ->where('stock_quantity', '<=', 5)
                ->where('stock_quantity', '>', 0)
                ->orderBy('stock_quantity', 'asc')
                ->take(5)
                ->get();
                
            return response()->json([
                'recent_orders' => $recentOrders,
                'pending_vendors' => $recentVendors,
                'low_stock_items' => $lowStockItems
            ]);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Unable to fetch recent activities'], 500);
        }
    }
}
