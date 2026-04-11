@extends('layouts.app')

@section('title', 'Home')
@php
    header('Location: ' . route('home'));
    exit();
@endphp
@section('home_')
    @include('components.page-header')

    <!-- START SECTION BREADCRUMB -->
    @include('components.breadcrumbs')
    <!-- END SECTION BREADCRUMB -->
@endsection

@section('content')
    <!-- START MAIN CONTENT -->
    <div class="main_content">

        <!-- START LOGIN SECTION -->
        <div class="login_register_wrap section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-10 col-md-10">
                        <div class="login_wrap">
                            <div class=" bg-white">
                                <div class="row g-0">
                                    <div class="col-sm-4">
                                        <div class="background_bg h-100"
                                            data-img-src="{{ asset('assets/imgs/popup_img.png') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-8">
                                        <div class="popup_content">
                                            <div class="popup-text text-center mb-3">
                                                <div class="heading_s4">
                                                    <h4>New User Registration</h4>
                                                </div>

                                            </div>

                                            {{-- Login Form --}}
                                            <form id="loginForm" method="POST" data-parsley-validate>
                                                @csrf

                                                <!-- Email/Phone Input -->
                                                <div class="form-group mb-3 position-relative">
                                                    <input type="text" name="contact" class="form-control" required
                                                        placeholder="Email or Phone"
                                                        data-parsley-required-message="Enter your email or phone">
                                                    <small class="error-text text-danger"></small>
                                                </div>

                                                <!-- Password Input with Eye Icon -->

                                                <div class="form-group mb-3 position-relative">
                                                    <span
                                                        class="linearicons-eye toggle-password position-absolute start-0 top-50 translate-middle-y ms-3"
                                                        style="cursor: pointer;"></span>
                                                    <input type="password" name="password" required
                                                        class="form-control ps-5" placeholder="Enter password"
                                                        data-parsley-minlength="6">
                                                    <small class="error-text text-danger"></small>
                                                </div>


                                                <!-- Submit Button -->
                                                <div class="form-group mb-3">
                                                    <button type="submit" id="loginBtn"
                                                        class="btn btn-fill-out btn-block text-uppercase rounded-0">
                                                        Login
                                                    </button>
                                                </div>
                                            </form>
                                            <div class="text-center">
                                                <p class="small">Don't have an account?
                                                    <a href="{{ route('register') }}">Register here</a>
                                                </p>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END LOGIN SECTION -->

        <!-- START SECTION SUBSCRIBE NEWSLETTER -->
        <div class="section bg_default small_pt small_pb">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <div class="heading_s1 mb-md-0 heading_light">
                            <h3>Subscribe Our Newsletter</h3>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="newsletter_form">
                            <form>
                                <input type="text" required="" class="form-control rounded-0"
                                    placeholder="Enter Email Address">
                                <button type="submit" class="btn btn-dark rounded-0" name="submit"
                                    value="Submit">Subscribe</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- START SECTION SUBSCRIBE NEWSLETTER -->

    </div>
    <!-- END MAIN CONTENT -->

@endsection
@push('scripts')
    <script>
        $(document).ready(function() {

            // Initialize Parsley
            $('#loginForm').parsley({
                errorClass: 'is-invalid',
                successClass: 'is-valid',
                errorsWrapper: '<ul class="parsley-errors-list d-none"></ul>',
                errorTemplate: '<li></li>',
                trigger: 'change keyup focusout',
            });

            // Helper function for Toastify notifications
            function showToast(message, type = 'info') {
                let bgColor;
                switch (type) {
                    case 'success':
                        bgColor = "linear-gradient(to right, #00b09b, #96c93d)";
                        break;
                    case 'error':
                        bgColor = "linear-gradient(to right, #e74c3c, #c0392b)";
                        break;
                    case 'warning':
                        bgColor = "linear-gradient(to right, #f39c12, #e67e22)";
                        break;
                    default:
                        bgColor = "linear-gradient(to right, #3498db, #2980b9)";
                }

                Toastify({
                    text: message,
                    duration: 4000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: bgColor,
                        color: "#fff"
                    }
                }).showToast();
            }

            // Handle login form submission
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                // Validate with Parsley
                if (!$(this).parsley().isValid()) return;

                const formData = $(this).serialize();
                $('#loginBtn').prop('disabled', true).text('Logging in...');

                // Optional: Show processing toast
                const processingToast = Toastify({
                    text: "Processing login...",
                    duration: -1,
                    close: false,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: "linear-gradient(to right, #2c3e50, #4ca1af)",
                        color: "#fff"
                    }
                }).showToast();

                $.ajax({
                    url: "{{ route('loginCustomer') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        processingToast.hideToast();

                        showToast(response.message || "Login successful!", 'success');

                        // Clear form & validation styles
                        const form = $('#loginForm');
                        form[0].reset();
                        form.find('.is-invalid').removeClass('is-invalid');
                        form.find('.error-icon').remove();

                        // Redirect after short delay
                        setTimeout(() => {
                            window.location.href = response.redirect_url ||
                                "{{ route('customer.dashboard') }}";
                        }, 1500);
                    },
                    error: function(xhr) {
                        processingToast.hideToast();

                        const form = $('#loginForm');
                        form.find('.is-invalid').removeClass('is-invalid');
                        form.find('.error-icon').remove();

                        let errorMsg = "Something went wrong. Please try again.";

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;

                            for (const [field, messages] of Object.entries(errors)) {
                                const input = $(`[name="${field}"]`);
                                if (input.length) {
                                    input.addClass('is-invalid');
                                    input.after(
                                        '<span class="error-icon"><i class="fas fa-exclamation-circle text-danger"></i></span>'
                                    );
                                }
                            }

                            errorMsg = Object.values(errors).flat().join('<br>');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }

                        showToast(errorMsg, 'error');
                    },
                    complete: function() {
                        $('#loginBtn').prop('disabled', false).text('Login');
                    }
                });
            });
        });

        $(document).on('click', '.toggle-password', function() {
            let input = $(this).siblings('input');
            let isPassword = input.attr('type') === 'password';

            input.attr('type', isPassword ? 'text' : 'password');
            $(this).toggleClass('linearicons-eye-crossed');
        });
    </script>
@endpush()

@push('styles')
    <style>
        /* Input border feedback */
        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error {
            border-color: #e74c3c !important;
            background-image: url('data:image/svg+xml;utf8,<svg fill="none" stroke="%23e74c3c" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 18px;
        }

        /* Valid state */
        input.parsley-success,
        select.parsley-success,
        textarea.parsley-success {
            border-color: #28a745 !important;
            background-image: url('data:image/svg+xml;utf8,<svg fill="none" stroke="%2328a745" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>');
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 18px;
        }

        /* Keep layout steady */
        .parsley-errors-list {
            display: none !important;
        }

        .is-invalid {
            border-color: #e3342f !important;
            box-shadow: 0 0 3px rgba(227, 52, 47, 0.4);
        }

        .error-icon {
            position: absolute;
            right: 10px;
            top: 12px;
            color: #e3342f;
            font-size: 14px;
        }
    </style>
@endpush
