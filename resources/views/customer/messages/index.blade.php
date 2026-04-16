@extends('layouts.customer')

@section('title', 'My Notifications')

@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => auth()->user()->nickname ?: 'My Notifications',
        'header_2' => 'Notifications',
    ])
@endsection

@section('content')
    <div class="row justify-content-center">

        <div class="col-lg-8 col-12 my-4">

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                        <div>
                            <h3 class="mb-1 fw-bold">Notification Center</h3>
                            <div class="text-muted mb-2">
                                Stay updated with wallet funding, withdrawals, orders, and system alerts.
                            </div>

                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge badge-primary px-3 py-2">
                                    Total: {{ $totalCount }}
                                </span>

                                <span class="badge badge-success px-3 py-2">
                                    Read: {{ $readCount }}
                                </span>

                                <span class="badge badge-warning px-3 py-2">
                                    Unread: {{ $unreadCount }}
                                </span>
                            </div>
                        </div>

                        <div class="mt-3 mt-md-0 text-md-right">
                            <button type="button" id="markAllReadBtn" class="btn btn-primary btn-sm px-3">
                                Mark All as Read
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0 fw-bold">Notifications</h4>

                    <small class="text-muted">
                        {{ $unreadCount }} unread
                    </small>
                </div>

                <div class="card-body p-2">
                    @forelse ($messages as $message)
                        <a href="{{ route('customer.messages.show', $message->id) }}"
                           class="message-row d-flex justify-content-between align-items-center px-3 py-3 {{ $message->is_read ? '' : 'unread' }}">

                            <div class="flex-grow-1 pr-3">
                                <div class="d-flex align-items-center mb-1">
                                    <strong class="mr-2 text-truncate">
                                        {{ $message->title ?? 'Notification' }}
                                    </strong>

                                    @if(!$message->is_read)
                                        <span class="badge badge-primary ml-1">New</span>
                                    @endif

                                    @if($message->type)
                                        <span class="badge badge-warning ml-2 text-capitalize">
                                            {{ $message->type }}
                                        </span>
                                    @endif
                                </div>

                                <div class="text-muted small text-truncate">
                                    {{ \Illuminate\Support\Str::limit($message->message, 90) }}
                                </div>
                            </div>

                            <div class="text-muted small text-nowrap">
                                {{ $message->created_at->diffForHumans() }}
                            </div>
                        </a>
                    @empty
                        <div class="p-4 text-center text-muted">
                            No notifications yet.
                        </div>
                    @endforelse
                </div>

                <div class="card-footer bg-white border-0">
                    <div class="d-flex justify-content-center">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>

        </div>

        <div class="col-lg-4 col-12 my-4">

            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body text-center p-4">
                    <div class="mb-3">
                        <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                             style="width: 90px; height: 90px; font-size: 30px; font-weight: 700;">
                            <i class="mdi mdi-bell-ring"></i>
                        </div>
                    </div>

                    <h4 class="mb-1 fw-bold">Message Inbox</h4>
                    <p class="text-muted mb-3">{{ auth()->user()->email ?? 'N/A' }}</p>

                    <div class="row text-center">
                        <div class="col-6 border-right">
                            <div class="small text-muted">Unread</div>
                            <div class="fw-bold">{{ $unreadCount }}</div>
                        </div>
                        <div class="col-6">
                            <div class="small text-muted">Total</div>
                            <div class="fw-bold">{{ $totalCount }}</div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
@endsection

@push('scripts')
<script>
document.getElementById('markAllReadBtn')?.addEventListener('click', async function () {
    try {
        const response = await fetch("{{ route('customer.messages.read_all') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });

        const data = await response.json();

        if (data.status) {
            window.location.reload();
        }
    } catch (error) {
        console.error('Unable to mark all as read.', error);
    }
});
</script>

<style>
.message-row {
    transition: all 0.2s ease;
    border-bottom: 1px solid #f1f1f1;
    text-decoration: none;
    color: inherit;
}

.message-row:last-child {
    border-bottom: none;
}

.message-row:hover {
    background: #f8fafc;
    text-decoration: none;
}

.message-row.unread {
    background: #eef5ff;
    font-weight: 600;
}

.message-row strong {
    font-size: 14px;
}

.message-row .text-muted {
    font-size: 13px;
}
</style>
@endpush