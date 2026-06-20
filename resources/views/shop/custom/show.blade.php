@extends('layouts.app')
@section('title', 'Custom Request '.$request->request_number)

@section('content')
<div class="container" style="max-width:760px">
    <div class="card p-4 p-md-5">
        <div class="d-flex justify-content-between align-items-start flex-wrap mb-3">
            <div>
                <h3 class="section-title mb-1">{{ $request->title }}</h3>
                <small class="text-muted">Request #{{ $request->request_number }}</small>
            </div>
            <span class="badge bg-{{ $request->status_color }} fs-6">{{ $request->status_label }}</span>
        </div>

        <div class="row g-3 small mb-3">
            <div class="col-md-4"><span class="text-muted">Color:</span> {{ $request->color ?: '—' }}</div>
            <div class="col-md-4"><span class="text-muted">Size:</span> {{ $request->size ?: '—' }}</div>
            <div class="col-md-4"><span class="text-muted">Quantity:</span> {{ $request->quantity }}</div>
            @if($request->preferred_delivery_date)
                <div class="col-md-6"><span class="text-muted">Preferred delivery:</span> {{ $request->preferred_delivery_date->format('M d, Y') }}</div>
            @endif
        </div>

        @if($request->notes)<p class="text-muted">{{ $request->notes }}</p>@endif

        @if($request->images->isNotEmpty())
            <div class="d-flex gap-2 flex-wrap mb-3">
                @foreach($request->images as $img)
                    <img src="{{ asset('storage/'.$img->path) }}" width="90" height="90" class="rounded-2 border" style="object-fit:cover" alt="">
                @endforeach
            </div>
        @endif

        @if($request->quoted_price)
            <div class="prepay-note p-3 mb-3">
                <div class="fw-semibold">Your quote: <span class="text-brand fs-5">{{ money($request->quoted_price) }}</span></div>
                @if($request->quote_note)<p class="small text-muted mb-0">{{ $request->quote_note }}</p>@endif
            </div>
        @else
            <div class="alert alert-info small"><i class="bi bi-hourglass-split me-1"></i>We're reviewing your request and will send a quote shortly.</div>
        @endif

        <a href="{{ $whatsappLink }}" target="_blank" rel="noopener" class="btn btn-success"><i class="bi bi-whatsapp me-1"></i>Discuss on WhatsApp</a>
        <a href="{{ route('home') }}" class="btn btn-link">Back to home</a>
    </div>
</div>
@endsection
