@extends('layouts.admin')

@section('title', 'Approve Payment')

@section('content')

    <div class="container-fluid">

        <h4 class="mb-3">{{ gtrans('Wallet Deposits') }}</h4>

        {{-- Deposits card --}}
        <div class="card shadow-sm">
            <div class="card-header py-3 px-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 small text-uppercase">
                    {{ gtrans('Pending & recent deposits') }}
                </h6>
                <span class="small text-muted">
                    {{ gtrans('Total') }}: {{ $deposits->total() }}
                </span>
            </div>

            <div class="card-body p-3">
                @if ($deposits->isEmpty())
                    <p class="text-muted small mb-0">
                        {{ gtrans('No wallet deposits found.') }}
                    </p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3">{{ gtrans('Date') }}</th>
                                    <th class="px-3">{{ gtrans('User') }}</th>
                                    <th class="px-3">{{ gtrans('Method / Network') }}</th>
                                    <th class="text-end px-3">{{ gtrans('Amount') }}</th>
                                    <th class="px-3">{{ gtrans('Status') }}</th>
                                    <th class="text-center px-3">{{ gtrans('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($deposits as $deposit)
                                    @php
                                        $status = $deposit->status;
                                        $badgeClass = match ($status) {
                                            'approved' => 'bg-success',
                                            'rejected' => 'bg-danger',
                                            default => 'bg-warning text-dark',
                                        };
                                    @endphp
                                    <tr>
                                        <td class="px-3">
                                            {{-- {{ $deposit->created_at->format('M d, Y H:i') }} --}}
                                            <div class="small text-muted">
                                                {{ $deposit->created_at->diffForHumans() }}
                                            </div>
                                        </td>
                                        <td class="px-3">
                                            {{ optional($deposit->user)->name ?? '#' . $deposit->user_id }}
                                            <div class="small text-muted">
                                                {{ optional($deposit->user)->email }}
                                            </div>
                                        </td>
                                        <td class="px-3">
                                            {{ optional($deposit->paymentWallet)->method ?? '-' }}
                                            <div class="small text-muted">
                                                {{ optional($deposit->paymentWallet)->network ?? '-' }}
                                            </div>
                                        </td>
                                        <td class="text-end px-3">
                                            {{ number_format($deposit->amount, 2) }}
                                            <span class="small text-muted">{{ $deposit->currency }}</span>
                                        </td>
                                        <td class="px-3">
                                            <span class="badge {{ $badgeClass }}">
                                                {{ strtoupper($status) }}
                                            </span>
                                        </td>
                                        <td class="text-center px-3">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-secondary mb-1 btn-show-deposit"
                                                data-id="{{ $deposit->id }}">
                                                {{ gtrans('View') }}
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="pt-3">
                        {{ $deposits->links() }}
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Details modal --}}
    <div class="modal fade" id="depositDetailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">{{ gtrans('Deposit Details') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="{{ gtrans('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">

                        {{-- Left: text details --}}
                        <div class="col-md-7">
                            <dl class="row mb-0 small" id="depositDetailList">
                                {{-- filled by JS --}}
                            </dl>

                            <div class="mt-3">
                                <label class="form-label small fw-semibold">
                                    {{ gtrans('Admin note (optional)') }}
                                </label>
                                <textarea id="depositAdminNote" class="form-control" rows="3"
                                    placeholder="{{ gtrans('Add a note for this decision') }}"></textarea>
                            </div>
                        </div>

                        {{-- Right: proof image --}}
                        <div class="col-md-5">
                            <div class="border rounded p-2 text-center h-100 d-flex flex-column">
                                <div class="small text-muted mb-2">
                                    {{ gtrans('Payment proof') }}
                                </div>
                                <div class="flex-grow-1 d-flex align-items-center justify-content-center proof-wrapper">
                                    <img id="depositProofImage" src="{{ asset('assets/images/sample_qr.png') }}"
                                        alt="Proof" class="img-fluid rounded deposit-proof-img" style="display:none;">
                                    <div id="depositProofPlaceholder" class="text-muted small">
                                        {{ gtrans('No proof uploaded') }}
                                    </div>
                                </div>

                                <a href="#" target="_blank" id="depositProofLink" class="small mt-2"
                                    style="display:none;">
                                    {{ gtrans('Open full image') }}
                                </a>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" id="btnApproveDeposit" class="btn btn-success">
                        {{ gtrans('Approve') }}
                    </button>
                    <button type="button" id="btnRejectDeposit" class="btn btn-outline-danger">
                        {{ gtrans('Reject') }}
                    </button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        {{ gtrans('Close') }}
                    </button>
                </div>


            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- in layouts.admin, before @stack('scripts') --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const detailModal = $('#depositDetailModal');
            const detailList = document.getElementById('depositDetailList');
            const adminNoteInput = document.getElementById('depositAdminNote');
            const proofImg = document.getElementById('depositProofImage');
            const proofPlaceholder = document.getElementById('depositProofPlaceholder');
            const proofLink = document.getElementById('depositProofLink');
            const btnApprove = document.getElementById('btnApproveDeposit');
            const btnReject = document.getElementById('btnRejectDeposit');

            let currentDepositId = null;

            function loadDeposit(id) {
                detailList.innerHTML =
                    '<div class="col-12 small text-muted">{{ gtrans('Loading...') }}</div>';
                adminNoteInput.value = '';

                proofImg.style.display = 'none';
                proofPlaceholder.style.display = 'block';
                proofLink.style.display = 'none';
                proofLink.href = '#';

                fetch("{{ url('admin/deposits/wallet-deposits/') }}/" + id + "/json", {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(res => res.json())
                    .then(function(data) {
                        const rows = [
                            ['{{ gtrans('User') }}', data.user.name || ('#' + data.user.id)],
                            ['{{ gtrans('Email') }}', data.user.email || '-'],
                            ['{{ gtrans('Method') }}', data.method || '-'],
                            ['{{ gtrans('Network') }}', data.network || '-'],
                            ['{{ gtrans('Amount') }}', data.amount + ' ' + data.currency],
                            ['{{ gtrans('Status') }}', data.status.toUpperCase()],
                            ['{{ gtrans('Created at') }}', data.created_at],
                        ];

                        detailList.innerHTML = '';
                        rows.forEach(function([label, value]) {
                            const dt = document.createElement('dt');
                            dt.className = 'col-4 text-muted';
                            dt.textContent = label;

                            const dd = document.createElement('dd');
                            dd.className = 'col-8';
                            dd.textContent = value;

                            detailList.appendChild(dt);
                            detailList.appendChild(dd);
                        });

                        if (data.proof_url) {
                            proofImg.src = data.proof_url;
                            proofImg.style.display = 'block';
                            proofPlaceholder.style.display = 'none';
                            proofLink.href = data.proof_url;
                            proofLink.style.display = 'inline-block';
                        }
                    })
                    .catch(function() {
                        detailList.innerHTML =
                            '<div class="col-12 text-danger small">{{ gtrans('Failed to load deposit details.') }}</div>';
                    });
            }

            // open modal on "View"
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-show-deposit');
                if (!btn) return;

                const id = btn.getAttribute('data-id');
                if (!id) return;

                currentDepositId = id;
                loadDeposit(id);
                detailModal.modal('show');
            });

            function ajaxAction(action) {
                if (!currentDepositId) return;

                const note = adminNoteInput.value;
                const url = "{{ url('admin/deposits/wallet-deposits/') }}/" +
                    currentDepositId + '/' + action + '-ajax';

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            admin_note: note
                        }),
                    })
                    .then(res => res.json().then(data => ({
                        ok: res.ok,
                        data
                    })))
                    .then(({
                        ok,
                        data
                    }) => {
                        if (!ok) throw data;

                        const okColor = action === 'approve' ? '#28a745' : '#dc3545';

                        Toastify({
                            text: data.message || (action === 'approve' ?
                                "{{ gtrans('Deposit approved.') }}" :
                                "{{ gtrans('Deposit rejected.') }}"),
                            duration: 3000,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: okColor
                            },
                        }).showToast();

                        detailModal.modal('hide');

                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    })
                    .catch((error) => {
                        let msg = "{{ gtrans('An error occurred.') }}";
                        if (error && error.message) msg = error.message;

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
            }

            if (btnApprove) {
                btnApprove.addEventListener('click', function() {
                    ajaxAction('approve');
                });
            }

            if (btnReject) {
                btnReject.addEventListener('click', function() {
                    ajaxAction('reject');
                });
            }
        });
    </script>

    <style>
        /* Wrapper: keeps image inside and scrolls if needed */
        .proof-wrapper {
            max-height: 70vh;
            /* never taller than 70% of viewport */
            overflow: hidden;
            /* prevent jumping outside */
        }

        /* Image: responsive inside wrapper */
        .deposit-proof-img {
            max-width: 100%;
            /* never wider than wrapper */
            max-height: 100%;
            /* never taller than wrapper */
            height: auto;
            width: auto;
            object-fit: contain;
            display: block;
        }

        /* Mobile tweaks */
        @media (max-width: 767.98px) {
            .proof-wrapper {
                max-height: 60vh;
            }
        }
    </style>
@endpush
