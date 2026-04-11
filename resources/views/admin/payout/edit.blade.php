@extends('layouts.admin')

@section('title', 'Create Payment')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">{{ gtrans('Edit wallet option') }}</h4>

            <a href="{{ route('admin.wallet-options.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fa fa-arrow-left me-1"></i> {{ gtrans('Back to options') }}
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fa fa-exclamation-circle me-1"></i> {{ gtrans('Please fix the errors below') }}:
                <ul class="mb-0 mt-1 small">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <form id="walletOptionForm" action="{{ route('admin.wallet-options.update', $option) }}" method="POST"
            data-parsley-validate>
            @csrf
            @method('PUT')

            <div class="row">
                <div class="col-lg-7">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                {{ gtrans('Wallet option details') }}
                            </h6>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="currency">{{ gtrans('Currency') }} *</label>
                                        <input type="text" id="currency" name="currency"
                                            class="form-control @error('currency') is-invalid @enderror"
                                            value="{{ old('currency', $option->currency) }}" required
                                            data-parsley-required-message="{{ gtrans('Currency is required.') }}">
                                        @error('currency')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="chain">{{ gtrans('Chain / Network') }} *</label>
                                        <input type="text" id="chain" name="chain"
                                            class="form-control @error('chain') is-invalid @enderror"
                                            value="{{ old('chain', $option->chain) }}" required
                                            data-parsley-required-message="{{ gtrans('Chain / network is required.') }}">
                                        @error('chain')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label for="note">
                                    {{ gtrans('Internal note') }}
                                    <span class="text-muted">({{ gtrans('optional') }})</span>
                                </label>
                                <textarea id="note" name="note" rows="2" class="form-control @error('note') is-invalid @enderror"
                                    placeholder="{{ gtrans('Example: Default USDT withdrawal option.') }}">{{ old('note', $option->note) }}</textarea>
                                @error('note')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">{{ gtrans('Settings') }}</h6>
                        </div>
                        <div class="card-body">

                            <div class="form-group mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                        value="1" {{ old('is_active', $option->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        {{ gtrans('Option is active') }}
                                    </label>
                                </div>
                                <small class="form-text text-muted">
                                    {{ gtrans('Inactive options are hidden from the user wallet dropdown.') }}
                                </small>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save me-1"></i> {{ gtrans('Update option') }}
                                </button>
                                <a href="{{ route('admin.wallet-options.index') }}" class="btn btn-outline-secondary">
                                    {{ gtrans('Cancel') }}
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

@endsection
