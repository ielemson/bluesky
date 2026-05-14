@extends('layouts.admin')

@section('title', 'Create Vendor Invitation Code')

@section('content')
<div class="container-full">

    @include('admin.partials.page_header', [
        'header1' => 'Vendor Invitation Codes',
        'header2' => 'Create New Invitation Code'
    ])

    <section class="content">
        <div class="container-fluid">

            @include("admin.partials.category_alert")

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fa fa-ticket"></i> Create Vendor Invitation Code
                    </h6>

                    <a href="{{ route("admin.invitation.vendor-invitation-codes.index") }}" class="btn btn-sm btn-secondary">
                        <i class="fa fa-arrow-left"></i> Back
                    </a>
                </div>

                <div class="card-body">

                    <form action="{{ route("admin.invitation.vendor-invitation-codes.store") }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">

                                <h6 class="text-primary mb-3">
                                    <i class="fa fa-info-circle"></i> Invitation Information
                                </h6>

                                <div class="form-group">
                                    <label for="title">Branch / Office Title *</label>
                                    <input type="text"
                                           class="form-control @error('title') is-invalid @enderror"
                                           id="title"
                                           name="title"
                                           value="{{ old('title') }}"
                                           placeholder="Example: Office Branch A"
                                           required>

                                    @error('title')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <small class="form-text text-muted">
                                        This is the office, branch, desk, agent, or source tied to the invitation code.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="location">Location</label>
                                    <input type="text"
                                           class="form-control @error('location') is-invalid @enderror"
                                           id="location"
                                           name="location"
                                           value="{{ old('location') }}"
                                           placeholder="Example: Port Harcourt Office">

                                    @error('location')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <small class="form-text text-muted">
                                        This location will appear during vendor approval.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="description">Description / Note</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description"
                                              name="description"
                                              rows="4"
                                              placeholder="Optional note about this invitation code">{{ old('description') }}</textarea>

                                    @error('description')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                            </div>

                            <div class="col-md-4">

                                <h6 class="text-primary mb-3">
                                    <i class="fa fa-cog"></i> Code Settings
                                </h6>

                                <div class="form-group">
                                    <label for="usage_limit">Usage Limit</label>
                                    <input type="number"
                                           class="form-control @error('usage_limit') is-invalid @enderror"
                                           id="usage_limit"
                                           name="usage_limit"
                                           value="{{ old('usage_limit') }}"
                                           min="1"
                                           placeholder="Leave empty for unlimited">

                                    @error('usage_limit')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <small class="form-text text-muted">
                                        Set how many vendors can use this code.
                                    </small>
                                </div>

                                <div class="form-group">
                                    <label for="expires_at">Expiry Date</label>
                                    <input type="datetime-local"
                                           class="form-control @error('expires_at') is-invalid @enderror"
                                           id="expires_at"
                                           name="expires_at"
                                           value="{{ old('expires_at') }}">

                                    @error('expires_at')
                                        <span class="invalid-feedback">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                    <small class="form-text text-muted">
                                        Leave empty if the code should not expire.
                                    </small>
                                </div>

                                <div class="form-group mt-4">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox"
                                               class="custom-control-input"
                                               id="is_active"
                                               name="is_active"
                                               value="1"
                                               {{ old('is_active', 1) ? 'checked' : '' }}>

                                        <label class="custom-control-label" for="is_active">
                                            Active Invitation Code
                                        </label>
                                    </div>

                                    <small class="form-text text-muted">
                                        Inactive codes cannot be used by vendors during application.
                                    </small>
                                </div>

                                <div class="alert alert-info mt-4">
                                    <strong>Note:</strong><br>
                                    The system will automatically generate a unique invitation code after saving.
                                </div>

                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-save"></i> Create Invitation Code
                                </button>

                                <a href="{{ route("admin.invitation.vendor-invitation-codes.index") }}" class="btn btn-secondary">
                                    <i class="fa fa-times"></i> Cancel
                                </a>
                            </div>
                        </div>

                    </form>

                </div>
            </div>

        </div>
    </section>
</div>
@endsection