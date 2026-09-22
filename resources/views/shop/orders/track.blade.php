@extends('layouts.app')
@section('title', 'Track Order')

@section('content')
<div class="container" style="max-width:720px">
    <h2 class="section-title mb-4">Track Your Order</h2>

    <div class="card p-4 mb-4">
        <form action="{{ route('orders.track') }}" method="POST">@csrf
            <div class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label class="form-label small">Order number</label>
                    <input type="text" name="order_number" value="{{ old('order_number') }}" class="form-control" placeholder="CRS-2026-0001" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label small">Phone number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-control" placeholder="98XXXXXXXX" required>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-brand w-100">Track</button>
                </div>
            </div>
        </form>
    </div>

    @isset($order)
        <div class="card p-4">
            <div class="d-flex justify-content-between flex-wrap mb-3">
                <div>
                    <h5 class="fw-bold mb-1">{{ $order->order_number }}</h5>
                    <small class="text-muted">Placed {{ $order->created_at->format('M d, Y') }}</small>
                </div>
                <span class="badge bg-{{ $order->status_color }} align-self-start fs-6">{{ $order->status_label }}</span>
            </div>

            {{-- Timeline --}}
            <div class="mb-3">
                @foreach($order->statusHistory as $h)
                    <div class="d-flex gap-2 mb-2">
                        <i class="bi bi-circle-fill text-brand small mt-1"></i>
                        <div>
                            <div class="fw-semibold small">{{ \App\Models\Order::STATUSES[$h->to_status] ?? ucfirst($h->to_status) }}</div>
                            <div class="text-muted" style="font-size:.78rem">{{ $h->created_at->format('M d, Y H:i') }} @if($h->note)— {{ $h->note }}@endif</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <hr>
            @foreach($order->items as $item)
                <div class="d-flex justify-content-between small py-1">
                    <span>{{ $item->name }} × {{ $item->quantity }}</span>
                    <span>{{ money($item->line_total) }}</span>
                </div>
            @endforeach
            <div class="d-flex justify-content-between fw-bold mt-2"><span>Total</span><span class="price">{{ money($order->grand_total) }}</span></div>
        </div>
    @endisset
</div>
@endsection
