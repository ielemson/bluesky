@extends('layouts.admin')

@section('content')
@section('title', 'Active Vendors')
<div class="container-full">
    <!-- Content Header (Page header) -->
    @include('admin.partials.page_header', ['header1' => 'Vendors', 'header2' => 'Active Vendors'])
    <!-- Main content -->
    <section class="content">

        <div class="container-fluid">

            <!-- Applications Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Active Vendors</h6>
                </div>
                <div class="card-body">
                    @if ($activeVendors->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Store Info</th>
                                        <th>Contact Person</th>
                                        <th>ID Number</th>
                                        <th>Business</th>
                                        <th>Applied Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($activeVendors as $vendor)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($vendor->store_logo)
                                                        <img src="{{ asset("$vendor->store_logo") }}"
                                                            alt="{{ $vendor->store_name }}" class="rounded-circle mr-3"
                                                            width="40" height="40" style="object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-3"
                                                            style="width: 40px; height: 40px;">
                                                            <i class="fa fa-store text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $vendor->store_name }}</strong><br>
                                                        <small
                                                            class="text-muted">{{ $vendor->invite_code ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $vendor->contact_person }}</td>
                                            <td>{{ $vendor->id_number }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $vendor->main_business }}</span>
                                            </td>
                                            <td>{{ $vendor->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="{{ route('admin.vendors.show', $vendor->id) }}"
                                                        class="btn btn-info" title="View Details">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    {{-- <a href="javascript:;" class="btn btn-info" title="View Details"
                                                        onclick="viewVendorDetails({{ $vendor->id }})">
                                                        <i class="fa fa-eye"></i>
                                                    </a> --}}
                                                    <button type="button" class="btn btn-success"
                                                        onclick="approveApplication({{ $vendor->id }})"
                                                        title="Approve">
                                                        <i class="fa fa-check"></i>
                                                    </button>

                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#rejectModal"
                                                        data-application-id="{{ $vendor->id }}" title="Reject">
                                                        <i class="fa fa-times"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4">
                            <i class="fa fa-check-circle fa-3x text-success mb-3"></i>
                            <h4>No Active Vendors</h4>
                            <p class="text-muted">No vendor to display.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div class="modal fade" id="rejectModal" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectModalLabel">Reject Vendor Application</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="rejectForm" method="POST">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="application_id" id="modalApplicationId">
                            <div class="form-group">
                                <label for="rejection_reason" class="font-weight-bold">Reason for Rejection</label>
                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="4"
                                    placeholder="Please provide a reason for rejection..." required maxlength="500"></textarea>
                                <small class="form-text text-muted">This reason will be shared with the
                                    applicant.</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">Reject Application</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </section>
    <!-- /.content -->
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <style>
        .swal2-popup {
            font-size: 1rem;
        }

        .vendor-details-container {
            text-align: left;
            max-height: 70vh;
            overflow-y: auto;
            padding: 10px;
        }

        .vendor-image {
            width: 100%;
            max-height: 200px;
            object-fit: contain;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .vendor-images-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 10px;
        }

        .vendor-images-grid img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #ddd;
        }

        .detail-group {
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .detail-group:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #555;
            margin-bottom: 5px;
        }

        .detail-value {
            color: #333;
        }

        .status-badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85em;
            font-weight: 600;
        }
    </style>
@endpush


@endsection
