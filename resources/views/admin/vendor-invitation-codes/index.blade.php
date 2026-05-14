@extends('layouts.admin')

@section('title', 'Vendor Invitation Codes')

@section('content')

<div class="container-full">

    @include('admin.partials.page_header', [
        'header1' => 'Vendor Invitation Codes',
        'header2' => 'Manage Invitation Codes'
    ])

    <section class="content">
        <div class="container-fluid">

            @include('admin.partials.category_alert')

            <div class="card shadow mb-4">

                <div class="card-header py-3 d-flex justify-content-between align-items-center">

                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fa fa-ticket"></i>
                        Vendor Invitation Codes
                    </h6>

                    <a href="{{ route("admin.invitation.vendor-invitation-codes.create") }}"
                       class="btn btn-primary btn-sm">
                        <i class="fa fa-plus"></i>
                        Create Invitation Code
                    </a>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table class="table table-bordered table-hover">

                            <thead class="thead-light">
                                <tr>
                                    <th width="5%">#</th>
                                    <th>Code</th>
                                    <th>Title</th>
                                    <th>Location</th>
                                    <th>Usage</th>
                                    <th>Status</th>
                                    <th>Expiry</th>
                                    <th>Created By</th>
                                    <th width="15%">Actions</th>
                                </tr>
                            </thead>

                            <tbody>

                                @forelse($codes as $key => $code)

                                    <tr>

                                        <td>
                                            {{ $codes->firstItem() + $key }}
                                        </td>

                                        <td>
                                            <div class="d-flex flex-column">

                                                <strong class="text-primary">
                                                    {{ $code->code }}
                                                </strong>

                                                <small class="text-muted">
                                                    Created:
                                                    {{ $code->created_at->format('d M Y') }}
                                                </small>

                                            </div>
                                        </td>

                                        <td>
                                            {{ $code->title }}
                                        </td>

                                        <td>
                                            {{ $code->location ?? 'N/A' }}
                                        </td>

                                        <td>

                                            <div class="d-flex flex-column">

                                                <span>
                                                    Used:
                                                    <strong>
                                                        {{ $code->used_count }}
                                                    </strong>
                                                </span>

                                                <small class="text-muted">
                                                    Limit:
                                                    {{ $code->usage_limit ?? 'Unlimited' }}
                                                </small>

                                            </div>

                                        </td>

                                        <td>

                                            @if($code->isUsable())

                                                <span class="badge badge-success">
                                                    Active
                                                </span>

                                            @else

                                                <span class="badge badge-danger">
                                                    Inactive
                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            @if($code->expires_at)

                                                <div class="d-flex flex-column">

                                                    <span>
                                                        {{ $code->expires_at->format('d M Y') }}
                                                    </span>

                                                    <small class="text-muted">
                                                        {{ $code->expires_at->format('h:i A') }}
                                                    </small>

                                                </div>

                                            @else

                                                <span class="badge badge-info">
                                                    No Expiry
                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            @if($code->creator)

                                                <div class="d-flex flex-column">
                                                    <span>
                                                        {{ $code->creator->name }}
                                                    </span>

                                                    <small class="text-muted">
                                                        {{ $code->creator->email }}
                                                    </small>
                                                </div>

                                            @else

                                                <span class="text-muted">
                                                    System
                                                </span>

                                            @endif

                                        </td>

                                        <td>

                                            <div class="btn-group">

                                                <a href=""
                                                   class="btn btn-sm btn-info">
                                                    <i class="fa fa-edit"></i>
                                                </a>

                                                <form action=""
                                                      method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this invitation code?')">

                                                    @csrf
                                                    @method('DELETE')

                                                    <button type="submit"
                                                            class="btn btn-sm btn-danger">
                                                        <i class="fa fa-trash"></i>
                                                    </button>

                                                </form>

                                            </div>

                                        </td>

                                    </tr>

                                @empty

                                    <tr>
                                        <td colspan="9" class="text-center py-5">

                                            <div class="d-flex flex-column align-items-center">

                                                <i class="fa fa-ticket fa-3x text-muted mb-3"></i>

                                                <h5 class="text-muted">
                                                    No Invitation Codes Found
                                                </h5>

                                                <p class="text-muted">
                                                    Create your first vendor invitation code.
                                                </p>

                                                <a href="{{ route("admin.invitation.vendor-invitation-codes.create") }}"
                                                   class="btn btn-primary btn-sm">

                                                    <i class="fa fa-plus"></i>
                                                    Create Invitation Code

                                                </a>

                                            </div>

                                        </td>
                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                    <div class="mt-3">
                        {{ $codes->links() }}
                    </div>

                </div>

            </div>

        </div>
    </section>

</div>

@endsection