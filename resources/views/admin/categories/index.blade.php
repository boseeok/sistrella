@extends('layouts.admin')
@section('title', 'Categories')
@section('heading', 'Categories')

@section('content')
<div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.categories.create') }}" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>New Category</a></div>
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Name</th><th>Parent</th><th>Products</th><th>Featured</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($categories as $c)
                    <tr>
                        <td><i class="bi {{ $c->icon ?: 'bi-tag' }} me-1 text-brand"></i>{{ $c->name }}</td>
                        <td class="small text-muted">{{ $c->parent->name ?? '—' }}</td>
                        <td>{{ $c->products_count }}</td>
                        <td>{!! $c->is_featured ? '<i class="bi bi-star-fill text-warning"></i>' : '—' !!}</td>
                        <td>{!! $c->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Hidden</span>' !!}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.categories.edit', $c) }}" class="btn btn-sm btn-outline-brand"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.categories.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this category?')">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No categories yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $categories->links() }}</div>
@endsection
