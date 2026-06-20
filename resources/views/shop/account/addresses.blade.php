@extends('layouts.app')
@section('title', 'Addresses')

@section('content')
<div class="container">
    <h2 class="section-title mb-4">My Account</h2>
    <div class="row g-4">
        <div class="col-lg-3">@include('partials.account-nav')</div>
        <div class="col-lg-9">
            <div class="row g-3 mb-4">
                @forelse($addresses as $address)
                    <div class="col-md-6">
                        <div class="card p-3 h-100">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $address->label ?: 'Address' }}</strong>
                                @if($address->is_default)<span class="badge bg-brand">Default</span>@endif
                            </div>
                            <p class="small text-muted mb-2 mt-1">
                                {{ $address->full_name }}<br>{{ $address->phone }}<br>{{ $address->formatted }}
                            </p>
                            <form action="{{ route('account.addresses.destroy', $address) }}" method="POST" class="mt-auto">@csrf @method('DELETE')
                                <button class="btn btn-link btn-sm text-danger p-0">Delete</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-12"><p class="text-muted">No saved addresses yet.</p></div>
                @endforelse
            </div>

            <div class="card p-4">
                <h5 class="fw-bold mb-3">Add new address</h5>
                <form action="{{ route('account.addresses.store') }}" method="POST">@csrf
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label small">Label</label><input type="text" name="label" class="form-control" placeholder="Home, Office"></div>
                        <div class="col-md-6"><label class="form-label small">Full name *</label><input type="text" name="full_name" value="{{ auth()->user()->name }}" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label small">Phone *</label><input type="text" name="phone" value="{{ auth()->user()->phone }}" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label small">City *</label><input type="text" name="city" class="form-control" required></div>
                        <div class="col-12"><label class="form-label small">Address line 1 *</label><input type="text" name="line1" class="form-control" required></div>
                        <div class="col-12"><label class="form-label small">Address line 2</label><input type="text" name="line2" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label small">District</label><input type="text" name="district" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label small">Province</label><input type="text" name="province" class="form-control"></div>
                        <div class="col-md-4"><label class="form-label small">Postal code</label><input type="text" name="postal_code" class="form-control"></div>
                        <div class="col-12">
                            <div class="form-check">
                                <input type="checkbox" name="is_default" value="1" id="isDefault" class="form-check-input">
                                <label for="isDefault" class="form-check-label small">Set as default address</label>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-brand mt-3">Add Address</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
