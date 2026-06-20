@extends('layouts.app')
@section('title', 'Profile')

@section('content')
<div class="container">
    <h2 class="section-title mb-4">My Account</h2>
    <div class="row g-4">
        <div class="col-lg-3">@include('partials.account-nav')</div>
        <div class="col-lg-9">
            <div class="card p-4 mb-4">
                <h5 class="fw-bold mb-3">Profile details</h5>
                <form action="{{ route('account.profile.update') }}" method="POST">@csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Phone</label>
                            <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="form-control" required>
                        </div>
                    </div>
                    <button class="btn btn-brand mt-3">Save Changes</button>
                </form>
            </div>

            <div class="card p-4">
                <h5 class="fw-bold mb-3">Change password</h5>
                <form action="{{ route('account.password.update') }}" method="POST">@csrf @method('PUT')
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small">Current password</label>
                            <input type="password" name="current_password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">New password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small">Confirm new password</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>
                    <button class="btn btn-brand mt-3">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
