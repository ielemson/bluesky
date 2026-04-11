@extends('layouts.customer')

@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => Auth()->user()->nickname,
        'header_2' => 'Dashboard',
    ])
@endsection

@section('content')
    <section class="content">

        {{-- Balances --}}
        <div class="row justify-content-center">
            <div class="col-lg-12 my-5">
                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <h4 class="mb-4 fw-bold">{{ gtrans('My Balance') }}</h4>

                        <div class="row g-3 mb-4">
                            {{-- Account Balance (total) --}}
                            <div class="col-md-6">
                                <div class="border rounded-3 p-4 h-100">
                                    <div class="text-muted small mb-1">
                                        {{ gtrans('Account Balance') }}
                                    </div>
                                    <div class="h4 fw-bold mb-0">
                                        ${{ number_format($wallet->account_balance, 2) }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ gtrans('Includes available + on hold') }}
                                    </div>
                                </div>
                            </div>

                            {{-- Available Balance --}}
                            <div class="col-md-6">
                                <div class="border rounded-3 p-4 h-100 bg-light">
                                    <div class="text-muted small mb-1">
                                        {{ gtrans('Available Balance') }}
                                    </div>
                                    <div class="h4 fw-bold mb-0">
                                        ${{ number_format($wallet->available_balance, 2) }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ gtrans('Funds you can use immediately') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="d-flex flex-wrap gap-2">
                            <a href="javascript:;" id="btnTopUp" class="btn btn-primary px-4" data-toggle="modal"
                                data-target="#topUpModal">
                                {{ gtrans('Top up') }}
                            </a>

                            <a href="javascript:;" class="btn btn-outline-primary px-4" data-toggle="modal"
                                data-target="#withdrawModal">
                                {{ gtrans('Withdrawal') }}
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- Pending deposits (unchanged) --}}
        <h5 class="mb-3">{{ gtrans('Pending wallet recharges') }}</h5>

        <div class="card mb-4">
            <div class="card-header py-2">
                <h6 class="mb-0 small text-uppercase">
                    {{ gtrans('Pending transactions') }}
                </h6>
            </div>

            <div class="card-body p-0">
                @if ($pendingDeposits->isEmpty())
                    <p class="text-muted small m-3">
                        {{ gtrans('You have no pending recharge requests.') }}
                    </p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm mb-0 align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ gtrans('Date') }}</th>
                                    <th>{{ gtrans('Method') }}</th>
                                    <th>{{ gtrans('Network') }}</th>
                                    <th class="text-end">{{ gtrans('Amount') }}</th>
                                    <th>{{ gtrans('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingDeposits as $deposit)
                                    <tr>
                                        <td>{{ $deposit->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ optional($deposit->paymentWallet)->method }}</td>
                                        <td>{{ optional($deposit->paymentWallet)->network ?? '-' }}</td>
                                        <td class="text-end">
                                            {{ number_format($deposit->amount, 2) }} {{ $deposit->currency }}
                                        </td>
                                        <td>
                                            <span class="badge bg-warning text-dark">
                                                {{ strtoupper($deposit->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </section>
@endsection

@section('modal_wapper')
    {{-- Top‑up Modal --}}
    <div class="modal center-modal fade" id="topUpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{ gtrans('Recharge Center') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ gtrans('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="topUpForm" enctype="multipart/form-data" data-parsley-validate>
                    @csrf

                    <div class="modal-body">

                        <input type="hidden" name="wallet_id" id="wallet_id" required data-parsley-required="true">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-1">{{ gtrans('Recharge Method') }}</label>
                                    <select class="custom-select form-control" id="recharge_method" required
                                        data-parsley-required="true" data-parsley-trigger="change">
                                        <option value="">{{ gtrans('Select method') }}</option>
                                        {{-- filled by JS --}}
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="mb-1">{{ gtrans('Please select the recharge network') }}</label>
                                    <select class="custom-select form-control" id="recharge_network" required
                                        data-parsley-required="true" data-parsley-trigger="change">
                                        <option value="">{{ gtrans('Select network') }}</option>
                                        {{-- filled by JS --}}
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <div class="me-3">
                                <img id="wallet_qr" src="{{ asset('assets/images/sample_qr.png') }}" alt="QR"
                                    style="width:80px;height:80px;object-fit:contain;">
                            </div>
                            <div class="small">
                                <div class="text-muted mb-1">
                                    {{ gtrans('Scan the QR code to recharge') }}
                                </div>
                                <div id="depositAddress" class="mb-1 small text-monospace">
                                    {{-- filled by JS --}}
                                </div>
                                <button type="button" id="btnCopyAddress" class="btn btn-link p-0 small">
                                    {{ gtrans('Copy deposit address') }}
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-semibold">
                                {{ gtrans('Recharge amount (USDT)') }}
                            </label>
                            <input type="number" name="amount" id="recharge_amount" min="0" step="0.01"
                                class="form-control" data-parsley-type="number" data-parsley-min="0.01"
                                placeholder="{{ gtrans('Please enter the recharge amount (USDT)') }}">
                        </div>

                        <div class="mb-3" id="amount_received_group" style="display:none;">
                            <label class="form-label small fw-semibold">
                                {{ gtrans('Amount received (USDT)') }}
                            </label>
                            <input type="number" name="amount_received" id="amount_received" min="0"
                                step="0.01" class="form-control"
                                placeholder="{{ gtrans('Amount actually received (USDT)') }}" readonly>
                        </div>

                        <div class="mb-1">
                            <label class="form-label small fw-semibold d-block text-center mb-2">
                                {{ gtrans('Upload recharge voucher') }}
                            </label>

                            <div class="d-flex justify-content-center">
                                <input type="file" name="voucher" id="voucherInput" class="d-none" accept="image/*">

                                <div id="voucherPreview"
                                    class="border rounded d-flex flex-column align-items-center justify-content-center"
                                    style="width:130px;height:130px;cursor:pointer;">
                                    <span class="display-6 text-muted">+</span>
                                    <span class="small text-muted text-center">
                                        {{ gtrans('Upload Credentials') }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="modal-footer modal-footer-uniform">
                        <button type="submit" class="btn btn-bold btn-pure btn-primary">
                            {{ gtrans('Confirm recharge') }}
                        </button>
                        <button type="button" class="btn btn-bold btn-pure btn-info" data-dismiss="modal">
                            {{ gtrans('Cancel Top up') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Withdrawal Modal --}}
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered"> {{-- wider / landscape --}}
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{ gtrans('Withdrawal Center') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="{{ gtrans('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="withdrawForm" data-parsley-validate>
                    @csrf

                    <div class="modal-body">

                        {{-- method id (payment_wallet / withdrawal_method) --}}
                        <input type="hidden" name="withdraw_method_id" id="withdraw_method_id" required
                            data-parsley-required="true">

                        {{-- Withdrawal Methods --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">
                                {{ gtrans('Withdrawal Methods') }}
                            </label>
                            <select class="custom-select form-control" id="withdraw_method" required
                                data-parsley-required="true" data-parsley-trigger="change">
                                <option value="">{{ gtrans('Select method') }}</option>
                                {{-- filled by JS --}}
                            </select>
                        </div>

                        {{-- Currency + chain --}}
                        <div class="row mb-3">
                            <div class="col-6 d-flex flex-column justify-content-center">
                                <div class="text-muted small mb-1">
                                    {{ gtrans('Currency') }}
                                </div>
                                <div class="fw-semibold" id="withdraw_currency">
                                    BTC {{-- default / filled by JS --}}
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <div class="text-muted small mb-1">
                                    {{ gtrans('Chain Name') }}
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="withdraw_chain_btn">
                                    BitCoin {{-- filled by JS --}}
                                </button>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-danger">
                                * {{ gtrans('Withdrawal address') }}：
                            </label>
                            <select class="custom-select form-control" name="address_id" id="withdraw_address" required
                                data-parsley-required="true" data-parsley-trigger="change">
                                <option value="">{{ gtrans('Select withdrawal address') }}</option>
                                {{-- user saved addresses via JS --}}
                            </select>
                        </div>

                        {{-- Amount --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-danger">
                                * {{ gtrans('Withdrawal amount') }}：
                            </label>
                            <input type="number" name="amount" id="withdraw_amount" min="0.00000001"
                                step="0.00000001" class="form-control" required data-parsley-required="true"
                                data-parsley-type="number" data-parsley-min="0.00000001"
                                placeholder="{{ gtrans('Please enter the withdrawal amount') }}">
                        </div>

                        {{-- Password --}}
                        <div class="mb-3">
                            <label class="form-label small fw-semibold text-danger">
                                * {{ gtrans('Withdrawal password') }}：
                            </label>
                            <div class="input-group">
                                <input type="password" name="withdraw_password" id="withdraw_password"
                                    class="form-control" required data-parsley-required="true"
                                    placeholder="{{ gtrans('Please enter the withdrawal password') }}">
                                <span class="input-group-text" id="toggle_withdraw_password" style="cursor:pointer;">
                                    <i class="lnr lnr-eye"></i>
                                </span>
                            </div>
                        </div>

                        {{-- Available balance --}}
                        <div class="mb-3 small text-muted">
                            {{ gtrans('Available balance') }}:
                            <span id="withdraw_available_balance">
                                $0.00 {{-- filled by JS from wallet.available_balance --}}
                            </span>
                        </div>

                        {{-- Notice --}}
                        <div class="mb-0 small text-muted">
                            {{ gtrans('The credited amount will be settled according to the relevant fees charged by your receiving account or the real-time exchange rate.') }}
                            <br>
                            {{ gtrans('Your withdrawal will be credited within 24 hours, please wait patiently! If it is not credited within 24 hours, please contact online customer service!') }}
                        </div>

                    </div>

                    <div class="modal-footer border-0">
                        <button type="submit" class="btn btn-primary w-100">
                            {{ gtrans('Withdrawal') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

{{-- Copy toast --}}
<div id="copyToast"
    class="position-fixed top-0 start-50 translate-middle-x mt-3 px-3 py-2 bg-green text-white rounded shadow-sm"
    style="z-index:1080;display:none;font-size:0.875rem;">
    {{ gtrans('Copy Success') }}
</div>

@push('scripts')
    {{-- Vendor scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script>
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyToast = document.getElementById('copyToast');
            const methodSelect = document.getElementById('recharge_method');
            const networkSelect = document.getElementById('recharge_network');
            const qrImg = document.getElementById('wallet_qr');
            const addrEl = document.getElementById('depositAddress');
            const walletIdInput = document.getElementById('wallet_id');
            const amountInput = document.getElementById('recharge_amount');
            const amountGroup = document.getElementById('amount_received_group');
            const amountReceived = document.getElementById('amount_received');
            const copyBtn = document.getElementById('btnCopyAddress');
            const voucherInput = document.getElementById('voucherInput');
            const voucherPreview = document.getElementById('voucherPreview');
            const topUpForm = document.getElementById('topUpForm');

            const txtSelectMethod = "{{ gtrans('Select method') }}";
            const txtSelectNetwork = "{{ gtrans('Select network') }}";
            const txtDefaultNet = "{{ gtrans('Default') }}";

            let wallets = [];

            function showCopyToast() {
                if (!copyToast) return;
                copyToast.style.display = 'block';
                setTimeout(function() {
                    copyToast.style.display = 'none';
                }, 1500);
            }

            function syncAmountReceived() {
                if (amountGroup.style.display === 'block') {
                    const v = amountInput.value;
                    amountReceived.value = v ? parseFloat(v).toFixed(2) : '';
                }
            }

            function updateWalletDetails() {
                const method = methodSelect.value;
                const network = networkSelect.value || null;

                let wallet = wallets.find(w => w.method === method && w.network === network && w.is_primary) ||
                    wallets.find(w => w.method === method && w.network === network) ||
                    wallets.find(w => w.method === method);

                if (!wallet) {
                    qrImg.src = "{{ asset('assets/images/sample_qr.png') }}";
                    addrEl.textContent = '';
                    walletIdInput.value = '';
                    amountGroup.style.display = 'none';
                    amountReceived.value = '';
                    $(amountInput).attr('data-parsley-required', 'false');
                    return;
                }

                walletIdInput.value = wallet.id;
                addrEl.textContent = wallet.deposit_address;

                qrImg.src = wallet.qr_image_path ?
                    "{{ url('/') }}/" + wallet.qr_image_path :
                    "{{ asset('assets/images/sample_qr.png') }}";

                if (wallet.method && wallet.method.toLowerCase() === 'usdt') {
                    amountGroup.style.display = 'block';
                    syncAmountReceived();
                    $(amountInput).attr('data-parsley-required', 'true');
                } else {
                    amountGroup.style.display = 'none';
                    amountReceived.value = '';
                    $(amountInput).attr('data-parsley-required', 'false');
                }

                if ($(amountInput).parsley) {
                    $(amountInput).parsley().reset();
                }
            }

            function updateNetworks(selectDefault) {
                const method = methodSelect.value;
                networkSelect.innerHTML = "<option value=''>" + txtSelectNetwork + "</option>";

                const filtered = wallets.filter(w => w.method === method);
                const networks = [...new Set(filtered.map(w => w.network || ''))];

                networks.forEach(function(n) {
                    const opt = document.createElement('option');
                    opt.value = n;
                    opt.textContent = n || txtDefaultNet;
                    networkSelect.appendChild(opt);
                });

                if (selectDefault && filtered.length) {
                    const primary = filtered.find(w => w.is_primary);
                    if (primary) {
                        networkSelect.value = primary.network || '';
                    } else if (networks.length) {
                        networkSelect.value = networks[0];
                    }
                }

                updateWalletDetails();
            }

            function populateMethods(selectDefault) {
                methodSelect.innerHTML = "<option value=''>" + txtSelectMethod + "</option>";

                const methods = [...new Set(wallets.map(w => w.method))];

                methods.forEach(function(m) {
                    const opt = document.createElement('option');
                    opt.value = m;
                    opt.textContent = m.toUpperCase();
                    methodSelect.appendChild(opt);
                });

                if (selectDefault && methods.length) {
                    const hasUsdt = methods.find(m => m && m.toLowerCase() === 'usdt');
                    methodSelect.value = hasUsdt || methods[0];
                }

                updateNetworks(selectDefault);
            }

            $('#topUpModal').on('show.bs.modal', function() {
                if (wallets.length === 0) {
                    fetch("{{ route('customer.payment-methods') }}", {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(r => r.json())
                        .then(function(data) {
                            wallets = data;
                            populateMethods(true);
                        })
                        .catch(function() {
                            populateMethods(true);
                        });
                } else {
                    populateMethods(false);
                }
            });

            methodSelect.addEventListener('change', function() {
                updateNetworks(false);
            });

            networkSelect.addEventListener('change', updateWalletDetails);
            amountInput.addEventListener('input', syncAmountReceived);

            if (copyBtn) {
                copyBtn.addEventListener('click', function() {
                    const text = addrEl.textContent.trim();
                    if (!text) return;

                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text)
                            .then(showCopyToast)
                            .catch(function() {});
                    } else {
                        const tmp = document.createElement('textarea');
                        tmp.value = text;
                        document.body.appendChild(tmp);
                        tmp.select();
                        try {
                            document.execCommand('copy');
                            showCopyToast();
                        } catch (e) {}
                        document.body.removeChild(tmp);
                    }
                });
            }

            if (voucherPreview && voucherInput) {
                voucherPreview.addEventListener('click', function() {
                    voucherInput.click();
                });

                voucherInput.addEventListener('change', function() {
                    const file = this.files && this.files[0];
                    if (!file) return;

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        voucherPreview.innerHTML = '';
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.alt = 'voucher';
                        img.style.width = '100%';
                        img.style.height = '100%';
                        img.style.objectFit = 'cover';
                        img.className = 'rounded';
                        voucherPreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                });
            }

            const parsleyInstance = $(topUpForm).parsley();

            topUpForm.addEventListener('submit', function(e) {
                e.preventDefault();

                parsleyInstance.whenValidate().done(function() {
                    const formData = new FormData(topUpForm);

                    fetch("{{ route('wallet.deposit.ajax.store') }}", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: formData,
                        })
                        .then(async (response) => {
                            const data = await response.json();
                            if (!response.ok) throw data;

                            Toastify({
                                text: data.message ||
                                    "{{ gtrans('Recharge proof submitted successfully.') }}",
                                duration: 3000,
                                gravity: "top",
                                position: "right",
                                style: {
                                    background: "#28a745"
                                },
                            }).showToast();

                            $('#topUpModal').modal('hide');
                            topUpForm.reset();

                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);

                            voucherPreview.innerHTML =
                                '<span class="display-6 text-muted">+</span>' +
                                '<span class="small text-muted text-center">{{ gtrans('Upload Credentials') }}</span>';

                            amountGroup.style.display = 'none';
                            amountReceived.value = '';
                        })
                        .catch((error) => {
                            let msg = "{{ gtrans('An error occurred.') }}";

                            if (error && error.errors) {
                                const firstField = Object.keys(error.errors)[0];
                                msg = error.errors[firstField][0];
                            } else if (error && error.message) {
                                msg = error.message;
                            }

                            Toastify({
                                text: msg,
                                duration: 4000,
                                gravity: "top",
                                position: "right",
                                style: {
                                    background: "#dc3545"
                                },
                            }).showToast();
                        });
                });
            });
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
