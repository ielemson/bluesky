@extends('layouts.customer')

@section('title', 'My Billing Records')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="mb-1">My Billing Records</h3>
            <p class="text-muted mb-0">Track all wallet credits, debits, and related transactions.</p>
        </div>
    </div>

    @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">
            @if($billingRecords->count())
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Type</th>
                                <th>Category</th>
                                <th>Amount</th>
                                <th>Reference</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th class="text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($billingRecords as $index => $record)
                                <tr>
                                    <td>{{ $billingRecords->firstItem() + $index }}</td>

                                    <td>
                                        @if($record->direction === 'credit')
                                            <span class="badge badge-success">Credit</span>
                                        @else
                                            <span class="badge badge-danger">Debit</span>
                                        @endif
                                    </td>

                                    <td>{{ ucwords(str_replace('_', ' ', $record->category ?? '-')) }}</td>

                                    <td>
                                        @if($record->direction === 'credit')
                                            <span class="text-success">+{{ number_format((float) $record->amount, 2) }}</span>
                                        @else
                                            <span class="text-danger">-{{ number_format((float) $record->amount, 2) }}</span>
                                        @endif
                                    </td>

                                    <td>{{ $record->reference ?: '-' }}</td>

                                    <td>
                                        @php
                                            $status = strtolower($record->status ?? 'completed');
                                        @endphp

                                        @if($status === 'completed')
                                            <span class="badge badge-success">Completed</span>
                                        @elseif($status === 'pending')
                                            <span class="badge badge-warning">Pending</span>
                                        @elseif($status === 'failed')
                                            <span class="badge badge-danger">Failed</span>
                                        @else
                                            <span class="badge badge-secondary">{{ ucfirst($status) }}</span>
                                        @endif
                                    </td>

                                    <td>{{ optional($record->posted_at ?? $record->created_at)->format('d M Y, h:i A') }}</td>

                                    <td class="text-end">
                                        <a href="{{ route('customer.billing.show', $record) }}" class="btn btn-sm btn-outline-info">
                                            View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $billingRecords->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <h5 class="mb-2">No billing record yet</h5>
                    <p class="text-muted mb-3">Your wallet transaction history will appear here once available.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection