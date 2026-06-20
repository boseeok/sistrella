@extends('layouts.app')
@section('title', 'Order '.$order->order_number)

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <a href="{{ route('account.orders') }}" class="small text-muted"><i class="bi bi-chevron-left"></i> Back to orders</a>
            <h2 class="section-title mb-0">{{ $order->order_number }}</h2>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('orders.invoice', $order->order_number) }}" class="btn btn-outline-brand btn-sm"><i class="bi bi-download me-1"></i>Invoice</a>
            @if($order->requires_prepayment && $order->remaining_balance > 0)
                <a href="{{ route('orders.pay.form', $order->order_number) }}" class="btn btn-brand btn-sm">Pay Now</a>
            @endif
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card p-4 mb-4">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-bold mb-0">Items</h5>
                    <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                </div>
                @foreach($order->items as $item)
                    <div class="d-flex justify-content-between py-2 border-bottom">
                        <div>
                            <div class="fw-semibold small">{{ $item->name }}</div>
                            @if($item->options)<small class="text-muted">{{ collect($item->options)->map(fn($v,$k)=>is_string($k)?"$k: $v":$v)->join(', ') }}</small>@endif
                            <div class="small text-muted">{{ money($item->unit_price) }} × {{ $item->quantity }}</div>
                        </div>
                        <strong>{{ money($item->line_total) }}</strong>
                    </div>
                @endforeach
            </div>

            <div class="card p-4">
                <h5 class="fw-bold mb-3">Order timeline</h5>
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
        </div>

        <div class="col-lg-4">
            <div class="card p-4 mb-3">
                <h6 class="fw-bold mb-3">Summary</h6>
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Subtotal</span><span>{{ money($order->subtotal) }}</span></div>
                @if($order->discount_total > 0)<div class="d-flex justify-content-between mb-1 text-success"><span>Discount</span><span>−{{ money($order->discount_total) }}</span></div>@endif
                @if($order->tax_total > 0)<div class="d-flex justify-content-between mb-1"><span class="text-muted">Tax</span><span>{{ money($order->tax_total) }}</span></div>@endif
                <div class="d-flex justify-content-between mb-1"><span class="text-muted">Shipping</span><span>{{ $order->shipping_total > 0 ? money($order->shipping_total) : 'Free' }}</span></div>
                <hr>
                <div class="d-flex justify-content-between fw-bold"><span>Total</span><span class="price">{{ money($order->grand_total) }}</span></div>
                @if($order->requires_prepayment)
                    <hr>
                    <div class="d-flex justify-content-between small"><span>Advance</span><span>{{ money($order->advance_amount) }}</span></div>
                    <div class="d-flex justify-content-between small"><span>Paid</span><span class="text-success">{{ money($order->amount_paid) }}</span></div>
                    <div class="d-flex justify-content-between small"><span>Balance</span><span>{{ money($order->remaining_balance) }}</span></div>
                @endif
            </div>

            @if($order->shipping_address)
                <div class="card p-4 mb-3">
                    <h6 class="fw-bold mb-2">Shipping to</h6>
                    @php $a = $order->shipping_address; @endphp
                    <p class="small text-muted mb-0">
                        {{ $a['full_name'] ?? '' }}<br>
                        {{ $a['phone'] ?? '' }}<br>
                        {{ collect([$a['line1']??null,$a['line2']??null,$a['city']??null,$a['district']??null,$a['province']??null])->filter()->join(', ') }}
                    </p>
                </div>
            @endif

            @if($order->payments->isNotEmpty())
                <div class="card p-4">
                    <h6 class="fw-bold mb-2">Payments</h6>
                    @foreach($order->payments as $p)
                        <div class="d-flex justify-content-between small py-1">
                            <span>{{ ucfirst($p->kind) }} ({{ str_replace('_',' ',$p->method) }})</span>
                            <span>{{ money($p->amount) }} <span class="badge bg-{{ $p->status==='verified'?'success':($p->status==='rejected'?'danger':'warning') }}">{{ $p->status }}</span></span>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
