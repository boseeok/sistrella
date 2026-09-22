@extends('layouts.admin')
@section('title', 'Orders')
@section('heading', 'Orders')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small">Search</label>
            <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control form-control-sm" placeholder="Order #, name, phone">
        </div>
        <div class="col-md-3">
            <label class="form-label small">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">All statuses</option>
                @foreach($statuses as $key => $label)
                    <option value="{{ $key }}" {{ ($filters['status'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><label class="form-label small">From</label><input type="date" name="from" value="{{ $filters['from'] ?? '' }}" class="form-control form-control-sm"></div>
        <div class="col-md-2"><label class="form-label small">To</label><input type="date" name="to" value="{{ $filters['to'] ?? '' }}" class="form-control form-control-sm"></div>
        <div class="col-md-2"><button class="btn btn-brand btn-sm w-100">Filter</button></div>
    </form>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Order</th><th>Customer</th><th>Items</th><th>Total</th><th>Paid</th><th>Payment</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $order->order_number) }}" class="fw-semibold">{{ $order->order_number }}</a></td>
                        <td class="small">{{ $order->customer_name }}<br><span class="text-muted">{{ $order->customer_phone }}</span></td>
                        <td>{{ $order->items_count }}</td>
                        <td>{{ money($order->grand_total) }}</td>
                        <td class="small text-success">{{ money($order->amount_paid) }}</td>
                        <td><span class="badge bg-light text-dark">{{ $order->requires_prepayment ? 'Prepay' : 'COD' }}</span></td>
                        <td><span class="badge bg-{{ $order->status_color }}">{{ $order->status_label }}</span></td>
                        <td class="small text-muted">{{ $order->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No orders found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $orders->links() }}</div>
@endsection
