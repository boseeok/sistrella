@extends('layouts.admin')
@section('title', 'Notifications')
@section('heading', 'Notifications')

@section('content')
<div class="card p-4" style="max-width:760px">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">All Notifications</h6>
        @if(auth()->user()->unreadNotifications()->count())
            <form action="{{ route('admin.notifications.readAll') }}" method="POST">@csrf
                <button class="btn btn-outline-brand btn-sm">Mark all read</button>
            </form>
        @endif
    </div>

    @forelse($notifications as $n)
        <div class="d-flex gap-3 align-items-start py-3 border-bottom {{ $n->read_at ? '' : 'bg-light rounded px-2' }}">
            <i class="bi {{ $n->data['icon'] ?? 'bi-bell' }} fs-4 text-brand"></i>
            <div class="flex-grow-1">
                <div class="fw-semibold">{{ $n->data['title'] ?? 'Update' }} @unless($n->read_at)<span class="badge bg-danger ms-1">New</span>@endunless</div>
                <div class="text-muted small">{{ $n->data['message'] ?? '' }}</div>
                <div class="text-muted" style="font-size:.75rem">{{ $n->created_at->diffForHumans() }}</div>
            </div>
            <div class="d-flex flex-column gap-1 text-end">
                @if(!empty($n->data['url']))
                    <a href="{{ route('admin.notifications.read', $n->id) }}" class="btn btn-sm btn-brand">Open</a>
                @endif
                <form action="{{ route('admin.notifications.destroy', $n->id) }}" method="POST">@csrf @method('DELETE')
                    <button class="btn btn-link btn-sm text-danger p-0">Remove</button>
                </form>
            </div>
        </div>
    @empty
        <div class="text-center text-muted py-5">
            <i class="bi bi-bell-slash" style="font-size:2.5rem"></i>
            <p class="mt-3 mb-0">No notifications yet.</p>
        </div>
    @endforelse

    <div class="mt-3">{{ $notifications->links() }}</div>
</div>
@endsection
