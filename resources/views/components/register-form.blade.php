<div class="modal fade open-register-modal subscribe_popup" id="registerModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span><i class="fi-rs-cross"></i></span>
                </button>
                <div class="row g-0">
                    <div class="col-sm-4">
                        <div class="background_bg h-100" data-img-src="{{ asset('assets/imgs/popup_img.png') }}">
                        </div>
                    </div>

                    <div class="col-sm-8 p-2">
                        <div class="popup_content">
                            <div class="popup-text text-center mb-3">
                                <div class="heading_s4">
                                    <h4>{{ gtrans('New User Registration') }}</h4>
                                </div>
                            </div>

                            {{-- Registration Form --}}
                            <form id="registerForm" method="POST" data-parsley-validate>
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <input type="text" name="contact" class="form-control" required
                                                placeholder="{{ gtrans('Email or Phone') }}">
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="form-group mb-3">
                                            <input type="text" name="nickname" required class="form-control"
                                                placeholder="{{ gtrans('Enter Nickname') }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3 d-flex align-items-center">
                                    <input type="text" name="verification_code" required class="form-control me-2"
                                        placeholder="{{ gtrans('Enter verification code') }}">
                                    <span id="captcha-code" class="border px-3 py-2 rounded bg-light fw-bold"></span>
                                    <button type="button" id="reloadCaptcha"
                                        class="btn btn-outline-secondary ms-2 btn-sm">↻</button>
                                </div>

                                <div class="row">
                                    <!-- Password -->
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3 position-relative">
                                            <span
                                                class="linearicons-eye toggle-reg-password position-absolute start-0 top-50 translate-middle-y ms-3"
                                                style="cursor: pointer;"></span>
                                            <input type="password" name="password" id="regPassword" required
                                                class="form-control ps-5" placeholder="{{ gtrans('Enter password') }}"
                                                data-parsley-minlength="6">
                                        </div>
                                    </div>

                                    <!-- Confirm Password -->
                                    <div class="col-sm-6">
                                        <div class="form-group mb-3 position-relative">
                                            <span
                                                class="linearicons-eye toggle-reg-password position-absolute start-0 top-50 translate-middle-y ms-3"
                                                style="cursor: pointer;"></span>
                                            <input type="password" name="password_confirmation" required
                                                class="form-control ps-5" placeholder="{{ gtrans('Confirm password') }}"
                                                data-parsley-equalto="#regPassword">
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mb-3">
                                    <button type="submit" id="registerBtn"
                                        class="btn btn-fill-out btn-block text-uppercase rounded-0">
                                        {{ gtrans('Register') }}
                                    </button>
                                </div>
                            </form>

                            <div class="text-center">
                                <p class="small">
                                    {{ gtrans('Already have an account?') }}
                                    <a href="javascript:;" data-bs-target="#loginModal" data-bs-toggle="modal"
                                        data-bs-dismiss="modal">
                                        {{ gtrans('Go to login') }}
                                    </a><br>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        $(document).ready(function() {

            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content")
                }
            });

            $('#registerForm').parsley({
                errorClass: 'is-invalid',
                successClass: 'is-valid',
                errorsWrapper: '<ul class="parsley-errors-list d-none"></ul>',
                errorTemplate: '<li></li>',
                trigger: 'change keyup focusout',
            });

            function showToast(message, type = 'info') {
                let bg = {
                    success: "linear-gradient(to right, #00b09b, #96c93d)",
                    error: "linear-gradient(to right, #e74c3c, #c0392b)",
                    warning: "linear-gradient(to right, #f39c12, #e67e22)",
                    info: "linear-gradient(to right, #3498db, #2980b9)"
                } [type] || "linear-gradient(to right, #3498db, #2980b9)";

                Toastify({
                    text: message,
                    duration: 4000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: bg,
                        color: "#fff"
                    }
                }).showToast();
            }

            function generateCaptcha() {
                const code = Math.random().toString(36).substr(2, 6).toUpperCase();
                $("#captcha-code").text(code);
                return code;
            }

            let currentCaptcha = generateCaptcha();
            $("#reloadCaptcha").on('click', function() {
                currentCaptcha = generateCaptcha();
            });

            $('#registerForm').on('submit', function(e) {
                e.preventDefault();

                const form = $(this);

                if (!form.parsley().isValid()) return;

                form.find('.is-invalid').removeClass('is-invalid');
                form.find('.error-icon').remove();

                const inputCode = $("input[name='verification_code']").val().trim().toUpperCase();
                const actualCode = $("#captcha-code").text().trim();

                if (inputCode !== actualCode) {
                    showToast("{{ gtrans('Captcha mismatch!') }}", 'error');
                    currentCaptcha = generateCaptcha();
                    return;
                }

                const formData = form.serialize();
                $('#registerBtn').prop('disabled', true).text("{{ gtrans('Registering...') }}");

                const loader = Toastify({
                    text: "{{ gtrans('Processing registration...') }}",
                    duration: -1,
                    gravity: "top",
                    position: "right",
                    close: false,
                    style: {
                        background: "linear-gradient(to right, #2c3e50, #4ca1af)",
                        color: "#fff"
                    }
                }).showToast();

                $.ajax({
                    url: "{{ route('registerCustomer') }}",
                    type: "POST",
                    data: formData,
                    success: function(response) {
                        if (loader && loader.hideToast) loader.hideToast();

                        showToast(
                            response.message || "{{ gtrans('Registration successful!') }}",
                            'success'
                        );

                        form[0].reset();
                        form.find('.is-invalid').removeClass('is-invalid');
                        form.find('.error-icon').remove();
                        generateCaptcha();

                        $('#registerModal').one('hidden.bs.modal', function() {
                            $('#loginModal').modal('show');
                        });

                        $('#registerModal').modal('hide');
                    },
                    error: function(xhr) {
                        if (loader && loader.hideToast) loader.hideToast();

                        let err = "{{ gtrans('Something went wrong.') }}";

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            const errors = xhr.responseJSON.errors;

                            for (const [field, messages] of Object.entries(errors)) {
                                const input = $(`#registerForm [name="${field}"]`);
                                if (input.length) {
                                    input.addClass('is-invalid');
                                    input.after(
                                        '<span class="error-icon"><i class="fas fa-exclamation-circle text-danger"></i></span>'
                                    );
                                }
                            }

                            err = Object.values(errors).flat().join("<br>");
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            err = xhr.responseJSON.message;
                        }

                        showToast(err, 'error');
                        generateCaptcha();
                    },
                    complete: function() {
                        $('#registerBtn').prop('disabled', false).text(
                            "{{ gtrans('Register') }}");
                    }
                });
            });
        });

        $(document).on('click', '.toggle-reg-password', function() {
            let input = $(this).siblings('input');
            let isPassword = input.attr('type') === 'password';

            input.attr('type', isPassword ? 'text' : 'password');
            $(this).toggleClass('linearicons-eye-crossed');
        });
    </script>
@endpush()
