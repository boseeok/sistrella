@extends('layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="container" style="max-width:960px">
    <h2 class="section-title mb-4 text-center">Get in Touch</h2>
    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card p-4 h-100">
                <h5 class="fw-bold mb-3">Contact details</h5>
                <ul class="list-unstyled small">
                    <li class="mb-2"><i class="bi bi-geo-alt text-brand me-2"></i>{{ setting('store_address') }}</li>
                    <li class="mb-2"><i class="bi bi-telephone text-brand me-2"></i>{{ setting('store_phone') }}</li>
                    <li class="mb-2"><i class="bi bi-envelope text-brand me-2"></i>{{ setting('store_email') }}</li>
                </ul>
                <div class="d-flex gap-3 fs-4 mt-2">
                    @if(setting('facebook_url'))<a href="{{ setting('facebook_url') }}"><i class="bi bi-facebook"></i></a>@endif
                    @if(setting('instagram_url'))<a href="{{ setting('instagram_url') }}"><i class="bi bi-instagram"></i></a>@endif
                    <a href="https://wa.me/{{ preg_replace('/\D+/','',setting('whatsapp_number')) }}" class="text-success"><i class="bi bi-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <div class="col-lg-7">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">Send us a message</h5>
                <form action="{{ route('contact.submit') }}" method="POST">@csrf
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label small">Name *</label><input type="text" name="name" value="{{ old('name') }}" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label small">Email *</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required></div>
                        <div class="col-md-6"><label class="form-label small">Phone</label><input type="text" name="phone" value="{{ old('phone') }}" class="form-control"></div>
                        <div class="col-md-6"><label class="form-label small">Subject</label><input type="text" name="subject" value="{{ old('subject') }}" class="form-control"></div>
                        <div class="col-12"><label class="form-label small">Message *</label><textarea name="message" rows="4" class="form-control" required>{{ old('message') }}</textarea></div>
                    </div>
                    <button class="btn btn-brand mt-3">Send Message</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
