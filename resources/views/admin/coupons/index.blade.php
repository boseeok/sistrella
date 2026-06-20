@extends('layouts.admin')
@section('title', 'Coupons')
@section('heading', 'Coupons')

@section('content')
<div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.coupons.create') }}" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>New Coupon</a></div>
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Code</th><th>Type</th><th>Value</th><th>Min order</th><th>Used</th><th>Expires</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($coupons as $c)
                    <tr>
                        <td class="fw-semibold">{{ $c->code }}<br><small class="text-muted">{{ $c->description }}</small></td>
                        <td class="small">{{ ucfirst($c->type) }}</td>
                        <td>{{ $c->type==='percent' ? rtrim(rtrim(number_format($c->value,2),'0'),'.').'%' : money($c->value) }}</td>
                        <td class="small">{{ $c->min_order_amount ? money($c->min_order_amount) : '—' }}</td>
                        <td class="small">{{ $c->used_count }}{{ $c->usage_limit ? '/'.$c->usage_limit : '' }}</td>
                        <td class="small text-muted">{{ $c->expires_at ? $c->expires_at->format('M d, Y') : 'Never' }}</td>
                        <td>{!! $c->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Off</span>' !!}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.coupons.edit', $c) }}" class="btn btn-sm btn-outline-brand"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.coupons.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete coupon?')">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No coupons yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $coupons->links() }}</div>
@endsection
