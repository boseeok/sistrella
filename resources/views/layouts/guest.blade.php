<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Account') — {{ setting('store_name', 'Crochet Store') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Quicksand:wght@600;700&display=swap" rel="stylesheet">
    @include('partials.theme')
</head>
<body>
<div class="min-vh-100 d-flex flex-column align-items-center justify-content-center py-5">
    <a href="{{ route('home') }}" class="brand fs-2 fw-bold mb-3 text-decoration-none">
        <i class="bi bi-flower2"></i> {{ setting('store_name', 'Crochet Store') }}
    </a>

    <div class="card shadow-sm" style="width:100%;max-width:440px;">
        <div class="card-body p-4 p-md-5">
            @include('partials.flash')
            @yield('content')
        </div>
    </div>

    <p class="text-muted small mt-3"><a href="{{ route('home') }}">&larr; Back to store</a></p>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
