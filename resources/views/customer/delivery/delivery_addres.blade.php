@extends('layouts.customer')

@section('content_header')
    @include('customer.partials.content_header', [
        'header_1' => Auth()->user()->nickname,
        'header_2' => gtrans('Delivery address'),
    ])
@endsection

@section('content')
    <section class="content">

        <div class="row justify-content-center">
            <div class="col-lg-12 my-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">

                        <h4 class="mb-4 fw-bold">{{ gtrans('Delivery address') }}</h4>

                        @if ($addresses->isEmpty())
                            <div class="d-flex flex-column align-items-center justify-content-center py-5">
                                <div class="mb-3">
                                    {{-- placeholder icon / image --}}
                                    <div
                                        style="width:160px;height:160px;background:#f9fafb;border-radius:16px;
                                            display:flex;align-items:center;justify-content:center;">
                                        <span class="display-4 text-muted">+</span>
                                    </div>
                                </div>
                                <p class="text-muted mb-4">{{ gtrans('No data yet') }}</p>
                                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
                                    data-target="#addAddressModal">
                                    {{ gtrans('Add an address') }}
                                </button>
                            </div>
                        @else
                            {{-- list addresses --}}
                            <div class="mb-3">
                                @foreach ($addresses as $address)
                                    <div
                                        class="border rounded-3 p-3 mb-2 d-flex justify-content-between align-items-center">
                                        <div>
                                            <div class="fw-semibold mb-1">
                                                {{ $address->contact_name }}
                                                @if ($address->is_default)
                                                    <span class="badge badge-primary ml-1">{{ gtrans('Default') }}</span>
                                                @endif
                                            </div>
                                            <div class="text-muted small">
                                                {{ $address->address }}
                                            </div>
                                            <div class="text-muted small">
                                                {{ $address->phone_country_code }} {{ $address->phone_number }}
                                            </div>
                                        </div>

                                        <div
                                            class="border rounded-3 p-3 mb-2 d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="fw-semibold mb-1">
                                                    {{ $address->contact_name }}
                                                    @if ($address->is_default)
                                                        <span
                                                            class="badge badge-primary ml-1">{{ gtrans('Default') }}</span>
                                                    @endif
                                                </div>
                                                <div class="text-muted small">{{ $address->address }}</div>
                                                <div class="text-muted small">
                                                    {{ $address->phone_country_code }} {{ $address->phone_number }}
                                                </div>
                                            </div>

                                            <div class="btn-group btn-group-sm">
                                                <button type="button" class="btn btn-outline-secondary btn-edit-address"
                                                    data-id="{{ $address->id }}" data-address="{{ $address->address }}"
                                                    data-code="{{ $address->phone_country_code }}"
                                                    data-phone="{{ $address->phone_number }}"
                                                    data-contact="{{ $address->contact_name }}">
                                                    {{ gtrans('Edit') }}
                                                </button>
                                                <button type="button" class="btn btn-outline-danger btn-delete-address"
                                                    data-id="{{ $address->id }}">
                                                    {{ gtrans('Delete') }}
                                                </button>
                                            </div>
                                        </div>


                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-primary btn-lg" data-toggle="modal"
                                data-target="#addAddressModal">
                                {{ gtrans('Add an address') }}
                            </button>
                        @endif

                    </div>
                </div>
            </div>
        </div>

    </section>

