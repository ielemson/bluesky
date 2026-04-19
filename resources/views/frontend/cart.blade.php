@extends('layouts.app')

@section('title', gtrans('Shopping Cart'))

@section('home_')
    @include('partials._general_header')

    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ url('/') }}" rel="nofollow">{{ gtrans('Home') }}</a>
                <span></span> {{ gtrans('Shopping Cart') }}
            </div>
        </div>
    </div>

    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="product-detail accordion-detail">

                        <div class="row mb-50">
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-20 flex-wrap">
                                    <div>
                                        <h2 class="mb-10">{{ gtrans('Shopping Cart') }}</h2>
                                        <p class="text-muted mb-0">
                                            {{ gtrans('Review your selected items before proceeding.') }}
                                        </p>
                                    </div>

                                    <div class="mt-10 mt-md-0">
                                        <a href="{{ route('page.products.shop') }}" class="btn btn-sm">
                                            {{ gtrans('Continue Shopping') }}
                                        </a>
                                    </div>
                                </div>

                                <div class="table-responsive shop_cart_table">
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th class="product-thumbnail">&nbsp;</th>
                                                <th class="product-name">{{ gtrans('Product') }}</th>
                                                <th class="product-price">{{ gtrans('Price') }}</th>
                                                <th class="product-quantity">{{ gtrans('Quantity') }}</th>
                                                <th class="product-subtotal">{{ gtrans('Subtotal') }}</th>
                                                <th class="product-remove">{{ gtrans('Remove') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody id="cart-body">
                                            @forelse($cartItems as $item)
                                                @php
                                                    $productSlug = $item->attributes->product_slug ?? null;
                                                    $productUrl = $productSlug
                                                        ? route('page.products.show', ['slug' => $productSlug])
                                                        : 'javascript:;';
                                                    $image = $item->attributes->image ?? asset('assets/imgs/shop/product-placeholder.jpg');
                                                @endphp

                                                <tr id="row-{{ $item->id }}">
                                                    <td class="product-thumbnail">
                                                        <a href="{{ $productUrl }}">
                                                            <img src="{{ $image }}" alt="{{ $item->name }}">
                                                        </a>
                                                    </td>

                                                    <td class="product-name" data-title="{{ gtrans('Product') }}">
                                                        <a href="{{ $productUrl }}">{{ gtrans($item->name) }}</a>
                                                    </td>

                                                    <td class="product-price" data-title="{{ gtrans('Price') }}">
                                                        ${{ number_format($item->price, 2) }}
                                                    </td>

                                                    <td class="product-quantity" data-title="{{ gtrans('Quantity') }}">
                                                        <div class="detail-qty border radius m-auto">
                                                            <a href="javascript:;" class="qty-down qty-btn" data-id="{{ $item->id }}" data-action="minus">
                                                                <i class="fi-rs-angle-small-down"></i>
                                                            </a>

                                                            <input
                                                                type="text"
                                                                class="qty-input qty-val"
                                                                value="{{ $item->quantity }}"
                                                                data-id="{{ $item->id }}"
                                                                readonly
                                                                style="width: 40px; border: none; background: transparent; text-align: center;"
                                                            >

                                                            <a href="javascript:;" class="qty-up qty-btn" data-id="{{ $item->id }}" data-action="plus">
                                                                <i class="fi-rs-angle-small-up"></i>
                                                            </a>
                                                        </div>
                                                    </td>

                                                    <td class="product-subtotal" data-title="{{ gtrans('Subtotal') }}" id="subtotal-{{ $item->id }}">
                                                        ${{ number_format($item->price * $item->quantity, 2) }}
                                                    </td>

                                                    <td class="product-remove" data-title="{{ gtrans('Remove') }}">
                                                        <a href="javascript:;" class="item-remove" data-id="{{ $item->id }}" title="{{ gtrans('Remove item') }}">
                                                            <i class="fi-rs-trash"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr id="empty-cart-row">
                                                    <td colspan="6" class="text-center py-5">
                                                        <div class="text-muted">
                                                            <h5 class="mb-2">{{ gtrans('Your cart is empty.') }}</h5>
                                                            <p class="mb-3">{{ gtrans('Looks like you have not added anything yet.') }}</p>
                                                            <a href="{{ route('page.products.shop') }}" class="btn btn-sm">
                                                                {{ gtrans('Continue Shopping') }}
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        @if($cartItems->count())
                            <div class="row">
                                <div class="col-lg-6 ml-auto">
                                    <div class="border p-30 cart-totals">
                                        <h4 class="mb-25">{{ gtrans('Cart Totals') }}</h4>

                                        <div class="table-responsive">
                                            <table class="table no-border">
                                                <tbody>
                                                    <tr>
                                                        <td class="cart_total_label">{{ gtrans('Cart Subtotal') }}</td>
                                                        <td class="cart_total_amount" id="cart-subtotal">
                                                            ${{ number_format($cartTotal, 2) }}
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td class="cart_total_label">{{ gtrans('Shipping') }}</td>
                                                        <td class="cart_total_amount">{{ gtrans('Free Shipping') }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td class="cart_total_label"><strong>{{ gtrans('Total') }}</strong></td>
                                                        <td class="cart_total_amount">
                                                            <strong id="cart-total">${{ number_format($cartTotal, 2) }}</strong>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="mt-20 d-flex flex-wrap gap-2">
                                            <a href="{{ route('page.products.shop') }}" class="btn btn-border">
                                                {{ gtrans('Continue Shopping') }}
                                            </a>

                                            <a href="{{ route("checkout.index") }}" class="btn btn-fill-out">
                                                {{ gtrans('Proceed to Checkout') }}
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('components.login-form')
    @include('components.register-form')

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                document.body.addEventListener("click", async (e) => {
                    const qtyBtn = e.target.closest(".qty-btn");
                    if (qtyBtn) {
                        const id = qtyBtn.dataset.id;
                        const action = qtyBtn.dataset.action;
                        const input = document.querySelector(`.qty-input[data-id="${id}"]`);

                        let qty = parseInt(input.value || 1, 10);
                        qty = action === "plus" ? qty + 1 : Math.max(1, qty - 1);

                        await updateCartQty(id, qty);
                        return;
                    }

                    const removeBtn = e.target.closest(".item-remove");
                    if (removeBtn) {
                        const id = removeBtn.dataset.id;
                        await removeCartItem(id);
                    }
                });
            });

            function setCartCount(count) {
                document.querySelectorAll('.cart_count').forEach(el => {
                    el.textContent = count;
                });
            }

            function setCartDropdown(html) {
                document.querySelectorAll('.cart_box').forEach(el => {
                    el.innerHTML = html;
                });
            }

            async function updateCartQty(id, qty) {
                try {
                    const response = await fetch("{{ route('cart.updateQuantity') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ id, quantity: qty })
                    });

                    const data = await response.json();

                    if (!response.ok || !data.status) {
                        Toastify({
                            text: data.message || "{{ gtrans('Unable to update quantity.') }}",
                            duration: 2500,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "linear-gradient(to right, #ff5f6d, #ffc371)"
                            }
                        }).showToast();
                        return;
                    }

                    const qtyInput = document.querySelector(`.qty-input[data-id="${id}"]`);
                    if (qtyInput) qtyInput.value = qty;

                    const subtotalEl = document.querySelector(`#subtotal-${id}`);
                    if (subtotalEl) subtotalEl.innerText = '$' + data.subtotal;

                    const subtotal = document.querySelector("#cart-subtotal");
                    const total = document.querySelector("#cart-total");

                    if (subtotal) subtotal.innerText = "$" + data.cart_total;
                    if (total) total.innerText = "$" + data.cart_total;

                    setCartCount(data.cart_count);
                    setCartDropdown(data.cart_dropdown);

                    Toastify({
                        text: data.message || "{{ gtrans('Quantity updated.') }}",
                        duration: 2000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        }
                    }).showToast();
                } catch (error) {
                    console.error(error);
                }
            }

            async function removeCartItem(id) {
                try {
                    const response = await fetch("{{ route('cart.remove.ajax') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({ cart_id: id })
                    });

                    const data = await response.json();

                    if (!response.ok || !data.status) {
                        Toastify({
                            text: data.message || "{{ gtrans('Unable to remove item.') }}",
                            duration: 2500,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "linear-gradient(to right, #ff5f6d, #ffc371)"
                            }
                        }).showToast();
                        return;
                    }

                    const row = document.querySelector(`#row-${id}`);
                    if (row) row.remove();

                    const subtotal = document.querySelector("#cart-subtotal");
                    const total = document.querySelector("#cart-total");

                    if (subtotal) subtotal.innerText = "$" + data.cart_total;
                    if (total) total.innerText = "$" + data.cart_total;

                    setCartCount(data.cart_count);
                    setCartDropdown(data.cart_dropdown);

                    const body = document.querySelector('#cart-body');
                    if (body && !body.querySelector('tr[id^="row-"]')) {
                        body.innerHTML = `
                            <tr id="empty-cart-row">
                                <td colspan="6" class="text-center py-5">
                                    <div class="text-muted">
                                        <h5 class="mb-2">{{ gtrans('Your cart is empty.') }}</h5>
                                        <p class="mb-3">{{ gtrans('Looks like you have not added anything yet.') }}</p>
                                        <a href="{{ route('page.products.shop') }}" class="btn btn-sm">{{ gtrans('Continue Shopping') }}</a>
                                    </div>
                                </td>
                            </tr>
                        `;
                    }

                    Toastify({
                        text: data.message || "{{ gtrans('Item removed.') }}",
                        duration: 2000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #ff5f6d, #ffc371)"
                        }
                    }).showToast();
                } catch (error) {
                    console.error(error);
                }
            }
        </script>
    @endpush
@endsection