@extends('layouts.admin')
@section('title', $customer->name)
@section('heading', 'Customer')

@section('content')
<a href="{{ route('admin.customers.index') }}" class="btn btn-sm btn-light mb-3"><i class="bi bi-chevron-left"></i> Back</a>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="card p-4 text-center mb-3">
            <img src="{{ $customer->avatar_url }}" width="80" height="80" class="rounded-circle mx-auto mb-2">
            <h5 class="fw-bold mb-0">{{ $customer->name }}</h5>
            <p class="text-muted small mb-2">{{ $customer->email }}</p>
            <p class="small mb-1">{{ $customer->phone }}</p>
            <span class="badge bg-{{ $customer->is_active ? 'success':'danger' }}">{{ $customer->is_active ? 'Active' : 'Inactive' }}</span>
            <form action="{{ route('admin.customers.toggle', $customer) }}" method="POST" class="mt-3">@csrf @method('PATCH')
                <button class="btn btn-sm btn-outline-{{ $customer->is_active ? 'danger':'success' }} w-100">{{ $customer->is_active ? 'Deactivate' : 'Activate' }}</button>
            </form>
        </div>

        @if($customer->addresses->isNotEmpty())
            <div class="card p-3">
                <h6 class="fw-bold mb-2">Addresses</h6>
                @foreach($customer->addresses as $a)
                    <p class="small text-muted mb-2">{{ $a->label }} — {{ $a->formatted }}</p>
                @endforeach
            </div>
        @endif
    </div>

    <div class="col-lg-8">
        <div class="card p-3">
            <h6 class="fw-bold mb-3">Orders</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light"><tr><th>Order</th><th>Total</th><th>Status</th><th>Date</th></tr></thead>
                    <tbody>
                        @forelse($orders as $o)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $o->order_number) }}">{{ $o->order_number }}</a></td>
                                <td>{{ money($o->grand_total) }}</td>
                                <td><span class="badge bg-{{ $o->status_color }}">{{ $o->status_label }}</span></td>
                                <td class="small text-muted">{{ $o->created_at->format('M d, Y') }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="text-center text-muted py-3">No orders.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-2">{{ $orders->links() }}</div>
        </div>
    </div>
</div>
@endsection
