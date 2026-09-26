@extends('layouts.guest')
@section('title', 'Login')

@section('content')
    <h4 class="fw-bold mb-1">Welcome back</h4>
    <p class="text-muted small mb-4">Login to your account to continue.</p>

    <form action="{{ route('login') }}" method="POST">@csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label for="remember" class="form-check-label small">Remember me</label>
            </div>
            <a href="{{ route('password.request') }}" class="small">Forgot password?</a>
        </div>
        <button class="btn btn-brand w-100">Login</button>
    </form>

    <p class="text-center small mt-4 mb-0">Don't have an account? <a href="{{ route('register') }}">Sign up</a></p>
@endsection
