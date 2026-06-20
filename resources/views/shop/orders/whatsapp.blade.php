@extends('layouts.app')
@section('title', 'Complete Your Order')

@section('content')
<div class="container" style="max-width:640px">
    <div class="card p-4 p-md-5 text-center">
        <i class="bi bi-whatsapp text-success" style="font-size:3.5rem"></i>
        <h2 class="section-title mt-3">Almost done!</h2>
        <p class="text-muted">Order <strong>{{ $order->order_number }}</strong> has been created. To confirm it, send us the pre-filled message on WhatsApp to arrange your advance payment.</p>

        <div class="prepay-note p-3 text-start small my-3">
            <div class="d-flex justify-content-between"><span>Order total</span><strong>{{ money($order->grand_total) }}</strong></div>
            <div class="d-flex justify-content-between"><span>Advance to pay now ({{ rtrim(rtrim(number_format($order->prepayment_percent,2),'0'),'.') }}%)</span><strong class="text-brand">{{ money($order->advance_amount) }}</strong></div>
            <div class="d-flex justify-content-between"><span>Balance on delivery</span><span>{{ money($order->cod_balance) }}</span></div>
        </div>

        <a href="{{ $whatsappLink }}" target="_blank" rel="noopener" class="btn btn-success btn-lg w-100" id="waBtn"><i class="bi bi-whatsapp me-1"></i>Send Order on WhatsApp</a>
        <div class="d-flex gap-2 mt-3">
            <a href="{{ route('orders.pay.form', $order->order_number) }}" class="btn btn-outline-brand flex-grow-1">Submit Payment Proof Instead</a>
            <a href="{{ route('orders.confirmation', $order->order_number) }}" class="btn btn-link">View Order</a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Gently nudge the customer to WhatsApp.
    setTimeout(() => { /* auto-open could be added here */ }, 800);
</script>
@endpush
