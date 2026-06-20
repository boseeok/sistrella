@extends('layouts.guest')
@section('title', 'Sign Up')

@section('content')
    <h4 class="fw-bold mb-1">Create your account</h4>
    <p class="text-muted small mb-4">Join us and start shopping handmade crochet.</p>

    <form action="{{ route('register') }}" method="POST">@csrf
        <div class="mb-3">
            <label class="form-label">Full name</label>
            <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required autofocus>
            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" required>
            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>

        <div class="mb-3"><label class="form-label">Phone *</label>
        <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" required maxlength="10" minlength="10" pattern="[0-9]{10}" inputmode="numeric" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,10)"></div>
      
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="mb-3">
            <label class="form-label">Confirm password</label>
            <input type="password" name="password_confirmation" class="form-control" required>
        </div>
        <button class="btn btn-brand w-100">Create account</button>
    </form>

    <p class="text-center small mt-4 mb-0">Already have an account? <a href="{{ route('login') }}">Login</a></p>
@endsection
