@extends('layouts.customer')

@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => Auth()->user()->nickname,
        'header_2' => 'Dashboard',
    ])
@endsection

@section('content')
    <section class="content">
        <div class="row justify-content-center">
            <div class="col-lg-12 my-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <h4 class="mb-4 font-weight-bold">{{ gtrans('My Balance') }}</h4>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-4 h-100">
                                    <div class="text-muted small mb-1">
                                        {{ gtrans('Account Balance') }}
                                    </div>
                                    <div class="h4 font-weight-bold mb-1">
                                        ${{ number_format($wallet->account_balance, 2) }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ gtrans('Includes available + on hold') }}
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <div class="border rounded p-4 h-100 bg-light">
                                    <div class="text-muted small mb-1">
                                        {{ gtrans('Available Balance') }}
                                    </div>
                                    <div class="h4 font-weight-bold mb-1">
                                        ${{ number_format($wallet->available_balance, 2) }}
                                    </div>
                                    <div class="small text-muted">
                                        {{ gtrans('Funds you can use immediately') }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="javascript:void(0);" id="btnTopUp" class="btn btn-primary mr-2" data-toggle="modal"
                                data-target="#topUpModal">
                                {{ gtrans('Top up') }}
                            </a>

                            <a href="javascript:void(0);" class="btn btn-outline-primary" data-toggle="modal"
                                data-target="#withdrawModal">
                                {{ gtrans('Withdrawal') }}
                            </a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

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
                                    <th class="text-right">{{ gtrans('Amount') }}</th>
                                    <th>{{ gtrans('Status') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pendingDeposits as $deposit)
                                    <tr>
                                        <td>{{ $deposit->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ optional($deposit->paymentWallet)->method }}</td>
                                        <td>{{ optional($deposit->paymentWallet)->network ?? '-' }}</td>
                                        <td class="text-right">
                                            {{ number_format($deposit->amount, 2) }} {{ $deposit->currency }}
                                        </td>
                                        <td>
                                            <span class="badge badge-warning text-dark">
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

@section('modal_wrapper')
    {{-- Top Up Modal --}}
    <div class="modal fade" id="topUpModal" tabindex="-1" role="dialog" aria-labelledby="topUpModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="topUpModalLabel">{{ gtrans('Recharge Center') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ gtrans('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="topUpForm" enctype="multipart/form-data" data-parsley-validate>
                    @csrf

                    <div class="modal-body">
                        <input type="hidden" name="wallet_id" id="wallet_id">

                        <div class="form-group">
                            <label>{{ gtrans('Recharge Method') }}</label>
                            <select class="form-control" id="recharge_method" required data-parsley-required="true">
                                <option value="">{{ gtrans('Select method') }}</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>{{ gtrans('Please select the recharge network') }}</label>
                            <select class="form-control" id="recharge_network" required data-parsley-required="true">
                                <option value="">{{ gtrans('Select network') }}</option>
                            </select>
                        </div>

                        <div class="d-flex align-items-center mb-3">
                            <div class="mr-3">
                                <img id="wallet_qr" src="{{ asset('assets/imgs/sample_qr.png') }}" alt="QR"
                                    style="width:80px;height:80px;object-fit:contain;">
                            </div>
                            <div class="small">
                                <div class="text-muted mb-1">
                                    {{ gtrans('Scan the QR code to recharge') }}
                                </div>
                                <div id="depositAddress" class="mb-1" style="word-break: break-all;"></div>
                                <button type="button" id="btnCopyAddress" class="btn btn-link p-0">
                                    {{ gtrans('Copy deposit address') }}
                                </button>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>{{ gtrans('Recharge amount (USDT)') }}</label>
                            <input type="number" name="amount" id="recharge_amount" min="0" step="0.01"
                                class="form-control" data-parsley-type="number" data-parsley-min="0.01"
                                placeholder="{{ gtrans('Please enter the recharge amount (USDT)') }}">
                        </div>

                        <div class="form-group" id="amount_received_group" style="display:none;">
                            <label>{{ gtrans('Amount received (USDT)') }}</label>
                            <input type="number" name="amount_received" id="amount_received" min="0"
                                step="0.01" class="form-control"
                                placeholder="{{ gtrans('Amount actually received (USDT)') }}" readonly>
                        </div>

                        <div class="form-group text-center">
                            <label class="d-block">{{ gtrans('Upload recharge voucher') }}</label>
                            <input type="file" name="voucher" id="voucherInput" class="d-none" accept="image/*">

                            <div id="voucherPreview"
                                class="border rounded d-flex flex-column align-items-center justify-content-center mx-auto"
                                style="width:130px;height:130px;cursor:pointer;">
                                <span class="display-4 text-muted">+</span>
                                <span class="small text-muted text-center">
                                    {{ gtrans('Upload Credentials') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            {{ gtrans('Confirm recharge') }}
                        </button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ gtrans('Cancel Top up') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    {{-- Withdrawal Modal --}}
    <div class="modal fade" id="withdrawModal" tabindex="-1">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header border-0">
                    <h5 class="modal-title font-weight-bold">Withdrawal Center</h5>
                    <button type="button" class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>

                <form id="withdrawForm">
                    @csrf

                    <div class="modal-body pt-0">

                 
                        <input type="hidden" id="withdraw_type" name="withdraw_type" value="crypto">

                        {{-- METHOD --}}
                        <div class="form-group">
                            <label>Withdrawal Method</label>
                          <select class="form-control" id="withdraw_method" name="withdraw_method_id" required data-parsley-required="true">
                            <option value="">Select method</option>
                        </select>
                        </div>

                        {{-- CRYPTO --}}
                        <div id="cryptoWithdrawFields">

                            <div class="row mb-3">
                                <div class="col-6">
                                    <small>Currency</small>
                                    <div id="withdraw_currency">---</div>
                                </div>
                                <div class="col-6 text-right">
                                    <small>Chain</small><br>
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                        id="withdraw_chain_btn">---</button>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="text-danger">Withdrawal Address</label>
                                <select class="form-control" id="withdraw_address" name="address_id">
                                    <option value="">Select address</option>
                                </select>
                            </div>

                        </div>

                        {{-- BANK --}}
                        <div id="bankWithdrawFields" style="display:none;">

                            <div class="form-group">
                                <label class="text-danger">* Cash withdrawal bank</label>
                                <input type="text" name="bank_name" id="bank_name" class="form-control"
                                    placeholder="Enter bank name">
                            </div>

                            <div class="form-group">
                                <label class="text-danger">* Bank code</label>
                                <input type="text" name="bank_code" id="bank_code" class="form-control"
                                    placeholder="Enter bank code (e.g. 058)">
                            </div>

                            <div class="form-group">
                                <label class="text-danger">* Withdrawal card number</label>
                                <input type="text" name="bank_account_number" id="bank_account_number"
                                    class="form-control" placeholder="Enter account number">
                            </div>

                            <div class="form-group">
                                <label class="text-danger">* Name</label>
                                <input type="text" name="account_name" id="account_name" class="form-control"
                                    placeholder="Account holder name">
                            </div>

                            <div class="form-group">
                                <label>Bank branches</label>
                                <input type="text" name="bank_branch" id="bank_branch" class="form-control"
                                    placeholder="Optional branch">
                            </div>

                        </div>
                        {{-- AMOUNT --}}
                        <div class="form-group">
                            <label>Amount</label>
                            <input type="number" name="amount" class="form-control" required>
                        </div>

                        {{-- PASSWORD --}}
                        <div class="form-group">
                            <label>Transaction Password</label>
                            <input type="password" name="password" id="withdraw_password" value="12345"
                                class="form-control" required data-parsley-required="true">
                        </div>

                    </div>

                    <div class="modal-footer border-0">
                        <button class="btn btn-primary btn-block">Submit Withdrawal</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
@endsection

<div id="copyToast" class="position-fixed px-3 py-2 bg-success text-white rounded shadow-sm"
    style="z-index:1080;display:none;top:20px;left:50%;transform:translateX(-50%);font-size:0.875rem;">
    {{ gtrans('Copy Success') }}
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script>
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

   
    <script>
        $(document).ready(function() {
            /**
             * Shared helpers
             */
            function showToast(text, type = 'success', duration = 3000) {
                Toastify({
                    text: text,
                    duration: duration,
                    gravity: "top",
                    position: "right",
                    style: {
                        background: type === 'success' ? "#28a745" : "#dc3545"
                    }
                }).showToast();
            }

            /**
             * =========================
             * TOP UP LOGIC
             * =========================
             */
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

            let wallets = [];

            const txtSelectMethod = "{{ gtrans('Select method') }}";
            const txtSelectNetwork = "{{ gtrans('Select network') }}";
            const txtDefaultNet = "{{ gtrans('Default') }}";

            function showCopyToast() {
                if (!copyToast) return;
                copyToast.style.display = 'block';
                setTimeout(function() {
                    copyToast.style.display = 'none';
                }, 1500);
            }

            function syncAmountReceived() {
                if (!amountInput || !amountReceived || !amountGroup) return;

                if (amountGroup.style.display === 'block') {
                    const v = amountInput.value;
                    amountReceived.value = v ? parseFloat(v).toFixed(2) : '';
                }
            }

            function updateWalletDetails() {
                if (!methodSelect || !networkSelect) return;

                const method = methodSelect.value;
                const network = networkSelect.value || null;

                let wallet =
                    wallets.find(w => w.method === method && w.network === network && w.is_primary) ||
                    wallets.find(w => w.method === method && w.network === network) ||
                    wallets.find(w => w.method === method);

                if (!wallet) {
                    if (qrImg) qrImg.src = "{{ asset('assets/imgs/sample_qr.png') }}";
                    if (addrEl) addrEl.textContent = '';
                    if (walletIdInput) walletIdInput.value = '';
                    if (amountGroup) amountGroup.style.display = 'none';
                    if (amountReceived) amountReceived.value = '';
                    if (amountInput) $(amountInput).removeAttr('data-parsley-required');
                    return;
                }

                if (walletIdInput) walletIdInput.value = wallet.id;
                if (addrEl) addrEl.textContent = wallet.deposit_address || '';

                if (qrImg) {
                    qrImg.src = wallet.qr_image_path
                        ? "{{ url('/') }}/" + wallet.qr_image_path
                        : "{{ asset('assets/imgs/sample_qr.png') }}";
                }

                if (wallet.method && wallet.method.toLowerCase() === 'usdt') {
                    if (amountGroup) amountGroup.style.display = 'block';
                    syncAmountReceived();
                    if (amountInput) $(amountInput).attr('data-parsley-required', 'true');
                } else {
                    if (amountGroup) amountGroup.style.display = 'none';
                    if (amountReceived) amountReceived.value = '';
                    if (amountInput) $(amountInput).removeAttr('data-parsley-required');
                }

                if (amountInput && $(amountInput).data('Parsley')) {
                    $(amountInput).parsley().reset();
                }
            }

            function updateNetworks(selectDefault) {
                if (!methodSelect || !networkSelect) return;

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
                if (!methodSelect) return;

                methodSelect.innerHTML = "<option value=''>" + txtSelectMethod + "</option>";

                const methods = [...new Set(wallets.map(w => w.method).filter(Boolean))];

                methods.forEach(function(m) {
                    const opt = document.createElement('option');
                    opt.value = m;
                    opt.textContent = m.toUpperCase();
                    methodSelect.appendChild(opt);
                });

                if (selectDefault && methods.length) {
                    const hasUsdt = methods.find(m => m.toLowerCase() === 'usdt');
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
                            wallets = data || [];
                            populateMethods(true);
                        })
                        .catch(function() {
                            populateMethods(true);
                        });
                } else {
                    populateMethods(false);
                }
            });

            if (methodSelect) {
                methodSelect.addEventListener('change', function() {
                    updateNetworks(false);
                });
            }

            if (networkSelect) {
                networkSelect.addEventListener('change', updateWalletDetails);
            }

            if (amountInput) {
                amountInput.addEventListener('input', syncAmountReceived);
            }

            if (copyBtn) {
                copyBtn.addEventListener('click', function() {
                    const text = addrEl ? addrEl.textContent.trim() : '';
                    if (!text) return;

                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).then(showCopyToast);
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

            if (topUpForm) {
                const parsleyInstance = $(topUpForm).parsley();

                topUpForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    parsleyInstance.whenValidate().done(function() {
                        const formData = new FormData(topUpForm);

                        fetch("{{ route('wallet.deposit.ajax.store') }}", {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: formData,
                            })
                            .then(async (response) => {
                                const data = await response.json();
                                if (!response.ok) throw data;

                                showToast(
                                    data.message || "{{ gtrans('Recharge proof submitted successfully.') }}",
                                    'success'
                                );

                                $('#topUpModal').modal('hide');
                                topUpForm.reset();

                                if (voucherPreview) {
                                    voucherPreview.innerHTML =
                                        '<span class="display-4 text-muted">+</span>' +
                                        '<span class="small text-muted text-center">{{ gtrans('Upload Credentials') }}</span>';
                                }

                                if (amountGroup) amountGroup.style.display = 'none';
                                if (amountReceived) amountReceived.value = '';

                                setTimeout(function() {
                                    window.location.reload();
                                }, 1500);
                            })
                            .catch((error) => {
                                let msg = "{{ gtrans('An error occurred.') }}";

                                if (error && error.errors) {
                                    const firstField = Object.keys(error.errors)[0];
                                    msg = error.errors[firstField][0];
                                } else if (error && error.message) {
                                    msg = error.message;
                                }

                                showToast(msg, 'error', 4000);
                            });
                    });
                });
            }

            /**
             * =========================
             * WITHDRAWAL LOGIC
             * =========================
             */
            let payoutOptions = [];

            const withdrawForm = document.getElementById('withdrawForm');
            const withdrawMethod = document.getElementById('withdraw_method');
            const withdrawType = document.getElementById('withdraw_type');
            const cryptoFields = document.getElementById('cryptoWithdrawFields');
            const bankFields = document.getElementById('bankWithdrawFields');
            const withdrawCurrency = document.getElementById('withdraw_currency');
            const withdrawChainBtn = document.getElementById('withdraw_chain_btn');
            const withdrawAddress = document.getElementById('withdraw_address');
            const withdrawPassword = document.getElementById('withdraw_password');

            function switchForm(type) {
                if (!withdrawType || !cryptoFields || !bankFields) return;

                withdrawType.value = type;

                if (type === 'bank') {
                    cryptoFields.style.display = 'none';
                    bankFields.style.display = 'block';
                } else {
                    cryptoFields.style.display = 'block';
                    bankFields.style.display = 'none';
                }
            }

            function clearWithdrawState() {
                if (withdrawCurrency) withdrawCurrency.textContent = '---';
                if (withdrawChainBtn) withdrawChainBtn.textContent = '---';

                if (withdrawAddress) {
                    withdrawAddress.innerHTML = `<option value="">Select address</option>`;
                }
            }

            function updateWithdrawUI() {
                if (!withdrawMethod) return;

                const id = withdrawMethod.value;
                const selected = payoutOptions.find(x => String(x.id) === String(id));
                const text = withdrawMethod.options[withdrawMethod.selectedIndex]?.textContent.toLowerCase() || '';

                if (!id) {
                    switchForm('crypto');
                    clearWithdrawState();
                    return;
                }

                if (
                    (selected && selected.type === 'bank') ||
                    text.includes('bank') ||
                    text.includes('transfer')
                ) {
                    switchForm('bank');
                    clearWithdrawState();
                    return;
                }

                switchForm('crypto');

                if (withdrawCurrency) withdrawCurrency.textContent = selected?.currency || '---';
                if (withdrawChainBtn) withdrawChainBtn.textContent = selected?.chain || '---';

                loadWithdrawAddresses(id);
            }

            function loadWithdrawAddresses(optionId) {
                if (!withdrawAddress) return;

                fetch(`/user/withdrawal-addresses/${optionId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        withdrawAddress.innerHTML = `<option value="">Select address</option>`;

                        if (!Array.isArray(data) || !data.length) {
                            withdrawAddress.innerHTML += `<option value="" disabled>No wallet found</option>`;
                            return;
                        }

                        data.forEach(w => {
                            withdrawAddress.innerHTML += `
                                <option value="${w.id}">
                                    ${w.display || w.wallet_address || 'Wallet'}
                                </option>
                            `;
                        });
                    })
                    .catch(() => {
                        withdrawAddress.innerHTML = `<option value="">Error loading wallets</option>`;
                    });
            }

            function loadWithdrawMethods() {
                if (!withdrawMethod) return;

                fetch(`/user/withdrawal-methods`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        payoutOptions = Array.isArray(data) ? data : [];

                        withdrawMethod.innerHTML = `<option value="">Select method</option>`;

                        payoutOptions.forEach(item => {
                            withdrawMethod.innerHTML += `
                                <option value="${item.id}">
                                    ${item.name}
                                </option>
                            `;
                        });
                    })
                    .catch(() => {
                        withdrawMethod.innerHTML = `<option value="">Unable to load methods</option>`;
                    });
            }

            function resetWithdrawForm() {
                if (!withdrawForm) return;

                withdrawForm.reset();
                switchForm('crypto');
                clearWithdrawState();

                if ($(withdrawForm).data('Parsley')) {
                    $(withdrawForm).parsley().reset();
                }
            }

            if (withdrawMethod) {
                withdrawMethod.addEventListener('change', updateWithdrawUI);
            }

            $('#withdrawModal').on('show.bs.modal', function() {
                loadWithdrawMethods();
            });

            $('#withdrawModal').on('hidden.bs.modal', function() {
                resetWithdrawForm();
            });

            if (withdrawForm) {
                const parsleyWithdraw = $(withdrawForm).parsley();

                withdrawForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    parsleyWithdraw.whenValidate().done(function() {
                        const formData = new FormData(withdrawForm);

                        fetch(`{{ route('customer.withdrawal-request.store') }}`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                },
                                body: formData
                            })
                            .then(async (response) => {
                                const data = await response.json();

                                if (!response.ok) {
                                    throw data;
                                }

                                showToast(
                                    data.message || 'Withdrawal request submitted successfully.',
                                    'success'
                                );

                                $('#withdrawModal').modal('hide');
                                resetWithdrawForm();

                                setTimeout(function() {
                                    window.location.reload();
                                }, 1200);
                            })
                            .catch((error) => {
                                let msg = 'An error occurred.';

                                if (error && error.errors) {
                                    const firstField = Object.keys(error.errors)[0];
                                    msg = error.errors[firstField][0];
                                } else if (error && error.message) {
                                    msg = error.message;
                                }

                                showToast(msg, 'error', 4000);
                            });
                    });
                });
            }

            const togglePassword = document.getElementById('toggle_withdraw_password');
            if (togglePassword && withdrawPassword) {
                togglePassword.addEventListener('click', function() {
                    withdrawPassword.type = withdrawPassword.type === 'password' ? 'text' : 'password';
                });
            }
        });
    </script>
@endpush
  
@push("styles")
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