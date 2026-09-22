@extends('layouts.guest')
@section('title', 'Forgot Password')

@section('content')
    <h4 class="fw-bold mb-1">Forgot password?</h4>
    <p class="text-muted small mb-4">Enter your email and we'll send you a reset link.</p>

    <form action="{{ route('password.email') }}" method="POST">@csrf
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required autofocus>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <button class="btn btn-brand w-100">Send reset link</button>
    </form>

    <p class="text-center small mt-4 mb-0"><a href="{{ route('login') }}">&larr; Back to login</a></p>
@endsection
