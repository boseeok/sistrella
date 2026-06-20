<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Login — {{ setting('store_name', 'Crochet Store') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root{--brand:#3D4B33;--brand-dark:#2E3A27;--terracotta:#B26B3C;--terracotta-dark:#9A5A30;}
        body{font-family:'Inter',sans-serif;background:linear-gradient(135deg,#DCE3CE,#8C9A6E 45%,#2E3A27);min-height:100vh;}
        .btn-brand{background:var(--brand);border-color:var(--brand);color:#F6F3ED;font-weight:600;}
        .btn-brand:hover{background:var(--brand-dark);border-color:var(--brand-dark);color:#fff;}
        .card{border:none;border-radius:1rem;border-top:5px solid var(--terracotta);}
    </style>
</head>
<body>
<div class="min-vh-100 d-flex align-items-center justify-content-center py-5">
    <div class="card shadow-lg" style="width:100%;max-width:410px;">
        <div class="card-body p-4 p-md-5">
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="{{ setting('store_name', 'Sistrella') }}" style="height:84px;width:auto;">
                <h4 class="fw-bold mt-3 mb-0">Admin Panel</h4>
                <p class="text-muted small">{{ setting('store_name', 'Crochet Store') }}</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger small">{{ $errors->first() }}</div>
            @endif

            <form action="{{ route('admin.login.submit') }}" method="POST">@csrf
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control" required autofocus>
                </div>
                <div class="mb-3">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>
                <div class="form-check mb-3">
                    <input type="checkbox" name="remember" id="remember" class="form-check-input">
                    <label for="remember" class="form-check-label small">Remember me</label>
                </div>
                <button class="btn btn-brand w-100">Sign in</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
