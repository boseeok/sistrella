@extends('layouts.app')
@section('title', 'Your Cart')

@section('content')
<div class="container">
    <h2 class="section-title mb-4">Your Cart</h2>

    @if(!$cart || $totals['item_count'] === 0)
        <div class="card p-5 text-center">
            <i class="bi bi-bag-x text-muted" style="font-size:3rem"></i>
            <p class="mt-3 text-muted">Your cart is empty.</p>
            <div><a href="{{ route('shop') }}" class="btn btn-brand">Start Shopping</a></div>
        </div>
    @else
        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card p-3">
                    @foreach($cart->items as $item)
                        <div class="d-flex gap-3 align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <img src="{{ $item->product->thumbnail }}" width="80" height="80" class="rounded-3" style="object-fit:cover" alt="">
                            <div class="flex-grow-1">
                                <a href="{{ route('products.show', $item->product->slug) }}" class="fw-semibold text-dark text-decoration-none">{{ $item->product->name }}</a>
                                @if($item->options)<div class="small text-muted">{{ collect($item->options)->map(fn($v,$k)=>is_string($k)?"$k: $v":$v)->join(', ') }}</div>@endif
                                <div class="price small">{{ money($item->unit_price) }}</div>
                            </div>
                            <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex align-items-center">@csrf @method('PATCH')
                                <input type="number" name="quantity" value="{{ $item->quantity }}" min="0" max="999" class="form-control form-control-sm" style="width:72px" onchange="this.form.submit()">
                            </form>
                            <div class="text-end" style="width:100px">
                                <strong>{{ money($item->line_total) }}</strong>
                            </div>
                            <div class="d-flex flex-column gap-1">
                                <form action="{{ route('cart.save', $item) }}" method="POST">@csrf
                                    <button class="btn btn-link btn-sm text-muted p-0" title="Save for later"><i class="bi bi-bookmark"></i></button>
                                </form>
                                <form action="{{ route('cart.remove', $item) }}" method="POST">@csrf @method('DELETE')
                                    <button class="btn btn-link btn-sm text-danger p-0" title="Remove"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Saved for later --}}
                @if($saved->isNotEmpty())
                    <h5 class="mt-4 mb-3">Saved for later</h5>
                    <div class="card p-3">
                        @foreach($saved as $item)
                            <div class="d-flex gap-3 align-items-center py-2 {{ !$loop->last ? 'border-bottom' : '' }}">
                                <img src="{{ $item->product->thumbnail }}" width="56" height="56" class="rounded-2" style="object-fit:cover" alt="">
                                <div class="flex-grow-1"><span class="small fw-semibold">{{ $item->product->name }}</span><div class="price small">{{ money($item->unit_price) }}</div></div>
                                <form action="{{ route('cart.move', $item) }}" method="POST">@csrf
                                    <button class="btn btn-outline-brand btn-sm">Move to cart</button>
                                </form>
                                <form action="{{ route('cart.remove', $item) }}" method="POST">@csrf @method('DELETE')
                                    <button class="btn btn-link btn-sm text-danger p-0"><i class="bi bi-trash"></i></button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Summary --}}
            <div class="col-lg-4">
                <div class="card p-4">
                    <h5 class="fw-bold mb-3">Order Summary</h5>

                    {{-- Coupon --}}
                    @if($totals['coupon_code'])
                        <div class="d-flex justify-content-between align-items-center mb-2 small">
                            <span class="badge bg-success-subtle text-success">{{ $totals['coupon_code'] }}</span>
                            <form action="{{ route('cart.coupon.remove') }}" method="POST">@csrf @method('DELETE')
                                <button class="btn btn-link btn-sm text-danger p-0">Remove</button>
                            </form>
                        </div>
                    @else
                        <form action="{{ route('cart.coupon.apply') }}" method="POST" class="input-group input-group-sm mb-3">@csrf
                            <input type="text" name="code" class="form-control" placeholder="Coupon code">
                            <button class="btn btn-outline-brand">Apply</button>
                        </form>
                    @endif

                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">Subtotal</span><span>{{ money($totals['subtotal']) }}</span></div>
                    @if($totals['discount'] > 0)<div class="d-flex justify-content-between mb-1 text-success"><span>Discount</span><span>−{{ money($totals['discount']) }}</span></div>@endif
                    @if($totals['tax'] > 0)<div class="d-flex justify-content-between mb-1"><span class="text-muted">Tax ({{ rtrim(rtrim(number_format($totals['tax_rate'],2),'0'),'.') }}%)</span><span>{{ money($totals['tax']) }}</span></div>@endif
                    <div class="d-flex justify-content-between mb-1"><span class="text-muted">Shipping</span><span>{{ $totals['shipping'] > 0 ? money($totals['shipping']) : 'Free' }}</span></div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold fs-5"><span>Total</span><span class="price">{{ money($totals['grand_total']) }}</span></div>

                    {{-- Prepayment breakdown --}}
                    @if($totals['prepayment']['requires_prepayment'])
                        <div class="prepay-note p-3 mt-3 small">
                            <div class="fw-semibold mb-1"><i class="bi bi-shield-check text-brand"></i> Advance payment required</div>
                            <div class="d-flex justify-content-between"><span>Pay now ({{ rtrim(rtrim(number_format($totals['prepayment']['percent'],2),'0'),'.') }}%)</span><strong>{{ money($totals['prepayment']['advance_amount']) }}</strong></div>
                            <div class="d-flex justify-content-between"><span>Pay on delivery</span><span>{{ money($totals['prepayment']['cod_balance']) }}</span></div>
                        </div>
                    @else
                        <div class="alert alert-success small mt-3 mb-0"><i class="bi bi-truck"></i> Eligible for full Cash on Delivery.</div>
                    @endif

                    <a href="{{ route('checkout.index') }}" class="btn btn-brand w-100 mt-3">Proceed to Checkout</a>
                    <a href="{{ route('shop') }}" class="btn btn-link w-100 text-muted">Continue shopping</a>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
