@php
    $storeName = setting('store_name', 'Sistrella');
    $pageTitle = trim($__env->yieldContent('title'));
    $fullTitle = $pageTitle ? $pageTitle.' | '.$storeName : $storeName.' — '.setting('store_tagline', 'Handmade crochet');
    $metaDescription = trim($__env->yieldContent('meta_description')) ?: setting('store_tagline', 'Handmade gifts from Nepal: crochet, satin ribbon bouquets and fuzzy wire flowers for every occasion.');
    $ogImage = trim($__env->yieldContent('og_image')) ?: asset('images/hero-crochet.jpg');
    $isCollection = fn (?string $c) => request()->routeIs('shop') && request('collection') === $c;
    $searchTerm = is_string(request('search')) ? request('search') : '';
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $fullTitle }}</title>
    <meta name="description" content="{{ \Illuminate\Support\Str::limit(strip_tags($metaDescription), 160) }}">
    <link rel="canonical" href="{{ trim($__env->yieldContent('canonical')) ?: url()->current() }}">
    @stack('meta')
    <meta property="og:site_name" content="{{ $storeName }}">
    <meta property="og:title" content="{{ $fullTitle }}">
    <meta property="og:description" content="{{ \Illuminate\Support\Str::limit(strip_tags($metaDescription), 200) }}">
    <meta property="og:type" content="@yield('og_type', 'website')">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ $ogImage }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="theme-color" content="#3D4B33">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @include('partials.theme')
    @stack('styles')
</head>
<body class="@yield('body_class')">
<a href="#main" class="skip-link">Skip to content</a>

