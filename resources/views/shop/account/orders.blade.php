@extends('layouts.app')
@section('title', 'My Orders')

@section('content')
<div class="container">
    <h2 class="section-title mb-4">My Account</h2>
    <div class="row g-4">
        <div class="col-lg-3">@include('partials.account-nav')</div>
        <div class="col-lg-9">
            <div class="card p-4">
                <h5 class="fw-bold mb-3">My Orders</h5>
                @forelse($orders as $order)
                    <a href="{{ route('account.orders.show', $order->order_number) }}" class="d-flex justify-content-between align-items-center py-3 border-bottom text-decoration-none text-dark">
                        <div>
                            <div class="fw-semibold">{{ $order->order_number }}</div>
                            <small class="text-muted">{{ $order->created_at->format('M d, Y') }} · {{ $order->items_count }} item(s)</small>
                        </div>
                        <div class="text-end">
                            <div class="price">{{ money($order->grand_total) }}</div>
                            <span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span>
                        </div>
                    </a>
                @empty
                    <p class="text-muted text-center py-4 mb-0">You haven't placed any orders yet.</p>
                @endforelse
                <div class="mt-3">{{ $orders->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
