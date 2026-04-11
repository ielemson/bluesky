<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Cart;
use App\Models\VendorProduct;

class CartController extends Controller
{
  

public function addAjax(Request $request)
{
    $request->validate([
        'product_id' => ['required', 'integer'],
        'name'       => ['required', 'string'],
        'price'      => ['required', 'numeric'],
        'slug'       => ['nullable', 'string'],
        'image'      => ['nullable', 'string'],
    ]);

    \Cart::add([
        'id'       => $request->product_id,
        'name'     => $request->name,
        'price'    => (float) $request->price,
        'quantity' => 1,
        'attributes' => [
            'product_id'   => $request->product_id,
            'image'        => $request->image,
            'product_slug' => $request->slug,
        ]
    ]);

    $cartItems = \Cart::getContent();
    $cartTotal = \Cart::getTotal();
    $cartCount = $cartItems->sum('quantity');

    $cartDropdownHtml = view('components.cart-dropdown', [
        'cartItems' => $cartItems,
        'cartTotal' => $cartTotal
    ])->render();

    return response()->json([
        'status'        => true,
        'message'       => 'Product added to cart!',
        'cart_count'    => $cartCount,
        'cart_total'    => $cartTotal,
        'cart_dropdown' => $cartDropdownHtml,
    ]);
}

public function loadDropdown()
{
    $cartItems = \Cart::getContent();
    $cartTotal = \Cart::getTotal();
    $cartCount = $cartItems->sum('quantity');

    $html = view('components.cart-dropdown', [
        'cartItems' => $cartItems,
        'cartTotal' => $cartTotal
    ])->render();

    return response()->json([
        'cart_count' => $cartCount,
        'cart_total' => $cartTotal,
        'cart_dropdown' => $html
    ]);
}

public function removeAjax(Request $request)
{
    $cartId = $request->cart_id;

    // Remove item from cart
    \Cart::remove($cartId);

    // Get updated cart details
    $cartItems = \Cart::getContent();
    $cartTotal = \Cart::getTotal();
    $cartCount = $cartItems->sum('quantity');

    // Render dropdown HTML
    $cartDropdownHtml = view('components.cart-dropdown', [
        'cartItems' => $cartItems,
        'cartTotal' => $cartTotal
    ])->render();

    return response()->json([
        'status' => true,
        'message' => 'Item removed from cart!',
        'cart_count' => $cartCount,
        'cart_total' => $cartTotal,
        'cart_dropdown' => $cartDropdownHtml
    ]);
}

// View full cart page :::::::::::::::::::::::::::::::::::
    public function viewCart()
    {
        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();
        return view('frontend.cart', compact('cartItems', 'cartTotal'));
    }

    // Helper to build cart response JSON
    private function cartResponse($message = null)
    {
        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();
        $cartCount = $cartItems->sum('quantity');

        $cartDropdownHtml = view('components.cart-dropdown', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal
        ])->render();

        return response()->json([
            'status' => true,
            'message' => $message,
            'cart_count' => $cartCount,
            'cart_total' => $cartTotal,
            'cart_dropdown' => $cartDropdownHtml
        ]);
    }

    public function updateQuantity(Request $request)
{
    $item = \Cart::get($request->id);

    if (!$item) {
        return response()->json(['status' => false, 'message' => 'Item not found']);
    }

    $newQty = max(1, intval($request->quantity));

    \Cart::update($request->id, [
        'quantity' => ['relative' => false, 'value' => $newQty]
    ]);

    $cartItems = \Cart::getContent();
    $cartTotal = \Cart::getTotal();
    $cartCount = $cartItems->sum('quantity');

    return response()->json([
        'status'   => true,
        'subtotal' => number_format($item->price * $newQty, 2),
        'cart_total' => number_format($cartTotal, 2),
        'cart_count' => $cartCount,
        'dropdown'  => view('components.cart-dropdown', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal
        ])->render()
    ]);
}

public function remove(Request $request)
{
    \Cart::remove($request->id);

    $cartItems = \Cart::getContent();
    $cartTotal = \Cart::getTotal();
    $cartCount = $cartItems->sum('quantity');

    return response()->json([
        'status' => true,
        'message' => 'Item removed',
        'cart_total' => number_format($cartTotal, 2),
        'cart_count' => $cartCount,
        'dropdown' => view('components.cart-dropdown', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal
        ])->render()
    ]);
}


}
