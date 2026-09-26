@extends('layouts.app')
@section('title', 'Your cart')
@push('meta')<meta name="robots" content="noindex">@endpush

@section('content')
<div class="container">
    @if(!$cart || $totals['item_count'] === 0)
        <div class="surface text-center p-5 my-3">
            <i class="bi bi-bag text-muted" style="font-size:3rem" aria-hidden="true"></i>
            <h1 class="h3 mt-3">Your cart is empty</h1>
            <p class="text-muted mb-4">Looks like you haven’t added anything yet. Our makers have plenty to share.</p>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ route('shop') }}" class="btn btn-brand btn-lg">Start shopping</a>
                <a href="{{ route('shop', ['collection' => 'new']) }}" class="btn btn-ghost btn-lg">See new arrivals</a>
            </div>
        </div>

        @if($suggestions->isNotEmpty())
            <section class="section pb-0" aria-labelledby="suggestTitle">
                <x-section-header title="Customer favourites" :link="route('shop', ['collection' => 'bestsellers'])" heading-id="suggestTitle" />
                <div class="row row-cols-2 row-cols-lg-4 g-3 g-md-4">
                    @foreach($suggestions as $product)<div class="col"><x-product-card :product="$product" /></div>@endforeach
                </div>
            </section>
        @endif
    @else
        <x-checkout-steps :current="1" />
        <h1 class="h2 mb-4">Your cart <span class="text-muted fs-5 fw-normal">({{ $totals['item_count'] }} {{ \Illuminate\Support\Str::plural('item', $totals['item_count']) }})</span></h1>

        <div class="row g-4">
            <div class="col-lg-8">
                <ul class="surface list-unstyled p-3 p-md-4 mb-0">
                    @foreach($cart->items as $item)
                        <li class="cart-line {{ ! $loop->last ? 'border-bottom' : '' }}">
                            <a href="{{ route('products.show', $item->product->slug) }}" class="cart-thumb">
                                <img src="{{ $item->product->thumbnail }}" alt="{{ $item->product->name }}" width="96" height="96" loading="lazy">
                            </a>
                            <div class="cart-info">
                                <a href="{{ route('products.show', $item->product->slug) }}" class="fw-semibold text-reset">{{ $item->product->name }}</a>
                                @if($item->options)<div class="small text-muted">{{ collect($item->options)->map(fn($v,$k)=>is_string($k)?"$k: $v":$v)->join(', ') }}</div>@endif
                                <div class="small text-muted">{{ money($item->unit_price) }} each</div>
                                <div class="d-flex gap-3 mt-1 small">
                                    <form action="{{ route('cart.save', $item) }}" method="POST">@csrf
                                        <button class="btn btn-link btn-sm p-0 text-muted">Save for later</button>
                                    </form>
                                    <form action="{{ route('cart.remove', $item) }}" method="POST">@csrf @method('DELETE')
                                        <button class="btn btn-link btn-sm p-0 text-danger" aria-label="Remove {{ $item->product->name }}">Remove</button>
                                    </form>
                                </div>
                            </div>
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="cart-qty">@csrf @method('PATCH')
                                <label for="qty-{{ $item->id }}" class="visually-hidden">Quantity for {{ $item->product->name }}</label>
                                <div class="qty qty-sm">
                                    <button type="button" data-qty="-1" aria-label="Decrease quantity"><i class="bi bi-dash"></i></button>
                                    <input id="qty-{{ $item->id }}" type="number" name="quantity" value="{{ $item->quantity }}" min="1" max="999" data-autosubmit inputmode="numeric">
                                    <button type="button" data-qty="1" aria-label="Increase quantity"><i class="bi bi-plus"></i></button>
                                </div>
                                <noscript><button class="btn btn-link btn-sm p-0">Update</button></noscript>
                                <span class="fw-bold d-sm-none">{{ money($item->line_total) }}</span>
                            </form>
                            <div class="cart-total fw-bold">{{ money($item->line_total) }}</div>
                        </li>
                    @endforeach
                </ul>

                <a href="{{ route('shop') }}" class="btn btn-link text-muted px-0 mt-2"><i class="bi bi-arrow-left me-1"></i>Continue shopping</a>

                @if($saved->isNotEmpty())
                    <h2 class="h5 mt-4 mb-3">Saved for later</h2>
                    <ul class="surface list-unstyled p-3 mb-0">
                        @foreach($saved as $item)
                            <li class="d-flex gap-3 align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <img src="{{ $item->product->thumbnail }}" width="56" height="56" class="rounded-2 object-fit-cover" alt="" loading="lazy">
                                <div class="flex-grow-1"><span class="small fw-semibold">{{ $item->product->name }}</span><div class="small text-muted">{{ money($item->unit_price) }}</div></div>
                                <form action="{{ route('cart.move', $item) }}" method="POST">@csrf
                                    <button class="btn btn-outline-brand btn-sm">Move to cart</button>
                                </form>
                                <form action="{{ route('cart.remove', $item) }}" method="POST">@csrf @method('DELETE')
                                    <button class="btn btn-link btn-sm text-danger p-0" aria-label="Remove {{ $item->product->name }}"><i class="bi bi-trash"></i></button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>

            {{-- Summary --}}
            <div class="col-lg-4">
                <aside class="surface p-4 summary-sticky" aria-labelledby="summaryTitle">
                    <h2 id="summaryTitle" class="h5 mb-3" style="font-family:var(--font-body);font-weight:700">Order summary</h2>

                    @if($totals['coupon_code'])
                        <div class="d-flex justify-content-between align-items-center mb-3 small">
                            <span class="badge bg-success-subtle text-success"><i class="bi bi-tag me-1"></i>{{ $totals['coupon_code'] }}</span>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">@csrf @method('DELETE')
                                <button class="btn btn-link btn-sm text-danger p-0">Remove</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="mb-3">@csrf
                            <label for="couponCode" class="form-label small">Have a coupon?</label>
                            <div class="input-group input-group-sm">
                                <input id="couponCode" type="text" name="code" class="form-control" placeholder="Enter code" autocomplete="off" required>
                                <button class="btn btn-outline-brand" style="border-radius:0 999px 999px 0">Apply</button>
                            </div>
                        </form>
                    @endif

                    <dl class="small mb-0">
                        <div class="d-flex justify-content-between mb-2"><dt class="fw-normal text-muted">Subtotal</dt><dd class="mb-0">{{ money($totals['subtotal']) }}</dd></div>
                        @if($totals['discount'] > 0)<div class="d-flex justify-content-between mb-2 text-success"><dt class="fw-normal">Discount</dt><dd class="mb-0">−{{ money($totals['discount']) }}</dd></div>@endif
                        @if($totals['tax'] > 0)<div class="d-flex justify-content-between mb-2"><dt class="fw-normal text-muted">Tax ({{ rtrim(rtrim(number_format($totals['tax_rate'],2),'0'),'.') }}%)</dt><dd class="mb-0">{{ money($totals['tax']) }}</dd></div>@endif
                        <div class="d-flex justify-content-between mb-2"><dt class="fw-normal text-muted">Shipping</dt><dd class="mb-0">{{ $totals['shipping'] > 0 ? money($totals['shipping']) : 'Free' }}</dd></div>
                    </dl>
                    <hr>
                    <div class="d-flex justify-content-between align-items-baseline fw-bold fs-5"><span>Total</span><span class="price">{{ money($totals['grand_total']) }}</span></div>

                    @if($totals['prepayment']['requires_prepayment'])
                        <div class="prepay-note p-3 mt-3 small">
                            <div class="fw-semibold mb-1"><i class="bi bi-shield-check text-brand me-1"></i>Advance payment required</div>
                            <div class="d-flex justify-content-between"><span>Pay now ({{ rtrim(rtrim(number_format($totals['prepayment']['percent'],2),'0'),'.') }}%)</span><strong>{{ money($totals['prepayment']['advance_amount']) }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Pay on delivery</span><span>{{ money($totals['prepayment']['cod_balance']) }}</span></div>
                        </div>
                    @else
                        <div class="prepay-note p-3 mt-3 small"><i class="bi bi-truck text-brand me-1"></i>Eligible for full Cash on Delivery.</div>
                    @endif

                    <a href="{{ route('checkout.index') }}" class="btn btn-brand btn-lg w-100 mt-3">Checkout <i class="bi bi-arrow-right ms-1"></i></a>
                    <ul class="list-unstyled small text-muted mt-3 mb-0">
                        <li class="mb-1"><i class="bi bi-lock me-1"></i>Secure checkout, no card details needed</li>
                        <li><i class="bi bi-arrow-repeat me-1"></i>Questions? We reply on WhatsApp</li>
                    </ul>
                </aside>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    .cart-line{display:grid;grid-template-columns:96px 1fr auto auto;gap:1rem;align-items:center;padding:1rem 0;}
    .cart-line:first-child{padding-top:0;} .cart-line:last-child{padding-bottom:0;}
    .cart-thumb img{width:96px;height:96px;object-fit:cover;border-radius:var(--radius-sm);background:var(--sage-light);}
    .cart-total{min-width:100px;text-align:right;}
    .summary-sticky{position:sticky;top:150px;}
    @media(max-width:575.98px){
        .cart-line{grid-template-columns:80px 1fr;grid-template-areas:"thumb info" "thumb qty";row-gap:.5rem;}
        .cart-thumb{grid-area:thumb;align-self:start;} .cart-thumb img{width:80px;height:80px;}
        .cart-info{grid-area:info;}
        .cart-qty{grid-area:qty;display:flex;justify-content:space-between;align-items:center;}
        .cart-total{display:none;}
    }
    @media(max-width:991.98px){.summary-sticky{position:static;}}
</style>
@endpush
