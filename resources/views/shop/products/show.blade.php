@extends('layouts.app')
@section('title', $product->meta_title ?: $product->name)
@section('meta_description', $product->meta_description ?: $product->short_description)

@php
    $waNumber = preg_replace('/\D+/', '', setting('whatsapp_number', '977-9761612457'));
    $waMsg = "Hello, I want this product:\n{$product->name}\n".route('products.show', $product->slug);
    $waLink = 'https://wa.me/'.$waNumber.'?text='.rawurlencode($waMsg);
    $gallery = $product->images->count() ? $product->images : collect();
@endphp

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('shop') }}">Shop</a></li>
            @if($product->category)<li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a></li>@endif
            <li class="breadcrumb-item active">{{ \Illuminate\Support\Str::limit($product->name, 30) }}</li>
        </ol>
    </nav>

    <div class="row g-4 mb-5">
        {{-- Gallery --}}
        <div class="col-md-6">
            <div class="card p-2">
                <div class="zoom-wrap">
                    <img id="mainImage" src="{{ $product->thumbnail }}" alt="{{ $product->name }}" class="zoom-img img-fluid rounded-3 w-100" style="aspect-ratio:1/1;object-fit:cover;background:#DCE3CE">
                    <div class="zoom-controls">
                        <button type="button" id="zoomIn" title="Zoom in"><i class="bi bi-plus-lg"></i></button>
                        <button type="button" id="zoomOut" title="Zoom out"><i class="bi bi-dash-lg"></i></button>
                        <button type="button" id="zoomReset" title="Reset"><i class="bi bi-arrow-counterclockwise"></i></button>
                    </div>
                </div>
            </div>
            @if($gallery->count() > 1)
                <div class="d-flex gap-2 mt-2 overflow-auto">
                    @foreach($gallery as $img)
                        <img src="{{ asset('storage/'.$img->path) }}" class="thumb-img rounded-2 border" style="width:72px;height:72px;object-fit:cover;cursor:pointer" onclick="document.getElementById('mainImage').src=this.src">
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Info --}}
        <div class="col-md-6">
            @if($product->category)<small class="text-muted text-uppercase">{{ $product->category->name }}</small>@endif
            <h1 class="section-title h2">{{ $product->name }}</h1>

            @if($product->rating_count > 0)
                <div class="rating mb-2">
                    @for($i=1;$i<=5;$i++)<i class="bi {{ $i <= round($product->rating_avg) ? 'bi-star-fill' : 'bi-star' }}"></i>@endfor
                    <span class="text-muted small">{{ number_format($product->rating_avg,1) }} ({{ $product->rating_count }} reviews)</span>
                </div>
            @endif

            <div class="my-3">
                <span class="price fs-3">{{ money($product->current_price) }}</span>
                @if($product->old_price)<span class="old-price ms-2 fs-5">{{ money($product->old_price) }}</span>
                    <span class="badge badge-sale ms-2">-{{ $product->discount_percent }}%</span>@endif
            </div>

            @if($product->short_description)<p class="text-muted">{{ $product->short_description }}</p>@endif

            <div class="mb-3">
                @if($product->in_stock)
                    <span class="badge bg-success-subtle text-success"><i class="bi bi-check-circle"></i> In Stock</span>
                    @if($product->is_low_stock)<span class="badge bg-warning-subtle text-warning ms-1">Only {{ $product->stock }} left!</span>@endif
                @else
                    <span class="badge bg-danger-subtle text-danger"><i class="bi bi-x-circle"></i> Out of Stock</span>
                @endif
            </div>

            {{-- Prepayment notice --}}
            <div class="prepay-note p-3 mb-3 small">
                <i class="bi bi-info-circle text-brand me-1"></i>{{ prepayment_notice() }}
            </div>

            @if($product->in_stock)
                <form action="{{ route('cart.add') }}" method="POST" class="js-add-to-cart">@csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">

                    @if($product->variants->count())
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Options</label>
                            <select name="variant_id" class="form-select">
                                @foreach($product->variants as $variant)
                                    <option value="{{ $variant->id }}">{{ $variant->label ?: $variant->sku }} — {{ money($variant->effective_price) }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif

                    <div class="d-flex gap-2 align-items-end mb-3">
                        <div style="width:110px">
                            <label class="form-label fw-semibold small">Quantity</label>
                            <input type="number" name="quantity" value="1" min="1" max="999" class="form-control">
                        </div>
                        <button class="btn btn-brand btn-lg flex-grow-1"><i class="bi bi-bag-plus me-1"></i>Add to Cart</button>
                    </div>
                </form>
            @endif

            <div class="d-flex gap-2 flex-wrap">
                <a href="{{ $waLink }}" target="_blank" rel="noopener" class="btn btn-success"><i class="bi bi-whatsapp me-1"></i>Ask on WhatsApp</a>
                @auth
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST">@csrf
                        <button class="btn btn-outline-brand"><i class="bi bi-heart me-1"></i>Wishlist</button>
                    </form>
                @endauth
                @if($product->is_customizable)
                    <a href="{{ route('custom.create') }}" class="btn btn-outline-secondary"><i class="bi bi-stars me-1"></i>Customize</a>
                @endif
            </div>

            <ul class="list-unstyled small text-muted mt-3 mb-0">
                <li><i class="bi bi-upc me-1"></i>SKU: {{ $product->sku }}</li>
                <li><i class="bi bi-truck me-1"></i>Cash on Delivery available</li>
                <li><i class="bi bi-hand-thumbs-up me-1"></i>100% handmade with premium yarn</li>
            </ul>
        </div>
    </div>

    {{-- Description & reviews tabs --}}
    <div class="card p-4 mb-5">
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#desc">Description</a></li>
            <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#reviews">Reviews ({{ $product->approvedReviews->count() }})</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="desc">
                {!! $product->description ?: '<p class="text-muted">No description available.</p>' !!}
            </div>
            <div class="tab-pane fade" id="reviews">
                @forelse($product->approvedReviews as $review)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between">
                            <strong>{{ $review->user->name ?? 'Customer' }}</strong>
                            <span class="rating small">@for($i=1;$i<=5;$i++)<i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}"></i>@endfor</span>
                        </div>
                        @if($review->is_verified_purchase)<span class="badge bg-success-subtle text-success small">Verified Purchase</span>@endif
                        @if($review->title)<div class="fw-semibold mt-1">{{ $review->title }}</div>@endif
                        <p class="text-muted small mb-0">{{ $review->body }}</p>
                    </div>
                @empty
                    <p class="text-muted">No reviews yet. Be the first to review!</p>
                @endforelse

                @auth
                    @if($canReview)
                        <h6 class="fw-bold mt-4">Write a review</h6>
                        <form action="{{ route('products.review', $product->slug) }}" method="POST">@csrf
                            <div class="mb-2">
                                <label class="form-label small">Rating</label>
                                <select name="rating" class="form-select form-select-sm" style="width:auto">
                                    @for($i=5;$i>=1;$i--)<option value="{{ $i }}">{{ $i }} star{{ $i>1?'s':'' }}</option>@endfor
                                </select>
                            </div>
                            <div class="mb-2"><input type="text" name="title" class="form-control form-control-sm" placeholder="Title (optional)"></div>
                            <div class="mb-2"><textarea name="body" rows="3" class="form-control form-control-sm" placeholder="Share your thoughts..."></textarea></div>
                            <button class="btn btn-brand btn-sm">Submit Review</button>
                        </form>
                    @endif
                @else
                    <p class="small mt-3"><a href="{{ route('login') }}">Login</a> to write a review.</p>
                @endauth
            </div>
        </div>
    </div>

    {{-- Related --}}
    @if($related->isNotEmpty())
        <section class="mb-5">
            <h4 class="section-title mb-3">You may also like</h4>
            <div class="row g-3 g-md-4">
                @foreach($related as $product)
                    <div class="col-6 col-md-3">@include('partials.product-card')</div>
                @endforeach
            </div>
        </section>
    @endif
