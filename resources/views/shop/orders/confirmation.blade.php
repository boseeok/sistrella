@extends('layouts.app')
@section('title', 'Order Confirmation')

@section('content')
<div class="container" style="max-width:760px">
    <div class="card p-4 p-md-5 text-center mb-4">
        <i class="bi bi-check-circle-fill text-success" style="font-size:3.5rem"></i>
        <h2 class="section-title mt-3">Thank you for your order!</h2>
        <p class="text-muted">Your order <strong>{{ $order->order_number }}</strong> has been placed.</p>

        @if($order->requires_prepayment && $order->amount_paid < $order->advance_amount)
            <div class="prepay-note p-3 text-start small mt-2">
                <strong><i class="bi bi-exclamation-circle text-brand"></i> Action required:</strong>
                Please pay the advance of <strong>{{ money($order->advance_amount) }}</strong> to confirm your order.
            </div>
            <div class="d-flex gap-2 justify-content-center mt-3 flex-wrap">
                <a href="{{ $whatsappLink }}" target="_blank" rel="noopener" class="btn btn-success"><i class="bi bi-whatsapp me-1"></i>Pay via WhatsApp</a>
                <a href="{{ route('orders.pay.form', $order->order_number) }}" class="btn btn-brand">Submit Payment Proof</a>
            </div>
        @endif
    </div>

    <div class="card p-4">
        <div class="d-flex justify-content-between flex-wrap mb-3">
            <div>
                <h5 class="fw-bold mb-1">Order details</h5>
                <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
            </div>
            <a href="{{ route('orders.invoice', $order->order_number) }}" class="btn btn-outline-brand btn-sm"><i class="bi bi-download me-1"></i>Invoice (PDF)</a>
        </div>

        @foreach($order->items as $item)
            <div class="d-flex justify-content-between small py-2 border-bottom">
                <span>{{ $item->name }} <span class="text-muted">× {{ $item->quantity }}</span></span>
                <span>{{ money($item->line_total) }}</span>
            </div>
        @endforeach

        <div class="d-flex justify-content-between mt-3"><span class="text-muted">Subtotal</span><span>{{ money($order->subtotal) }}</span></div>
        @if($order->discount_total > 0)<div class="d-flex justify-content-between text-success"><span>Discount</span><span>−{{ money($order->discount_total) }}</span></div>@endif
        @if($order->tax_total > 0)<div class="d-flex justify-content-between"><span class="text-muted">Tax</span><span>{{ money($order->tax_total) }}</span></div>@endif
        <div class="d-flex justify-content-between"><span class="text-muted">Shipping</span><span>{{ $order->shipping_total > 0 ? money($order->shipping_total) : 'Free' }}</span></div>
        <div class="d-flex justify-content-between fw-bold fs-5 mt-1"><span>Total</span><span class="price">{{ money($order->grand_total) }}</span></div>

        @if($order->requires_prepayment)
            <hr>
            <div class="d-flex justify-content-between small"><span>Advance ({{ rtrim(rtrim(number_format($order->prepayment_percent,2),'0'),'.') }}%)</span><strong>{{ money($order->advance_amount) }}</strong></div>
            <div class="d-flex justify-content-between small"><span>Balance on delivery</span><span>{{ money($order->cod_balance) }}</span></div>
            <div class="d-flex justify-content-between small text-success"><span>Paid so far</span><span>{{ money($order->amount_paid) }}</span></div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('shop') }}" class="btn btn-link">Continue shopping</a>
        </div>
    </div>
</div>
@endsection
