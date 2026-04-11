@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('home_')

    <!-- START HEADER -->
    @include('frontend.partials.page-header')
    <!-- END HEADER -->
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- START CONTAINER -->
            @include('frontend.partials.bread-crumb', [
                'header_1' => gtrans('Shopping Cart'),
                'header_2' => gtrans('Shopping Cart'),
            ])
        </div>
        <!-- END CONTAINER-->
    </div>
    <!-- END SECTION BREADCRUMB -->

    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- START SECTION SHOP -->
        <div class="section">
            <div class="container">
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive shop_cart_table">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th class="product-thumbnail">&nbsp;</th>
                                        <th class="product-name">{{ gtrans('Product') }}</th>
                                        <th class="product-price">{{ gtrans('Price') }}</th>
                                        <th class="product-quantity">{{ gtrans('Quantity') }}</th>
                                        <th class="product-subtotal">{{ gtrans('Total') }}</th>
                                        <th class="product-remove">{{ gtrans('Remove') }}</th>
                                    </tr>
                                </thead>

                                <tbody id="cart-body">
                                    @forelse($cartItems as $item)
                                        <tr id="row-{{ $item->id }}">
                                            <td class="product-thumbnail">
                                                <a
                                                    href="{{ route('page.products.show', ['slug' => $item->attributes->product_slug, 'vendor' => $item->attributes->vendor_id]) }}">
                                                    <img src="{{ $item->attributes->image }}" alt="{{ $item->name }}">
                                                </a>
                                            </td>

                                            <td class="product-name" data-title="{{ gtrans('Product') }}">
                                                <a
                                                    href="{{ route('page.products.show', ['slug' => $item->attributes->product_slug, 'vendor' => $item->attributes->vendor_id]) }}">
                                                    {{ $item->name }}
                                                </a>
                                            </td>

                                            <td class="product-price" data-title="{{ gtrans('Price') }}">
                                                ${{ number_format($item->price, 2) }}
                                            </td>

                                            <td class="product-quantity" data-title="{{ gtrans('Quantity') }}">
                                                <div class="quantity">
                                                    <button class="minus qty-btn" data-id="{{ $item->id }}"
                                                        data-action="minus">-</button>

                                                    <input type="text" class="qty-input qty" size="4"
                                                        value="{{ $item->quantity }}" data-id="{{ $item->id }}">

                                                    <button class="plus qty-btn" data-id="{{ $item->id }}"
                                                        data-action="plus">+</button>
                                                </div>
                                            </td>

                                            <td class="product-subtotal" data-title="{{ gtrans('Total') }}"
                                                id="subtotal-{{ $item->id }}">
                                                ${{ number_format($item->price * $item->quantity, 2) }}
                                            </td>

                                            <td class="product-remove" data-title="{{ gtrans('Remove') }}">
                                                <a href="javascript:;" class="item-remove" data-id="{{ $item->id }}">
                                                    <i class="ti-close"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">
                                                {{ gtrans('Your cart is empty.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                {{-- CART TOTALS --}}
                <div class="row mt-5">
                    <div class="col-md-6">
                        <div class="border p-3 p-md-4">
                            <div class="heading_s1 mb-3">
                                <h6>{{ gtrans('Cart Totals') }}</h6>
                            </div>

                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td class="cart_total_label">{{ gtrans('Cart Subtotal') }}</td>
                                            <td class="cart_total_amount" id="cart-subtotal">
                                                ${{ number_format($cartTotal, 2) }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="cart_total_label">{{ gtrans('Shipping') }}</td>
                                            <td class="cart_total_amount">
                                                {{ gtrans('Free Shipping') }}
                                            </td>
                                        </tr>

                                        <tr>
                                            <td class="cart_total_label">{{ gtrans('Total') }}</td>
                                            <td class="cart_total_amount">
                                                <strong id="cart-total">
                                                    ${{ number_format($cartTotal, 2) }}
                                                </strong>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <a href="{{ route('checkout') }}" class="btn btn-fill-out">
                                {{ gtrans('Proceed To CheckOut') }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    </div>

    @include('components.login-form')
    @include('components.register-form')

    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", () => {

                // UPDATE QTY (plus/minus)
                document.body.addEventListener("click", async (e) => {
                    if (!e.target.classList.contains("qty-btn")) return;

                    const id = e.target.dataset.id;
                    const action = e.target.dataset.action;
                    const input = document.querySelector(`.qty-input[data-id="${id}"]`);

                    let qty = parseInt(input.value);

                    qty = action === "plus" ? qty + 1 : Math.max(1, qty - 1);

                    updateCartQty(id, qty);
                });

                // MANUAL INPUT
                document.body.addEventListener("change", (e) => {
                    if (!e.target.classList.contains("qty-input")) return;

                    const id = e.target.dataset.id;
                    const qty = Math.max(1, parseInt(e.target.value));

                    updateCartQty(id, qty);
                });

                // REMOVE ITEM
                document.body.addEventListener("click", (e) => {
                    if (!e.target.closest(".item-remove")) return;

                    const id = e.target.closest(".item-remove").dataset.id;
                    removeCartItem(id);
                });

            });

            // AJAX — UPDATE QTY
            async function updateCartQty(id, qty) {
                const response = await fetch("{{ route('cart.updateQuantity') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id,
                        quantity: qty
                    })
                });

                const data = await response.json();

                if (data.status) {
                    document.querySelector(`#subtotal-${id}`).innerText = '$' + data.subtotal;
                    document.querySelector("#cart-subtotal").innerText = "$" + data.cart_total;
                    document.querySelector("#cart-total").innerText = "$" + data.cart_total;
                    document.querySelector(".cart_count").innerText = data.cart_count;
                    document.querySelector(".cart_box").innerHTML = data.dropdown;

                    Toastify({
                        text: "Quantity updated",
                        duration: 2000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #00b09b, #96c93d)"
                        }
                    }).showToast();
                }
            }

            // AJAX — REMOVE ITEM
            async function removeCartItem(id) {
                const response = await fetch("{{ route('cart.remove') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        id
                    })
                });

                const data = await response.json();

                if (data.status) {
                    document.querySelector(`#row-${id}`).remove();
                    document.querySelector("#cart-subtotal").innerText = "$" + data.cart_total;
                    document.querySelector("#cart-total").innerText = "$" + data.cart_total;
                    document.querySelector(".cart_count").innerText = data.cart_count; ///
                    document.querySelector('.cart_trigger .amount').innerHTML =
                        `<span class="currency_symbol">$</span>${data.cart_total}`;
                    document.querySelector(".cart_box").innerHTML = data.dropdown;

                    Toastify({
                        text: "Item removed",
                        duration: 2000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right, #ff5f6d, #ffc371)"
                        }
                    }).showToast();
                }
            }
        </script>
    @endpush
@endsection
