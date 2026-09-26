@extends('layouts.app')
@section('title', 'Checkout')
@push('meta')<meta name="robots" content="noindex">@endpush

@php
    $pp   = $totals['prepayment'];
    $u    = auth()->user();
    $addr = $addresses->firstWhere('is_default', true) ?? $addresses->first();
    $provinces = ['Koshi', 'Madhesh', 'Bagmati', 'Gandaki', 'Lumbini', 'Karnali', 'Sudurpashchim'];
    $pct = fn ($v) => rtrim(rtrim(number_format($v, 2), '0'), '.');
@endphp

@section('content')
<div class="container">
    <x-checkout-steps :current="2" />
    <h1 class="h2 mb-4">Checkout</h1>

    <form action="{{ route('checkout.place') }}" method="POST" novalidate class="needs-validation">@csrf
        <div class="row g-4">
            <div class="col-lg-7">
                <section class="surface p-4 mb-3" aria-labelledby="stepContact">
                    <h2 id="stepContact" class="checkout-h"><span class="step-num">1</span>Contact details</h2>
                    <div class="row g-3">
                        <div class="col-md-6"><x-field name="customer_name" label="Full name" :value="$u->name ?? ''" required autocomplete="name" /></div>
                        <div class="col-md-6"><x-field name="customer_phone" label="Mobile number" type="tel" :value="$u->phone ?? ''" required autocomplete="tel" hint="We’ll call to confirm delivery." /></div>
                        <div class="col-12"><x-field name="customer_email" label="Email" type="email" :value="$u->email ?? ''" autocomplete="email" hint="Optional, for your order receipt." /></div>
                    </div>
                </section>

                <section class="surface p-4 mb-3" aria-labelledby="stepShipping">
                    <h2 id="stepShipping" class="checkout-h"><span class="step-num">2</span>Delivery address</h2>
                    <div class="row g-3">
                        <div class="col-12"><x-field name="line1" label="Street address" :value="$addr->line1 ?? ''" required autocomplete="address-line1" placeholder="House no., street, tole" /></div>
                        <div class="col-12"><x-field name="line2" label="Landmark / apartment" :value="$addr->line2 ?? ''" autocomplete="address-line2" /></div>
                        <div class="col-md-6"><x-field name="city" label="City" :value="$addr->city ?? ''" required autocomplete="address-level2" /></div>
                        <div class="col-md-6"><x-field name="district" label="District" :value="$addr->district ?? ''" /></div>
                        <div class="col-md-6">
                            <label for="f-province" class="form-label">Province</label>
                            <select id="f-province" name="province" class="form-select @error('province') is-invalid @enderror" autocomplete="address-level1">
                                <option value="">Select province</option>
                                @php $selProvince = old('province', $addr->province ?? ''); @endphp
                                @foreach($provinces as $prov)
                                    <option value="{{ $prov }}" @selected($selProvince === $prov)>{{ $prov }}</option>
                                @endforeach
                                @if($selProvince && ! in_array($selProvince, $provinces))<option value="{{ $selProvince }}" selected>{{ $selProvince }}</option>@endif
                            </select>
                            @error('province')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6"><x-field name="postal_code" label="Postal code" :value="$addr->postal_code ?? ''" autocomplete="postal-code" inputmode="numeric" /></div>
                        <div class="col-12"><x-field name="notes" label="Order notes" type="textarea" placeholder="Gift message, preferred delivery time, colour notes…" /></div>
                    </div>
                </section>

                <section class="surface p-4" aria-labelledby="stepPayment">
                    <h2 id="stepPayment" class="checkout-h"><span class="step-num">3</span>Payment</h2>
                    @if($pp['requires_prepayment'])
                        <input type="hidden" name="payment_choice" value="prepayment">
                        <div class="pay-option selected">
                            <i class="bi bi-shield-check fs-4 text-brand" aria-hidden="true"></i>
                            <div>
                                <div class="fw-semibold">{{ $pct($pp['percent']) }}% advance + cash on delivery</div>
                                <div class="small text-muted">Orders above {{ money(prepayment_threshold(), false) }} need an advance of <strong>{{ money($pp['advance_amount']) }}</strong> via eSewa, Khalti or bank transfer. You’ll pay the remaining {{ money($pp['cod_balance']) }} on delivery. After placing the order we’ll confirm the advance with you on WhatsApp.</div>
                            </div>
                        </div>
                    @else
                        <label class="pay-option selected" for="payCod">
                            <input type="radio" name="payment_choice" value="cod" id="payCod" class="form-check-input mt-1" checked>
                            <div>
                                <div class="fw-semibold">Cash on Delivery</div>
                                <div class="small text-muted">Pay the full amount in cash when your order arrives.</div>
                            </div>
                        </label>
                    @endif
                    @error('payment_choice')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
                </section>
            </div>

            {{-- Order summary --}}
            <div class="col-lg-5">
                <aside class="surface p-4 summary-sticky" aria-labelledby="summaryTitle">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h2 id="summaryTitle" class="h5 mb-0" style="font-family:var(--font-body);font-weight:700">Order summary</h2>
                        <a href="{{ route('cart.index') }}" class="small">Edit cart</a>
                    </div>
                    <ul class="list-unstyled mb-3">
                        @foreach($cart->items as $item)
                            <li class="d-flex gap-3 align-items-center mb-3">
                                <span class="position-relative flex-shrink-0">
                                    <img src="{{ $item->product->thumbnail }}" alt="" width="60" height="60" class="rounded-3 object-fit-cover border">
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-secondary">{{ $item->quantity }}</span>
                                </span>
                                <span class="flex-grow-1 small fw-semibold">{{ $item->product->name }}
                                    @if($item->options)<span class="d-block text-muted fw-normal">{{ collect($item->options)->map(fn($v,$k)=>is_string($k)?"$k: $v":$v)->join(', ') }}</span>@endif
                                </span>
                                <span class="small">{{ money($item->line_total) }}</span>
                            </li>
                        @endforeach
                    </ul>
                    <dl class="small mb-0 border-top pt-3">
                        <div class="d-flex justify-content-between mb-2"><dt class="fw-normal text-muted">Subtotal</dt><dd class="mb-0">{{ money($totals['subtotal']) }}</dd></div>
                        @if($totals['discount'] > 0)<div class="d-flex justify-content-between mb-2 text-success"><dt class="fw-normal">Discount @if($totals['coupon_code'])({{ $totals['coupon_code'] }})@endif</dt><dd class="mb-0">−{{ money($totals['discount']) }}</dd></div>@endif
                        @if($totals['tax'] > 0)<div class="d-flex justify-content-between mb-2"><dt class="fw-normal text-muted">Tax</dt><dd class="mb-0">{{ money($totals['tax']) }}</dd></div>@endif
                        <div class="d-flex justify-content-between mb-2"><dt class="fw-normal text-muted">Shipping</dt><dd class="mb-0">{{ $totals['shipping'] > 0 ? money($totals['shipping']) : 'Free' }}</dd></div>
                    </dl>
                    <hr>
                    <div class="d-flex justify-content-between align-items-baseline fw-bold fs-5 mb-1"><span>Total</span><span class="price">{{ money($totals['grand_total']) }}</span></div>
                    @if($pp['requires_prepayment'])
                        <div class="d-flex justify-content-between small text-accent fw-semibold"><span>Due now (advance)</span><span>{{ money($pp['advance_amount']) }}</span></div>
                        <div class="d-flex justify-content-between small text-muted mb-3"><span>Due on delivery</span><span>{{ money($pp['cod_balance']) }}</span></div>
                        <button class="btn btn-brand btn-lg w-100"><i class="bi bi-whatsapp me-2"></i>Place order &amp; arrange advance</button>
                    @else
                        <div class="d-flex justify-content-between small text-muted mb-3"><span>Due on delivery</span><span>{{ money($totals['grand_total']) }}</span></div>
                        <button class="btn btn-brand btn-lg w-100">Place order</button>
                    @endif
                    <p class="small text-muted mt-3 mb-0">By placing your order you agree to our <a href="{{ route('policy.terms') }}">terms</a> and <a href="{{ route('policy.privacy') }}">privacy policy</a>.</p>
                </aside>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
    .checkout-h{font-family:var(--font-body);font-size:1.1rem;font-weight:700;display:flex;align-items:center;gap:.6rem;margin-bottom:1.25rem;}
    .step-num{width:28px;height:28px;border-radius:50%;background:var(--forest);color:#fff;font-size:.85rem;display:inline-flex;align-items:center;justify-content:center;}
    .pay-option{display:flex;gap:.9rem;align-items:flex-start;border:1px solid var(--line);border-radius:var(--radius-sm);padding:1rem;cursor:pointer;margin:0;}
    .pay-option.selected{border-color:var(--forest);background:var(--brand-bg);}
    .summary-sticky{position:sticky;top:150px;}
    @media(max-width:991.98px){.summary-sticky{position:static;}}
</style>
@endpush

@push('scripts')
<script>
// Native constraint validation with Bootstrap styling; the server validates again.
document.querySelector('form.needs-validation')?.addEventListener('submit', function (e) {
    if (!this.checkValidity()) {
        e.preventDefault();
        this.classList.add('was-validated');
        this.querySelector(':invalid')?.focus();
    }
});
</script>
@endpush
