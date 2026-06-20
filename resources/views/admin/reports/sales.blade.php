@extends('layouts.admin')
@section('title', 'Sales Report')
@section('heading', 'Sales Report')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label small">From</label><input type="date" name="from" value="{{ $from }}" class="form-control form-control-sm"></div>
        <div class="col-md-3"><label class="form-label small">To</label><input type="date" name="to" value="{{ $to }}" class="form-control form-control-sm"></div>
        <div class="col-md-2"><button class="btn btn-brand btn-sm w-100">Apply</button></div>
        <div class="col-md-2 ms-auto"><a href="{{ route('admin.reports.export', 'orders') }}" class="btn btn-success btn-sm w-100"><i class="bi bi-download me-1"></i>CSV</a></div>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">Orders (revenue statuses)</small><div class="fs-5 fw-bold">{{ $summary->orders ?? 0 }}</div></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">Revenue</small><div class="fs-5 fw-bold">{{ money($summary->revenue ?? 0) }}</div></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">Collected</small><div class="fs-5 fw-bold">{{ money($summary->collected ?? 0) }}</div></div></div>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Order</th><th>Customer</th><th>Total</th><th>Paid</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($orders as $o)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $o->order_number) }}">{{ $o->order_number }}</a></td>
                        <td class="small">{{ $o->customer_name }}</td>
                        <td>{{ money($o->grand_total) }}</td>
                        <td class="small text-success">{{ money($o->amount_paid) }}</td>
                        <td><span class="badge bg-{{ $o->status_color }}">{{ $o->status_label }}</span></td>
                        <td class="small text-muted">{{ $o->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No orders in this range.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $orders->links() }}</div>
@endsection
