@extends('layouts.customer')
@section('content')

@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => Auth()->user()->nickname,
        'header_2' => 'Dashboard',
    ])
@endsection
<section class="content">
    <div class="row">
        <div class="container my-5">
            <div class="card shadow-sm">
                <div class="card-body">

                    <h4 class="mb-4 font-weight-bold">My Account</h4>
                    <!-- ID -->
                    <div class="d-flex justify-content-between py-3 border-bottom">
                        <span class="text-muted">ID</span>
                        <span class="font-weight-bold">{{ auth()->user()->customer_id }}</span>
                    </div>

                    <!-- Nickname -->
                    <div class="d-flex justify-content-between py-3 border-bottom">
                        <span class="text-muted">Nick name</span>
                        <span class="font-weight-bold">{{ auth()->user()->nickname }}</span>
                    </div>

                    <!-- Avatar -->
                    <div class="d-flex justify-content-between align-items-center py-3 border-bottom">
                        <span class="text-muted">Avatar</span>
                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white font-weight-bold"
                            style="width:50px; height:50px; background:#d49ce6;">
                            J
                        </div>
                    </div>

                    <!-- Mail -->
                    <div class="d-flex justify-content-between py-3 border-bottom">
                        <span class="text-muted">Mail</span>
                        <span class="font-weight-bold"> {{ auth()->user()->maskedEmail() }}</span>
                    </div>

                    <!-- Login Password -->
                    <div class="d-flex justify-content-between py-3 border-bottom">
                        <span class="text-muted">Login Password</span>
                        <a href="javacript:;" class="text-primary font-weight-bold" data-toggle="modal"
                            data-target="#changePasswordModal">Go to Settings</a>
                    </div>

                    <!-- Payment Password -->
                    <div class="d-flex justify-content-between py-3 border-bottom">
                        <span class="text-muted">Payment password</span>
                        <a href="#" class="text-primary font-weight-bold">Go to Settings</a>
                    </div>

                </div>

                <!-- Logout Form -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <div class="p-3">
                        <button type="submit" class="btn btn-outline-primary btn-lg btn-block">
                            Log out
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection
@section('modal_wapper')
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-sm">

            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title font-weight-semibold">
                    {{ gtrans('New login password') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ gtrans('Close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form id="changePasswordForm" class="pt-2" novalidate>
                @csrf

                <div class="modal-body pt-3">

                    {{-- Original password --}}
                    <div class="form-group mb-3">
                        <label class="form-label d-block mb-1">
                            <span class="text-danger">*</span>
                            {{ gtrans('Original login password') }}
                        </label>

                        <div class="input-group">
                            <input type="password" name="current_password" class="form-control"
                                placeholder="{{ gtrans('Please enter your original login password') }}"
                                autocomplete="current-password" required data-parsley-required="true"
                                data-parsley-minlength="6" data-parsley-maxlength="20">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1"
                                    aria-label="{{ gtrans('Show or hide password') }}">
                                    <i class="fa fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="text-danger small mt-1 d-none field-error" data-for="current_password">
                            {{ gtrans('Please enter your original login password') }}
                        </div>
                    </div>

                    {{-- New password --}}
                    <div class="form-group mb-3">
                        <label class="form-label d-block mb-1">
                            <span class="text-danger">*</span>
                            {{ gtrans('New login password') }}
                        </label>

                        <div class="input-group">
                            <input type="password" id="new_password" name="password" class="form-control"
                                placeholder="{{ gtrans('Please enter your new login password') }}"
                                autocomplete="new-password" required data-parsley-required="true"
                                data-parsley-minlength="6" data-parsley-maxlength="20">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary toggle-password" tabindex="-1"
                                    aria-label="{{ gtrans('Show or hide password') }}">
                                    <i class="fa fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="text-danger small mt-1 d-none field-error" data-for="password">
                            {{ gtrans('Please enter your new login password') }}
                        </div>
                    </div>

                    {{-- Repeat password --}}
                    <div class="form-group mb-2">
                        <label class="form-label d-block mb-1">
                            <span class="text-danger">*</span>
                            {{ gtrans('Repeat login password') }}
                        </label>

                        <div class="input-group">
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="{{ gtrans('Please enter your password again') }}"
                                autocomplete="new-password" required data-parsley-required="true"
                                data-parsley-equalto="#new_password">
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    tabindex="-1" aria-label="{{ gtrans('Show or hide password') }}">
                                    <i class="fa fa-eye-slash"></i>
                                </button>
                            </div>
                        </div>

                        <div class="text-danger small mt-1 d-none field-error" data-for="password_confirmation">
                            {{ gtrans('Please enter your password again') }}
                        </div>
                    </div>

                    <p class="small text-muted mt-2 mb-0">
                        {{ gtrans('Must be 6-20 digits or English characters') }}
                    </p>
                </div>

                <div class="modal-footer border-0 pt-0 d-flex flex-column flex-sm-row">
                    <button type="submit" class="btn btn-primary btn-block mb-2 mb-sm-0 mr-sm-2"
                        style="font-weight:600;height:44px;">
                        {{ gtrans('Confirm changes') }}
                    </button>
                    <button type="button" class="btn btn-light btn-block" style="font-weight:600;height:44px;"
                        data-dismiss="modal">
                        {{ gtrans('Close') }}
                    </button>
                </div>
            </form>

        </div>
    </div>
</div>
@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.toggle-password');
        if (!btn) return;

        const group = btn.closest('.input-group');
        const input = group ? group.querySelector('input') : null;
        if (!input) return;

        const icon = btn.querySelector('i');
        const isHidden = input.type === 'password';
        input.type = isHidden ? 'text' : 'password';

        if (icon) {
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    });

    document.addEventListener('DOMContentLoaded', function() {
        const $form = $('#changePasswordForm');

        $form.parsley();

        $form.on('submit', function(e) {
            e.preventDefault();
            if (!$form.parsley().validate()) return;

            const $btn = $form.find('button[type="submit"]');
            const original = $btn.text();
            $btn.prop('disabled', true).text('{{ gtrans('Please wait...') }}');

            fetch("{{ route('customer.password.change') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: new FormData($form[0]),
                })
                .then(async res => {
                    const json = await res.json();
                    if (res.ok && json.status === 'ok') {
                        Toastify({
                            text: json.message ||
                                '{{ gtrans('Password updated successfully.') }}',
                            duration: 4000,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "linear-gradient(to right,#00b09b,#96c93d)"
                            }
                        }).showToast();

                        $form[0].reset();
                        $('#changePasswordModal').modal('hide');
                    } else {
                        const msg = json.message ||
                            '{{ gtrans('Unable to change password.') }}';
                        Toastify({
                            text: msg,
                            duration: 4000,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "linear-gradient(to right,#e53935,#e35d5b)"
                            }
                        }).showToast();
                    }
                })
                .catch(() => {
                    Toastify({
                        text: '{{ gtrans('An error occurred. Please try again.') }}',
                        duration: 4000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right,#e53935,#e35d5b)"
                        }
                    }).showToast();
                })
                .finally(() => {
                    $btn.prop('disabled', false).text(original);
                });
        });
    });
</script>

<style>
    /* Hide Parsley error messages completely */
    .parsley-errors-list {
        margin: 0;
        padding: 0;
        list-style: none;
        display: none !important;
    }

    /* Error state: red border / glow */
    input.parsley-error,
    select.parsley-error,
    textarea.parsley-error {
        border-color: #e3342f !important;
        box-shadow: 0 0 0 0.08rem rgba(227, 52, 47, 0.35) !important;
    }

    /* Success state: optional (green border) */
    input.parsley-success,
    select.parsley-success,
    textarea.parsley-success {
        border-color: #38c172 !important;
        box-shadow: 0 0 0 0.08rem rgba(56, 193, 114, 0.35) !important;
    }
</style>
@endpush
