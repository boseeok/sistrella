@extends('layouts.admin')
@section('title', 'Subscribers')
@section('heading', 'Newsletter Subscribers')

@section('content')
<a href="{{ route('admin.marketing.index') }}" class="btn btn-sm btn-light mb-3"><i class="bi bi-chevron-left"></i> Back</a>
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Email</th><th>Status</th><th>Subscribed</th></tr></thead>
            <tbody>
                @forelse($subscribers as $s)
                    <tr>
                        <td>{{ $s->email }}</td>
                        <td>{!! $s->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-secondary">Inactive</span>' !!}</td>
                        <td class="small text-muted">{{ optional($s->subscribed_at)->format('M d, Y') ?? $s->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted py-4">No subscribers yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $subscribers->links() }}</div>
@endsection
