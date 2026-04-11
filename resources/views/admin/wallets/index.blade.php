@extends('layouts.admin')

@section('title', 'Payment Wallets')

@section('content')
    <div class="container-fluid">

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle"></i> Please fix the following errors:
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif
        <div class="container-fluid">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Wallet List</h6>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Method</th>
                                    <th>Network</th>
                                    <th>Deposit Address</th>
                                    <th>Min Amount</th>
                                    <th>QR</th>
                                    <th>Primary</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($wallets as $wallet)
                                    <tr>
                                        <td>{{ $wallet->id }}</td>
                                        <td>{{ $wallet->name }}</td>
                                        <td class="text-uppercase">{{ $wallet->method }}</td>
                                        <td>{{ $wallet->network ?? '—' }}</td>
                                        <td>
                                            <span class="small text-monospace">
                                                {{ Str::limit($wallet->deposit_address, 26) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if (!is_null($wallet->min_amount))
                                                {{ rtrim(rtrim(number_format($wallet->min_amount, 8, '.', ''), '0'), '.') }}
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if ($wallet->qr_image_path)
                                                <img src="{{ asset($wallet->qr_image_path) }}" alt="QR"
                                                    style="width:40px;height:40px;object-fit:contain;">
                                            @else
                                                —
                                            @endif
                                        </td>
                                        <td>
                                            @if ($wallet->is_primary)
                                                <span class="badge bg-info text-white">Primary</span>
                                            @else
                                                <span class="badge bg-light text-muted">No</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($wallet->is_active)
                                                <span class="badge bg-success text-white">Active</span>
                                            @else
                                                <span class="badge bg-secondary text-white">Inactive</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            <a href="{{ route('admin.wallets.edit', $wallet) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            <form action="{{ route('admin.wallets.destroy', $wallet) }}" method="POST"
                                                class="d-inline" onsubmit="return confirm('Delete this wallet?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            No wallets found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
