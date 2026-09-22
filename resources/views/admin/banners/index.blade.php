@extends('layouts.admin')
@section('title', 'Banners')
@section('heading', 'Banners')

@section('content')
<div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.banners.create') }}" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>New Banner</a></div>
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Image</th><th>Title</th><th>Position</th><th>Link</th><th>Order</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($banners as $b)
                    <tr>
                        <td><img src="{{ $b->image_url }}" width="80" height="40" class="rounded" style="object-fit:cover"></td>
                        <td class="small fw-semibold">{{ $b->title ?: '—' }}<br><span class="text-muted">{{ $b->subtitle }}</span></td>
                        <td><span class="badge bg-light text-dark">{{ ucfirst($b->position) }}</span></td>
                        <td class="small text-muted">{{ $b->link ?: '—' }}</td>
                        <td>{{ $b->sort_order }}</td>
                        <td>{!! $b->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Off</span>' !!}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.banners.edit', $b) }}" class="btn btn-sm btn-outline-brand"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('admin.banners.destroy', $b) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete banner?')">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No banners yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $banners->links() }}</div>
@endsection
