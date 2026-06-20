@extends('layouts.app')
@section('title', 'My Account')

@section('content')
<div class="container">
    <h2 class="section-title mb-4">My Account</h2>
    <div class="row g-4">
        <div class="col-lg-3">@include('partials.account-nav')</div>
        <div class="col-lg-9">
            @unless(auth()->user()->hasVerifiedEmail())
                <div class="alert alert-warning d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-exclamation-triangle me-1"></i>Please verify your email address.</span>
                    <a href="{{ route('verification.notice') }}" class="btn btn-sm btn-warning">Verify now</a>
                </div>
            @endunless

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="card p-3 text-center"><div class="fs-3 fw-bold text-brand">{{ $ordersCount }}</div><small class="text-muted">Total Orders</small></div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 text-center"><div class="fs-3 fw-bold text-warning">{{ $pendingCount }}</div><small class="text-muted">Pending Payment</small></div>
                </div>
                <div class="col-md-4">
                    <div class="card p-3 text-center"><div class="fs-3 fw-bold text-danger">{{ $wishlistCount }}</div><small class="text-muted">Wishlist Items</small></div>
                </div>
            </div>

            <div class="card p-4">
                <div class="d-flex justify-content-between mb-3">
                    <h5 class="fw-bold mb-0">Recent Orders</h5>
                    <a href="{{ route('account.orders') }}" class="small">View all</a>
                </div>
                @forelse($recentOrders as $order)
                    <a href="{{ route('account.orders.show', $order->order_number) }}" class="d-flex justify-content-between align-items-center py-2 border-bottom text-decoration-none text-dark">
                        <div>
                            <div class="fw-semibold small">{{ $order->order_number }}</div>
                            <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                        </div>
                        <div class="text-end">
                            <div class="price small">{{ money($order->grand_total) }}</div>
                            <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-muted text-center py-3 mb-0">No orders yet. <a href="{{ route('shop') }}">Start shopping</a></p>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
