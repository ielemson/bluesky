@extends('layouts.customer')

@section('title', 'Withdrawal Record Details')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h3 class="mb-1">Withdrawal Record Details</h3>
        <p class="text-muted mb-0">Full details of the selected withdrawal request.</p>
    </div>

   <div class="card shadow-sm border-0">
    <div class="card-body">

        <div class="table-responsive">
            <table class="table table-bordered align-middle">

                <tbody>

                    <tr>
                        <th width="30%">Method Type</th>
                        <td>{{ ucfirst($withdrawal->method_type ?? '-') }}</td>
                    </tr>

                    <tr>
                        <th>Amount</th>
                        <td>{{ number_format((float) $withdrawal->amount, 2) }}</td>
                    </tr>

                    <tr>
                        <th>Fee</th>
                        <td>{{ number_format((float) $withdrawal->fee, 2) }}</td>
                    </tr>

                    <tr>
                        <th>Net Amount</th>
                        <td class="text-success font-weight-bold">
                            {{ number_format((float) $withdrawal->net_amount, 2) }}
                        </td>
                    </tr>

                    <tr>
                        <th>Request Currency</th>
                        <td>{{ $withdrawal->request_currency ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Status</th>
                        <td>
                            @php $status = strtolower($withdrawal->status ?? 'pending'); @endphp

                            @if($status === 'approved')
                                <span class="badge badge-success">Approved</span>
                            @elseif($status === 'paid')
                                <span class="badge badge-success">Paid</span>
                            @elseif($status === 'rejected')
                                <span class="badge badge-danger">Rejected</span>
                            @elseif($status === 'cancelled')
                                <span class="badge badge-dark">Cancelled</span>
                            @else
                                <span class="badge badge-warning">Pending</span>
                            @endif
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        {{-- ================= BANK DETAILS ================= --}}
        @if(in_array($withdrawal->method_type, ['bank', 'online_banking']))
            <h5 class="mt-4 mb-3">Bank Details</h5>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%">Bank Name</th>
                            <td>{{ $withdrawal->bank_name ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bank Code</th>
                            <td>{{ $withdrawal->bank_code ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Account Name</th>
                            <td>{{ $withdrawal->account_name ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Account Number</th>
                            <td>{{ $withdrawal->account_number ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Bank Branch</th>
                            <td>{{ $withdrawal->bank_branch ?: '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif


        {{-- ================= CRYPTO DETAILS ================= --}}
        @if($withdrawal->method_type === 'crypto')
            <h5 class="mt-4 mb-3">Crypto Details</h5>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th width="30%">Currency</th>
                            <td>{{ $withdrawal->crypto_currency ?: ($withdrawal->option_currency ?: '-') }}</td>
                        </tr>
                        <tr>
                            <th>Chain</th>
                            <td>{{ $withdrawal->crypto_chain ?: ($withdrawal->option_chain ?: '-') }}</td>
                        </tr>
                        <tr>
                            <th>Wallet Address</th>
                            <td class="text-break">{{ $withdrawal->wallet_address ?: '-' }}</td>
                        </tr>
                        <tr>
                            <th>Tag / Memo</th>
                            <td>{{ $withdrawal->wallet_tag_memo ?: '-' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif


        {{-- ================= PROCESSING ================= --}}
        <h5 class="mt-4 mb-3">Processing Details</h5>

        <div class="table-responsive">
            <table class="table table-bordered">
                <tbody>

                    <tr>
                        <th width="30%">Option Currency</th>
                        <td>{{ $withdrawal->option_currency ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Option Chain</th>
                        <td>{{ $withdrawal->option_chain ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>User Note</th>
                        <td>{{ $withdrawal->note ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Admin Remark</th>
                        <td>{{ $withdrawal->admin_remark ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Reviewed At</th>
                        <td>{{ optional($withdrawal->reviewed_at)->format('d M Y, h:i A') ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Approved At</th>
                        <td>{{ optional($withdrawal->approved_at)->format('d M Y, h:i A') ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Paid At</th>
                        <td>{{ optional($withdrawal->paid_at)->format('d M Y, h:i A') ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Cancelled At</th>
                        <td>{{ optional($withdrawal->cancelled_at)->format('d M Y, h:i A') ?: '-' }}</td>
                    </tr>

                    <tr>
                        <th>Created At</th>
                        <td>{{ optional($withdrawal->created_at)->format('d M Y, h:i A') ?: '-' }}</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="mt-4">
            <a href="{{ route('customer.withdrawals.index') }}" class="btn btn-outline-secondary">
                Back to Withdrawal Records
            </a>
        </div>

    </div>
</div>
</div>
@endsection