@extends('layouts.admin')

@section('title', 'Payout Options')

@section('content')
<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">{{ gtrans('Payout Wallet Options') }}</h4>

        <a href="{{ route('admin.wallet-options.create') }}" class="btn btn-primary btn-sm">
            <i class="fa fa-plus me-1"></i> {{ gtrans('Add option') }}
        </a>
    </div>

    {{-- Success Message --}}
    @if(session('status'))
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fa fa-check-circle me-1"></i> {{ session('status') }}
            <button class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                {{ gtrans('All payout methods') }}
            </h6>
        </div>

        <div class="card-body">

            @if($options->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ gtrans('Type') }}</th>
                                <th>{{ gtrans('Currency') }}</th>
                                <th>{{ gtrans('Network / Chain') }}</th>
                                <th>{{ gtrans('Note') }}</th>
                                <th>{{ gtrans('Status') }}</th>
                                <th class="text-center">{{ gtrans('Actions') }}</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($options as $key => $option)
                                @php
                                    $isBank = strtoupper($option->chain) === 'BANK_TRANSFER';
                                @endphp

                                <tr>
                                    <td>{{ $key + 1 }}</td>

                                    {{-- TYPE --}}
                                    <td>
                                        @if($isBank)
                                            <span class="badge bg-info">
                                                <i class="fa fa-university me-1"></i>
                                                {{ gtrans('Bank') }}
                                            </span>
                                        @else
                                            <span class="badge bg-dark">
                                                <i class="fa fa-coins me-1"></i>
                                                {{ gtrans('Crypto') }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- CURRENCY --}}
                                    <td>
                                        <strong>{{ $option->currency }}</strong>
                                    </td>

                                    {{-- CHAIN --}}
                                    <td>
                                        @if($isBank)
                                            <span class="text-muted">—</span>
                                        @else
                                            {{ $option->chain }}
                                        @endif
                                    </td>

                                    {{-- NOTE --}}
                                    <td>
                                        @if($option->note)
                                            <small>{{ $option->note }}</small>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>

                                    {{-- STATUS --}}
                                    <td>
                                        @if($option->is_active)
                                            <span class="badge bg-success">
                                                {{ gtrans('Active') }}
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ gtrans('Inactive') }}
                                            </span>
                                        @endif
                                    </td>

                                    {{-- ACTIONS --}}
                                    <td class="text-center">
                                        <a href="{{ route('admin.wallet-options.edit', $option->id) }}"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.wallet-options.destroy', $option->id) }}"
                                              method="POST"
                                              class="d-inline-block"
                                              onsubmit="return confirm('Delete this option?')">
                                            @csrf
                                            @method('DELETE')

                                            <button class="btn btn-sm btn-outline-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa fa-wallet fa-2x text-muted mb-2"></i>
                    <p class="text-muted mb-0">{{ gtrans('No payout options found.') }}</p>
                </div>
            @endif

        </div>
    </div>

</div>
@endsection