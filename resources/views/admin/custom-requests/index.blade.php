@extends('layouts.admin')
@section('title', 'Custom Requests')
@section('heading', 'Custom Requests')

@section('content')
<div class="card p-3 mb-3">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small">Status</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All</option>
                @foreach($statuses as $k=>$v)<option value="{{ $k }}" {{ ($filters['status'] ?? '')===$k ? 'selected':'' }}>{{ $v }}</option>@endforeach
            </select>
        </div>
    </form>
</div>

<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Request</th><th>Customer</th><th>Title</th><th>Quote</th><th>Status</th><th>Date</th><th class="text-end">Action</th></tr></thead>
            <tbody>
                @forelse($requests as $r)
                    <tr>
                        <td class="small fw-semibold">{{ $r->request_number }}</td>
                        <td class="small">{{ $r->customer_name }}<br><span class="text-muted">{{ $r->customer_phone }}</span></td>
                        <td class="small">{{ \Illuminate\Support\Str::limit($r->title, 30) }}</td>
                        <td class="small">{{ $r->quoted_price ? money($r->quoted_price) : '—' }}</td>
                        <td><span class="badge bg-{{ $r->status_color }}">{{ $r->status_label }}</span></td>
                        <td class="small text-muted">{{ $r->created_at->format('M d') }}</td>
                        <td class="text-end"><a href="{{ route('admin.custom.show', $r->request_number) }}" class="btn btn-sm btn-outline-brand">Manage</a></td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">No custom requests.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $requests->links() }}</div>
@endsection
