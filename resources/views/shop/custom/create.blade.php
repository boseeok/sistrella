@extends('layouts.app')
@section('title', 'Custom & Personalised Gifts')
@section('meta_description', 'Request a custom handmade gift: crochet, ribbon bouquets or fuzzy wire pieces in your colours, for any occasion.')

@section('content')
<div class="container" style="max-width:820px">
    <div class="text-center mb-4">
        <i class="bi bi-stars text-brand" style="font-size:2.5rem" aria-hidden="true"></i>
        <h1 class="section-title h2">Request a Custom Gift</h1>
        <p class="text-muted">Tell us what you'd love — a crochet friend, a ribbon bouquet in your colours or a fuzzy wire keepsake — and we'll craft it just for you. We'll review your request and send a quote.</p>
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
                <div class="col-md-6">
                    <label for="crLine" class="form-label small">Gift type</label>
                    <select id="crLine" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">Not sure yet</option>
                        @foreach($productLines as $line)
                            <option value="{{ $line->id }}" @selected((int) old('category_id', $selectedLine) === $line->id)>{{ $line->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label for="crOccasion" class="form-label small">Occasion</label>
                    <select id="crOccasion" name="collection_id" class="form-select @error('collection_id') is-invalid @enderror">
                        <option value="">Just because</option>
                        @foreach($occasions as $occ)
                            <option value="{{ $occ->id }}" @selected((int) old('collection_id', $selectedOccasion) === $occ->id)>{{ $occ->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12"><label class="form-label small">Title / what you want *</label><input type="text" name="title" value="{{ old('title') }}" class="form-control" placeholder="e.g. Pink ribbon rose bouquet with a name tag" required></div>
                <div class="col-md-4"><label class="form-label small">Preferred color</label><input type="text" name="color" value="{{ old('color') }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label small">Size</label><input type="text" name="size" value="{{ old('size') }}" class="form-control" placeholder="Small / Medium / Large"></div>
                <div class="col-md-4"><label class="form-label small">Quantity *</label><input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1" max="999" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label small">Preferred delivery date</label><input type="date" name="preferred_delivery_date" value="{{ old('preferred_delivery_date') }}" min="{{ now()->addDay()->toDateString() }}" class="form-control"></div>
                <div class="col-md-6">
                    <label for="crBudget" class="form-label small">Budget ({{ setting('currency_symbol', 'NPR') }})</label>
                    <input id="crBudget" type="number" name="budget" value="{{ old('budget') }}" min="0" step="50" class="form-control @error('budget') is-invalid @enderror" placeholder="Optional">
                </div>
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
