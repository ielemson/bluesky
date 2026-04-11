@extends('layouts.app')
@section('title', 'Shopping Checkout')
@section('home_')
    <!-- START HEADER -->
    @include('frontend.partials.page-header')
    <!-- END HEADER -->
    <!-- START SECTION BREADCRUMB -->
    <div class="breadcrumb_section bg_gray page-title-mini">
        <div class="container"><!-- STRART CONTAINER -->
            @include('frontend.partials.bread-crumb', [
                'header_1' => 'Shopping Checkout',
                'header_2' => 'Shopping Checkout',
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
                {{-- CART ITEMS --}}
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

                                <tbody>
                                    @forelse($cartItems as $item)
                                        <tr>
                                            <td class="product-thumbnail">
                                                <a href="#">
                                                    <img src="{{ $item->attributes->image }}" alt="{{ $item->name }}"
                                                        width="70">
                                                </a>
                                            </td>

                                            <td class="product-name">{{ $item->name }}</td>

                                            <td class="product-price">
                                                ${{ number_format($item->price, 2) }}
                                            </td>

                                            <td class="product-quantity">
                                                <div class="quantity">
                                                    <input type="button" value="-" class="minus update-qty"
                                                        data-id="{{ $item->id }}" data-type="minus">

                                                    <input type="text" value="{{ $item->quantity }}" class="qty"
                                                        readonly>

                                                    <input type="button" value="+" class="plus update-qty"
                                                        data-id="{{ $item->id }}" data-type="plus">
                                                </div>
                                            </td>

                                            <td class="product-subtotal">
                                                ${{ number_format($item->price * $item->quantity, 2) }}
                                            </td>

                                            <td class="product-remove">
                                                <a href="javascript:;" class="item_remove" data-id="{{ $item->id }}">
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

                {{-- SECTION DIVIDER --}}
                <div class="row">
                    <div class="col-12">
                        <div class="medium_divider"></div>
                        <div class="divider center_icon"><i class="ti-map-alt"></i></div>
                        <div class="medium_divider"></div>
                    </div>
                </div>

                {{-- CHECKOUT FORM --}}
                <div class="row">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>{{ gtrans('Delivery Information') }}</h6>
                            <form id="checkout-form" method="POST" data-parsley-validate>
                                @csrf

                                <div class="form-group mb-3">
                                    <label>{{ gtrans('Contact Person') }}</label>
                                    <input type="text" name="contact_person" class="form-control" required
                                        data-parsley-required-message="{{ gtrans('Please enter contact person') }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label>{{ gtrans('Phone Number') }}</label>
                                    <input type="text" name="phone" class="form-control" required
                                        data-parsley-required-message="{{ gtrans('Please enter phone number') }}">
                                </div>

                                <div class="form-group mb-3">
                                    <label>{{ gtrans('Delivery Address') }}</label>
                                    <textarea name="address" class="form-control" rows="3" required
                                        data-parsley-required-message="{{ gtrans('Please enter delivery address') }}"></textarea>
                                </div>

                                <div class="form-group mb-3">
                                    <label>{{ gtrans('Select Crypto') }}</label>
                                    <select name="crypto_currency" id="crypto_currency" class="form-control" required
                                        data-parsley-required-message="{{ gtrans('Select a crypto currency') }}">
                                        <option value="">{{ gtrans('--Select--') }}</option>
                                        <option value="usdttrc20">{{ gtrans('USDT TRC20') }}</option>
                                        <option value="btc">{{ gtrans('BTC') }}</option>
                                        <option value="eth">{{ gtrans('ETH') }}</option>
                                    </select>
                                </div>
                            </form>
                        </div>

                        <div class="col-md-6">
                            <h6>{{ gtrans('Order Summary') }}</h6>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <td>{{ gtrans('Cart Subtotal') }}</td>
                                        <td id="cart_total">
                                            ${{ number_format($cartTotal, 2) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>{{ gtrans('Shipping') }}</td>
                                        <td>{{ gtrans('Free Shipping') }}</td>
                                    </tr>
                                    <tr>
                                        <td>{{ gtrans('Total') }}</td>
                                        <td>
                                            <strong id="order_total">
                                                ${{ number_format($cartTotal, 2) }}
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <button type="button" id="placeOrderBtn" data-url="{{ url('/api/create-payment') }}"
                                data-csrf="{{ csrf_token() }}" class="btn btn-fill-out">
                                {{ gtrans('Pay with Crypto') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- END SECTION SHOP -->

        <!-- END SECTION SHOP -->

        @include('components.login-form')
        @include('components.register-form')

        @push('scripts')
            {{-- JS --}}
            <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
            <script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script>
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
                        document.querySelector(".cart_count").innerText = data.cart_count;
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

                document.addEventListener('DOMContentLoaded', function() {
                    const placeBtn = document.getElementById('placeOrderBtn');
                    const form = document.getElementById('checkout-form');

                    placeBtn.addEventListener('click', function(e) {
                        if (!$(form).parsley().validate()) return;

                        const formData = new FormData(form);
                        const total = parseFloat(document.getElementById('order_total').textContent.replace(/[$,]/g,
                            ''));
                        formData.append('total_amount', total);

                        const cryptoCurrency = document.getElementById('crypto_currency').value;
                        formData.append('crypto', cryptoCurrency);

                        const url = placeBtn.dataset.url;
                        const csrf = placeBtn.dataset.csrf;

                        fetch(url, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': csrf
                                },
                                body: formData
                            })
                            .then(res => res.json())
                            .then(res => {
                                if (res.status && res.payment_url) {
                                    Toastify({
                                        text: "Redirecting to Crypto Payment...",
                                        duration: 3000,
                                        gravity: "top",
                                        position: "right",
                                        backgroundColor: "#4fbe87"
                                    }).showToast();
                                    window.location.href = res.payment_url;
                                } else {
                                    Toastify({
                                        text: res.message || "Payment creation failed.",
                                        duration: 4000,
                                        gravity: "top",
                                        position: "right",
                                        backgroundColor: "#f36161"
                                    }).showToast();
                                    console.error(res.error || res);
                                }
                            })
                            .catch(err => {
                                console.error(err);
                                Toastify({
                                    text: "Network error. Please try again.",
                                    duration: 4000,
                                    gravity: "top",
                                    position: "right",
                                    backgroundColor: "#f36161"
                                }).showToast();
                            });
                    });
                });
            </script>
        @endpush

        @push('styles')
            <style>
                /* ------------------------------------------
                   PARSLEY VALIDATION (Native Classes Only)
                ------------------------------------------- */

                /* Error input state */
                input.parsley-error,
                select.parsley-error,
                textarea.parsley-error {
                    border-color: #e74c3c !important;
                    background-color: #fff7f7 !important;
                    padding-right: 40px !important;
                    transition: all 0.25s ease-in-out;
                }

                /* Success input state */
                input.parsley-success,
                select.parsley-success,
                textarea.parsley-success {
                    border-color: #28a745 !important;
                    padding-right: 40px !important;
                    transition: all 0.25s ease-in-out;
                }

                /* Error message list */
                .parsley-errors-list {
                    margin-top: 4px;
                    list-style: none;
                    padding: 0;
                    font-size: 0.85rem;
                    color: #e74c3c;
                    opacity: 0;
                    height: 0;
                    overflow: hidden;
                    transition: all 0.25s ease;
                }

                /* When error message is active */
                .parsley-errors-list.filled {
                    opacity: 1;
                    height: auto;
                }

                /* Required field rule */
                .parsley-required {
                    color: #e74c3c !important;
                    font-weight: 500;
                }

                /* Error icon */
                .error-icon {
                    position: absolute;
                    right: 10px;
                    top: 50%;
                    transform: translateY(-50%);
                    color: #e74c3c !important;
                    font-size: 16px;
                    pointer-events: none;
                    animation: fadeIn 0.25s ease-in-out;
                }

                /* Fade-in animation */
                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(-40%);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(-50%);
                    }
                }

                /* Ensure wrapper supports icon placement */
                .form-group,
                .position-relative {
                    position: relative;
                }

                /* Better outline on invalid fields */
                input.parsley-error:focus {
                    box-shadow: 0 0 0 0.2rem rgba(231, 76, 60, 0.25) !important;
                }

                /* Better outline on valid fields */
                input.parsley-success:focus {
                    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25) !important;
                }
            </style>
        @endpush
    @endsection
