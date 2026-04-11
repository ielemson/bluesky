<!DOCTYPE html>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="">

    <title>Admin - Log in </title>

    <!-- Vendors Style-->
    <link rel="stylesheet" href="{{ asset('admin/css/vendors_css.css') }}">
    <!-- Style-->
    <link rel="stylesheet" href="{{ asset('admin/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/css/skin_color.css') }}">
    <!-- Favicon Icon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.png') }}">


</head>

<body class="hold-transition theme-primary bg-img"
    style="background-image: url({{ asset('admin/images/auth-bg/bg-16.jpg') }})">
    <div class="container h-p100">
        <div class="row align-items-center justify-content-md-center h-p100">

            <div class="col-12">
                <div class="row justify-content-center g-0">
                    <div class="col-lg-5 col-md-5 col-12">
                        <div class="bg-white rounded10 shadow-lg">
                            <div class="content-top-agile p-20 pb-0">
                                <h2 class="text-primary fw-600">
                                    {{ gtrans("Let's Get Started") }}
                                </h2>
                                <p class="mb-0 text-fade">
                                    {{ gtrans('Sign in to continue to Cartiy Admin.') }}
                                </p>
                            </div>
                            <div class="p-40">

                                <form id="adminLoginForm" method="POST" data-parsley-validate>
                                    @csrf

                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent">
                                                <i class="text-fade ti-user"></i>
                                            </span>
                                            <input type="text" name="email"
                                                class="form-control ps-15 bg-transparent"
                                                placeholder="{{ gtrans('Email') }}" required
                                                data-parsley-required-message="{{ gtrans('Enter your admin email') }}">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="input-group mb-3">
                                            <span class="input-group-text bg-transparent">
                                                <i class="text-fade ti-lock"></i>
                                            </span>
                                            <input type="password" name="password"
                                                class="form-control ps-15 bg-transparent"
                                                placeholder="{{ gtrans('Password') }}" required
                                                data-parsley-required-message="{{ gtrans('Enter password') }}">
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-6">
                                            <div class="checkbox">
                                                <input type="checkbox" id="remember" name="remember">
                                                <label for="remember">
                                                    {{ gtrans('Remember Me') }}
                                                </label>
                                            </div>
                                        </div>

                                        <div class="col-6 text-end">
                                            <a href="{{ route('home') }}" class="text-primary fw-500 hover-primary">
                                                <i class="ion ion-home"></i>
                                                {{ gtrans('Go Home') }}
                                            </a>
                                        </div>

                                        <div class="col-12 text-center">
                                            <button type="submit" class="btn btn-primary w-p100 mt-10">
                                                {{ gtrans('SIGN IN') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>


    <!-- Vendor JS -->
    <script src="{{ asset('admin/js/vendors.min.js') }}"></script>
    <script src="{{ asset('admin/js/pages/chat-popup.js') }}"></script>
    <script src="{{ asset('admin/icons/feather-icons/feather.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script>

    <!-- Linearicons CDN -->
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

    <!-- Toastify (if already included elsewhere, skip this) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $(document).ready(function() {

            // Reload captcha
            $("#reloadCaptcha").click(function() {
                $.get("/captcha/default", function(res) {
                    $("#captchaImg").attr("src", res);
                });
            });

            // AJAX Login
            $("#adminLoginForm").on("submit", function(e) {
                e.preventDefault();

                // Validate with Parsley
                if (!$(this).parsley().validate()) return;

                let formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('admin.login.submit') }}",
                    method: "POST",
                    data: formData,
                    beforeSend: function() {
                        Toastify({
                            text: "Processing...",
                            duration: 1500,
                            gravity: "top",
                            backgroundColor: "#3498db"
                        }).showToast();
                    },
                    success: function(res) {
                        if (res.status === "success") {
                            Toastify({
                                text: "Login Successful. Redirecting...",
                                gravity: "top",
                                backgroundColor: "#27ae60"
                            }).showToast();

                            setTimeout(() => {
                                window.location.href =
                                    "{{ route('admin.dashboard') }}";
                            }, 1000);
                        }
                    },
                    error: function(xhr) {
                        let msg = "Login failed. Try again.";

                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            msg = xhr.responseJSON.message;
                        }

                        Toastify({
                            text: msg,
                            gravity: "top",
                            backgroundColor: "#e74c3c"
                        }).showToast();

                        $("#reloadCaptcha").click(); // refresh captcha
                    }
                });
            });

        });
    </script>


</body>

</html>
