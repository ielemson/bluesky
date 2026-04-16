@extends('layouts.customer')

@section('title', 'My Wallet Payout Options')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">My Wallet Payout Options</h3>
            <p class="text-muted mb-0">Manage the wallets you want to use for payouts.</p>
        </div>

        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addWalletModal">
            Add Wallet
        </button>
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
            @if($wallets->count())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Currency</th>
                                <th>Chain</th>
                                <th>Wallet Address</th>
                                <th>Default</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($wallets as $index => $wallet)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $wallet->walletOption->currency ?? '-' }}</td>
                                    <td>{{ $wallet->walletOption->chain ?? '-' }}</td>
                                    <td>{{ $wallet->wallet_address }}</td>
                                    <td>
                                        @if($wallet->is_default)
                                            <span class="badge badge-success">Default</span>
                                        @else
                                            <span class="badge badge-secondary">No</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex gap-2 justify-content-end">
                                            @if(!$wallet->is_default)
                                                <form action="{{ route('customer.wallets.default', $wallet) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn btn-sm btn-outline-primary">
                                                        Set Default
                                                    </button>
                                                </form>
                                            @endif

                                            <form action="{{ route('customer.wallets.destroy', $wallet) }}" method="POST" onsubmit="return confirm('Remove this wallet?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="mb-2">No wallet added yet</h5>
                    <p class="text-muted mb-3">Click the button below to add your first payout wallet.</p>
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#addWalletModal">
                        Add Wallet
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@section('modal_wrapper')
<div class="modal fade" id="addWalletModal" tabindex="-1" role="dialog" aria-labelledby="addWalletModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content wallet-modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title wallet-modal-title" id="addWalletModalLabel">Add a wallet</h5>
                <button type="button" class="close wallet-modal-close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form action="{{ route('customer.wallets.store') }}" method="POST" id="addWalletForm" data-parsley-validate>
                @csrf

                <div class="modal-body pt-3">
                    <div class="form-group mb-4">
                        <label for="wallet_currency" class="wallet-label">Currency</label>
                        <div class="wallet-select-wrap">
                            <select
                                class="form-control wallet-input"
                                id="wallet_currency"
                                required
                                data-parsley-required="true"
                            >
                                <option value="">Select currency</option>
                                @foreach($walletOptions->pluck('currency')->unique()->values() as $currency)
                                    <option value="{{ $currency }}">{{ strtoupper($currency) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="wallet_chain" class="wallet-label">Chain Name</label>
                        <div class="wallet-select-wrap">
                            <select
                                class="form-control wallet-input"
                                id="wallet_chain"
                                name="payout_wallet_option_id"
                                required
                                data-parsley-required="true"
                            >
                                <option value="">Select chain</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group mb-4">
                        <label for="wallet_address" class="wallet-label">
                            <span class="text-danger">*</span> Wallet Address
                        </label>
                        <input
                            type="text"
                            class="form-control wallet-input"
                            id="wallet_address"
                            name="wallet_address"
                            value="{{ old('wallet_address') }}"
                            placeholder="Please enter your wallet address"
                            required
                            data-parsley-required="true"
                            data-parsley-trigger="keyup"
                        >
                    </div>

                    <div class="form-group mb-0">
                        <div class="form-check pl-0">
                            <input
                                type="checkbox"
                                class="form-check-input"
                                id="is_default"
                                name="is_default"
                                value="1"
                                {{ old('is_default') ? 'checked' : '' }}
                                style="margin-left: 0;"
                            >
                            <label class="form-check-label ml-4" for="is_default">
                                Make this my default wallet
                            </label>
                        </div>
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

    select.wallet-input {
        line-height: normal;
        padding-top: 0;
        padding-bottom: 0;
        padding-right: 45px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        background-image: none !important;
    }

    select.wallet-input::-ms-expand {
        display: none;
    }

    .wallet-select-wrap {
        position: relative;
    }

    .wallet-select-wrap::after {
        content: "▼";
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 12px;
        color: #9aa0a6;
        pointer-events: none;
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
    const currencySelect = document.getElementById('wallet_currency');
    const chainSelect = document.getElementById('wallet_chain');
    const walletOptions = window.payoutWalletOptions || [];

    function renderChains(selectedCurrency, selectedChainId) {
        if (!chainSelect) return;

        chainSelect.innerHTML = '<option value="">Select chain</option>';

        if (!selectedCurrency) {
            return;
        }

        walletOptions.forEach(function (option) {
            if (String(option.currency) === String(selectedCurrency)) {
                const opt = document.createElement('option');
                opt.value = option.id;
                opt.textContent = option.chain;

                if (String(selectedChainId || '') === String(option.id)) {
                    opt.selected = true;
                }

                chainSelect.appendChild(opt);
            }
        });
    }

    if (currencySelect) {
        currencySelect.addEventListener('change', function () {
            renderChains(this.value, '');
        });
    }

    @if(old('payout_wallet_option_id'))
        var oldOptionId = "{{ old('payout_wallet_option_id') }}";
        var oldOption = walletOptions.find(function (item) {
            return String(item.id) === String(oldOptionId);
        });

        if (oldOption && currencySelect) {
            currencySelect.value = oldOption.currency;
            renderChains(oldOption.currency, oldOptionId);
        }
    @endif

    @if($errors->any())
        $('#addWalletModal').modal('show');
    @endif
});
</script>
@endpush

@php
    $walletOptionsFormatted = $walletOptions->map(function ($option) {
        return [
            'id' => $option->id,
            'currency' => $option->currency,
            'chain' => $option->chain,
        ];
    })->values();
@endphp

@push('scripts')
<script>
    window.payoutWalletOptions = @json($walletOptionsFormatted);
</script>
@endpush