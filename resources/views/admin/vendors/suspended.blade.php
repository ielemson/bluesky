@extends('layouts.admin')

@section('content')
@section('title', 'Pending Vendor Applications')
<div class="container-full">
    <!-- Content Header (Page header) -->
    @include('admin.partials.page_header', ['header1' => 'Vendors', 'header2' => 'Pending Vendors'])
    <!-- Main content -->
    <section class="content">

        <div class="container-fluid">

            <!-- Applications Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Pending Applications</h6>
                </div>
                <div class="card-body">
                    @if ($suspendedVendors->count() > 0)
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
                                    @foreach ($suspendedVendors as $application)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($application->store_logo)
                                                        <img src="{{ asset("$application->store_logo") }}"
                                                            alt="{{ $application->store_name }}"
                                                            class="rounded-circle mr-3" width="40" height="40"
                                                            style="object-fit: cover;">
                                                    @else
                                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-3"
                                                            style="width: 40px; height: 40px;">
                                                            <i class="fa fa-store text-muted"></i>
                                                        </div>
                                                    @endif
                                                    <div>
                                                        <strong>{{ $application->store_name }}</strong><br>
                                                        <small
                                                            class="text-muted">{{ $application->user->email ?? 'N/A' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $application->contact_person }}</td>
                                            <td>{{ $application->id_number }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ $application->main_business }}</span>
                                            </td>
                                            <td>{{ $application->created_at->format('M d, Y H:i') }}</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="javascript:;" class="btn btn-info" title="View Details"   onclick="viewVendorDetails({{ $application->id }})">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-success"
                                                        onclick="approveApplication({{ $application->id }})"
                                                        title="Approve">
                                                        <i class="fa fa-check"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#rejectModal"
                                                        data-application-id="{{ $application->id }}" title="Reject">
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
                            <h4>No Suspended Applications</h4>
                            <p class="text-muted">All vendor applications have been processed.</p>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// Approve Application with SweetAlert
function approveApplication(applicationId, storeName) {
    Swal.fire({
        title: 'Approve Vendor Application?',
        html: `Are you sure you want to approve <strong>${storeName}</strong> as a vendor?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, Approve!',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            return new Promise((resolve) => {
                // Create and submit form
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/vendors/${applicationId}/approve`;
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
                
                resolve();
            });
        }
    }).then((result) => {
        if (result.isDismissed) {
            Swal.fire({
                title: 'Cancelled',
                text: 'Application approval was cancelled',
                icon: 'info',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
}

// Reject Application with SweetAlert
function rejectApplication(applicationId, storeName) {
    Swal.fire({
        title: 'Reject Vendor Application?',
        html: `Please provide a reason for rejecting <strong>${storeName}</strong>:`,
        icon: 'warning',
        input: 'textarea',
        inputLabel: 'Rejection Reason',
        inputPlaceholder: 'Enter the reason for rejection...',
        inputAttributes: {
            'aria-label': 'Enter the reason for rejection'
        },
        inputValidator: (value) => {
            if (!value) {
                return 'You need to provide a reason for rejection!';
            }
            if (value.length < 10) {
                return 'Please provide a more detailed reason (at least 10 characters)';
            }
            if (value.length > 500) {
                return 'Reason must be less than 500 characters';
            }
        },
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Reject Application',
        cancelButtonText: 'Cancel',
        reverseButtons: true,
        showLoaderOnConfirm: true,
        preConfirm: (rejectionReason) => {
            return new Promise((resolve, reject) => {
                // Create form data
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('rejection_reason', rejectionReason);

                // Submit via fetch
                fetch(`/admin/vendors/${applicationId}/reject`, {
                    method: 'POST',
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response;
                })
                .then(() => {
                    resolve();
                })
                .catch(error => {
                    reject('Error rejecting application: ' + error.message);
                });
            });
        }
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Rejected!',
                text: 'Vendor application has been rejected.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Reload page after successful rejection
                window.location.reload();
            });
        } else if (result.isDismissed) {
            Swal.fire({
                title: 'Cancelled',
                text: 'Application rejection was cancelled',
                icon: 'info',
                timer: 2000,
                showConfirmButton: false
            });
        }
    }).catch((error) => {
        Swal.fire({
            title: 'Error!',
            text: error,
            icon: 'error',
            confirmButtonText: 'OK'
        });
    });
}

// Success messages from server
@if(session('success'))
Swal.fire({
    title: 'Success!',
    text: '{{ session('success') }}',
    icon: 'success',
    timer: 3000,
    showConfirmButton: false
});
@endif

@if(session('error'))
Swal.fire({
    title: 'Error!',
    text: '{{ session('error') }}',
    icon: 'error',
    confirmButtonText: 'OK'
});
@endif

// View Vendor Details with SweetAlert
function viewVendorDetails(applicationId) {
    // Show loading state first
    Swal.fire({
        title: 'Loading...',
        text: 'Fetching vendor details',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    // Fetch vendor details via AJAX
    fetch(`/admin/vendors/${applicationId}/details`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Failed to fetch vendor details');
            }
            return response.json();
        })
        .then(data => {
            Swal.close();
            
            // Format the application date
            const appliedDate = new Date(data.created_at).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            // Format the status badge
            const statusBadge = getStatusBadge(data.status);

            Swal.fire({
                title: `Vendor Application - ${data.store_name}`,
                html: `
                    <div class="vendor-details-container">
                        <!-- Status and Basic Info -->
                        <div class="text-center mb-4">
                            ${statusBadge}
                            <p class="text-muted mt-2"><i class="fa fa-calendar"></i> Applied on ${appliedDate}</p>
                        </div>

                        <!-- Store Information -->
                        <div class="info-card">
                            <h6><i class="fa fa-store"></i> Store Information</h6>
                            <div class="row align-items-center">
                                <div class="col-md-3 text-center">
                                    ${data.store_logo ? 
                                        `<img src="{{ asset('${data.store_logo}') }}" alt="Store Logo" class="vendor-image" 
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                         <div class="image-placeholder" style="display: none;">
                                             <i class="fa fa-store"></i>
                                             <p class="small mb-0 mt-2">Logo not available</p>
                                         </div>` :
                                        `<div class="image-placeholder">
                                            <i class="fa fa-store"></i>
                                            <p class="small mb-0 mt-2">No logo uploaded</p>
                                         </div>`
                                    }
                                </div>
                                <div class="col-md-9">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p class="detail-label">Store Name</p>
                                            <p class="detail-value"><strong>${data.store_name}</strong></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="detail-label">Contact Person</p>
                                            <p class="detail-value">${data.contact_person}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="detail-label">Business Type</p>
                                            <p class="detail-value"><span class="badge badge-info">${data.main_business}</span></p>
                                        </div>
                                        <div class="col-md-6">
                                            <p class="detail-label">Invite Code</p>
                                            <p class="detail-value">${data.invite_code || '<span class="text-muted">N/A</span>'}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ID Information -->
                        <div class="info-card">
                            <h6><i class="fa fa-id-card"></i> Identification Details</h6>
                            <p class="detail-label">ID Number</p>
                            <p class="detail-value">${data.id_number}</p>
                            
                            <div class="vendor-images-grid mt-3">
                                <div class="text-center">
                                    <p class="detail-label mb-2"><i class="fa fa-id-card"></i> Front Side</p>
                                    ${data.idcard_front ? 
                                        `<img src="{{ asset('${data.idcard_front}') }}" alt="ID Card Front" 
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                         <div class="image-placeholder" style="display: none;">
                                             <i class="fa fa-id-card"></i>
                                             <p class="small mb-0 mt-2">Front image not available</p>
                                         </div>` :
                                        `<div class="image-placeholder">
                                            <i class="fa fa-id-card"></i>
                                            <p class="small mb-0 mt-2">Front image not uploaded</p>
                                         </div>`
                                    }
                                </div>
                                <div class="text-center">
                                    <p class="detail-label mb-2"><i class="fa fa-id-card"></i> Back Side</p>
                                    ${data.idcard_back ? 
                                        `<img src="{{ asset('${data.idcard_back}') }}" alt="ID Card Back" 
                                             onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                         <div class="image-placeholder" style="display: none;">
                                             <i class="fa fa-id-card"></i>
                                             <p class="small mb-0 mt-2">Back image not available</p>
                                         </div>` :
                                        `<div class="image-placeholder">
                                            <i class="fa fa-id-card"></i>
                                            <p class="small mb-0 mt-2">Back image not uploaded</p>
                                         </div>`
                                    }
                                </div>
                            </div>
                        </div>

                        <!-- Address Information -->
                        <div class="info-card">
                            <h6><i class="fa fa-map-marker-alt"></i> Store Address</h6>
                            <p class="detail-value">${data.address}</p>
                        </div>

                        <!-- Application Meta -->
                        <div class="info-card">
                            <h6><i class="fa fa-info-circle"></i> Application Metadata</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="detail-label">Application ID</p>
                                    <p class="detail-value">#${data.id}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="detail-label">User ID</p>
                                    <p class="detail-value">${data.user_id || '<span class="text-muted">N/A</span>'}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="detail-label">User Email</p>
                                    <p class="detail-value">${data.user_email || '<span class="text-muted">N/A</span>'}</p>
                                </div>
                                <div class="col-md-6">
                                    <p class="detail-label">Last Updated</p>
                                    <p class="detail-value">${new Date(data.updated_at).toLocaleDateString('en-US', {
                                        year: 'numeric',
                                        month: 'short',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit'
                                    })}</p>
                                </div>
                            </div>
                        </div>

                        ${data.rejection_reason ? `
                        <!-- Rejection Reason -->
                        <div class="info-card border border-danger">
                            <h6 class="text-danger"><i class="fa fa-times-circle"></i> Rejection Reason</h6>
                            <p class="detail-value">${data.rejection_reason}</p>
                        </div>
                        ` : ''}
                    </div>
                `,
                width: 950,
                padding: '1.5em',
                showCloseButton: true,
                showConfirmButton: false,
                showCancelButton: false,
                customClass: {
                    popup: 'animate__animated animate__fadeInUp'
                },
                didOpen: () => {
                    // Add image zoom functionality
                    const images = document.querySelectorAll('.vendor-images-grid img, .vendor-image');
                    images.forEach(img => {
                        if (img.style.display !== 'none') {
                            img.style.cursor = 'pointer';
                            img.addEventListener('click', function() {
                                Swal.fire({
                                    imageUrl: this.src,
                                    imageAlt: this.alt,
                                    imageHeight: 'auto',
                                    imageWidth: '80%',
                                    showCloseButton: true,
                                    showConfirmButton: false,
                                    background: 'rgba(0,0,0,0.9)',
                                    customClass: {
                                        popup: 'animate__animated animate__zoomIn'
                                    }
                                });
                            });
                        }
                    });
                }
            });
        })
        .catch(error => {
            Swal.fire({
                title: 'Error!',
                text: error.message,
                icon: 'error',
                confirmButtonText: 'OK',
                customClass: {
                    popup: 'animate__animated animate__shakeX'
                }
            });
        });
}

// Helper function to generate status badge
function getStatusBadge(status) {
    const statusConfig = {
        'pending': { 
            class: 'bg-warning text-dark', 
            icon: '⏳', 
            text: 'Pending Review'
        },
        'approved': { 
            class: 'bg-success text-white', 
            icon: '✅', 
            text: 'Approved'
        },
        'rejected': { 
            class: 'bg-danger text-white', 
            icon: '❌', 
            text: 'Rejected'
        }
    };
    
    const config = statusConfig[status] || { 
        class: 'bg-secondary text-white', 
        icon: '❓', 
        text: status
    };
    
    return `<span class="status-badge ${config.class}">${config.icon} ${config.text}</span>`;
}

</script>
@endpush
@endsection
