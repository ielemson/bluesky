<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class CustomerOrderController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = Order::query()
            ->with([
                'items.product.images',
                'vendor',
            ])
            ->where('user_id', $user->id);

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

        return view('customer.orders.index', compact('orders'));
    }

    public function show(Order $order)
    {
        $user = request()->user();

        abort_if($order->user_id !== $user->id, 403, 'Unauthorized access to this order.');

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

        return view('customer.orders.show', compact(
            'order',
            'subtotal',
            'shipping',
            'tax',
            'discount',
            'total'
        ));
    }
}
