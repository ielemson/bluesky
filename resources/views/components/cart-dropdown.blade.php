@if($cartItems->count())
    <ul>
        @foreach($cartItems as $item)
            <li>
                <div class="shopping-cart-img">
                    <a href="javascript:;">
                        <img
                            alt="{{ $item->name }}"
                            src="{{ $item->attributes->image ?? asset('assets/imgs/shop/thumbnail-1.jpg') }}">
                    </a>
                </div>

                <div class="shopping-cart-title">
                    <h4>
                        <a href="javascript:;">
                            {{ $item->name }}
                        </a>
                    </h4>
                    <h4>
                        <span>{{ $item->quantity }} × </span>
                        ${{ number_format($item->price, 2) }}
                    </h4>
                </div>

                <div class="shopping-cart-delete">
                    <a href="javascript:;" class="item_remove" data-cart-id="{{ $item->id }}">
                        <i class="fi-rs-cross-small"></i>
                    </a>
                </div>
            </li>
        @endforeach
    </ul>

    <div class="shopping-cart-footer">
        <div class="shopping-cart-total">
            <h4>Total <span>${{ number_format($cartTotal, 2) }}</span></h4>
        </div>
        <div class="shopping-cart-button">
            <a href="{{ route('cart.view') }}" class="outline">View cart</a>
            <a href="{{ route('checkout') }}">Checkout</a>
        </div>
    </div>
@else
    <div class="shopping-cart-footer">
        <p class="mb-0 text-muted">Your cart is empty.</p>
    </div>
@endif