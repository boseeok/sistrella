@extends('layouts.app')
@section('title', 'Checkout')

@php $pp = $totals['prepayment']; @endphp

@section('content')
<div class="container">
    <h2 class="section-title mb-4">Checkout</h2>

    <form action="{{ route('checkout.place') }}" method="POST">@csrf
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="card p-4 mb-3">
                    <h5 class="fw-bold mb-3">Contact details</h5>
                    @php $u = auth()->user(); $addr = $addresses->firstWhere('is_default', true) ?? $addresses->first(); @endphp
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label small">Full name *</label>
                            <input type="text" name="customer_name" value="{{ old('customer_name', $u->name ?? '') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Phone *</label>
                            <input type="text" name="customer_phone" value="{{ old('customer_phone', $u->phone ?? '') }}" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Email</label>
                            <input type="email" name="customer_email" value="{{ old('customer_email', $u->email ?? '') }}" class="form-control">
                        </div>
                    </div>
                </div>

                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Shipping address</h5>
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label small">Address line 1 *</label>
                            <input type="text" name="line1" value="{{ old('line1', $addr->line1 ?? '') }}" class="form-control" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Address line 2</label>
                            <input type="text" name="line2" value="{{ old('line2', $addr->line2 ?? '') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">City *</label>
                            <input type="text" name="city" value="{{ old('city', $addr->city ?? '') }}" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">District</label>
                            <input type="text" name="district" value="{{ old('district', $addr->district ?? '') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Province</label>
                            <input type="text" name="province" value="{{ old('province', $addr->province ?? '') }}" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small">Postal code</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code', $addr->postal_code ?? '') }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label small">Order notes</label>
                            <textarea name="notes" rows="2" class="form-control" placeholder="Any special instructions?">{{ old('notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Summary --}}
            <div class="col-lg-5">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Your order</h5>
                    @foreach($cart->items as $item)
                        <div class="d-flex justify-content-between small mb-2">
                            <span>{{ $item->product->name }} <span class="text-muted">× {{ $item->quantity }}</span></span>
                            <span>{{ money($item->line_total) }}</span>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">Subtotal</span><span>{{ money($totals['subtotal']) }}</span></div>
                    @if($totals['discount'] > 0)<div class="d-flex justify-content-between mb-1 text-success"><span>Discount</span><span>−{{ money($totals['discount']) }}</span></div>@endif
                    @if($totals['tax'] > 0)<div class="d-flex justify-content-between mb-1"><span class="text-muted">Tax</span><span>{{ money($totals['tax']) }}</span></div>@endif
                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">Shipping</span><span>{{ $totals['shipping'] > 0 ? money($totals['shipping']) : 'Free' }}</span></div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5 mb-3"><span>Total</span><span class="price">{{ money($totals['grand_total']) }}</span></div>

                    @if($pp['requires_prepayment'])
                        <div class="prepay-note p-3 small mb-3">
                            <div class="fw-semibold mb-1"><i class="bi bi-shield-check text-brand"></i> Advance payment required</div>
                            <div class="d-flex justify-content-between"><span>Pay now ({{ rtrim(rtrim(number_format($pp['percent'],2),'0'),'.') }}%)</span><strong>{{ money($pp['advance_amount']) }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Pay on delivery</span><span>{{ money($pp['cod_balance']) }}</span></div>
                            <p class="mb-0 mt-2 text-muted">After placing the order you'll confirm the advance via WhatsApp.</p>
                        </div>
                        <input type="hidden" name="payment_choice" value="prepayment">
                        <button class="btn btn-brand w-100"><i class="bi bi-whatsapp me-1"></i>Place Order & Pay Advance</button>
                    @else
                        <div class="mb-3">
                            <label class="form-label small fw-semibold">Payment method</label>
                            <div class="form-check">
                                <input type="radio" name="payment_choice" value="cod" id="cod" class="form-check-input" checked>
                                <label for="cod" class="form-check-label">Cash on Delivery</label>
                            </div>
                        </div>
                        <button class="btn btn-brand w-100">Place Order</button>
                    @endif
                    <a href="{{ route('cart.index') }}" class="btn btn-link w-100 text-muted">&larr; Back to cart</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
