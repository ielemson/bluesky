@if($cartItems->count())
    <ul>
        @foreach($cartItems as $item)
            @php
                $productSlug = $item->attributes->product_slug ?? null;
                $productUrl = $productSlug ? url('/product/' . $productSlug) : 'javascript:;';
                $image = $item->attributes->image ?? asset('assets/imgs/shop/thumbnail-1.jpg');
            @endphp

            <li>
                <div class="shopping-cart-img">
                    <a href="{{ $productUrl }}">
                        <img alt="{{ $item->name }}" src="{{ $image }}">
                    </a>
                </div>

                <div class="shopping-cart-title">
                    <h4>
                        <a href="{{ $productUrl }}">
                            {{ gtrans($item->name) }}
                        </a>
                    </h4>
                    <h4>
                        <span>{{ $item->quantity }} × </span>
                        ${{ number_format($item->price, 2) }}
                    </h4>
                </div>

                <div class="shopping-cart-delete">
                    <a href="javascript:;" class="item_remove" data-cart-id="{{ $item->id }}" title="{{ gtrans('Remove item') }}">
                        <i class="fi-rs-cross-small"></i>
                    </a>
                </div>
            </li>
        @endforeach
    </ul>

    <div class="shopping-cart-footer">
        <div class="shopping-cart-total">
            <h4>{{ gtrans('Total') }} <span class="mini_cart_total">${{ number_format($cartTotal, 2) }}</span></h4>
        </div>
        <div class="shopping-cart-button">
            <a href="{{ route('cart.view') }}" class="outline">{{ gtrans('View cart') }}</a>
            <a href="javascript:;">{{ gtrans('Checkout') }}</a>
        </div>
    </div>
@else
    <div class="shopping-cart-footer text-center">
        <p class="mb-2 text-muted">{{ gtrans('Your cart is empty.') }}</p>
        <a href="{{ url('/shop') }}" class="btn btn-sm">{{ gtrans('Continue Shopping') }}</a>
    </div>
@endif