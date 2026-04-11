@extends('layouts.customer')

@section('content')
@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => Auth()->user()->nickname,
        'header_2' => 'Shop Details',
    ])
@endsection

<section class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="box">
                <div class="box-body">
                    <div class="row">

                        {{-- Store Images --}}
                        <div class="col-md-4 col-sm-6">
                            <div class="box box-body b-1 text-center no-shadow">
                                <img src="{{ $vendor->store_logo ? asset($vendor->store_logo) : 'https://via.placeholder.com/500x350?text=Store+Logo' }}"
                                     id="shop-main-image"
                                     class="img-fluid"
                                     alt="{{ $vendor->store_name }}" />
                            </div>

                            <div class="pro-photos">
                                <div class="photos-item item-active">
                                    <img src="{{ $vendor->store_logo ? asset($vendor->store_logo) : 'https://via.placeholder.com/120x100?text=Logo' }}"
                                         alt="Store Logo">
                                </div>
                                <div class="photos-item">
                                    <img src="{{ $vendor->idcard_front ? asset($vendor->idcard_front) : 'https://via.placeholder.com/120x100?text=ID+Front' }}"
                                         alt="ID Front">
                                </div>
                                <div class="photos-item">
                                    <img src="{{ $vendor->idcard_back ? asset($vendor->idcard_back) : 'https://via.placeholder.com/120x100?text=ID+Back' }}"
                                         alt="ID Back">
                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>

                        {{-- Store Details --}}
                        <div class="col-md-8 col-sm-6">
                            <h2 class="box-title mt-0">{{ $vendor->store_name }}</h2>

                            <h4 class="pro-price mb-0 mt-20">
                                <span class="badge {{ $vendor->status === 'approved' ? 'badge-success' : ($vendor->status === 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                    {{ ucfirst($vendor->status) }}
                                </span>
                            </h4>

                            <hr>

                            <p>
                                {{ $vendor->main_business ?: 'No business description has been added for this store yet.' }}
                            </p>

                            <div class="row">
                                <div class="col-sm-12">
                                    <h6>Contact Person</h6>
                                    <p class="text-muted mb-15">{{ $vendor->contact_person ?: 'N/A' }}</p>

                                    <h6>Business Address</h6>
                                    <p class="text-muted mb-15">{{ $vendor->address ?: 'N/A' }}</p>

                                    <h6>Invite Code</h6>
                                    <p class="mb-0">
                                        <span class="badge badge-lg badge-default">
                                            {{ $vendor->invite_code ?: 'Not Available' }}
                                        </span>
                                    </p>
                                </div>
                            </div>

                            <hr>

                            <a href="{{ route('vendor.listings.my') }}" class="btn btn-primary btn-outline mr-5">
                                <i class="mdi mdi-view-list"></i> My Listings
                            </a>

                            <a href="{{ route('customer.products.index') }}" class="btn btn-success btn-outline mr-5">
                                <i class="mdi mdi-cart-plus"></i> Add Products
                            </a>

                            <a href="{{ route('vendor.balance') }}" class="btn btn-info btn-outline mr-5">
                                <i class="mdi mdi-wallet"></i> My Balance
                            </a>

                            <h4 class="box-title mt-40">Shop Highlights</h4>
                            <ul class="list-icons">
                                <li>
                                    <i class="fa fa-check text-danger float-none"></i>
                                    Store Name: {{ $vendor->store_name }}
                                </li>
                                <li>
                                    <i class="fa fa-check text-danger float-none"></i>
                                    Main Business: {{ $vendor->main_business ?: 'N/A' }}
                                </li>
                                <li>
                                    <i class="fa fa-check text-danger float-none"></i>
                                    Current Status: {{ ucfirst($vendor->status) }}
                                </li>
                            </ul>
                        </div>

                        {{-- General Info --}}
                        <div class="col-lg-12 col-md-12 col-sm-12">
                            <h4 class="box-title mt-40">General Info</h4>
                            <div class="table-responsive">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <td width="390">Store ID</td>
                                            <td>#{{ $vendor->id }}</td>
                                        </tr>
                                        <tr>
                                            <td>Store Name</td>
                                            <td>{{ $vendor->store_name }}</td>
                                        </tr>
                                        <tr>
                                            <td>Contact Person</td>
                                            <td>{{ $vendor->contact_person ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>ID Number</td>
                                            <td>{{ $vendor->id_number ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Invite Code</td>
                                            <td>{{ $vendor->invite_code ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Main Business</td>
                                            <td>{{ $vendor->main_business ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Address</td>
                                            <td>{{ $vendor->address ?: 'N/A' }}</td>
                                        </tr>
                                        <tr>
                                            <td>Status</td>
                                            <td>
                                                <span class="{{ $vendor->status === 'approved' ? 'text-success' : ($vendor->status === 'pending' ? 'text-warning' : 'text-danger') }}">
                                                    {{ ucfirst($vendor->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Created At</td>
                                            <td>{{ optional($vendor->created_at)->format('d M, Y h:i A') }}</td>
                                        </tr>
                                        <tr>
                                            <td>Last Updated</td>
                                            <td>{{ optional($vendor->updated_at)->format('d M, Y h:i A') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const mainImage = document.getElementById('shop-main-image');
        const thumbs = document.querySelectorAll('.photos-item img');

        thumbs.forEach(img => {
            img.addEventListener('click', function () {
                if (mainImage) {
                    mainImage.src = this.src;
                }

                document.querySelectorAll('.photos-item').forEach(item => {
                    item.classList.remove('item-active');
                });

                this.closest('.photos-item')?.classList.add('item-active');
            });
        });
    });
</script>
@endpush