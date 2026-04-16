@extends('layouts.admin')

@section('title', 'User Wallet Top Up')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">User Wallet Top Up</h1>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-triangle"></i>
            Please fix the highlighted errors and try again.
            <ul class="mb-0 mt-2 ps-3">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Filters</h6>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('admin.wallet-options.topup') }}">
                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="search" class="form-label">Search</label>
                            <input
                                type="text"
                                class="form-control"
                                id="search"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="Search by name, email, contact, customer ID..."
                            >
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="verified" class="form-label">Verification Status</label>
                            <select class="form-control" id="verified" name="verified">
                                <option value="">All Status</option>
                                <option value="verified" {{ request('verified') == 'verified' ? 'selected' : '' }}>Verified</option>
                                <option value="unverified" {{ request('verified') == 'unverified' ? 'selected' : '' }}>Unverified</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-3"></div>

                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fa fa-filter"></i> Filter
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Users</h6>
            <span class="text-muted small">Total: {{ $users->total() }}</span>
        </div>

        <div class="card-body">
            @if ($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="thead-light">
                            <tr>
                                <th>User</th>
                                <th>Contact Info</th>
                                <th>Customer ID</th>
                                {{-- <th>Status</th> --}}
                                <th>Wallet Balance</th>
                                <th>Registered</th>
                                <th width="160">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                         <tbody>
    @foreach ($users as $user)
        @php
            $wallet = $user->wallet;
            $accountBalance = $wallet ? number_format((float) $wallet->account_balance, 2) : '0.00';
            $availableBalance = $wallet ? number_format((float) $wallet->available_balance, 2) : '0.00';
            $onHold = $wallet ? number_format((float) $wallet->on_hold, 2) : '0.00';
            $currency = 'USD';
        @endphp

        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <div
                        class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3"
                        style="width: 40px; height: 40px;"
                    >
                        <span class="text-white font-weight-bold">
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        </span>
                    </div>

                    <div>
                        <strong>{{ $user->name }}</strong>

                        @if (!empty($user->nickname))
                            <br>
                            <small class="text-muted">@ {{ $user->nickname }}</small>
                        @endif

                        <br>
                        <small class="text-muted">{{ $user->email }}</small>
                    </div>
                </div>
            </td>

            <td>
                @if ($user->contact)
                    @php
                        $isPhone = preg_match('/^([0-9\s\-\+\(\)]*)$/', $user->contact);
                    @endphp

                    @if ($isPhone)
                        <i class="fa fa-phone text-muted me-1"></i> {{ $user->contact }}
                    @else
                        <i class="fa fa-envelope text-muted me-1"></i> {{ $user->contact }}
                    @endif
                @else
                    <span class="text-muted">No contact</span>
                @endif
            </td>

            <td>
                <code>{{ $user->customer_id ?? '—' }}</code>
            </td>

            <td>
                @if ($wallet)
                    <div class="font-weight-bold text-success">
                        {{ $availableBalance }} {{ $currency }}
                    </div>
                    <small class="text-muted d-block mt-1">
                        Total: {{ $accountBalance }} {{ $currency }} |
                        Hold: {{ $onHold }} {{ $currency }}
                    </small>
                @else
                    <span class="text-muted">0.00 {{ $currency }}</span>
                @endif
            </td>

            <td>
                <small class="text-muted">
                    {{ optional($user->created_at)->format('h:i A') }}
                    @if($user->created_at)
                        • {{ $user->created_at->diffForHumans() }}
                    @endif
                </small>
            </td>

            <td>
                <button
                    type="button"
                    class="btn btn-success btn-sm open-topup-modal"
                    data-bs-toggle="modal"
                    data-bs-target="#topUpModal"
                    data-user-id="{{ $user->id }}"
                    data-user-name="{{ $user->name }}"
                    data-user-email="{{ $user->email }}"
                    data-currency="{{ $currency }}"
                    data-wallet-balance="{{ $availableBalance }}"
                    data-account-balance="{{ $accountBalance }}"
                    data-on-hold="{{ $onHold }}"
                >
                    <i class="fa fa-plus-circle"></i> Top Up
                </button>
            </td>
        </tr>
    @endforeach
</tbody>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $users->firstItem() }} to {{ $users->lastItem() }} of {{ $users->total() }} entries
                    </div>
                    <div>
                        {{ $users->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa fa-users fa-3x text-muted mb-3"></i>
                    <h4>No Users Found</h4>
                    <p class="text-muted mb-0">No users matched the current filter.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="modal fade" id="topUpModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">

            <form method="POST" action="{{ route('admin.wallet-options.topup.store') }}" id="topUpForm">
                @csrf

                <input type="hidden" name="user_id" id="topup_user_id">

                <div class="modal-header py-2">
                    <h5 class="modal-title" id="topupModalTitle">Wallet Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    {{-- <!-- User Info -->
                    <div class="mb-3">
                        <strong id="topup_user_name">User Name</strong><br>
                        <small class="text-muted" id="topup_user_email"></small>
                    </div> --}}

                    <!-- Balance -->
                    <div class="alert alert-light py-2 mb-3">
                        Current Balance:
                        <strong id="topup_wallet_balance">₦0.00</strong>
                    </div>

                    <!-- Action -->
                    <div class="mb-2">
                        <label class="form-label">Action</label>
                        <select name="action" id="topup_action" class="form-control" required>
                            <option value="credit">🟢 Fund Wallet</option>
                            <option value="debit">🔴 Deduct Wallet</option>
                        </select>
                    </div>

                    <!-- Amount -->
                    <div class="mb-2">
                        <label class="form-label">Amount</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0.01"
                            name="amount"
                            id="topup_amount"
                            class="form-control"
                            placeholder="Enter amount"
                            required
                        >
                        <small class="text-danger d-none" id="topup_error"></small>
                    </div>

                    {{-- <!-- Reference -->
                    <div class="mb-2">
                        <label class="form-label">Reference (optional)</label>
                        <input
                            type="text"
                            name="transaction_reference"
                            class="form-control"
                            placeholder="Auto-generated if empty"
                        >
                    </div>

                    <!-- Admin Note -->
                    <div class="mb-2">
                        <label class="form-label">Admin Note</label>
                        <textarea
                            name="admin_note"
                            class="form-control"
                            rows="2"
                            placeholder="Optional note for audit"
                        ></textarea>
                    </div> --}}

                </div>

                <div class="modal-footer py-2">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" id="topup_submit_btn" class="btn btn-success btn-sm">
                        Fund Wallet
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

<script>
    $(function () {
        function showError(message) {
            if (typeof Swal !== 'undefined') {
                Swal.fire('Error', message, 'error');
            } else {
                alert(message);
            }
        }

        function populateTopupModal(data) {
            const userId = data.userId || '';
            const userName = data.userName || '—';
            const userEmail = data.userEmail || '';
            const walletBalance = data.walletBalance || '0.00';

            $('#topup_user_id').val(userId);
            $('#topup_user_name').text(userName);
            $('#topup_user_email').text(userEmail);
            $('#topup_wallet_balance').text(walletBalance);
        }

        $(document).on('click', '.open-topup-modal', function () {
            populateTopupModal({
                userId: $(this).data('user-id'),
                userName: $(this).data('user-name'),
                userEmail: $(this).data('user-email'),
                walletBalance: $(this).data('wallet-balance')
            });
        });

        $('#topUpForm').on('submit', function (event) {
            const amountRaw = $(this).find('input[name="amount"]').val();
            const amount = parseFloat(amountRaw);

            if (amountRaw === '' || isNaN(amount) || amount <= 0) {
                event.preventDefault();
                showError('Please enter a valid amount.');
                return false;
            }
        });

        @if ($errors->any() && old('user_id'))
            populateTopupModal({
                userId: @json(old('user_id')),
                userName: @json(old('user_name', 'Selected User')),
                userEmail: @json(old('user_email', '')),
                walletBalance: '0.00'
            });

            const modalEl = document.getElementById('topUpModal');
            if (modalEl) {
                const topUpModal = new bootstrap.Modal(modalEl);
                topUpModal.show();
            }
        @endif
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const actionSelect = document.getElementById('topup_action');
    const submitBtn = document.getElementById('topup_submit_btn');
    const modalTitle = document.getElementById('topupModalTitle');
    const amountInput = document.getElementById('topup_amount');
    const balanceEl = document.getElementById('topup_wallet_balance');
    const errorEl = document.getElementById('topup_error');

    function getBalance() {
        let balanceText = balanceEl.innerText.replace(/[₦,]/g, '');
        return parseFloat(balanceText) || 0;
    }

    function updateUI() {
        const action = actionSelect.value;

        if (action === 'credit') {
            submitBtn.classList.remove('btn-danger');
            submitBtn.classList.add('btn-success');
            submitBtn.innerText = 'Fund Wallet';
            modalTitle.innerText = 'Fund Wallet';
        } else {
            submitBtn.classList.remove('btn-success');
            submitBtn.classList.add('btn-danger');
            submitBtn.innerText = 'Deduct Wallet';
            modalTitle.innerText = 'Deduct Wallet';
        }

        validateAmount();
    }

    function validateAmount() {
        const action = actionSelect.value;
        const amount = parseFloat(amountInput.value || 0);
        const balance = getBalance();

        errorEl.classList.add('d-none');

        if (action === 'debit' && amount > balance) {
            errorEl.innerText = 'Insufficient balance for this deduction.';
            errorEl.classList.remove('d-none');
            submitBtn.disabled = true;
        } else {
            submitBtn.disabled = false;
        }
    }

    actionSelect.addEventListener('change', updateUI);
    amountInput.addEventListener('input', validateAmount);

    updateUI();
});
</script>
@endpush

