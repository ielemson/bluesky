@extends('layouts.customer')

@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => Auth()->user()->nickname,
        'header_2' => 'Dashboard',
    ])
@endsection

@section('content')
    <div class="container py-4">
        <h4 class="mb-3">Wallet Management</h4>

        <div class="card shadow-sm border-0">
            <div class="card-body p-10">

                @forelse($wallets as $wallet)
                    <div class="d-flex justify-content-between align-items-start p-3 border-bottom">
                        <div>
                            <div class="fw-semibold mb-1">
                                Wallet Address:
                                <span>
                                    {{ Str::mask($wallet->address, '*', 4, max(strlen($wallet->address) - 7, 0)) }}
                                </span>
                            </div>
                            <div class="small">
                                Currency:
                                <span class="fw-semibold">{{ $wallet->option->currency }}</span>
                            </div>
                            <div class="small">
                                Chain Name:
                                <span class="fw-semibold">{{ $wallet->option->chain }}</span>
                            </div>
                        </div>

                        <div class="text-end">
                            <button type="button" class="btn btn-link p-0 me-3 text-primary btn-edit-wallet"
                                data-id="{{ $wallet->id }}" data-option="{{ $wallet->option->id }}"
                                data-address="{{ $wallet->address }}">
                                <i class="far fa-edit fa-lg"></i>
                            </button>
                            <button type="button" class="btn btn-link p-0 text-danger btn-delete-wallet"
                                data-id="{{ $wallet->id }}">
                                <i class="far fa-trash-alt fa-lg"></i>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="p-3">
                        <p class="text-muted small mb-0">
                            You have not added any payout wallet yet.
                        </p>
                    </div>
                @endforelse

                <div class="p-2 border-top">
                    <button type="button" class="btn btn-primary w-100 py-3" data-toggle="modal"
                        data-target="#addWalletModal">
                        Add a wallet
                    </button>
                </div>

            </div>
        </div>
    </div>
@endsection

