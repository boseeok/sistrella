@extends('layouts.app')
@section('title', 'About Us')

@section('content')
<div class="container" style="max-width:880px">
    <div class="hero p-4 p-md-5 mb-4 text-center">
        <i class="bi bi-flower2 text-brand" style="font-size:3rem"></i>
        <h1 class="section-title">About {{ setting('store_name', 'Crochet Store') }}</h1>
        <p class="lead text-muted mb-0">{{ setting('store_tagline', 'Handmade with love, one stitch at a time') }}</p>
    </div>

    <div class="card p-4 p-md-5">
        <p>Welcome to {{ setting('store_name', 'Crochet Store') }} — a small, passion-driven studio creating
        handmade crochet pieces from the heart of Nepal. Every item in our shop is crafted by hand using
        premium, skin-friendly yarn, making each piece unique and full of character.</p>

        <p>From cuddly amigurumi and cozy wearables to charming home decor and everlasting crochet bouquets,
        we pour love and patience into every stitch. We also welcome custom orders — if you can dream it,
        we'll do our best to crochet it.</p>

        <h5 class="fw-bold mt-4">Why shop with us?</h5>
        <div class="row g-3 mt-1">
            <div class="col-md-4"><div class="card p-3 h-100 text-center"><i class="bi bi-hand-thumbs-up text-brand fs-3"></i><div class="fw-semibold mt-2">100% Handmade</div><small class="text-muted">Crafted with care, never mass-produced.</small></div></div>
            <div class="col-md-4"><div class="card p-3 h-100 text-center"><i class="bi bi-truck text-brand fs-3"></i><div class="fw-semibold mt-2">Cash on Delivery</div><small class="text-muted">Convenient COD on eligible orders.</small></div></div>
            <div class="col-md-4"><div class="card p-3 h-100 text-center"><i class="bi bi-whatsapp text-brand fs-3"></i><div class="fw-semibold mt-2">Friendly Support</div><small class="text-muted">Chat with us anytime on WhatsApp.</small></div></div>
        </div>

        <div class="text-center mt-4">
            <a href="{{ route('shop') }}" class="btn btn-brand">Explore Our Products</a>
            <a href="{{ route('contact') }}" class="btn btn-outline-brand">Contact Us</a>
        </div>
    </div>
</div>
@endsection
