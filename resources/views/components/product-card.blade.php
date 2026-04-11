@php
    $primaryImage = $product->images->where('is_primary', true)->first() ?? $product->images->first();
    $hoverImage = $product->images->where('is_primary', false)->first();
@endphp

<div class="item">
    <div class="product_wrap">
        <div class="product_img">
            <a href="{{ route('page.products.show', ['slug' => $product->slug]) }}">

                @if ($primaryImage)
                    <img src="{{ asset($primaryImage->image_path) }}"
                        alt="{{ $primaryImage->alt_text ?? $product->name }}">
                @endif

                @if ($hoverImage)
                    <img class="product_hover_img" src="{{ asset($hoverImage->image_path) }}"
                        alt="{{ $hoverImage->alt_text ?? $product->name }}">
                @endif

            </a>

            {{-- Actions --}}
            <div class="product_action_box">
                <ul class="list_none pr_action_btn">

                    {{-- Add to Cart --}}
                    <li class="add-to-cart">
                        <a href="javascript:;"
                            class="add-to-cart-link {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}"
                            data-product-id="{{ $product->id }}" data-price="{{ $product->price }}"
                            data-name="{{ $product->name }}" data-slug="{{ $product->slug }}"
                            data-image="{{ $primaryImage ? asset($primaryImage->image_path) : '' }}">

                            <i class="icon-basket-loaded"></i>
                            {{ $product->stock_quantity <= 0 ? gtrans('Out of Stock') : gtrans('Add To Cart') }}
                        </a>
                    </li>

                    {{-- Optional Icons --}}
                    <li>
                        <a href="#" class="popup-ajax">
                            <i class="icon-shuffle"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" class="popup-ajax">
                            <i class="icon-magnifier-add"></i>
                        </a>
                    </li>
                    <li>
                        <a href="#" data-product-id="{{ $product->id }}">
                            <i class="icon-heart"></i>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="product_info">

            {{-- Product Title --}}
            <h6 class="product_title">
                <a href="{{ route('page.products.show', ['slug' => $product->slug]) }}">
                    {{ gtrans($product->name) }}
                </a>
            </h6>

            {{-- Price --}}
            <div class="product_price">
                <span class="price">
                    ${{ number_format($product->price, 2) }}
                </span>

                {{-- Compare Price --}}
                @if ($product->compare_price && $product->compare_price > $product->price)
                    <del>${{ number_format($product->compare_price, 2) }}</del>

                    <div class="on_sale">
                        <span>
                            {{ calculateDiscountPercentage($product->compare_price, $product->price) }}%
                            {{ gtrans('Off') }}
                        </span>
                    </div>
                @endif
            </div>

            {{-- Stock Status --}}
            {{-- <div class="mb-2">
                @if ($product->stock_quantity > 0)
                    <small class="text-success">
                        {{ gtrans('In Stock') }} ({{ $product->stock_quantity }})
                    </small>
                @else
                    <small class="text-danger">
                        {{ gtrans('Out of Stock') }}
                    </small>
                @endif
            </div> --}}

            {{-- Rating (optional placeholder) --}}
            {{-- <div class="rating_wrap">
                <div class="rating">
                    <div class="product_rate" style="width:{{ rand(60, 95) }}%"></div>
                </div>
                <span class="rating_num">({{ rand(5, 50) }})</span>
            </div> --}}

            {{-- Description --}}
            <div class="pr_desc">
                <p>
                    {{ gtrans(
                        $product->short_description ?? \Illuminate\Support\Str::limit(strip_tags($product->description), 120),
                    ) }}
                </p>
            </div>
        </div>
    </div>
</div>
