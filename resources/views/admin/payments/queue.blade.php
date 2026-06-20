@extends('layouts.admin')
@section('title', 'Verification Queue')
@section('heading', 'Payment Verification Queue')

@section('content')
<div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.payments.index') }}" class="btn btn-sm btn-light">All payments</a></div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Order</th><th>Customer</th><th>Amount</th><th>Method</th><th>Ref</th><th>Proof</th><th>Submitted</th><th class="text-end">Action</th></tr></thead>
            <tbody>
                @forelse($payments as $p)
                    <tr>
                        <td><a href="{{ route('admin.orders.show', $p->order->order_number) }}">{{ $p->order->order_number }}</a></td>
                        <td class="small">{{ $p->order->customer_name }}</td>
                        <td class="fw-semibold">{{ money($p->amount) }}</td>
                        <td class="small">{{ str_replace('_',' ',$p->method) }}</td>
                        <td class="small">{{ $p->reference ?: '—' }}</td>
                        <td>@if($p->proof_url)<a href="{{ $p->proof_url }}" target="_blank">View</a>@else <span class="text-muted">—</span>@endif</td>
                        <td class="small text-muted">{{ $p->created_at->diffForHumans() }}</td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <form action="{{ route('admin.payments.verify', $p) }}" method="POST">@csrf<button class="btn btn-success btn-sm">Verify</button></form>
                                <form action="{{ route('admin.payments.reject', $p) }}" method="POST">@csrf<button class="btn btn-outline-danger btn-sm">Reject</button></form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">Nothing awaiting verification. 🎉</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $payments->links() }}</div>
@endsection
