<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\VendorWalletTransaction;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function index()
    {
        // Pull cart content
        $cartItems = \Cart::getContent();
        $cartTotal = \Cart::getTotal();
        $cartCount = $cartItems->sum('quantity');

        // If cart is empty, redirect back
        if ($cartCount < 1) {
            return redirect()->route('home')
                ->with('error', 'Your cart is empty.');
        }

        return view('frontend.checkout', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
            'cartCount' => $cartCount,
        ]);
    }


   public function placeOrder(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'customer_phone' => 'required|string',
            'customer_address' => 'required|string',
            'shipping_address' => 'required|string',
        ]);

        $cartItems = Cart::getContent();
        if($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty!');
        }

        $order = Order::create([
            'order_number' => 'ORD'.time(),
            'user_id' => auth()->id(),
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'customer_address' => $request->customer_address,
            'shipping_address' => $request->shipping_address,
            'subtotal' => Cart::getSubTotal(),
            'shipping_cost' => 0,
            'tax_amount' => 0,
            'discount_amount' => 0,
            'total_amount' => Cart::getTotal(),
            'payment_method' => 'bitcoin',
            'payment_status' => 'pending',
            'order_status' => 'pending',
            'ordered_at' => now()
        ]);

        foreach($cartItems as $item) {
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'vendor_product_id' => $item->id,
                'product_id' => $item->attributes->product_id,
                'vendor_id' => $item->attributes->vendor_id,
                'product_name' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'total_price' => $item->price * $item->quantity
            ]);

            // create vendor wallet transaction as pending
            VendorTransaction::create([
                'vendor_id' => $item->attributes->vendor_id,
                'order_id' => $order->id,
                'amount' => $item->price * $item->quantity,
                'status' => 'pending',
                'transaction_type' => 'credit',
                'notes' => 'Order pending approval'
            ]);
        }

        // clear cart
        Cart::clear();

        return redirect()->route('checkout.success', $order->id)
                         ->with('success', 'Order placed successfully! Awaiting payment confirmation.');
    }
}
