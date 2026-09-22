@extends('layouts.app')
@section('title', 'Notifications')

@section('content')
<div class="container">
    <h2 class="section-title mb-4">My Account</h2>
    <div class="row g-4">
        <div class="col-lg-3">@include('partials.account-nav')</div>
        <div class="col-lg-9">
            <div class="card p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Notifications</h5>
                    @if(auth()->user()->unreadNotifications()->count())
                        <form action="{{ route('account.notifications.readAll') }}" method="POST">@csrf
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
                                <a href="{{ route('account.notifications.read', $n->id) }}" class="btn btn-sm btn-brand">View</a>
                            @endif
                            <form action="{{ route('account.notifications.destroy', $n->id) }}" method="POST">@csrf @method('DELETE')
                                <button class="btn btn-link btn-sm text-danger p-0">Remove</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-bell-slash" style="font-size:2.5rem"></i>
                        <p class="mt-3 mb-0">You have no notifications yet.</p>
                    </div>
                @endforelse

                <div class="mt-3">{{ $notifications->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