</div>
@endsection

@push('styles')
<style>
    .zoom-wrap{position:relative;overflow:hidden;border-radius:.75rem;}
    .zoom-img{transition:transform .15s ease;transform-origin:center;cursor:zoom-in;display:block;}
    .zoom-img.zoomed{cursor:move;}
    .zoom-controls{position:absolute;bottom:12px;right:12px;display:flex;flex-direction:column;gap:6px;}
    .zoom-controls button{width:38px;height:38px;border:none;border-radius:50%;background:rgba(255,255,255,.92);
        box-shadow:0 2px 8px rgba(0,0,0,.2);color:#333;display:flex;align-items:center;justify-content:center;font-size:1rem;}
    .zoom-controls button:hover{background:var(--brand);color:#fff;}
</style>
@endpush

@push('scripts')
<script>
(function(){
    const img = document.getElementById('mainImage');
    if (!img) return;
    let scale = 1;
    const min = 1, max = 4, step = 0.5;

    function apply(){
        img.style.transform = 'scale(' + scale + ')';
        img.classList.toggle('zoomed', scale > 1);
        if (scale === 1) img.style.transformOrigin = 'center';
    }
    function reset(){ scale = 1; img.style.transformOrigin = 'center'; apply(); }

    document.getElementById('zoomIn').addEventListener('click', () => { scale = Math.min(max, scale + step); apply(); });
    document.getElementById('zoomOut').addEventListener('click', () => { scale = Math.max(min, scale - step); apply(); });
    document.getElementById('zoomReset').addEventListener('click', reset);

    // Click image to toggle zoom; mouse wheel to zoom in/out.
    img.addEventListener('click', () => { scale = scale >= max ? min : scale + step; apply(); });
    img.addEventListener('wheel', (e) => {
        e.preventDefault();
        scale = Math.min(max, Math.max(min, scale + (e.deltaY < 0 ? step : -step)));
        apply();
    }, { passive: false });

    // Pan toward the cursor while zoomed.
    img.addEventListener('mousemove', (e) => {
        if (scale <= 1) return;
        const r = img.getBoundingClientRect();
        const x = ((e.clientX - r.left) / r.width) * 100;
        const y = ((e.clientY - r.top) / r.height) * 100;
        img.style.transformOrigin = x + '% ' + y + '%';
    });

    // Reset zoom when a thumbnail is selected.
    document.querySelectorAll('.thumb-img').forEach(t => t.addEventListener('click', reset));
})();
</script>
@endpush
