@extends('layouts.customer')

@section('title', 'Billing Record Details')

@section('content')
<div class="container py-4">
    <div class="mb-4">
        <h3 class="mb-1">Billing Record Details</h3>
        <p class="text-muted mb-0">Full details of the selected wallet transaction.</p>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Transaction Type</div>
                <div class="col-md-8">
                    @if($billingRecord->direction === 'credit')
                        <span class="badge badge-success">Credit</span>
                    @else
                        <span class="badge badge-danger">Debit</span>
                    @endif
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Category</div>
                <div class="col-md-8">{{ ucwords(str_replace('_', ' ', $billingRecord->category ?? '-')) }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Amount</div>
                <div class="col-md-8">{{ number_format((float) $billingRecord->amount, 2) }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Balance Before</div>
                <div class="col-md-8">{{ number_format((float) $billingRecord->balance_before, 2) }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Balance After</div>
                <div class="col-md-8">{{ number_format((float) $billingRecord->balance_after, 2) }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Reference</div>
                <div class="col-md-8">{{ $billingRecord->reference ?: '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Status</div>
                <div class="col-md-8">{{ ucfirst($billingRecord->status ?? 'completed') }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Description</div>
                <div class="col-md-8">{{ $billingRecord->description ?: '-' }}</div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4 font-weight-bold">Date</div>
                <div class="col-md-8">{{ optional($billingRecord->posted_at ?? $billingRecord->created_at)->format('d M Y, h:i A') }}</div>
            </div>

            @if(!empty($billingRecord->meta))
                <div class="row mb-3">
                    <div class="col-md-4 font-weight-bold">Meta</div>
                    <div class="col-md-8">
                        <pre class="bg-light p-3 rounded mb-0">{{ json_encode($billingRecord->meta, JSON_PRETTY_PRINT) }}</pre>
                    </div>
                </div>
            @endif

            <div class="mt-4">
                <a href="{{ route('customer.billing.index') }}" class="btn btn-outline-secondary">
                    Back to Billing Records
                </a>
            </div>
        </div>
    </div>
</div>
@endsection