<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use App\Models\OrderItem;
use App\Models\Vendor;
use App\Models\VendorProduct;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class AdminOrderController extends Controller
{


    public function index(Request $request)
    {
        $vendors = Vendor::orderBy('store_name')->get();

        $orders = Order::with(['vendor', 'creator', 'items'])
            ->where('order_source', Order::SOURCE_ADMIN)
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%")
                        ->orWhere('customer_email', 'like', "%{$search}%")
                        ->orWhere('customer_phone', 'like', "%{$search}%");
                });
            })
            ->when($request->vendor_id, fn($query, $vendorId) => $query->where('vendor_id', $vendorId))
            ->when($request->order_status, fn($query, $status) => $query->where('order_status', $status))
            ->when($request->payment_status, fn($query, $status) => $query->where('payment_status', $status))
            ->latest()
            ->paginate(20);

        return view('admin.orders.index', compact('orders', 'vendors'));
    }


    /**
     * Show the form for creating a new admin order
     */
    public function create()
    {
        $vendors = Vendor::query()
            ->orderBy('store_name')
            ->get();
        $categories = Category::query()
            ->orderBy('name')
            ->get();

        return view('admin.orders.create', compact('vendors', 'categories'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_address' => ['required', 'string'],
            'order_date' => ['required', 'date'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.vendor_product_id' => ['required', 'exists:vendor_products,id'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        try {
            $orderDate = Carbon::parse($validated['order_date'])->startOfDay();
            $today = now()->startOfDay();
            $maxAllowedDate = now()->addDays(14)->endOfDay();

            if ($orderDate->gt($maxAllowedDate)) {
                return back()
                    ->withErrors(['order_date' => 'Order date cannot be more than 14 days ahead.'])
                    ->withInput();
            }

            $isScheduled = $orderDate->gt($today);

            DB::beginTransaction();

            $order = Order::create([
                'order_number'     => $this->generateOrderNumber(),
                'vendor_id'        => $validated['vendor_id'],
                'customer_name'    => $validated['customer_name'],
                'customer_phone'   => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'order_date'       => $validated['order_date'],
                'scheduled_for'    => $isScheduled ? $orderDate : null,
                'released_at'      => $isScheduled ? null : now(),
                'is_scheduled'     => $isScheduled,
                'order_status'     => $isScheduled ? 'scheduled' : 'pending',
            ]);

            $grandTotal = 0;

            foreach ($validated['items'] as $item) {
                $vendorProduct = VendorProduct::with('product')
                    ->where('id', $item['vendor_product_id'])
                    ->where('vendor_id', $validated['vendor_id'])
                    ->firstOrFail();

                $quantity = (int) $item['quantity'];
                $price = (float) $item['price']; // or use $vendorProduct->price if you want DB price as source of truth
                $lineTotal = $quantity * $price;
                $grandTotal += $lineTotal;

                $order->items()->create([
                    'order_id'           => $order->id,
                    'product_id'         => $vendorProduct->product_id,
                    'vendor_id'          => $order->vendor_id,
                    'vendor_product_id'  => $vendorProduct->id,
                    'name'               => $vendorProduct->product->name ?? 'Product',
                    'price'              => $price,
                    'quantity'           => $quantity,
                    'total'              => $lineTotal,
                    'vendor_amount'      => $lineTotal,
                    'status'             => 'pending',
                ]);
            }

            $order->update([
                'total_amount' => $grandTotal,
            ]);

            DB::commit();

            return redirect()
                ->route('admin.orders.index')
                ->with(
                    'success',
                    $isScheduled
                        ? 'Order scheduled successfully.'
                        : 'Order submitted successfully.'
                );
        } catch (\Throwable $e) {
            DB::rollBack();

            Log::error('Admin order submission failed', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Order submission failed. ' . $e->getMessage());
        }
    }


public function getVendorProducts(Request $request, Vendor $vendor)
{
    $query = VendorProduct::with([
            'product.images',
            'product.category',
        ])
        ->where('vendor_id', $vendor->id)
        ->where('is_active', true)
        ->whereHas('product', function ($q) use ($request) {
            $q->where('status', 'active');

            if ($request->filled('search')) {
                $search = trim($request->search);

                $q->where(function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%");
                });
            }

            if ($request->filled('category_id')) {
                $q->where('category_id', $request->category_id);
            }
        });

    if ($request->filled('min_price')) {
        $query->where('vendor_price', '>=', (float) $request->min_price);
    }

    if ($request->filled('max_price')) {
        $query->where('vendor_price', '<=', (float) $request->max_price);
    }

    switch ($request->sort) {
        case 'price_low':
            $query->orderBy('vendor_price', 'asc');
            break;

        case 'price_high':
            $query->orderBy('vendor_price', 'desc');
            break;

        case 'oldest':
            $query->orderBy('id', 'asc');
            break;

        default:
            $query->orderByDesc('id');
            break;
    }

    $vendorProducts = $query->paginate(10);

    $products = $vendorProducts->getCollection()->map(function ($vendorProduct) {
        $product = $vendorProduct->product;
        $firstImage = $product?->images?->first();

        return [
            'vendor_product_id' => $vendorProduct->id,
            'product_id' => $vendorProduct->product_id,
            'name' => $product?->name ?? 'Unnamed Product',
            'sku' => $product?->sku ?? '',
            'price' => (float) ($vendorProduct->vendor_price ?? 0),
            'stock_quantity' => (int) ($vendorProduct->stock_quantity ?? 0),
            'image' => $firstImage ? asset($firstImage->image_path) : null,
            'category' => $product?->category?->name,
            'is_active' => (bool) $vendorProduct->is_active,
        ];
    })->values();

    return response()->json([
        'success' => true,
        'vendor' => [
            'id' => $vendor->id,
            'user_id' => $vendor->user_id,
            'store_name' => $vendor->store_name,
        ],
        'products' => $products,
        'pagination' => [
            'current_page' => $vendorProducts->currentPage(),
            'last_page' => $vendorProducts->lastPage(),
            'per_page' => $vendorProducts->perPage(),
            'total' => $vendorProducts->total(),
            'from' => $vendorProducts->firstItem(),
            'to' => $vendorProducts->lastItem(),
        ],
    ]);
}

    protected function generateOrderNumber(): string
    {
        do {
            $orderNumber = 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (Order::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }


    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'vendor_id' => ['required', 'exists:vendors,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
            'customer_address' => ['required', 'string'],
            'order_date' => ['required', 'date'],

            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.price' => ['required', 'numeric', 'min:0'],
        ]);

        $orderDate = Carbon::parse($validated['order_date'])->startOfDay();
        $today = now()->startOfDay();
        $maxAllowedDate = now()->addDays(14)->endOfDay();

        if ($orderDate->gt($maxAllowedDate)) {
            return back()
                ->withErrors([
                    'order_date' => 'Order date cannot be more than 14 days ahead.'
                ])
                ->withInput();
        }

        $isScheduled = $orderDate->gt($today);

        DB::transaction(function () use ($validated, $order, $orderDate, $isScheduled) {

            $newStatus = $isScheduled ? 'scheduled' : 'pending';

            $scheduledFor = $isScheduled ? $orderDate : null;

            // if it is immediate, mark as released if not previously released
            $releasedAt = $isScheduled ? null : ($order->released_at ?? now());

            /*
|--------------------------------------------------------------------------
| Update main order record
|--------------------------------------------------------------------------
*/
            $order->update([
                'vendor_id'        => $validated['vendor_id'],
                'customer_name'    => $validated['customer_name'],
                'customer_phone'   => $validated['customer_phone'],
                'customer_address' => $validated['customer_address'],
                'order_date'       => $validated['order_date'],
                'scheduled_for'    => $scheduledFor,
                'released_at'      => $releasedAt,
                'is_scheduled'     => $isScheduled,
                'order_status'     => $newStatus,
            ]);

            /*
|--------------------------------------------------------------------------
| Rebuild order items
|--------------------------------------------------------------------------
|
| Since admin may have changed quantities/products/prices,
| the safest clean approach is:
| 1. delete old items
| 2. recreate from submitted payload
|
*/
            $order->items()->delete();

            $grandTotal = 0;

            foreach ($validated['items'] as $item) {
                $lineTotal = $item['quantity'] * $item['price'];
                $grandTotal += $lineTotal;

                $order->items()->create([
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'total'      => $lineTotal,
                ]);
            }

            /*
|--------------------------------------------------------------------------
| Update final totals
|--------------------------------------------------------------------------
*/
            $order->update([
                'total_amount' => $grandTotal,
            ]);
        });

        return redirect()
            ->route('admin.orders.index')
            ->with(
                'success',
                $isScheduled
                    ? 'Order updated and scheduled successfully.'
                    : 'Order updated successfully.'
            );
    }
    /**
     * Display the specified admin order
     */
    public function show(Order $order)
    {
        $order->load([
            'vendor',
            'creator',
            'user',
            'items.product',
            'items.vendor',
            'items.vendorProduct',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Generate a unique order number
     */
}
