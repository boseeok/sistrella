@extends('layouts.admin')
@section('title', 'Products')
@section('heading', 'Products')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" class="row g-2 flex-grow-1 me-3">
        <div class="col-md-4"><input type="text" name="search" value="{{ $filters['search'] ?? '' }}" class="form-control form-control-sm" placeholder="Search name or SKU"></div>
        <div class="col-md-3">
            <select name="category_id" class="form-select form-select-sm">
                <option value="">All categories</option>
                @foreach($categories as $c)<option value="{{ $c->id }}" {{ ($filters['category_id'] ?? '')==$c->id ? 'selected':'' }}>{{ $c->name }}</option>@endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select form-select-sm">
                <option value="">Any status</option>
                <option value="active" {{ ($filters['status'] ?? '')==='active'?'selected':'' }}>Active</option>
                <option value="inactive" {{ ($filters['status'] ?? '')==='inactive'?'selected':'' }}>Inactive</option>
            </select>
        </div>
        <div class="col-md-2"><button class="btn btn-brand btn-sm w-100">Filter</button></div>
    </form>
    <a href="{{ route('admin.products.create') }}" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>New</a>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th></th><th>Name</th><th>SKU</th><th>Category</th><th>Price</th><th>Stock</th><th>Flags</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($products as $p)
                    <tr>
                        <td><img src="{{ $p->thumbnail }}" width="42" height="42" class="rounded" style="object-fit:cover"></td>
                        <td class="small fw-semibold">{{ \Illuminate\Support\Str::limit($p->name, 36) }}</td>
                        <td class="small text-muted">{{ $p->sku }}</td>
                        <td class="small">{{ $p->category->name ?? '—' }}</td>
                        <td class="small">{{ money($p->current_price) }}</td>
                        <td><span class="badge bg-{{ $p->stock <= 0 ? 'danger' : ($p->is_low_stock ? 'warning text-dark' : 'light text-dark') }}">{{ $p->stock }}</span></td>
                        <td class="small">
                            @if($p->is_featured)<span class="badge bg-info">F</span>@endif
                            @if($p->is_trending)<span class="badge bg-secondary">T</span>@endif
                            @if($p->is_best_seller)<span class="badge bg-success">B</span>@endif
                            @if($p->isOnFlashSale())<span class="badge bg-danger">Sale</span>@endif
                        </td>
                        <td>{!! $p->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Hidden</span>' !!}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.products.edit', $p) }}" class="btn btn-sm btn-outline-brand"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.products.destroy', $p) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?')">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center text-muted py-4">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $products->links() }}</div>
@endsection
