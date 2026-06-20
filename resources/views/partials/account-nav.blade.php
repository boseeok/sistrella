<div class="card p-3">
    <div class="text-center mb-3">
        <img src="{{ auth()->user()->avatar_url }}" class="rounded-circle mb-2" width="64" height="64" alt="">
        <div class="fw-semibold">{{ auth()->user()->name }}</div>
        <small class="text-muted">{{ auth()->user()->email }}</small>
    </div>
    <div class="list-group list-group-flush">
        <a href="{{ route('account.dashboard') }}" class="list-group-item list-group-item-action border-0 rounded {{ request()->routeIs('account.dashboard') ? 'active bg-brand border-brand' : '' }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a>
        <a href="{{ route('account.orders') }}" class="list-group-item list-group-item-action border-0 rounded {{ request()->routeIs('account.orders*') ? 'active bg-brand' : '' }}"><i class="bi bi-bag me-2"></i>My Orders</a>
        <a href="{{ route('account.notifications') }}" class="list-group-item list-group-item-action border-0 rounded {{ request()->routeIs('account.notifications') ? 'active bg-brand' : '' }}"><i class="bi bi-bell me-2"></i>Notifications @if(auth()->user()->unreadNotifications()->count())<span class="badge bg-danger rounded-pill ms-1">{{ auth()->user()->unreadNotifications()->count() }}</span>@endif</a>
        <a href="{{ route('wishlist.index') }}" class="list-group-item list-group-item-action border-0 rounded {{ request()->routeIs('wishlist.*') ? 'active bg-brand' : '' }}"><i class="bi bi-heart me-2"></i>Wishlist</a>
        <a href="{{ route('account.addresses') }}" class="list-group-item list-group-item-action border-0 rounded {{ request()->routeIs('account.addresses') ? 'active bg-brand' : '' }}"><i class="bi bi-geo-alt me-2"></i>Addresses</a>
        <a href="{{ route('account.profile') }}" class="list-group-item list-group-item-action border-0 rounded {{ request()->routeIs('account.profile') ? 'active bg-brand' : '' }}"><i class="bi bi-person me-2"></i>Profile</a>
        <form action="{{ route('logout') }}" method="POST">@csrf
            <button class="list-group-item list-group-item-action border-0 rounded text-danger w-100 text-start"><i class="bi bi-box-arrow-right me-2"></i>Logout</button>
        </form>
    </div>
</div>
<style>.list-group-item.active{color:#fff!important;}</style>
