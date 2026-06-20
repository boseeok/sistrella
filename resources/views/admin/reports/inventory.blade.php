@extends('layouts.admin')
@section('title', 'Inventory Report')
@section('heading', 'Inventory Report')

@section('content')
<div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.reports.export', 'inventory') }}" class="btn btn-success btn-sm"><i class="bi bi-download me-1"></i>Export CSV</a></div>

<div class="row g-3 mb-4">
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">Total units in stock</small><div class="fs-5 fw-bold">{{ number_format($stockValue->units ?? 0) }}</div></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">Stock value (at cost)</small><div class="fs-5 fw-bold">{{ money($stockValue->value ?? 0) }}</div></div></div>
    <div class="col-md-4"><div class="card p-3"><small class="text-muted">Out of stock items</small><div class="fs-5 fw-bold text-danger">{{ $outOfStock->count() }}</div></div></div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card p-3">
            <h6 class="fw-bold mb-3 text-warning">Low Stock</h6>
            <table class="table table-sm mb-0"><thead><tr><th>Product</th><th>Stock</th><th>Threshold</th></tr></thead><tbody>
                @forelse($lowStock as $p)<tr><td class="small">{{ $p->name }}</td><td><span class="badge bg-warning text-dark">{{ $p->stock }}</span></td><td>{{ $p->low_stock_threshold }}</td></tr>
                @empty<tr><td colspan="3" class="text-muted text-center py-3">All good!</td></tr>@endforelse
            </tbody></table>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card p-3">
            <h6 class="fw-bold mb-3 text-danger">Out of Stock</h6>
            <table class="table table-sm mb-0"><thead><tr><th>Product</th><th>Category</th></tr></thead><tbody>
                @forelse($outOfStock as $p)<tr><td class="small">{{ $p->name }}</td><td class="small text-muted">{{ $p->category->name ?? '—' }}</td></tr>
                @empty<tr><td colspan="2" class="text-muted text-center py-3">Nothing out of stock.</td></tr>@endforelse
            </tbody></table>
        </div>
    </div>
</div>
@endsection