<header class="site-header sticky-top">
    {{-- Announcement bar (content from store settings) --}}
    <div class="topbar">
        <div class="marquee">
            @php
                $announcements = array_filter([
                    '<i class="bi bi-truck me-1"></i>Cash on Delivery across Nepal',
                    '<i class="bi bi-shield-check me-1"></i>'.e(prepayment_notice()),
                    '<i class="bi bi-stars me-1"></i>Custom &amp; personalised gifts welcome',
                    setting('store_phone') ? '<i class="bi bi-telephone me-1"></i>'.e(setting('store_phone')) : null,
                ]);
            @endphp
            @for($i = 0; $i < 2; $i++)
                <div class="marquee-track" @if($i) aria-hidden="true" @endif>
                    @foreach($announcements as $item)<span class="marquee-item">{!! $item !!}</span>@endforeach
                    <a href="{{ route('orders.track.form') }}" class="marquee-item" @if($i) tabindex="-1" @endif><i class="bi bi-box-seam me-1"></i>Track your order &rarr;</a>
                </div>
            @endfor
        </div>
    </div>

    <div class="container">
        <div class="header-main">
            <div class="d-flex align-items-center gap-1">
                <button class="header-icon d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav" aria-controls="mobileNav" aria-label="Open menu">
                    <i class="bi bi-list"></i>
                </button>
                <a class="header-logo" href="{{ route('home') }}" aria-label="{{ $storeName }} home">
                    <img src="{{ asset('images/logo-header.png') }}" alt="{{ $storeName }}" width="90" height="56">
                </a>
            </div>

            <form action="{{ route('search') }}" method="GET" class="header-search d-none d-md-block" role="search">
                <label for="headerSearch" class="visually-hidden">Search products</label>
                <i class="bi bi-search" aria-hidden="true"></i>
                <input id="headerSearch" type="search" name="search" class="form-control" placeholder="Search bouquets, crochet, gifts…" value="{{ $searchTerm }}" autocomplete="off">
            </form>

            <div class="header-actions">
                <button class="header-icon d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#mobileSearch" aria-controls="mobileSearch" aria-expanded="false" aria-label="Search">
                    <i class="bi bi-search"></i>
                </button>

                @auth
                    <div class="dropdown">
                        <button class="header-icon" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications{{ ($notifCount ?? 0) ? ' ('.$notifCount.' unread)' : '' }}">
                            <i class="bi bi-bell"></i>
                            @if(($notifCount ?? 0) > 0)<span class="count">{{ $notifCount > 9 ? '9+' : $notifCount }}</span>@endif
                        </button>
                        <div class="dropdown-menu dropdown-menu-end p-0 notif-menu">
                            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                                <strong class="small">Notifications</strong>
                                @if(($notifCount ?? 0) > 0)
                                    <form action="{{ route('account.notifications.readAll') }}" method="POST">@csrf
                                        <button class="btn btn-link btn-sm p-0 small">Mark all read</button>
                                    </form>
                                @endif
                            </div>
                            @forelse(($recentNotifs ?? collect()) as $n)
                                <a href="{{ route('account.notifications.read', $n->id) }}" class="dropdown-item d-flex gap-2 py-2 {{ $n->read_at ? '' : 'bg-light' }}" style="white-space:normal">
                                    <i class="bi {{ $n->data['icon'] ?? 'bi-bell' }} text-brand mt-1"></i>
                                    <span class="small">
                                        <span class="fw-semibold d-block">{{ $n->data['title'] ?? 'Update' }}</span>
                                        <span class="text-muted">{{ \Illuminate\Support\Str::limit($n->data['message'] ?? '', 70) }}</span>
                                        <span class="text-muted d-block" style="font-size:.7rem">{{ $n->created_at->diffForHumans() }}</span>
                                    </span>
                                </a>
                            @empty
                                <div class="text-center text-muted small py-4">No notifications yet.</div>
                            @endforelse
                            <a href="{{ route('account.notifications') }}" class="dropdown-item text-center small border-top py-2 text-brand">View all</a>
                        </div>
                    </div>

                    <a class="header-icon d-none d-sm-inline-flex" href="{{ route('wishlist.index') }}" aria-label="Wishlist">
                        <i class="bi bi-heart"></i><span class="label d-none d-lg-block">Wishlist</span>
                    </a>

                    <div class="dropdown">
                        <button class="header-icon" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Account menu">
                            <i class="bi bi-person"></i><span class="label d-none d-lg-block">Account</span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Hi, {{ \Illuminate\Support\Str::before(auth()->user()->name, ' ') }}</h6></li>
                            <li><a class="dropdown-item" href="{{ route('account.dashboard') }}">My account</a></li>
                            <li><a class="dropdown-item" href="{{ route('account.orders') }}">My orders</a></li>
                            <li><a class="dropdown-item" href="{{ route('wishlist.index') }}">Wishlist</a></li>
                            <li><a class="dropdown-item" href="{{ route('account.addresses') }}">Addresses</a></li>
                            @if(auth()->user()->isAdmin() || auth()->user()->hasPermission('dashboard.access'))
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-1"></i>Admin panel</a></li>
                            @endif
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">@csrf
                                    <button class="dropdown-item" type="submit">Log out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @else
                    <a class="header-icon" href="{{ route('login') }}" aria-label="Log in">
                        <i class="bi bi-person"></i><span class="label d-none d-lg-block">Log in</span>
                    </a>
                @endauth

                <a class="header-icon" href="{{ route('cart.index') }}" aria-label="Cart, {{ $cartCount ?? 0 }} items">
                    <i class="bi bi-bag"></i><span class="label d-none d-lg-block">Cart</span>
                    <span id="cart-count" class="count" @if(($cartCount ?? 0) === 0) style="display:none" @endif>{{ $cartCount ?? 0 }}</span>
                </a>
            </div>
        </div>

        <div class="collapse d-md-none pb-3" id="mobileSearch">
            <form action="{{ route('search') }}" method="GET" class="header-search" role="search">
                <label for="mobileSearchInput" class="visually-hidden">Search products</label>
                <i class="bi bi-search" aria-hidden="true"></i>
                <input id="mobileSearchInput" type="search" name="search" class="form-control" placeholder="Search handmade gifts…" value="{{ $searchTerm }}">
            </form>
        </div>
    </div>

    {{-- Desktop navigation --}}
    <nav class="main-nav d-none d-lg-block" aria-label="Main">
        <div class="container position-relative">
            <ul class="nav justify-content-center">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('shop') && ! request('collection') ? 'active' : '' }}" href="{{ route('shop') }}">Shop All</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('custom.*') ? 'active' : '' }}" href="{{ route('custom.create') }}">Custom Orders</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('contact') ? 'active' : '' }}" href="{{ route('contact') }}">Contact</a></li>
            </ul>
        </div>
    </nav>
</header>

{{-- Mobile navigation --}}
<div class="offcanvas offcanvas-start offcanvas-nav" tabindex="-1" id="mobileNav" aria-labelledby="mobileNavLabel">
    <div class="offcanvas-header border-bottom">
        <img src="{{ asset('images/logo-header.png') }}" alt="" height="40" width="64">
        <h2 class="visually-hidden" id="mobileNavLabel">Menu</h2>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close menu"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="{{ route('home') }}" class="text-reset">Home</a></li>
            <li class="list-group-item"><a href="{{ route('shop') }}" class="text-reset">Shop All</a></li>
            <li class="list-group-item"><a href="{{ route('custom.create') }}" class="text-reset">Custom Orders</a></li>
            <li class="list-group-item"><a href="{{ route('about') }}" class="text-reset">About</a></li>
            <li class="list-group-item"><a href="{{ route('contact') }}" class="text-reset">Contact</a></li>
            <li class="list-group-item"><a href="{{ route('orders.track.form') }}" class="text-reset">Track order</a></li>
        </ul>
        <div class="d-grid gap-2 mt-4">
            @auth
                <a href="{{ route('account.dashboard') }}" class="btn btn-outline-brand">My account</a>
            @else
                <a href="{{ route('login') }}" class="btn btn-brand">Log in</a>
                <a href="{{ route('register') }}" class="btn btn-outline-brand">Create account</a>
            @endauth
        </div>
    </div>
