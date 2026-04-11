@extends('layouts.app')
@php
    header('Location: ' . route('home'));
    exit();
@endphp
@section('title', 'Home')

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
                                            <form id="registerForm" method="POST" data-parsley-validate>
                                                @csrf
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <input type="text" name="contact" class="form-control"
                                                                required placeholder="Email or Phone">
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3">
                                                            <input type="text" name="nickname" required
                                                                class="form-control" placeholder="Enter Nickname">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-3 d-flex align-items-center">
                                                    <input type="text" name="verification_code" required
                                                        class="form-control me-2" placeholder="Enter verification code">
                                                    <span id="captcha-code"
                                                        class="border px-3 py-2 rounded bg-light fw-bold"></span>
                                                    <button type="button" id="reloadCaptcha"
                                                        class="btn btn-outline-secondary ms-2 btn-sm">↻</button>
                                                </div>

                                                <div class="row">
                                                    <!-- Password -->
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3 position-relative">
                                                            <span
                                                                class="linearicons-eye toggle-password position-absolute start-0 top-50 translate-middle-y ms-3"
                                                                style="cursor: pointer;"></span>
                                                            <input type="password" name="password" required
                                                                class="form-control ps-5" placeholder="Enter password"
                                                                data-parsley-minlength="6">
                                                        </div>
                                                    </div>

                                                    <!-- Confirm Password -->
                                                    <div class="col-md-6">
                                                        <div class="form-group mb-3 position-relative">
                                                            <span
                                                                class="linearicons-eye toggle-password position-absolute start-0 top-50 translate-middle-y ms-3"
                                                                style="cursor: pointer;"></span>
                                                            <input type="password" name="password_confirmation" required
                                                                class="form-control ps-5" placeholder="Confirm password"
                                                                data-parsley-equalto="[name='password']">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group mb-3">
                                                    <button type="submit" id="registerBtn"
                                                        class="btn btn-fill-out btn-block text-uppercase rounded-0">
                                                        Register
                                                    </button>
                                                </div>
                                            </form>
                                            <div class="text-center">
                                                <p class="small">Already have an account?
                                                    <a href="{{ route('login') }}">Login here</a>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        $(document).ready(function() {

            // 🔹 Initialize Parsley validation
            $('#registerForm').parsley({
                errorClass: 'is-invalid',
                successClass: 'is-valid',
                errorsWrapper: '<ul class="parsley-errors-list d-none"></ul>',
                errorTemplate: '<li></li>',
                trigger: 'change keyup focusout',
            });

            // 🔹 Toastify notification function
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
                    gravity: "top",
                    position: "right",
                    close: true,
                    stopOnFocus: true,
                    style: {
                        background: bgColor,
                        color: "#fff"
                    }
                }).showToast();
            }

            // 🔹 Generate Captcha
            function generateCaptcha() {
                const code = Math.random().toString(36).substring(2, 8).toUpperCase();
                $('#captcha-code').text(code);
                return code;
            }

            let currentCaptcha = generateCaptcha();

            $('#reloadCaptcha').on('click', function() {
                currentCaptcha = generateCaptcha();
            });

            // 🔹 Form Submit
            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                if (!$(this).parsley().isValid()) return;

                const inputCode = $("input[name='verification_code']").val().trim().toUpperCase();
                const actualCode = $("#captcha-code").text().trim();

                if (inputCode !== actualCode) {
                    showToast('Captcha mismatch! Please try again.', 'error');
                    return;
                }

                const formData = $(this).serialize();
                $('#registerBtn').prop('disabled', true).text('Registering...');

                // 🔹 Show loading toast
                let loadingToast = Toastify({
                    text: "Processing registration...",
                    gravity: "top",
                    position: "right",
                    duration: -1, // stays until manually dismissed
                    style: {
                        background: "linear-gradient(to right, #2c3e50, #4ca1af)",
                        color: "#fff"
                    }
                });
                loadingToast.showToast();

                // 🔹 AJAX request
                $.ajax({
                    url: "{{ route('registerCustomer') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        loadingToast.hideToast();

                        showToast(response.message || 'Registration successful!', 'success');

                        const form = $('#registerForm');
                        form[0].reset();
                        form.find('.is-invalid').removeClass('is-invalid');
                        form.find('.error-icon').remove();

                        generateCaptcha();

                        setTimeout(() => {
                            window.location.href = "{{ route('login') }}";
                        }, 2000);
                    },
                    error: function(xhr) {
                        loadingToast.hideToast();

                        const form = $('#registerForm');
                        form.find('.is-invalid').removeClass('is-invalid');
                        form.find('.error-icon').remove();

                        let errorMsg = 'Something went wrong. Please try again.';

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
                        $('#registerBtn').prop('disabled', false).text('Register');
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
