@extends('layouts.app')

@section('title', gtrans('Checkout'))

@section('home_')
    @include('partials._general_header')

    <div class="page-header breadcrumb-wrap">
        <div class="container">
            <div class="breadcrumb">
                <a href="{{ url('/') }}" rel="nofollow">{{ gtrans('Home') }}</a>
                <span></span> {{ gtrans('Checkout') }}
            </div>
        </div>
    </div>

    <section class="mt-50 mb-50">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="billing-info-wrap">
                        <h3 class="mb-30">{{ gtrans('Billing Details') }}</h3>

                        <form action="javascript:;" method="POST">
                            @csrf

                            <div class="row">
                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="first_name" placeholder="{{ gtrans('First name') }}">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="last_name" placeholder="{{ gtrans('Last name') }}">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <input type="email" name="email" placeholder="{{ gtrans('Email address') }}">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="phone" placeholder="{{ gtrans('Phone') }}">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <input type="text" name="address" placeholder="{{ gtrans('Address') }}">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="city" placeholder="{{ gtrans('City') }}">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="state" placeholder="{{ gtrans('State') }}">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="postcode" placeholder="{{ gtrans('Postcode / ZIP') }}">
                                    </div>
                                </div>

                                <div class="col-lg-6 col-md-6">
                                    <div class="form-group">
                                        <input type="text" name="country" placeholder="{{ gtrans('Country') }}">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <textarea name="notes" rows="5" placeholder="{{ gtrans('Order notes') }}"></textarea>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="border p-30 cart-totals mb-30">
                        <h4 class="mb-30">{{ gtrans('Your Order') }}</h4>

                        <div class="table-responsive">
                            <table class="table no-border">
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td class="image product-thumbnail pt-10">
                                                <img src="{{ $item->attributes->image ?? asset('assets/imgs/shop/product-placeholder.jpg') }}"
                                                     alt="{{ $item->name }}"
                                                     style="width: 70px;">
                                            </td>
                                            <td>
                                                <h6 class="w-160 mb-5">
                                                    <a href="{{ route('page.products.show', ['slug' => $item->attributes->product_slug]) }}" class="text-heading">
                                                        {{ gtrans($item->name) }}
                                                    </a>
                                                </h6>
                                                <div class="product-rate-cover">
                                                    <small class="text-muted">
                                                        {{ gtrans('Qty') }}: {{ $item->quantity }}
                                                    </small>
                                                </div>
                                            </td>
                                            <td>
                                                <h6 class="text-brand">${{ number_format($item->price * $item->quantity, 2) }}</h6>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="divider-2 mb-30"></div>

                        <div class="table-responsive order_table checkout">
                            <table class="table no-border">
                                <tbody>
                                    <tr>
                                        <td class="cart_total_label">{{ gtrans('Subtotal') }}</td>
                                        <td class="cart_total_amount">
                                            <span class="font-lg fw-900 text-brand">${{ number_format($cartTotal, 2) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cart_total_label">{{ gtrans('Shipping') }}</td>
                                        <td class="cart_total_amount">
                                            <span>{{ gtrans('Free Shipping') }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="cart_total_label">{{ gtrans('Total') }}</td>
                                        <td class="cart_total_amount">
                                            <strong>
                                                <span class="font-xl fw-900 text-brand">${{ number_format($cartTotal, 2) }}</span>
                                            </strong>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="payment ml-30">
                            <h4 class="mb-20">{{ gtrans('Payment') }}</h4>

                            <div class="payment_option">
                                <div class="custome-radio">
                                    <input class="form-check-input" type="radio" name="payment_method" id="cash_on_delivery" checked>
                                    <label class="form-check-label" for="cash_on_delivery" data-bs-toggle="collapse" data-target="#cashOnDelivery" aria-controls="cashOnDelivery">
                                        {{ gtrans('Cash on Delivery') }}
                                    </label>
                                    <div class="form-group collapse in" id="cashOnDelivery">
                                        <p class="text-muted mb-0">
                                            {{ gtrans('Pay with cash upon delivery.') }}
                                        </p>
                                    </div>
                                </div>

                                <div class="custome-radio">
                                    <input class="form-check-input" type="radio" name="payment_method" id="bank_transfer">
                                    <label class="form-check-label" for="bank_transfer" data-bs-toggle="collapse" data-target="#bankTransfer" aria-controls="bankTransfer">
                                        {{ gtrans('Bank Transfer') }}
                                    </label>
                                    <div class="form-group collapse" id="bankTransfer">
                                        <p class="text-muted mb-0">
                                            {{ gtrans('Make your payment directly into our bank account.') }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="javascript:;" class="btn btn-fill-out btn-block mt-30">
                            {{ gtrans('Place Order') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection