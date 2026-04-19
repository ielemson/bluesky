<?php

namespace App\Http\Controllers;
  use App\Models\Product;
use Illuminate\Http\Request;
use Cart;
class CartController extends Controller
{


public function addAjax(Request $request)
    {
        $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'quantity'   => ['nullable', 'integer', 'min:1'],
        ]);

        $qty = max(1, (int) $request->input('quantity', 1));

        $product = Product::with('images')->find($request->product_id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found.',
            ], 404);
        }

        if (isset($product->status) && $product->status !== 'active') {
            return response()->json([
                'status' => false,
                'message' => 'This product is not available.',
            ], 422);
        }

        if ((int) $product->stock_quantity < 1) {
            return response()->json([
                'status' => false,
                'message' => 'This item is out of stock.',
            ], 422);
        }

        $existingItem = Cart::get($product->id);
        $newQty = $existingItem ? ($existingItem->quantity + $qty) : $qty;

        if ($newQty > (int) $product->stock_quantity) {
            return response()->json([
                'status' => false,
                'message' => 'Requested quantity exceeds available stock.',
            ], 422);
        }

        $primaryImage = $product->images?->where('is_primary', true)->first() ?? $product->images?->first();
        $fallbackImage = asset('assets/imgs/shop/product-placeholder.jpg');
        $image = $primaryImage
            ? (\Illuminate\Support\Str::startsWith($primaryImage->image_path, ['http://', 'https://'])
                ? $primaryImage->image_path
                : asset($primaryImage->image_path))
            : $fallbackImage;

        if ($existingItem) {
            Cart::update($product->id, [
                'quantity' => [
                    'relative' => false,
                    'value' => $newQty,
                ],
            ]);

            return $this->cartResponse('Cart quantity updated.');
        }

        Cart::add([
            'id'       => $product->id,
            'name'     => $product->name,
            'price'    => (float) $product->price,
            'quantity' => $qty,
            'attributes' => [
                'product_id'     => $product->id,
                'product_slug'   => $product->slug,
                'image'          => $image,
                'stock_quantity' => (int) $product->stock_quantity,
            ],
        ]);

        return $this->cartResponse('Product added to cart!');
    }

    public function loadDropdown()
    {
        return $this->cartResponse();
    }

    public function summary()
    {
        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();
        $cartCount = $cartItems->sum('quantity');

        return response()->json([
            'status' => true,
            'cart_count' => $cartCount,
            'cart_total' => number_format($cartTotal, 2),
        ]);
    }

    public function removeAjax(Request $request)
    {
        $request->validate([
            'cart_id' => ['required'],
        ]);

        Cart::remove($request->cart_id);

        return $this->cartResponse('Item removed from cart!');
    }

    public function updateQuantity(Request $request)
    {
        $request->validate([
            'id'       => ['required'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $item = Cart::get($request->id);

        if (!$item) {
            return response()->json([
                'status' => false,
                'message' => 'Item not found.',
            ], 404);
        }

        $stock = (int) ($item->attributes->stock_quantity ?? 0);
        $newQty = max(1, (int) $request->quantity);

        if ($stock > 0 && $newQty > $stock) {
            return response()->json([
                'status' => false,
                'message' => 'Requested quantity exceeds available stock.',
            ], 422);
        }

        Cart::update($request->id, [
            'quantity' => [
                'relative' => false,
                'value' => $newQty,
            ],
        ]);

        $updatedItem = Cart::get($request->id);
        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();
        $cartCount = $cartItems->sum('quantity');

        return response()->json([
            'status' => true,
            'message' => 'Cart updated.',
            'subtotal' => number_format($updatedItem->price * $updatedItem->quantity, 2),
            'cart_total' => number_format($cartTotal, 2),
            'cart_count' => $cartCount,
            'cart_dropdown' => $this->renderCartDropdown($cartItems, $cartTotal),
        ]);
    }

    public function viewCart()
    {
        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();

        return view('frontend.cart', compact('cartItems', 'cartTotal'));
    }

    private function cartResponse(?string $message = null)
    {
        $cartItems = Cart::getContent();
        $cartTotal = Cart::getTotal();
        $cartCount = $cartItems->sum('quantity');

        return response()->json([
            'status' => true,
            'message' => $message,
            'cart_count' => $cartCount,
            'cart_total' => number_format($cartTotal, 2),
            'cart_dropdown' => $this->renderCartDropdown($cartItems, $cartTotal),
        ]);
    }

    private function renderCartDropdown($cartItems, $cartTotal): string
    {
        return view('components.cart-dropdown', [
            'cartItems' => $cartItems,
            'cartTotal' => $cartTotal,
        ])->render();
    }

// public function checkout()
//     {
//         $cartItems = Cart::getContent();
//         $cartTotal = Cart::getTotal();

//         if ($cartItems->count() < 1) {
//             return redirect()->route('cart.view')
//                 ->with('error', 'Your cart is empty.');
//         }

//         return view('frontend.checkout', compact('cartItems', 'cartTotal'));
//     }
}
