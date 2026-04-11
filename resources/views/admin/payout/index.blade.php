@extends('layouts.admin')

@section('title', 'Approve Payment')

@section('content')

    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">{{ gtrans('Wallet Options') }}</h4>

            <div class="d-flex align-items-center gap-2">
                <span class="small text-muted">
                    {{ gtrans('Total options') }}: {{ $options->count() }}
                </span>

                <a href="{{ route('admin.wallet-options.create') }}" class="btn btn-sm btn-primary">
                    {{ gtrans('Add wallet option') }}
                </a>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-header py-3 px-3 d-flex justify-content-between align-items-center">
                <h6 class="mb-0 text-uppercase small text-muted">
                    {{ gtrans('Available payout wallet options') }}
                </h6>

                <span class="badge bg-light text-muted small">
                    {{ gtrans('Active') }}: {{ $options->where('is_active', true)->count() }}
                </span>
            </div>

            <div class="card-body p-3">

                @if ($options->isEmpty())
                    <p class="text-muted small mb-0">
                        {{ gtrans('No wallet options defined yet. Click "Add wallet option" to create one.') }}
                    </p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="px-3">{{ gtrans('ID') }}</th>
                                    <th class="px-3">{{ gtrans('Currency') }}</th>
                                    <th class="px-3">{{ gtrans('Chain / Network') }}</th>
                                    <th class="px-3">{{ gtrans('Status') }}</th>
                                    <th class="text-end px-3">{{ gtrans('Created') }}</th>
                                    <th class="text-center px-3">{{ gtrans('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($options as $option)
                                    <tr>
                                        <td class="px-3 small text-muted">#{{ $option->id }}</td>

                                        <td class="px-3">
                                            <span class="fw-semibold">{{ $option->currency }}</span>
                                        </td>

                                        <td class="px-3">
                                            {{ $option->chain }}
                                        </td>

                                        <td class="px-3">
                                            @if ($option->is_active)
                                                <span class="badge rounded-pill bg-success">
                                                    {{ gtrans('Active') }}
                                                </span>
                                            @else
                                                <span class="badge rounded-pill bg-secondary">
                                                    {{ gtrans('Inactive') }}
                                                </span>
                                            @endif
                                        </td>

                                        <td class="text-end px-3">
                                            <div class="small fw-semibold">
                                                {{ $option->created_at->format('Y-m-d') }}
                                            </div>
                                            <div class="small text-muted">
                                                {{ $option->created_at->diffForHumans() }}
                                            </div>
                                        </td>

                                        <td class="text-center px-3">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.wallet-options.edit', $option) }}"
                                                    class="btn btn-outline-secondary">
                                                    {{ gtrans('Edit') }}
                                                </a>

                                                <form action="{{ route('admin.wallet-options.destroy', $option) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('{{ gtrans('Delete this option?') }}');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-outline-danger">
                                                        {{ gtrans('Delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif

            </div>
        </div>

    </div>

@endsection
