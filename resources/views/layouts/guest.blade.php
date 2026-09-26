<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="noindex">
    <title>@yield('title', 'Account') | {{ setting('store_name', 'Sistrella') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&family=Fraunces:opsz,wght@9..144,500;9..144,600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    @include('partials.theme')
</head>
<body>
<main class="min-vh-100 d-flex flex-column align-items-center justify-content-center py-5 px-3">
    <a href="{{ route('home') }}" class="mb-4" aria-label="{{ setting('store_name', 'Sistrella') }} home">
        <img src="{{ asset('images/logo-header.png') }}" alt="{{ setting('store_name', 'Sistrella') }}" height="64" width="102" style="height:64px;width:auto">
    </a>

    <div class="card" style="width:100%;max-width:440px;">
        <div class="card-body p-4 p-md-5">
            @include('partials.flash')
            @yield('content')
        </div>
    </div>

    <p class="text-muted small mt-3"><a href="{{ route('home') }}">&larr; Back to store</a></p>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
