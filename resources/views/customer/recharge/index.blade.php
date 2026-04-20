@extends('layouts.customer')

@section('title', 'My Recharge Records')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">My Recharge Records</h3>
            <p class="text-muted mb-0">Track your wallet top-up submissions and statuses.</p>
        </div>

        <a href="{{ route('vendor.balance') }}"  class="btn btn-primary">
            New Recharge
        </a>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Please fix the following:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($recharges->count())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Amount</th>
                                <th>Currency</th>
                                <th>Reference</th>
                                <th>Status</th>
                                <th>Submitted</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recharges as $index => $recharge)
                                <tr>
                                    <td>{{ $recharges->firstItem() + $index }}</td>
                                    <td>{{ number_format($recharge->amount, 2) }}</td>
                                    <td>{{ $recharge->currency ?? '-' }}</td>
                                    <td>{{ $recharge->transaction_reference ?: '-' }}</td>
                                    <td>
                                        @if($recharge->status === 'approved')
                                            <span class="badge badge-success">Approved</span>
                                        @elseif($recharge->status === 'rejected')
                                            <span class="badge badge-danger">Rejected</span>
                                        @else
                                            <span class="badge badge-warning">Pending</span>
                                        @endif
                                    </td>
                                    <td>{{ optional($recharge->created_at)->format('d M Y, h:i A') }}</td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            <a href="{{ route('customer.recharges.show', $recharge) }}" class="btn btn-sm btn-outline-info">
                                                View
                                            </a>

                                            @if($recharge->status === 'pending')
                                                <form action="{{ route('customer.recharges.destroy', $recharge) }}" method="POST" onsubmit="return confirm('Delete this recharge record?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $recharges->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="mb-2">No recharge record yet</h5>
                    <p class="text-muted mb-3">Click the button below to submit your first recharge.</p>
                    <a href ="{{ route('vendor.balance') }}" class="btn btn-primary">
                        New Recharge
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('modal_wrapper')
<div class="modal fade" id="addRechargeModal" tabindex="-1" role="dialog" aria-labelledby="addRechargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content wallet-modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title wallet-modal-title" id="addRechargeModalLabel">Submit a recharge</h5>
                <button type="button" class="close wallet-modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('customer.recharges.store') }}" method="POST" enctype="multipart/form-data" id="addRechargeForm" data-parsley-validate>
                @csrf

                <div class="modal-body pt-3">
                    <div class="form-group mb-4">
                        <label for="payment_wallet_id" class="wallet-label">Wallet ID</label>
                        <input
                            type="number"
                            class="form-control wallet-input"
                            id="payment_wallet_id"
                            name="payment_wallet_id"
                            value="{{ old('payment_wallet_id') }}"
                            placeholder="Enter wallet ID"
                            required
                            data-parsley-required="true"
                        >
                    </div>

                    <div class="form-group mb-4">
                        <label for="amount" class="wallet-label">
                            <span class="text-danger">*</span> Amount
                        </label>
                        <input
                            type="number"
                            step="0.01"
                            min="0.01"
                            class="form-control wallet-input"
                            id="amount"
                            name="amount"
                            value="{{ old('amount') }}"
                            placeholder="Enter amount"
                            required
                            data-parsley-required="true"
                        >
                    </div>

                    <div class="form-group mb-4">
                        <label for="currency" class="wallet-label">Currency</label>
                        <input
                            type="text"
                            class="form-control wallet-input"
                            id="currency"
                            name="currency"
                            value="{{ old('currency') }}"
                            placeholder="e.g USD, NGN, USDT"
                            required
                            data-parsley-required="true"
                        >
                    </div>

                    <div class="form-group mb-4">
                        <label for="transaction_reference" class="wallet-label">Transaction Reference</label>
                        <input
                            type="text"
                            class="form-control wallet-input"
                            id="transaction_reference"
                            name="transaction_reference"
                            value="{{ old('transaction_reference') }}"
                            placeholder="Enter transaction reference"
                        >
                    </div>

                    <div class="form-group mb-0">
                        <label for="proof_path" class="wallet-label">Proof of Payment</label>
                        <input
                            type="file"
                            class="form-control"
                            id="proof_path"
                            name="proof_path"
                            accept=".jpg,.jpeg,.png,.pdf,.webp"
                        >
                    </div>
                </div>

                <div class="modal-footer border-0 pt-2">
                    <button type="submit" class="btn wallet-submit-btn btn-block">
                        submit
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .wallet-modal-content {
        border: none;
        border-radius: 0;
        padding: 8px 8px 0;
    }

    .wallet-modal-title {
        font-size: 20px;
        font-weight: 700;
        color: #222;
    }

    .wallet-modal-close {
        font-size: 28px;
        font-weight: 300;
        color: #999;
        opacity: 1;
        outline: none !important;
        box-shadow: none !important;
        background: transparent;
        border: 0;
    }

    .wallet-label {
        display: block;
        font-size: 16px;
        font-weight: 500;
        color: #333;
        margin-bottom: 12px;
    }

    .wallet-input {
        display: block;
        width: 100%;
        height: 58px;
        min-height: 58px;
        padding: 0 20px;
        border: 1px solid #d9d9d9;
        border-radius: 20px;
        background-color: #fff;
        color: #495057;
        font-size: 16px;
        line-height: 58px;
        box-shadow: none !important;
        overflow: visible;
    }

    .wallet-input:focus {
        border-color: #40a9ff;
        box-shadow: none !important;
        outline: none;
    }

    input.wallet-input {
        line-height: normal;
        padding-top: 0;
        padding-bottom: 0;
    }

    .wallet-submit-btn {
        background: #2ea3f2;
        border: none;
        color: #fff;
        font-size: 18px;
        font-weight: 600;
        height: 56px;
        border-radius: 8px;
        text-transform: lowercase;
    }

    .wallet-submit-btn:hover,
    .wallet-submit-btn:focus {
        background: #1f94e4;
        color: #fff;
        box-shadow: none;
    }

    .parsley-errors-list {
        list-style: none;
        padding-left: 0;
        margin: 8px 0 0;
        color: #ff4d4f;
        font-size: 14px;
    }

    .parsley-error {
        border-color: #ff4d4f !important;
    }

    .wallet-input.form-control {
        height: 58px !important;
        min-height: 58px !important;
        padding-top: 0 !important;
        padding-bottom: 0 !important;
        line-height: normal !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function () {
    @if($errors->any())
        $('#addRechargeModal').modal('show');
    @endif
});
</script>
@endpush