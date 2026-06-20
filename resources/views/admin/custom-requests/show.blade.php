@extends('layouts.admin')
@section('title', $request->request_number)
@section('heading', 'Custom Request '.$request->request_number)

@section('content')
<a href="{{ route('admin.custom.index') }}" class="btn btn-sm btn-light mb-3"><i class="bi bi-chevron-left"></i> Back</a>

<div class="row g-3">
    <div class="col-lg-7">
        <div class="card p-4 mb-3">
            <div class="d-flex justify-content-between mb-2">
                <h5 class="fw-bold mb-0">{{ $request->title }}</h5>
                <span class="badge bg-{{ $request->status_color }}">{{ $request->status_label }}</span>
            </div>
            <div class="row g-2 small mb-3">
                <div class="col-6"><span class="text-muted">Customer:</span> {{ $request->customer_name }}</div>
                <div class="col-6"><span class="text-muted">Phone:</span> {{ $request->customer_phone }}</div>
                <div class="col-6"><span class="text-muted">Email:</span> {{ $request->customer_email ?: '—' }}</div>
                <div class="col-6"><span class="text-muted">Quantity:</span> {{ $request->quantity }}</div>
                <div class="col-6"><span class="text-muted">Color:</span> {{ $request->color ?: '—' }}</div>
                <div class="col-6"><span class="text-muted">Size:</span> {{ $request->size ?: '—' }}</div>
                @if($request->preferred_delivery_date)<div class="col-6"><span class="text-muted">Preferred:</span> {{ $request->preferred_delivery_date->format('M d, Y') }}</div>@endif
            </div>
            @if($request->notes)<p class="text-muted small">{{ $request->notes }}</p>@endif

            @if($request->images->isNotEmpty())
                <div class="d-flex gap-2 flex-wrap">
                    @foreach($request->images as $img)<img src="{{ asset('storage/'.$img->path) }}" width="90" height="90" class="rounded border" style="object-fit:cover">@endforeach
                </div>
            @endif

            @if($request->order)
                <div class="alert alert-success small mt-3 mb-0">Converted to order <a href="{{ route('admin.orders.show', $request->order->order_number) }}">{{ $request->order->order_number }}</a>.</div>
            @endif
        </div>
    </div>

    <div class="col-lg-5">
        {{-- Quote --}}
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Send Quotation</h6>
            <form action="{{ route('admin.custom.quote', $request->request_number) }}" method="POST">@csrf
                <div class="mb-2"><label class="form-label small">Quoted price *</label><input type="number" step="0.01" name="quoted_price" value="{{ $request->quoted_price }}" class="form-control" required></div>
                <div class="mb-2"><label class="form-label small">Note</label><textarea name="quote_note" rows="2" class="form-control">{{ $request->quote_note }}</textarea></div>
                <button class="btn btn-brand btn-sm w-100">Save Quote</button>
            </form>
        </div>

        {{-- Status --}}
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Update Status</h6>
            <form action="{{ route('admin.custom.status', $request->request_number) }}" method="POST">@csrf @method('PATCH')
                <select name="status" class="form-select form-select-sm mb-2">
                    @foreach($statuses as $k=>$v)<option value="{{ $k }}" {{ $request->status===$k ? 'selected':'' }}>{{ $v }}</option>@endforeach
                </select>
                <textarea name="admin_notes" rows="2" class="form-control form-control-sm mb-2" placeholder="Admin notes">{{ $request->admin_notes }}</textarea>
                <button class="btn btn-outline-brand btn-sm w-100">Update</button>
            </form>
        </div>

        {{-- Convert --}}
        @if($request->quoted_price && !$request->order)
            <div class="card p-3">
                <h6 class="fw-bold mb-2">Convert to Order</h6>
                <p class="small text-muted">Creates an order for {{ money($request->quoted_price) }} following the prepayment policy.</p>
                <form action="{{ route('admin.custom.convert', $request->request_number) }}" method="POST" onsubmit="return confirm('Convert this request to an order?')">@csrf
                    <button class="btn btn-success btn-sm w-100">Convert to Order</button>
                </form>
            </div>
        @endif
    </div>
</div>
@endsection
