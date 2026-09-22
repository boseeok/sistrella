@extends('layouts.app')
@section('title', $policy === 'terms' ? 'Terms & Conditions' : 'Privacy Policy')

@section('content')
<div class="container" style="max-width:820px">
    <div class="card p-4 p-md-5">
        @if($policy === 'terms')
            <h2 class="section-title mb-3">Terms &amp; Conditions</h2>
            <p class="text-muted">By using {{ setting('store_name', 'Crochet Store') }}, you agree to the following terms.</p>
            <h6 class="fw-bold mt-4">Orders &amp; Payment</h6>
            <p>{{ prepayment_notice() }} Orders within the threshold are eligible for full Cash on Delivery.
            Advance payments confirm your order and are non-refundable once production begins.</p>
            <h6 class="fw-bold mt-4">Handmade Products</h6>
            <p>All items are handmade, so slight variations in color and size are natural and not considered defects.</p>
            <h6 class="fw-bold mt-4">Custom Orders</h6>
            <p>Custom orders are quoted individually and follow the same prepayment policy. Production begins after the advance is received.</p>
            <h6 class="fw-bold mt-4">Cancellations</h6>
            <p>Ready-made orders may be cancelled before dispatch. Custom orders cannot be cancelled once production has started.</p>
        @else
            <h2 class="section-title mb-3">Privacy Policy</h2>
            <p class="text-muted">Your privacy matters to us at {{ setting('store_name', 'Crochet Store') }}.</p>
            <h6 class="fw-bold mt-4">Information We Collect</h6>
            <p>We collect the details you provide when placing an order or contacting us — name, phone, email and delivery address — solely to fulfil your orders.</p>
            <h6 class="fw-bold mt-4">How We Use It</h6>
            <p>Your information is used to process orders, arrange delivery, and (with consent) send occasional updates. We never sell your data.</p>
            <h6 class="fw-bold mt-4">Payment Proof</h6>
            <p>Payment screenshots you upload are stored securely and used only to verify your payment.</p>
            <h6 class="fw-bold mt-4">Contact</h6>
            <p>For any privacy questions, reach us at {{ setting('store_email') }}.</p>
        @endif
    </div>
</div>
@endsection
