@extends('layouts.guest')
@section('title', 'Verify Email')

@section('content')
    <div class="text-center">
        <i class="bi bi-envelope-paper-heart text-brand" style="font-size:3rem"></i>
        <h4 class="fw-bold mt-2 mb-1">Verify your email</h4>
        <p class="text-muted small mb-4">
            We've sent a verification link to your email address. Please click it to activate your account.
            Didn't get it? Resend below.
        </p>
    </div>

    <form action="{{ route('verification.send') }}" method="POST">@csrf
        <button class="btn btn-brand w-100">Resend verification email</button>
    </form>

    <div class="d-flex justify-content-between mt-3">
        <a href="{{ route('account.dashboard') }}" class="small">Skip for now</a>
        <form action="{{ route('logout') }}" method="POST">@csrf
            <button class="btn btn-link btn-sm text-muted p-0">Logout</button>
        </form>
    </div>
@endsection