@section('modal_wapper')
    {{-- Add Wallet Modal --}}
    <div class="modal fade" id="addWalletModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{ gtrans('Add payout wallet') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="{{ gtrans('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="addWalletForm" data-parsley-validate>
                    @csrf
                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <label class="mb-1 small fw-semibold">{{ gtrans('Wallet option') }}</label>
                            <select class="custom-select form-control" name="payout_wallet_option_id" id="add_wallet_option"
                                required data-parsley-required="true" data-parsley-trigger="change">
                                <option value="">{{ gtrans('Select currency & chain') }}</option>
                                @foreach ($options as $opt)
                                    <option value="{{ $opt->id }}">
                                        {{ $opt->currency }} – {{ $opt->chain }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                {{ gtrans('Choose the currency and chain you want to receive withdrawals with (e.g. USDT – TRC‑20).') }}
                            </small>
                        </div>

                        <div class="form-group mb-0">
                            <label class="mb-1 small fw-semibold">{{ gtrans('Wallet address') }}</label>
                            <input type="text" class="form-control" name="address" id="add_wallet_address"
                                placeholder="{{ gtrans('Please enter your wallet address') }}" required
                                data-parsley-required="true">
                            <small class="form-text text-muted">
                                {{ gtrans('Make sure this address matches the selected chain to avoid loss of funds.') }}
                            </small>
                        </div>

                    </div>

                    <div class="modal-footer modal-footer-uniform">
                        <button type="submit" class="btn btn-primary">
                            {{ gtrans('Save wallet') }}
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                            {{ gtrans('Cancel') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Edit Wallet Modal --}}
    <div class="modal fade" id="editWalletModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{ gtrans('Edit payout wallet') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="{{ gtrans('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="editWalletForm" data-parsley-validate>
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="wallet_id" id="edit_wallet_id" required data-parsley-required="true">

                    <div class="modal-body">

                        <div class="form-group mb-3">
                            <label class="mb-1 small fw-semibold">{{ gtrans('Wallet option') }}</label>
                            <select class="custom-select form-control" name="payout_wallet_option_id"
                                id="edit_wallet_option" required data-parsley-required="true" data-parsley-trigger="change">
                                <option value="">{{ gtrans('Select currency & chain') }}</option>
                                @foreach ($options as $opt)
                                    <option value="{{ $opt->id }}">
                                        {{ $opt->currency }} – {{ $opt->chain }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mb-0">
                            <label class="mb-1 small fw-semibold">{{ gtrans('Wallet address') }}</label>
                            <input type="text" class="form-control" name="address" id="edit_wallet_address" required
                                data-parsley-required="true">
                        </div>

                    </div>

                    <div class="modal-footer modal-footer-uniform">
                        <button type="submit" class="btn btn-primary">
                            {{ gtrans('Update wallet') }}
                        </button>
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                            {{ gtrans('Cancel') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

{{-- Copy toast (unused here but kept if needed elsewhere) --}}
<div id="copyToast"
    class="position-fixed top-0 start-50 translate-middle-x mt-3 px-3 py-2 bg-green text-white rounded shadow-sm"
    style="z-index:1080;display:none;font-size:0.875rem;">
    {{ gtrans('Copy Success') }}
</div>

@push('scripts')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script> [web:65][web:69]

    <script>
        $(function() {
            var $addForm = $('#addWalletForm');
            var $editForm = $('#editWalletForm');

            $addForm.parsley();
            $editForm.parsley(); // [web:65][web:101]

            // ADD WALLET
            $addForm.on('submit', function(e) {
                e.preventDefault();

                $addForm.parsley().validate();
                if (!$addForm.parsley().isValid()) {
                    showToast('{{ gtrans('Please fill in the required fields.') }}', 'error');
                    return;
                }

                $.ajax({
                    url: '{{ route('customer.wallets.store') }}',
                    method: 'POST',
                    data: $addForm.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(res) {
                        showToast(res.message || '{{ gtrans('Wallet added.') }}', 'success');
                        window.location.reload();
                    },
                    error: function(xhr) {
                        let msg = '{{ gtrans('Unable to add wallet. Please try again.') }}';
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors)[0][0] || msg;
                        }
                        showToast(msg, 'error');
                    }
                });
            });

            // OPEN EDIT MODAL
            $('.btn-edit-wallet').on('click', function() {
                var walletId = $(this).data('id');
                var optionId = $(this).data('option');
                var addressVal = $(this).data('address');

                $('#edit_wallet_id').val(walletId);
                $('#edit_wallet_option').val(optionId);
                $('#edit_wallet_address').val(addressVal);

                $('#editWalletModal').modal('show');
            });

            // EDIT SUBMIT
            $editForm.on('submit', function(e) {
                e.preventDefault();

                $editForm.parsley().validate();
                if (!$editForm.parsley().isValid()) {
                    showToast('{{ gtrans('Please fill in the required fields.') }}', 'error');
                    return;
                }

                var walletId = $('#edit_wallet_id').val();

                $.ajax({
                    url: '{{ route('customer.wallets.update', ':id') }}'.replace(':id', walletId),
                    method: 'POST',
                    data: $editForm.serialize(),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-HTTP-Method-Override': 'PUT'
                    },
                    success: function(res) {
                        showToast(res.message || '{{ gtrans('Wallet updated.') }}', 'success');
                        window.location.reload();
                    },
                    error: function(xhr) {
                        let msg = '{{ gtrans('Unable to update wallet. Please try again.') }}';
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            msg = Object.values(xhr.responseJSON.errors)[0][0] || msg;
                        }
                        showToast(msg, 'error');
                    }
                });
            });

            // DELETE WALLET
            $('.btn-delete-wallet').on('click', function() {
                var walletId = $(this).data('id');

                Swal.fire({
                    icon: 'warning',
                    title: '{{ gtrans('Delete wallet?') }}',
                    text: '{{ gtrans('This payout wallet will be removed from your account.') }}',
                    showCancelButton: true,
                    confirmButtonText: '{{ gtrans('Yes, delete') }}',
                    cancelButtonText: '{{ gtrans('Cancel') }}',
                }).then(function(result) {
                    if (!result.isConfirmed) return;

                    $.ajax({
                        url: '{{ route('customer.wallets.destroy', ':id') }}'.replace(':id',
                            walletId),
                        method: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(res) {
                            showToast(res.message || '{{ gtrans('Wallet deleted.') }}',
                                'success');
                            window.location.reload();
                        },
                        error: function() {
                            showToast(
                                '{{ gtrans('Unable to delete wallet. Please try again.') }}',
                                'error');
                        }
                    });
                });
            });

            function showToast(text, type) {
                Toastify({
                    text: text,
                    duration: 4000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    stopOnFocus: true,
                    style: {
                        background: type === 'success' ?
                            "linear-gradient(to right, #00b09b, #96c93d)" :
                            "linear-gradient(to right, #e53935, #e35d5b)"
                    }
                }).showToast(); // [web:69][web:81]
            }
        });
    </script>

    <style>
        .parsley-error {
            border-color: #dc3545 !important;
            box-shadow: 0 0 0 0.1rem rgba(220, 53, 69, 0.15);
        }

        .parsley-errors-list {
            margin: 4px 0 0;
            padding: 0;
            list-style: none;
            font-size: 0.8rem;
            color: #dc3545;
        }

        .parsley-errors-list.filled {
            display: block;
        }

        .parsley-success {
            border-color: #28a745 !important;
            box-shadow: 0 0 0 0.1rem rgba(40, 167, 69, 0.15);
        }
    </style>
@endpush
