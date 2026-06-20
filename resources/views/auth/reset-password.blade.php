@extends('layouts.guest')
@section('title', 'Reset Password')

@section('content')
    <h4 class="fw-bold mb-1">Reset password</h4>
    <p class="text-muted small mb-4">Choose a new password for your account.</p>

    <form action="{{ route('password.store') }}" method="POST">@csrf
        <input type="hidden" name="token" value="{{ $token }}">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">New password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm new password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button class="btn btn-brand w-100">Reset password</button>
    </form>
@endsection
