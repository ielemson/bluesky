@extends('layouts.customer')

@section('title', 'Notification Details')

@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => 'Notification Details',
        'header_2' => 'Notifications',
    ])
@endsection

@section('content')
    <div class="row justify-content-center">

        <div class="col-lg-8 col-12 my-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start flex-wrap">
                        <div>
                            <h3 class="mb-1 fw-bold">{{ $message->title ?? 'Notification' }}</h3>
                            <div class="text-muted mb-2">
                                {{ $message->created_at ? $message->created_at->format('d M, Y h:i A') : 'N/A' }}
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge badge-{{ $message->is_read ? 'success' : 'warning' }} px-3 py-2">
                                    {{ $message->is_read ? 'Read' : 'Unread' }}
                                </span>

                                <span class="badge badge-primary px-3 py-2 text-capitalize">
                                    {{ $message->type ?? 'system' }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-3 mt-md-0 text-md-right">
                            <a href="{{ route('customer.messages.index') }}" class="btn btn-outline-primary btn-sm">
                                Back to Notifications
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 pb-0">
                    <h4 class="mb-0 fw-bold">Message Content</h4>
                </div>
                <div class="card-body pt-3">
                    <div class="border rounded-3 p-4">
                        {{ $message->message }}
                    </div>
                </div>
            </div>

            @if(!empty($message->meta) && is_array($message->meta))
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-white border-0 pb-0">
                        <h4 class="mb-0 fw-bold">Additional Details</h4>
                    </div>
                    <div class="card-body pt-3">
                        <div class="row g-3">
                            @foreach($message->meta as $key => $value)
                                <div class="col-md-6 mt-3">
                                    <div class="border rounded-3 p-3 h-100">
                                        <div class="text-muted small mb-1">{{ ucwords(str_replace('_', ' ', $key)) }}</div>
                                        <div class="fw-bold">
                                            {{ is_array($value) ? json_encode($value) : $value }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-4 col-12 my-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                            style="width: 90px; height: 90px; font-size: 30px; font-weight: 700;">
                            <i class="mdi mdi-email-outline"></i>
                        </div>
                    </div>

                    <h4 class="mb-1 fw-bold">Notification Status</h4>
                    <p class="text-muted mb-3">{{ auth()->user()->email ?? 'N/A' }}</p>

                    <div class="row text-center">
                        <div class="col-6 border-right">
                            <div class="small text-muted">Type</div>
                            <div class="fw-bold text-capitalize">{{ $message->type ?? 'system' }}</div>
                        </div>
                        <div class="col-6">
                            <div class="small text-muted">Status</div>
                            <div class="fw-bold">{{ $message->is_read ? 'Read' : 'Unread' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-0 pb-0">
                    <h5 class="mb-0 fw-bold">Quick Info</h5>
                </div>
                <div class="card-body pt-3">
                    <div class="mb-3">
                        <div class="text-muted small">Created On</div>
                        <div class="fw-bold">
                            {{ $message->created_at ? $message->created_at->format('d M, Y h:i A') : 'N/A' }}
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="text-muted small">Read At</div>
                        <div class="fw-bold">
                            {{ $message->read_at ? $message->read_at->format('d M, Y h:i A') : 'Not yet read' }}
                        </div>
                    </div>

                    <div class="mb-0">
                        <div class="text-muted small">Recipient</div>
                        <div class="fw-bold">{{ auth()->user()->nickname ?? auth()->user()->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection