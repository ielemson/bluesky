@extends('layouts.customer')
@section('content')
    <section class="content">

        <div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-md-12">
            
            {{-- Display Status Messages --}}
            @if($vendorApplication)
                @if($vendorApplication->status == 'pending')
                    <div class="alert alert-warning">
                        <h4>⏳ Application Under Review</h4>
                        <p>Your vendor application is currently being reviewed. Please check back later for updates.</p>
                        <p><strong>Application Date:</strong> {{ $vendorApplication->created_at->format('M d, Y') }}</p>
                    </div>

                @elseif($vendorApplication->status == 'rejected')
                    <div class="alert alert-danger">
                        <h4>❌ Application Rejected</h4>
                        <p>Unfortunately, your vendor application has been rejected. Please contact support for more information.</p>
                        <p><strong>Rejected On:</strong> {{ $vendorApplication->updated_at->format('M d, Y') }}</p>
                    </div>

                @elseif($vendorApplication->status == 'approved')
                    {{-- Show success message AND the form for approved vendors --}}
                    <div class="alert alert-success">
                        <h4>✅ Application Approved!</h4>
                        <p>Congratulations! Your vendor application has been approved. You can now manage your store.</p>
                        <p><strong>Approved On:</strong> {{ $vendorApplication->updated_at->format('M d, Y') }}</p>
                    </div>
                @endif

                {{-- Show application details --}}
                <div class="card mb-4">
                    <div class="card-header">
                        <h5>Your Application Details</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Store Name:</strong> {{ $vendorApplication->store_name }}</p>
                                <p><strong>Contact Person:</strong> {{ $vendorApplication->contact_person }}</p>
                                <p><strong>ID Number:</strong> {{ $vendorApplication->id_number }}</p>
                                <p><strong>Main Business:</strong> {{ $vendorApplication->main_business }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Status:</strong> 
                                    <span class="badge 
                                        @if($vendorApplication->status == 'approved') bg-success
                                        @elseif($vendorApplication->status == 'rejected') bg-danger
                                        @else bg-warning @endif">
                                        {{ ucfirst($vendorApplication->status) }}
                                    </span>
                                </p>
                                <p><strong>Applied On:</strong> {{ $vendorApplication->created_at->format('M d, Y H:i') }}</p>
                                @if($vendorApplication->invite_code)
                                    <p><strong>Invite Code:</strong> {{ $vendorApplication->invite_code }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <p><strong>Address:</strong> {{ $vendorApplication->address }}</p>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                {{-- No application exists --}}
                <div class="alert alert-info">
                    <h4>Become a Vendor</h4>
                    <p>You haven't submitted a vendor application yet. Fill out the form below to apply.</p>
                </div>
            @endif

            {{-- SHOW FORM ONLY IF APPROVED OR NO APPLICATION EXISTS --}}
            @if(!$vendorApplication )
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">
                            @if($vendorApplication && $vendorApplication->status == 'approved')
                                🛍️ Manage Your Store
                            @else
                                📝 Vendor Application Form
                            @endif
                        </h4>
                    </div>
                    
                    <div class="card-body">
                        @if($vendorApplication && $vendorApplication->status == 'approved')
                            <div class="alert alert-info">
                                <p>You can update your store information below.</p>
                            </div>
                        @endif
                        
                        <form id="vendorForm" enctype="multipart/form-data" data-parsley-validate>
                            @if($vendorApplication && $vendorApplication->status == 'approved')
                                <input type="hidden" name="application_id" value="{{ $vendorApplication->id }}">
                            @endif

                            <div class="form-group mb-4">
                                <label class="font-weight-bold">Store Image</label>
                                <div class="upload-sample text-center" onclick="document.getElementById('store_image').click();">
                                    <img id="preview_store" 
                                        src="{{ $vendorApplication && $vendorApplication->store_logo ? asset('storage/' . $vendorApplication->store_logo) : asset('customer/images/store.png') }}" 
                                        alt="Store Image">
                                    <p class="text-muted mt-2">Click to upload store image</p>
                                </div>
                                <input type="file" id="store_image" name="store_logo" class="d-none" accept="image/*"
                                    {{ !$vendorApplication ? 'required' : '' }} 
                                    onchange="previewImage(this, 'preview_store')">
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Store Name</label>
                                        <input type="text" name="store_name" class="form-control" required
                                            value="{{ $vendorApplication ? $vendorApplication->store_name : '' }}"
                                            data-parsley-required-message="Store name is required">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Contact Person</label>
                                        <input type="text" name="contact_person" class="form-control" required
                                            value="{{ $vendorApplication ? $vendorApplication->contact_person : '' }}"
                                            data-parsley-required-message="Contact person is required">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>ID Number</label>
                                        <input type="text" name="id_number" class="form-control" required
                                            value="{{ $vendorApplication ? $vendorApplication->id_number : '' }}"
                                            data-parsley-required-message="ID number is required"
                                            {{ $vendorApplication ? 'readonly' : '' }}>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Invite Code</label>
                                        <input type="text" name="invite_code" class="form-control"
                                            value="{{ $vendorApplication ? $vendorApplication->invite_code : '' }}"
                                            data-parsley-required-message="Invite code is required">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- FRONT SIDE -->
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">ID Card (Front)</label>
                                        <div class="upload-sample text-center" onclick="document.getElementById('idcard_front').click();">
                                            <img id="preview_front" 
                                                src="{{ $vendorApplication && $vendorApplication->idcard_front ? asset('storage/' . $vendorApplication->idcard_front) : asset('customer/images/idcard-front.png') }}" 
                                                alt="ID Front">
                                            <p class="text-muted mt-2">Click to upload front side</p>
                                        </div>
                                        <input type="file" id="idcard_front" name="idcard_front" class="d-none" accept="image/*"
                                            {{ !$vendorApplication ? 'required' : '' }} 
                                            onchange="previewImage(this, 'preview_front')">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- BACK SIDE -->
                                    <div class="form-group mb-4">
                                        <label class="font-weight-bold">ID Card (Back)</label>
                                        <div class="upload-sample text-center" onclick="document.getElementById('idcard_back').click();">
                                            <img id="preview_back" 
                                                src="{{ $vendorApplication && $vendorApplication->idcard_back ? asset('storage/' . $vendorApplication->idcard_back) : asset('customer/images/idcard-back.png') }}" 
                                                alt="ID Back">
                                            <p class="text-muted mt-2">Click to upload back side</p>
                                        </div>
                                        <input type="file" id="idcard_back" name="idcard_back" class="d-none" accept="image/*"
                                            {{ !$vendorApplication ? 'required' : '' }} 
                                            onchange="previewImage(this, 'preview_back')">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label>Main Business</label>
                                <select name="main_business" class="form-control" required
                                    data-parsley-required-message="Select a business category">
                                    <option value="">-- Select Business --</option>
                                    <option value="Agriculture" {{ $vendorApplication && $vendorApplication->main_business == 'Agriculture' ? 'selected' : '' }}>Agriculture</option>
                                    <option value="Food & Groceries" {{ $vendorApplication && $vendorApplication->main_business == 'Food & Groceries' ? 'selected' : '' }}>Food & Groceries</option>
                                    <option value="Fashion" {{ $vendorApplication && $vendorApplication->main_business == 'Fashion' ? 'selected' : '' }}>Fashion</option>
                                    <option value="Electronics" {{ $vendorApplication && $vendorApplication->main_business == 'Electronics' ? 'selected' : '' }}>Electronics</option>
                                    <option value="Others" {{ $vendorApplication && $vendorApplication->main_business == 'Others' ? 'selected' : '' }}>Others</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <label>Detailed Address</label>
                                <textarea name="address" rows="3" class="form-control" required
                                    data-parsley-required-message="Address is required">{{ $vendorApplication ? $vendorApplication->address : '' }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary btn-block">
                                @if($vendorApplication && $vendorApplication->status == 'approved')
                                    Update Store Information
                                @else
                                    Submit Application
                                @endif
                            </button>
                        </form>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
           
    </section>
@endsection

@push('styles')
    <style>
        .upload-sample {
            cursor: pointer;
            border: 2px dashed #ccc;
            border-radius: 8px;
            padding: 10px;
            background: #f8f9fa;
            transition: 0.3s;
        }

        .upload-sample:hover {
            border-color: #007bff;
            background: #eef5ff;
        }

        .upload-sample img {
            width: 50%;
            max-height: 100px;
            object-fit: contain;
            border-radius: 6px;
        }

        /* Input border feedback */
        input.parsley-error,
        select.parsley-error,
        textarea.parsley-error {
            border-color: #e74c3c !important;
            background-image: url('data:image/svg+xml;utf8,<svg fill="none" stroke="%23e74c3c" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L4.34 16c-.77 1.333.192 3 1.732 3z"/></svg>');
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 18px;
        }

        /* Valid state */
        input.parsley-success,
        select.parsley-success,
        textarea.parsley-success {
            border-color: #28a745 !important;
            background-image: url('data:image/svg+xml;utf8,<svg fill="none" stroke="%2328a745" stroke-width="3" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>');
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 18px;
        }

        /* Keep layout steady */
        .parsley-errors-list {
            display: none !important;
        }

        .is-invalid {
            border-color: #e3342f !important;
            box-shadow: 0 0 3px rgba(227, 52, 47, 0.4);
        }

        .error-icon {
            position: absolute;
            right: 10px;
            top: 12px;
            color: #e3342f;
            font-size: 14px;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script>

    <!-- Linearicons CDN -->
    <link rel="stylesheet" href="https://cdn.linearicons.com/free/1.0.0/icon-font.min.css">

    <!-- Toastify (if already included elsewhere, skip this) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    
<script>
      // ---------------------------------------------
// SETUP CSRF TOKEN FOR ALL AJAX REQUESTS
// ---------------------------------------------
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


// ---------------------------------------------
// IMAGE PREVIEW HANDLER
// ---------------------------------------------
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        let reader = new FileReader();
        reader.onload = function(e) {
            $('#' + previewId).attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

 // ---------------------------------------------
// PARSLEY VALIDATION + AJAX SUBMISSION
// ---------------------------------------------
$('#vendorForm').parsley().on('form:submit', function() {

    // Create FormData properly
    let formData = new FormData();
    
    // Append all form fields individually
    formData.append('store_logo', $('#store_image')[0].files[0]);
    formData.append('store_name', $('input[name="store_name"]').val());
    formData.append('contact_person', $('input[name="contact_person"]').val());
    formData.append('id_number', $('input[name="id_number"]').val());
    formData.append('invite_code', $('input[name="invite_code"]').val());
    formData.append('idcard_front', $('#idcard_front')[0].files[0]);
    formData.append('idcard_back', $('#idcard_back')[0].files[0]);
    formData.append('main_business', $('select[name="main_business"]').val());
    formData.append('address', $('textarea[name="address"]').val());

    $.ajax({
        url: "{{ route('vendor.apply') }}",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,

        beforeSend: function () {
            $("#vendorForm button[type=submit]")
                .prop('disabled', true)
                .html('Submitting...');
        },

        success: function (response) {
            Toastify({
                text: "Application submitted successfully!",
                duration: 3500,
                gravity: "top",
                position: "right",
                backgroundColor: "#28a745"
            }).showToast();

            setTimeout(() => {
                window.location.reload();
            }, 1500);
        },

        error: function (xhr) {
            console.error('Error details:', xhr.responseJSON); // Debugging
            
            let message = "Something went wrong";

            if (xhr.status === 419) {
                message = "Session expired. Please refresh the page.";
            } else if (xhr.status === 422) {
                // Laravel validation errors
                const errors = xhr.responseJSON.errors;
                message = Object.values(errors)[0][0];
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }

            Toastify({
                text: message,
                duration: 3500,
                gravity: "top",
                position: "right",
                backgroundColor: "#dc3545"
            }).showToast();

            $("#vendorForm button[type=submit]")
                .prop('disabled', false)
                .html('Submit Application');
        }
    });

    return false; // prevent default submission
});
    </script>
@endpush
