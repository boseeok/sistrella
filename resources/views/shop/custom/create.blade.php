@extends('layouts.app')
@section('title', 'Custom Crochet Order')

@section('content')
<div class="container" style="max-width:820px">
    <div class="text-center mb-4">
        <i class="bi bi-stars text-brand" style="font-size:2.5rem"></i>
        <h2 class="section-title">Request a Custom Order</h2>
        <p class="text-muted">Tell us what you'd love and we'll craft it just for you. We'll review your request and send a quote.</p>
    </div>

    <div class="card p-4 p-md-5">
        <form action="{{ route('custom.store') }}" method="POST" enctype="multipart/form-data">@csrf
            <h6 class="fw-bold mb-3">Your details</h6>
            <div class="row g-3 mb-4">
                <div class="col-md-4"><label class="form-label small">Name *</label><input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label small">Phone *</label><input type="text" name="customer_phone" value="{{ old('customer_phone', auth()->user()->phone ?? '') }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label small">Email</label><input type="email" name="customer_email" value="{{ old('customer_email', auth()->user()->email ?? '') }}" class="form-control"></div>
            </div>

            <h6 class="fw-bold mb-3">What would you like?</h6>
            <div class="row g-3">
                <div class="col-12"><label class="form-label small">Title / what you want *</label><input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="e.g. Custom elephant amigurumi" required></div>
                <div class="col-md-4"><label class="form-label small">Preferred color</label><input type="text" name="color" value="{{ old('color') }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label small">Size</label><input type="text" name="size" value="{{ old('size') }}" class="form-control" placeholder="Small / Medium / Large"></div>
                <div class="col-md-4"><label class="form-label small">Quantity *</label><input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="999" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label small">Preferred delivery date</label><input type="date" name="preferred_delivery_date" value="{{ old('preferred_delivery_date') }}" class="form-control" min="{{ date('Y-m-d') }}" max="{{ date('Y-m-d', strtotime('+7 days')) }}"></div>
                <div class="col-12"><label class="form-label small">Details / notes</label><textarea name="notes" rows="3" class="form-control" placeholder="Describe colors, theme, dimensions, references...">{{ old('notes') }}</textarea></div>
                <div class="col-12">
                    <label class="form-label small">Inspiration images (up to 6)</label>
                    <input type="file" name="images[]" accept="image/*" multiple class="form-control">
                    <small class="text-muted">Upload reference photos to help us understand your idea.</small>
                </div>
            </div>

            <div class="prepay-note p-3 small my-4"><i class="bi bi-info-circle text-brand me-1"></i>{{ prepayment_notice() }}</div>

            <button class="btn btn-brand btn-lg w-100">Submit Request</button>
        </form>
    </div>
</div>
@endsection
