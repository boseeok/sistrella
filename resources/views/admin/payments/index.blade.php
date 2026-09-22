@extends('layouts.admin')
@section('title', 'Payments')
@section('heading', 'All Payments')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small">Status</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All</option>
                @foreach(['submitted'=>'Submitted','verified'=>'Verified','rejected'=>'Rejected'] as $k=>$v)
                    <option value="{{ $k }}" {{ ($filters['status'] ?? '')===$k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2"><a href="{{ route('admin.payments.queue') }}" class="btn btn-outline-brand btn-sm w-100">Queue</a></div>
    </form>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Order</th><th>Amount</th><th>Kind</th><th>Method</th><th>Status</th><th>Date</th></tr></thead>
            <tbody>
                @forelse($payments as $p)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $p->order->order_number) }}">{{ $p->order->order_number }}</a></td>
                        <td class="fw-semibold">{{ money($p->amount) }}</td>
                        <td class="small">{{ ucfirst($p->kind) }}</td>
                        <td class="small">{{ str_replace('_',' ',$p->method) }}</td>
                        <td><span class="badge bg-{{ $p->status==='verified'?'success':($p->status==='rejected'?'danger':'warning text-dark') }}">{{ $p->status }}</span></td>
                        <td class="small text-muted">{{ $p->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No payments yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $payments->links() }}</div>
@endsection
