<div class="modal fade open-login-modal subscribe_popup" id="loginModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span><i class="fi-rs-cross"></i></span>
                </button>
                <div class="row g-0">
                    <div class="col-sm-4">
                        <div class="background_bg h-100" data-img-src="{{ asset("assets/imgs/popup_img.png") }}">
                        </div>
                    </div>
                    <div class="col-sm-8 p-3">
                        <div class="popup_content">
                            
                            <!-- Heading -->
                            <div class="popup-text text-center mb-3">
                                <div class="heading_s4">
                                    <h4>Welcome Back to Bluesky Mart</h4>
                                </div>
                                <p class="text-muted small mb-0">
                                    Shop smarter, faster, and more conveniently. Log in to continue.
                                </p>
                            </div>

                            {{-- Login Form --}}
                            <form id="loginForm" method="POST" data-parsley-validate>
                                @csrf

                                <!-- Email/Phone -->
                                <div class="form-group mb-3 position-relative">
                                    <input type="text" name="contact" class="form-control" required
                                        placeholder="Email address or Phone number"
                                        data-parsley-required-message="Please enter your email or phone number">
                                    <small class="error-text text-danger"></small>
                                </div>

                                <!-- Password -->
                                <div class="form-group mb-3 position-relative">
                                    <span
                                        class="linearicons-eye toggle-password position-absolute start-0 top-50 translate-middle-y ms-3"
                                        style="cursor: pointer;"></span>
                                    <input type="password" name="password" required class="form-control ps-5"
                                        placeholder="Enter your password"
                                        data-parsley-minlength="6"
                                        data-parsley-minlength-message="Password must be at least 6 characters">
                                    <small class="error-text text-danger"></small>
                                </div>

                                <!-- Submit -->
                                <div class="form-group mb-3">
                                    <button type="submit" id="loginBtn"
                                        class="btn btn-fill-out btn-block text-uppercase rounded-0">
                                        Login to Account
                                    </button>
                                </div>
                            </form>

                            <!-- Register Link -->
                            <div class="text-center">
                                <p class="small mb-1">
                                    Don’t have an account?
                                    <a href="javascript:;" data-bs-target="#registerModal" data-bs-toggle="modal"
                                        data-bs-dismiss="modal" class="fw-bold text-primary">
                                        Create one now
                                    </a>
                                </p>
                            </div>

                            <!-- Terms -->
                            <div class="text-center mt-2">
                                <p class="small text-muted">
                                    By continuing, you agree to Bluesky Mart’s 
                                    <a href="#" class="text-primary">Terms of Use</a>, 
                                    <a href="#" class="text-primary">Privacy Policy</a>, 
                                    and 
                                    <a href="#" class="text-primary">Account Deletion Policy</a>.
                                </p>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


@push('scripts')
    <script>
        $(document).ready(function() {

            // ✅ Add CSRF Token to all AJAX requests globally
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#loginModal').on('show.bs.modal', function() {
                $('#loginRedirectTo').val(window.location.href);
            });

            // Initialize Parsley
            $('#loginForm').parsley({
                errorClass: 'is-invalid',
                successClass: 'is-valid',
                errorsWrapper: '<ul class="parsley-errors-list d-none"></ul>',
                errorTemplate: '<li></li>',
                trigger: 'change keyup focusout',
            });

            // Toast helper
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

            // Handle login
            $('#loginForm').on('submit', function(e) {
                e.preventDefault();

                if (!$(this).parsley().isValid()) return;

                const formData = $(this).serialize();
                $('#loginBtn').prop('disabled', true).text('Logging in...');

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

                        const form = $('#loginForm');
                        form[0].reset();
                        form.find('.is-invalid').removeClass('is-invalid');
                        form.find('.error-icon').remove();

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

        // Toggle password visibility
        $(document).on('click', '.toggle-password', function() {
            let input = $(this).siblings('input');
            let isPassword = input.attr('type') === 'password';
            input.attr('type', isPassword ? 'text' : 'password');
            $(this).toggleClass('linearicons-eye-crossed');
        });
    </script>
@endpush()