</div>

@include('partials.flash')

<main id="main" class="@yield('main_class', 'py-4')">
    @yield('content')
</main>

{{-- Newsletter --}}
@unless(request()->routeIs('checkout.*'))
<section class="container my-5" aria-labelledby="newsletterTitle">
    <div class="newsletter p-4 p-md-5">
        <div class="row align-items-center g-4">
            <div class="col-lg-6">
                <div class="eyebrow mb-2">Stay in the loop</div>
                <h2 id="newsletterTitle" class="h3 mb-2">New pieces, restocks &amp; maker stories</h2>
                <p class="text-muted mb-0">Join our list for first look at new arrivals and members-only offers. No spam, unsubscribe anytime.</p>
            </div>
            <div class="col-lg-6">
                <form action="{{ route('newsletter.subscribe') }}" method="POST" class="d-flex flex-column flex-sm-row gap-2">@csrf
                    <label for="newsletterEmail" class="visually-hidden">Email address</label>
                    <input id="newsletterEmail" type="email" name="email" class="form-control form-control-lg" placeholder="you@example.com" autocomplete="email" required>
                    <button class="btn btn-brand btn-lg">Subscribe</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endunless

<footer class="footer pt-5 pb-4">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4">
                <img src="{{ asset('images/logo-header.png') }}" alt="{{ $storeName }}" class="bg-white rounded-3 p-2 mb-3" height="60" width="96" style="height:60px;width:auto">
                <p class="small opacity-75 mb-3">{{ setting('store_tagline') }}</p>
                <ul class="list-unstyled small">
                    @if(setting('store_address'))<li><i class="bi bi-geo-alt me-2"></i>{{ setting('store_address') }}</li>@endif
                    @if(setting('store_phone'))<li><i class="bi bi-telephone me-2"></i><a href="tel:{{ preg_replace('/[^\d+]/', '', setting('store_phone')) }}">{{ setting('store_phone') }}</a></li>@endif
                    @if(setting('store_email'))<li><i class="bi bi-envelope me-2"></i><a href="mailto:{{ setting('store_email') }}">{{ setting('store_email') }}</a></li>@endif
                </ul>
                <div class="social d-flex gap-2 mt-3">
                    @if(setting('facebook_url'))<a href="{{ setting('facebook_url') }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="bi bi-facebook"></i></a>@endif
                    @if(setting('instagram_url'))<a href="{{ setting('instagram_url') }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="bi bi-instagram"></i></a>@endif
                    @if(setting('tiktok_url'))<a href="{{ setting('tiktok_url') }}" target="_blank" rel="noopener" aria-label="TikTok"><i class="bi bi-tiktok"></i></a>@endif
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-2">
                <h2>Shop</h2>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('shop') }}">Shop all</a></li>
                    <li><a href="{{ route('shop', ['collection' => 'new']) }}">New arrivals</a></li>
                    <li><a href="{{ route('shop', ['collection' => 'bestsellers']) }}">Best sellers</a></li>
                    <li><a href="{{ route('shop', ['collection' => 'sale']) }}">Sale</a></li>
                    <li><a href="{{ route('custom.create') }}">Custom orders</a></li>
                </ul>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <h2>Categories</h2>
                <ul class="list-unstyled small">
                    @foreach(($menuCategories ?? collect()) as $cat)
                        <li><a href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                    @endforeach
                </ul>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <h2>Help</h2>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('orders.track.form') }}">Track your order</a></li>
                    <li><a href="{{ route('contact') }}">Contact us</a></li>
                    <li><a href="{{ route('about') }}">About us</a></li>
                    <li><a href="{{ route('policy.privacy') }}">Privacy policy</a></li>
                    <li><a href="{{ route('policy.terms') }}">Terms of service</a></li>
                </ul>
            </div>
        </div>
        <hr class="border-light opacity-25 mt-4">
        <div class="d-flex flex-column flex-md-row justify-content-between gap-2 small opacity-75">
            <span>&copy; {{ date('Y') }} {{ $storeName }}. Handmade with <i class="bi bi-heart-fill" aria-hidden="true"></i><span class="visually-hidden">love</span> in Nepal.</span>
            <span><i class="bi bi-cash-coin me-1"></i>Cash on Delivery · eSewa · Khalti · Bank transfer</span>
        </div>
    </div>
</footer>

<a href="{{ $whatsappFloat ?? '#' }}" target="_blank" rel="noopener" class="whatsapp-float" aria-label="Chat with us on WhatsApp">
    <i class="bi bi-whatsapp"></i>
</a>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@include('partials.storefront-script')
@stack('scripts')
</body>
</html>
