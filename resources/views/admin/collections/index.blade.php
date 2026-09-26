@extends('layouts.admin')
@section('title', 'Occasions & Collections')
@section('heading', 'Occasions & Collections')

@section('content')
<div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
    <div class="btn-group btn-group-sm">
        <a href="{{ route('admin.collections.index') }}" class="btn {{ ! $type ? 'btn-brand' : 'btn-light' }}">All</a>
        @foreach(\App\Models\ProductCollection::TYPES as $key => $label)
            <a href="{{ route('admin.collections.index', ['type' => $key]) }}" class="btn {{ $type === $key ? 'btn-brand' : 'btn-light' }}">{{ $label }}s</a>
        @endforeach
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.collections.create', ['type' => 'occasion']) }}" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>New Occasion</a>
        <a href="{{ route('admin.collections.create', ['type' => 'curated']) }}" class="btn btn-outline-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>New Collection</a>
    </div>
</div>
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Name</th><th>Type</th><th>Products</th><th>Home page</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($collections as $c)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                @if($c->image_url)<img src="{{ $c->image_url }}" alt="" width="40" height="40" class="rounded" style="object-fit:cover">@else<i class="bi bi-{{ $c->icon ?: 'gift' }} fs-5 text-brand"></i>@endif
                                <div><div class="fw-semibold">{{ $c->name }}</div><div class="small text-muted">/collections/{{ $c->slug }}</div></div>
                            </div>
                        </td>
                        <td><span class="badge bg-light text-dark">{{ \App\Models\ProductCollection::TYPES[$c->type] ?? $c->type }}</span></td>
                        <td>{{ $c->products_count }}</td>
                        <td>{!! $c->is_featured ? '<i class="bi bi-star-fill text-warning"></i>' : '—' !!}</td>
                        <td>{!! $c->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Hidden</span>' !!}</td>
                        <td class="text-end text-nowrap">
                            <a href="{{ route('collections.show', $c->slug) }}" target="_blank" class="btn btn-sm btn-light" title="View on store"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('admin.collections.edit', $c) }}" class="btn btn-sm btn-outline-brand" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.collections.destroy', $c) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this collection? Products are not deleted.')">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No occasions or collections yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $collections->links() }}</div>
@endsection
