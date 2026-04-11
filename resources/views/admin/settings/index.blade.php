@extends('layouts.admin')

@section('title', 'Site Settings')

@section('content')
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Site Settings</h1>
            <button type="submit" form="settingsForm" class="btn btn-primary">
                <i class="fa fa-save"></i> Save All Settings
            </button>
        </div>

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
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <!-- Settings Form -->
   <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <!-- Left Navigation -->
        <div class="col-lg-3">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Settings Groups</h6>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="#general" class="list-group-item list-group-item-action active" data-toggle="tab">
                            <i class="fa fa-cog fa-fw mr-2"></i>General
                        </a>
                        <a href="#contact" class="list-group-item list-group-item-action" data-toggle="tab">
                            <i class="fa fa-phone fa-fw mr-2"></i>Contact Info
                        </a>
                        <a href="#about" class="list-group-item list-group-item-action" data-toggle="tab">
                            <i class="fa fa-info-circle fa-fw mr-2"></i>About Us
                        </a>
                        <a href="#seo" class="list-group-item list-group-item-action" data-toggle="tab">
                            <i class="fa fa-search fa-fw mr-2"></i>SEO & Meta
                        </a>
                        <a href="#appearance" class="list-group-item list-group-item-action" data-toggle="tab">
                            <i class="fa fa-palette fa-fw mr-2"></i>Appearance
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Content -->
        <div class="col-lg-9">
            <div class="tab-content">
                <!-- General Settings -->
                <div class="tab-pane fade show active" id="general">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">General Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Meta Title</label>
                                <div class="col-sm-9">
                                    <input type="text" name="meta_title" class="form-control" 
                                           value="{{ $settings->meta_title }}">
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Meta Description</label>
                                <div class="col-sm-9">
                                    <textarea name="meta_description" class="form-control" rows="3">{{ $settings->meta_description }}</textarea>
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Meta Keywords</label>
                                <div class="col-sm-9">
                                    <input type="text" name="meta_keywords" class="form-control" 
                                           value="{{ $settings->meta_keywords }}">
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Sort Order</label>
                                <div class="col-sm-9">
                                    <input type="number" name="sort_order" class="form-control" 
                                           value="{{ $settings->sort_order }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="tab-pane fade" id="contact">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Contact Email</label>
                                <div class="col-sm-9">
                                    <input type="email" name="contact_email" class="form-control" 
                                           value="{{ $settings->contact_email }}">
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Contact Phone</label>
                                <div class="col-sm-9">
                                    <input type="text" name="contact_phone" class="form-control" 
                                           value="{{ $settings->contact_phone }}">
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Address</label>
                                <div class="col-sm-9">
                                    <textarea name="contact_address" class="form-control" rows="2">{{ $settings->contact_address }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- About Us -->
                <div class="tab-pane fade" id="about">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">About Us Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">About Us</label>
                                <div class="col-sm-9">
                                    <textarea name="about_us" class="form-control" rows="5">{{ $settings->about_us }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SEO & Meta -->
                <div class="tab-pane fade" id="seo">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">SEO & Meta Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">OG Title</label>
                                <div class="col-sm-9">
                                    <input type="text" name="og_title" class="form-control" 
                                           value="{{ $settings->og_title }}">
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">OG Description</label>
                                <div class="col-sm-9">
                                    <textarea name="og_description" class="form-control" rows="3">{{ $settings->og_description }}</textarea>
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">OG Image</label>
                                <div class="col-sm-9">
                                    <input type="file" name="og_image" class="form-control-file">
                                    @if($settings->og_image)
                                        <img src="{{ Storage::url($settings->og_image) }}" class="mt-2" style="max-height: 100px;">
                                    @endif
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Twitter Title</label>
                                <div class="col-sm-9">
                                    <input type="text" name="twitter_title" class="form-control" 
                                           value="{{ $settings->twitter_title }}">
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Twitter Description</label>
                                <div class="col-sm-9">
                                    <textarea name="twitter_description" class="form-control" rows="3">{{ $settings->twitter_description }}</textarea>
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Twitter Image</label>
                                <div class="col-sm-9">
                                    <input type="file" name="twitter_image" class="form-control-file">
                                    @if($settings->twitter_image)
                                        <img src="{{ Storage::url($settings->twitter_image) }}" class="mt-2" style="max-height: 100px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Appearance -->
                <div class="tab-pane fade" id="appearance">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Appearance Settings</h6>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Site Logo</label>
                                <div class="col-sm-9">
                                    <input type="file" name="site_logo" class="form-control-file">
                                    @if($settings->site_logo)
                                        <img src="{{ Storage::url($settings->site_logo) }}" class="mt-2" style="max-height: 100px;">
                                    @endif
                                </div>
                            </div>
                            <hr>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Site Favicon</label>
                                <div class="col-sm-9">
                                    <input type="file" name="site_favicon" class="form-control-file">
                                    @if($settings->site_favicon)
                                        <img src="{{ Storage::url($settings->site_favicon) }}" class="mt-2" style="max-height: 50px;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </div>
            </div>
        </div>
    </div>
</form>
    </div>
@endsection

@push('styles')
    <style>
        .list-group-item.active {
            background-color: #4e73df;
            border-color: #4e73df;
        }

        .setting-image-preview {
            max-width: 200px;
            max-height: 100px;
            object-fit: contain;
        }

        .color-preview {
            width: 30px;
            height: 30px;
            border-radius: 4px;
            border: 1px solid #ddd;
            display: inline-block;
            margin-right: 10px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            // Initialize tab functionality
            $('a[data-toggle="tab"]').on('click', function(e) {
                e.preventDefault();
                $(this).tab('show');
            });

            // Tab persistence and activation
            var activeTab = localStorage.getItem('activeTab');
            if (activeTab) {
                // Remove active classes from all tabs and panes
                $('.list-group-item').removeClass('active');
                $('.tab-pane').removeClass('show active');

                // Activate the saved tab
                $('a[href="' + activeTab + '"]').addClass('active');
                $(activeTab).addClass('show active');
            }

            // Save active tab when changed
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                localStorage.setItem('activeTab', $(e.target).attr('href'));
            });

            // Rest of your existing code for image preview, color picker, etc.
            $('input[type="file"]').on('change', function() {
                const input = this;
                const preview = $(this).closest('.form-group').find('.image-preview');

                if (input.files && input.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.attr('src', e.target.result).removeClass('d-none');
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            });

            $('input[type="color"]').on('change', function() {
                const color = $(this).val();
                $(this).closest('.input-group').find('.color-preview').css('background-color', color);
            });

            $('.reset-setting').on('click', function(e) {
                e.preventDefault();
                const key = $(this).data('key');

                Swal.fire({
                    title: 'Reset Setting?',
                    text: 'Are you sure you want to reset this setting to its default value?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, reset it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = $(this).attr('href');
                    }
                });
            });

            $('.clear-image').on('click', function(e) {
                e.preventDefault();
                const key = $(this).data('key');

                Swal.fire({
                    title: 'Clear Image?',
                    text: 'Are you sure you want to remove this image?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, clear it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = $(this).attr('href');
                    }
                });
            });
        });
    </script>
@endpush
