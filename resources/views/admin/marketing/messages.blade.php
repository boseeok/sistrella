@extends('layouts.admin')
@section('title', 'Messages')
@section('heading', 'Contact Messages')

@section('content')
<a href="{{ route('admin.marketing.index') }}" class="btn btn-sm btn-light mb-3"><i class="bi bi-chevron-left"></i> Back</a>
<div class="card p-3">
    @forelse($messages as $m)
        <div class="py-3 {{ !$loop->last ? 'border-bottom' : '' }} {{ $m->is_read ? '' : 'bg-light rounded px-2' }}">
            <div class="d-flex justify-content-between">
                <div>
                    <strong>{{ $m->name }}</strong> <span class="text-muted small">&lt;{{ $m->email }}&gt;</span>
                    @if($m->phone)<span class="text-muted small">· {{ $m->phone }}</span>@endif
                    @unless($m->is_read)<span class="badge bg-brand ms-1">New</span>@endunless
                </div>
                <small class="text-muted">{{ $m->created_at->format('M d, Y H:i') }}</small>
            </div>
            @if($m->subject)<div class="fw-semibold small mt-1">{{ $m->subject }}</div>@endif
            <p class="text-muted small mb-1">{{ $m->message }}</p>
            <form action="{{ route('admin.marketing.messages.read', $m) }}" method="POST">@csrf @method('PATCH')
                <button class="btn btn-link btn-sm p-0">{{ $m->is_read ? 'Mark unread' : 'Mark read' }}</button>
            </form>
        </div>
    @empty
        <p class="text-muted text-center py-4 mb-0">No messages yet.</p>
    @endforelse
    <div class="mt-3">{{ $messages->links() }}</div>
</div>
@endsection
