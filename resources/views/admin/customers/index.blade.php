@extends('layouts.admin')
@section('title', 'Customers')
@section('heading', 'Customers')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4"><input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control form-control-sm" placeholder="Name, email or phone"></div>
        <div class="col-md-2"><button class="btn btn-brand btn-sm w-100">Search</button></div>
    </form>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Customer</th><th>Contact</th><th>Orders</th><th>Lifetime value</th><th>Status</th><th>Joined</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($customers as $u)
                    <tr>
                        <td><div class="d-flex align-items-center gap-2"><img src="{{ $u->avatar_url }}" width="34" height="34" class="rounded-circle"><span class="small fw-semibold">{{ $u->name }}</span></div></td>
                        <td class="small text-muted">{{ $u->email }}<br>{{ $u->phone }}</td>
                        <td>{{ $u->orders_count }}</td>
                        <td>{{ money($u->orders_sum_grand_total ?? 0) }}</td>
                        <td>{!! $u->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' !!}</td>
                        <td class="small text-muted">{{ $u->created_at->format('M d, Y') }}</td>
                        <td class="text-end"><a href="{{ route('admin.customers.show', $u) }}" class="btn btn-sm btn-outline-brand">View</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No customers found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $customers->links() }}</div>
@endsection
