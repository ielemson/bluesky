<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VendorOrderController extends Controller
{
    public function index(Request $request)
    {
        $vendor = Auth::user()->vendor ?? null;

        abort_if(!$vendor, 403, 'Vendor account not found.');

        $query = Order::query()
            ->with([
                'items.product.images',
                'vendor',
            ])
            ->where('vendor_id', $vendor->id)
            ->where(function ($q) {
                $q->where('is_scheduled', 0)
                  ->orWhereNotNull('released_at');
            });

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%")
                    ->orWhere('customer_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(12)->withQueryString();

        return view('vendor.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $vendor = Auth::user()->vendor ?? null;

        abort_if(!$vendor, 403, 'Vendor account not found.');
        abort_if($order->vendor_id !== $vendor->id, 403, 'Unauthorized access to this order.');

        $order->load([
            'vendor',
            'items.product.images',
            'items.product.category',
        ]);

        $subtotal = $order->items->sum(function ($item) {
            return (float) $item->price * (int) $item->quantity;
        });

        $shipping = (float) ($order->shipping_cost ?? 0);
        $tax = (float) ($order->tax_amount ?? 0);
        $discount = (float) ($order->discount_amount ?? 0);
        $total = $subtotal + $shipping + $tax - $discount;

        return view('vendor.orders.show', compact(
            'order',
            'subtotal',
            'shipping',
            'tax',
            'discount',
            'total'
        ));
    }
}
