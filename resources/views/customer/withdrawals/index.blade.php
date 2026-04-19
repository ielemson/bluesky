@extends('layouts.customer')

@section('title', 'My Withdrawal Records')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">My Withdrawal Records</h3>
            <p class="text-muted mb-0">Track your bank and crypto withdrawal requests and statuses.</p>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($withdrawals->count())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Method</th>
                                <th>Amount</th>
                                <th>Fee</th>
                                <th>Net Amount</th>
                                <th>Destination</th>
                                <th>Status</th>
                                <th>Requested</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($withdrawals as $index => $withdrawal)
                                <tr>
                                    <td>{{ $withdrawals->firstItem() + $index }}</td>

                                    <td>
                                        @if($withdrawal->method_type === 'bank')
                                            <span class="badge badge-primary">Bank</span>
                                        @elseif($withdrawal->method_type === 'crypto')
                                            <span class="badge badge-info">Crypto</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($withdrawal->method_type ?? '-') }}</span>
                                        @endif
                                    </td>

                                    <td>{{ number_format((float) $withdrawal->amount, 2) }}</td>
                                    <td>{{ number_format((float) $withdrawal->fee, 2) }}</td>
                                    <td>{{ number_format((float) $withdrawal->net_amount, 2) }}</td>

                                    <td>
                                        @if($withdrawal->method_type === 'bank')
                                            <div>{{ $withdrawal->bank_name ?: '-' }}</div>
                                            <small class="text-muted">
                                                {{ $withdrawal->account_name ?: '-' }}
                                                @if($withdrawal->account_number)
                                                    - {{ $withdrawal->account_number }}
                                                @endif
                                            </small>
                                        @elseif($withdrawal->method_type === 'crypto')
                                            <div>{{ $withdrawal->crypto_currency ?: ($withdrawal->option_currency ?: '-') }}</div>
                                            <small class="text-muted">{{ $withdrawal->wallet_address ?: '-' }}</small>
                                        @else
                                            -
                                        @endif
                                    </td>

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

                                    <td>{{ optional($withdrawal->created_at)->format('d M Y, h:i A') }}</td>

                                    <td class="text-end">
                                        <a href="{{ route('customer.withdrawals.show', $withdrawal) }}" class="btn btn-sm btn-outline-info">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $withdrawals->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="mb-2">No withdrawal record yet</h5>
                    <p class="text-muted mb-3">Your withdrawal history will appear here once you make a request.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection