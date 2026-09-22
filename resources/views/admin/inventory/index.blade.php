@extends('layouts.admin')
@section('title', 'Inventory')
@section('heading', 'Inventory')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-4"><label class="form-label small">Search</label><input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control form-control-sm" placeholder="Name or SKU"></div>
        <div class="col-md-3">
            <label class="form-label small">Filter</label>
            <select name="filter" class="form-select form-select-sm">
                <option value="">All tracked</option>
                <option value="low" {{ ($filters['filter'] ?? '')==='low' ? 'selected':'' }}>Low stock</option>
                <option value="out" {{ ($filters['filter'] ?? '')==='out' ? 'selected':'' }}>Out of stock</option>
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-brand btn-sm w-100">Filter</button></div>
    </form>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Product</th><th>SKU</th><th>Category</th><th>Current stock</th><th>Update</th></tr></thead>
            <tbody>
                @forelse($products as $p)
                    <tr>
                        <td class="small fw-semibold">{{ \Illuminate\Support\Str::limit($p->name, 40) }}</td>
                        <td class="small text-muted">{{ $p->sku }}</td>
                        <td class="small">{{ $p->category->name ?? '—' }}</td>
                        <td><span class="badge bg-{{ $p->stock <= 0 ? 'danger' : ($p->is_low_stock ? 'warning text-dark' : 'success') }}">{{ $p->stock }}</span></td>
                        <td>
                            <form action="{{ route('admin.inventory.update', $p) }}" method="POST" class="d-flex gap-1">@csrf @method('PATCH')
                                <input type="number" name="stock" value="{{ $p->stock }}" class="form-control form-control-sm" style="width:80px">
                                <input type="number" name="low_stock_threshold" value="{{ $p->low_stock_threshold }}" class="form-control form-control-sm" style="width:80px" title="Low stock threshold">
                                <button class="btn btn-sm btn-brand">Save</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
