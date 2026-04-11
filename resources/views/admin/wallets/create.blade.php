@extends('layouts.admin')

@section('title', 'Create Payment')

@section('content')
    <div class="container-fluid">

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle"></i> {{ session('success') }}
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

        {{-- resources/views/admin/wallets/create.blade.php --}}
        <form action="{{ route('admin.wallets.store') }}" method="POST" enctype="multipart/form-data" id="walletForm">
            @csrf
            <div class="row">
                {{-- Left column: Wallet information --}}
                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Wallet Information</h6>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Wallet Name *</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="e.g. USDT Main Wallet" required>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="method">Method *</label>
                                        <select id="method" name="method"
                                            class="form-control @error('method') is-invalid @enderror" required>
                                            <option value="">Select method</option>
                                            <option value="usdt" {{ old('method') == 'usdt' ? 'selected' : '' }}>USDT
                                            </option>
                                            <option value="btc" {{ old('method') == 'btc' ? 'selected' : '' }}>BTC
                                            </option>
                                            <option value="eth" {{ old('method') == 'eth' ? 'selected' : '' }}>ETH
                                            </option>
                                        </select>
                                        @error('method')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="network">Network</label>
                                        <input type="text" class="form-control @error('network') is-invalid @enderror"
                                            id="network" name="network" value="{{ old('network') }}"
                                            placeholder="e.g. TRC-20">
                                        @error('network')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="deposit_address">Deposit Address *</label>
                                <input type="text" class="form-control @error('deposit_address') is-invalid @enderror"
                                    id="deposit_address" name="deposit_address" value="{{ old('deposit_address') }}"
                                    placeholder="Wallet address" required>
                                @error('deposit_address')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="min_amount">Minimum Amount (optional)</label>
                                        <input type="number" step="0.00000001" min="0"
                                            class="form-control @error('min_amount') is-invalid @enderror" id="min_amount"
                                            name="min_amount" value="{{ old('min_amount') }}" placeholder="e.g. 10">
                                        @error('min_amount')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="qr_image">QR Code Image (optional)</label>
                                <input type="file" class="form-control-file @error('qr_image') is-invalid @enderror"
                                    id="qr_image" name="qr_image" accept="image/*">
                                @error('qr_image')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror

                                @isset($wallet)
                                    @if ($wallet->qr_image_path)
                                        <div class="mt-2">
                                            <img src="{{ asset($wallet->qr_image_path) }}" alt="QR"
                                                style="width:80px;height:80px;object-fit:contain;">
                                        </div>
                                    @endif
                                @endisset
                            </div>
                            <button type="submit" class="btn btn-primary btn-block">
                                <i class="fa fa-save"></i> Create Payment
                            </button>
                            <a href="{{ route('admin.wallets.index') }}" class="btn btn-secondary btn-block">
                                <i class="fa fa-times"></i> Cancel
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Right column: Settings + actions --}}
                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Settings</h6>
                        </div>
                        <div class="card-body">

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', 1) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_active">
                                        Wallet is active
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Inactive wallets will not appear in the recharge modal.
                                </small>
                            </div>

                            <div class="form-group">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input" id="is_primary"
                                        name="is_primary" value="1" {{ old('is_primary') ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_primary">
                                        Set as primary wallet for this method
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    Only one primary wallet per method (e.g. one primary USDT wallet).
                                </small>
                            </div>

                        </div>
                    </div>


                </div>
            </div>
        </form>

    </div>
@endsection