@section('modal_wapper')
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-md modal-dialog-centered">
            <div class="modal-content border-0">

                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-semibold">{{ gtrans('Add delivery address') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ gtrans('Close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form id="deliveryAddressForm" class="pt-2" novalidate>
                    @csrf
                    <div class="modal-body pt-3">

                        <div class="form-group mb-3">
                            <label class="form-label d-block mb-1">
                                <span class="text-danger">*</span> {{ gtrans('Delivery address') }}
                            </label>
                            <input type="text" name="address" class="form-control"
                                placeholder="{{ gtrans('Please enter the detailed address') }}" required
                                data-parsley-required="true" data-parsley-maxlength="255">
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label d-block mb-1">
                                <span class="text-danger">*</span> {{ gtrans('Contact Number') }}
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">
                                        <input type="text" name="phone_country_code" class="border-0" value="+1"
                                            style="width:40px;outline:none;" required data-parsley-required="true"
                                            data-parsley-maxlength="5">
                                    </span>
                                </div>
                                <input type="text" name="phone_number" class="form-control"
                                    placeholder="{{ gtrans('Please enter your contact number') }}" required
                                    data-parsley-required="true" data-parsley-maxlength="30">
                            </div>
                        </div>

                        <div class="form-group mb-2">
                            <label class="form-label d-block mb-1">
                                <span class="text-danger">*</span> {{ gtrans('Contact') }}
                            </label>
                            <input type="text" name="contact_name" class="form-control"
                                placeholder="{{ gtrans('Please enter a contact person') }}" required
                                data-parsley-required="true" data-parsley-maxlength="100">
                        </div>

                    </div>

                    <div class="modal-footer border-0 pt-0">
                        <button type="submit" class="btn btn-primary" style="height:48px;font-weight:600;">
                            {{ gtrans('Add an address') }}
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/parsleyjs"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const $form = $('#deliveryAddressForm');
        $form.parsley();

        $form.on('submit', function(e) {
            e.preventDefault();
            if (!$form.parsley().validate()) return;

            const $btn = $form.find('button[type="submit"]');
            const original = $btn.text();
            $btn.prop('disabled', true).text('{{ gtrans('Please wait...') }}');

            fetch("{{ route('vendor.delivery.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: new FormData($form[0]),
                })
                .then(async res => {
                    const json = await res.json();
                    if (res.ok && json.status === 'ok') {
                        Toastify({
                            text: json.message ||
                                '{{ gtrans('Delivery address added successfully.') }}',
                            duration: 4000,
                            gravity: "top",
                            position: "right",
                            style: {
                                background: "linear-gradient(to right,#00b09b,#96c93d)"
                            }
                        }).showToast();

                        $('#addAddressModal').modal('hide');
                        $form[0].reset();
                        setTimeout(() => window.location.reload(), 800);
                    } else {
                        throw json;
                    }
                })
                .catch(err => {
                    let msg = '{{ gtrans('Unable to add address.') }}';
                    if (err && err.errors) {
                        const first = Object.keys(err.errors)[0];
                        msg = err.errors[first][0];
                    } else if (err && err.message) {
                        msg = err.message;
                    }

                    Toastify({
                        text: msg,
                        duration: 4000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right,#e53935,#e35d5b)"
                        }
                    }).showToast();
                })
                .finally(() => {
                    $btn.prop('disabled', false).text(original);
                });
        });
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const $form = $('#deliveryAddressForm');
        const $modal = $('#addAddressModal');
        let editingId = null;

        // open for create
        $('[data-target="#addAddressModal"]').on('click', function() {
            editingId = null;
            $form[0].reset();
            $form.attr('data-mode', 'create');
            $modal.find('.modal-title').text('{{ gtrans('Add delivery address') }}');
        });

        // edit button
        $('.btn-edit-address').on('click', function() {
            const btn = $(this);
            editingId = btn.data('id');

            $form.find('input[name="address"]').val(btn.data('address'));
            $form.find('input[name="phone_country_code"]').val(btn.data('code'));
            $form.find('input[name="phone_number"]').val(btn.data('phone'));
            $form.find('input[name="contact_name"]').val(btn.data('contact'));

            $form.attr('data-mode', 'edit');
            $modal.find('.modal-title').text('{{ gtrans('Edit delivery address') }}');
            $modal.modal('show');
        });

        // submit (create or update)
        $form.off('submit').on('submit', function(e) {
            e.preventDefault();
            if (!$form.parsley().validate()) return;

            const $btn = $form.find('button[type="submit"]');
            const original = $btn.text();
            $btn.prop('disabled', true).text('{{ gtrans('Please wait...') }}');

            let url = "{{ route('vendor.delivery.store') }}";
            let method = 'POST';

            if (editingId) {
                url = "{{ url('/vendor/delivery-addresses') }}/" + editingId;
                method = 'POST'; // use POST + _method=PUT
            }

            const fd = new FormData($form[0]);
            if (editingId) fd.append('_method', 'PUT');

            fetch(url, {
                    method: method,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: fd,
                })
                .then(async res => {
                    const json = await res.json();
                    if (!res.ok || json.status !== 'ok') throw json;

                    Toastify({
                        text: json.message || '{{ gtrans('Saved successfully.') }}',
                        duration: 4000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right,#00b09b,#96c93d)"
                        }
                    }).showToast();

                    $modal.modal('hide');
                    setTimeout(() => window.location.reload(), 800);
                })
                .catch(err => {
                    let msg = '{{ gtrans('Unable to save address.') }}';
                    if (err && err.errors) {
                        const first = Object.keys(err.errors)[0];
                        msg = err.errors[first][0];
                    } else if (err && err.message) {
                        msg = err.message;
                    }
                    Toastify({
                        text: msg,
                        duration: 4000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right,#e53935,#e35d5b)"
                        }
                    }).showToast();
                })
                .finally(() => {
                    $btn.prop('disabled', false).text(original);
                });
        });

        // delete
        $('.btn-delete-address').on('click', function() {
            const id = $(this).data('id');
            if (!confirm('{{ gtrans('Are you sure you want to delete this address?') }}')) return;

            fetch("{{ url('/vendor/delivery-addresses') }}/" + id, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: new URLSearchParams({
                        _method: 'DELETE'
                    }),
                })
                .then(async res => {
                    const json = await res.json();
                    if (!res.ok || json.status !== 'ok') throw json;

                    Toastify({
                        text: json.message ||
                            '{{ gtrans('Delivery address deleted successfully.') }}',
                        duration: 4000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right,#00b09b,#96c93d)"
                        }
                    }).showToast();

                    setTimeout(() => window.location.reload(), 600);
                })
                .catch(() => {
                    Toastify({
                        text: '{{ gtrans('Unable to delete address.') }}',
                        duration: 4000,
                        gravity: "top",
                        position: "right",
                        style: {
                            background: "linear-gradient(to right,#e53935,#e35d5b)"
                        }
                    }).showToast();
                });
        });
    });
</script>

<style>
    .parsley-errors-list {
        display: none !important;
    }

    input.parsley-error,
    textarea.parsley-error {
        border-color: #e3342f !important;
        box-shadow: 0 0 0 0.08rem rgba(227, 52, 47, 0.35) !important;
    }
</style>
@endpush
