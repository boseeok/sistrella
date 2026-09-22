<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --brand:#3D4B33;--brand-dark:#2E3A27;
            --forest:#3D4B33;--terracotta:#B26B3C;--terracotta-dark:#9A5A30;
            --accent:#B26B3C;--accent-dark:#9A5A30;--ink:#2F3A2A;
            --sidebar:#2E3A27;          /* deep forest sidebar */
        }
        body{font-family:'Inter',system-ui,sans-serif;background:#F6F3ED;color:var(--ink);}
        .text-brand{color:var(--forest)!important;} .bg-brand{background:var(--forest)!important;}
        .text-accent{color:var(--terracotta)!important;} .bg-accent{background:var(--accent)!important;}
        .btn-brand{background:var(--forest);border-color:var(--forest);color:#F6F3ED;font-weight:600;}
        .btn-brand:hover{background:var(--brand-dark);border-color:var(--brand-dark);color:#fff;}
        .btn-outline-brand{color:var(--forest);border-color:var(--forest);}
        .btn-outline-brand:hover{background:var(--forest);color:#F6F3ED;}
        .btn-accent{background:var(--accent);border-color:var(--accent);color:var(--ink);font-weight:600;}
        .btn-accent:hover{background:var(--accent-dark);border-color:var(--accent-dark);color:var(--ink);}
        .admin-wrap{display:flex;min-height:100vh;}
        .sidebar{width:250px;background:var(--sidebar);color:#cfd2e0;flex-shrink:0;position:fixed;top:0;bottom:0;left:0;overflow-y:auto;transition:.2s;z-index:1040;}
        .sidebar .brand{color:#fff;font-weight:700;font-size:1.2rem;}
        .sidebar a{color:#b8bcce;text-decoration:none;display:flex;align-items:center;gap:.6rem;padding:.6rem 1.2rem;border-radius:.5rem;margin:.1rem .6rem;font-size:.92rem;}
        .sidebar a:hover{background:rgba(255,255,255,.07);color:#fff;}
        .sidebar a.active{background:var(--terracotta);color:#fff;font-weight:600;}
        .sidebar .nav-section{font-size:.7rem;text-transform:uppercase;letter-spacing:.08em;color:#6b6f85;padding:.9rem 1.4rem .3rem;}
        .content{flex:1;margin-left:250px;min-width:0;}
        .topbar{background:#fff;box-shadow:0 1px 8px rgba(0,0,0,.05);}
        .card{border:none;border-radius:.9rem;box-shadow:0 2px 12px rgba(0,0,0,.05);}
        .stat-card .icon{width:48px;height:48px;border-radius:.7rem;display:flex;align-items:center;justify-content:center;font-size:1.3rem;}
        .table>:not(caption)>*>*{padding:.65rem .75rem;}
        @media(max-width:991px){.sidebar{left:-260px;} .sidebar.show{left:0;} .content{margin-left:0;}}
    </style>
    @stack('styles')
</head>
<body>
@php $u = auth()->user(); @endphp
<div class="admin-wrap">
    <aside class="sidebar" id="adminSidebar">
        <div class="p-3 d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.dashboard') }}" class="brand d-inline-flex align-items-center gap-2">
                <img src="{{ asset('images/logo.png') }}" alt="{{ setting('store_name', 'Sistrella') }}" class="bg-white rounded-2 p-1" style="height:40px;width:auto;">
                <span>{{ setting('store_name', 'Sistrella') }}</span>
            </a>
            <button class="btn btn-sm text-light d-lg-none" onclick="document.getElementById('adminSidebar').classList.remove('show')"><i class="bi bi-x-lg"></i></button>
        </div>

        @php
            $is = fn($pattern) => request()->routeIs($pattern) ? 'active' : '';
        @endphp

        <a href="{{ route('admin.dashboard') }}" class="{{ $is('admin.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>

        <div class="nav-section">Sales</div>
        @if($u->hasPermission('orders.view'))
            <a href="{{ route('admin.orders.index') }}" class="{{ $is('admin.orders.*') }}"><i class="bi bi-receipt"></i> Orders</a>
        @endif
        @if($u->hasPermission('payments.manage'))
            <a href="{{ route('admin.payments.queue') }}" class="{{ $is('admin.payments.*') }}"><i class="bi bi-cash-coin"></i> Payments
                @php $vq = \App\Models\Payment::where('status','submitted')->count(); @endphp
                @if($vq)<span class="badge bg-warning text-dark ms-auto">{{ $vq }}</span>@endif
            </a>
        @endif
        @if($u->hasPermission('custom.manage'))
            <a href="{{ route('admin.custom.index') }}" class="{{ $is('admin.custom.*') }}"><i class="bi bi-stars"></i> Custom Requests</a>
        @endif

        <div class="nav-section">Catalogue</div>
        @if($u->hasPermission('products.manage'))
            <a href="{{ route('admin.products.index') }}" class="{{ $is('admin.products.*') }}"><i class="bi bi-box-seam"></i> Products</a>
        @endif
        @if($u->hasPermission('categories.manage'))
            <a href="{{ route('admin.categories.index') }}" class="{{ $is('admin.categories.*') }}"><i class="bi bi-diagram-3"></i> Categories</a>
        @endif
        @if($u->hasPermission('inventory.manage'))
            <a href="{{ route('admin.inventory.index') }}" class="{{ $is('admin.inventory.*') }}"><i class="bi bi-clipboard-data"></i> Inventory</a>
        @endif

        <div class="nav-section">Marketing</div>
        @if($u->hasPermission('coupons.manage'))
            <a href="{{ route('admin.coupons.index') }}" class="{{ $is('admin.coupons.*') }}"><i class="bi bi-ticket-perforated"></i> Coupons</a>
        @endif
        @if($u->hasPermission('marketing.manage'))
            <a href="{{ route('admin.banners.index') }}" class="{{ $is('admin.banners.*') }}"><i class="bi bi-images"></i> Banners</a>
            <a href="{{ route('admin.marketing.index') }}" class="{{ $is('admin.marketing.*') }}"><i class="bi bi-megaphone"></i> Newsletter & Messages</a>
        @endif

        <div class="nav-section">People</div>
        @if($u->hasPermission('customers.view'))
            <a href="{{ route('admin.customers.index') }}" class="{{ $is('admin.customers.*') }}"><i class="bi bi-people"></i> Customers</a>
        @endif
        @if($u->hasPermission('roles.manage'))
            <a href="{{ route('admin.staff.index') }}" class="{{ $is('admin.staff.*') }}"><i class="bi bi-person-badge"></i> Staff</a>
            <a href="{{ route('admin.roles.index') }}" class="{{ $is('admin.roles.*') }}"><i class="bi bi-shield-lock"></i> Roles</a>
        @endif

        <div class="nav-section">System</div>
        @if($u->hasPermission('reports.view'))
            <a href="{{ route('admin.reports.index') }}" class="{{ $is('admin.reports.*') }}"><i class="bi bi-graph-up"></i> Reports</a>
        @endif
        @if($u->hasPermission('settings.manage'))
            <a href="{{ route('admin.settings.index') }}" class="{{ $is('admin.settings.*') }}"><i class="bi bi-gear"></i> Settings</a>
        @endif
        <a href="{{ route('home') }}" target="_blank"><i class="bi bi-shop"></i> View Store</a>
    </aside>

    <div class="content">
        <nav class="topbar d-flex align-items-center justify-content-between px-3 py-2 mb-4">
            <button class="btn btn-light d-lg-none" onclick="document.getElementById('adminSidebar').classList.add('show')"><i class="bi bi-list"></i></button>
            <h5 class="mb-0 fw-semibold">@yield('heading', 'Dashboard')</h5>

            @php
                $adminUnread = $u->unreadNotifications()->count();
                $adminNotifs = $u->notifications()->latest()->limit(8)->get();
            @endphp

            <div class="d-flex align-items-center gap-2 ms-auto">
                <div class="dropdown">
                    <a href="#" class="position-relative d-flex align-items-center text-dark px-2" data-bs-toggle="dropdown" title="Notifications">
                        <i class="bi bi-bell fs-5"></i>
                        @if($adminUnread > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">{{ $adminUnread > 9 ? '9+' : $adminUnread }}</span>
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-end shadow p-0" style="min-width:340px;max-height:75vh;overflow:auto">
                        <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                            <strong class="small">Notifications</strong>
                            @if($adminUnread > 0)
                                <form action="{{ route('admin.notifications.readAll') }}" method="POST">@csrf
                                    <button class="btn btn-link btn-sm p-0 small">Mark all read</button>
                                </form>
                            @endif
                        </div>
                        @forelse($adminNotifs as $n)
                            <a href="{{ route('admin.notifications.read', $n->id) }}" class="dropdown-item d-flex gap-2 py-2 {{ $n->read_at ? '' : 'bg-light' }}" style="white-space:normal">
                                <i class="bi {{ $n->data['icon'] ?? 'bi-bell' }} text-brand mt-1"></i>
                                <span class="small">
                                    <span class="fw-semibold d-block">{{ $n->data['title'] ?? 'Update' }}</span>
                                    <span class="text-muted">{{ \Illuminate\Support\Str::limit($n->data['message'] ?? '', 70) }}</span>
                                    <span class="text-muted d-block" style="font-size:.7rem">{{ $n->created_at->diffForHumans() }}</span>
                                </span>
                            </a>
                        @empty
                            <div class="text-center text-muted small py-4">You're all caught up! 🎉</div>
                        @endforelse
                        <a href="{{ route('admin.notifications.index') }}" class="dropdown-item text-center small border-top py-2 text-brand">View all</a>
                    </div>
                </div>

            <div class="dropdown">
                <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" data-bs-toggle="dropdown">
                    <img src="{{ $u->avatar_url }}" class="rounded-circle me-2" width="34" height="34" alt="">
                    <span class="d-none d-sm-inline">{{ $u->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end shadow">
                    <li><span class="dropdown-item-text small text-muted">{{ $u->roles->pluck('display_name')->join(', ') }}</span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('account.dashboard') }}">My Account</a></li>
                    <li>
                        <form action="{{ route('admin.logout') }}" method="POST">@csrf
                            <button class="dropdown-item text-danger">Logout</button>
                        </form>
                    </li>
                </ul>
            </div>
            </div>
        </nav>

        <div class="px-3 px-md-4 pb-5">
            @include('partials.admin-flash')
            @yield('content')
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
