@extends('layouts.app')
@section('title', $product->meta_title ?: $product->name)
@section('meta_description', $product->meta_description ?: ($product->short_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 155)))
@section('og_type', 'product')
@section('og_image', $product->thumbnail)
@section('body_class', $product->in_stock ? 'has-sticky-buy' : '')

@php
    $waNumber = preg_replace('/\D+/', '', setting('whatsapp_number', '9779800000000'));
    $waMsg    = "Hello, I have a question about this product:\n{$product->name}\n".route('products.show', $product->slug);
    $waLink   = 'https://wa.me/'.$waNumber.'?text='.rawurlencode($waMsg);
    $gallery  = $product->images->sortBy([['is_primary', 'desc'], ['sort_order', 'asc']])->values();
    $main     = $gallery->first();
    $reviews  = $product->approvedReviews;
    $cat      = $product->category;

    $crumbs = ['Shop' => route('shop')];
    if ($cat?->parent) $crumbs[$cat->parent->name] = route('categories.show', $cat->parent->slug);
    if ($cat) $crumbs[$cat->name] = route('categories.show', $cat->slug);
    $crumbs[$product->name] = null;

    $jsonLd = array_filter([
        '@context'    => 'https://schema.org',
        '@type'       => 'Product',
        'name'        => $product->name,
        'image'       => $gallery->map->url->values()->all() ?: [$product->thumbnail],
        'description' => $product->short_description ?: \Illuminate\Support\Str::limit(strip_tags($product->description), 300),
        'sku'         => $product->sku,
        'category'    => $cat?->path_name,
        'brand'       => ['@type' => 'Brand', 'name' => setting('store_name', 'Sistrella')],
        'offers'      => [
            '@type'         => 'Offer',
            'url'           => route('products.show', $product->slug),
            'priceCurrency' => setting('currency_code', 'NPR'),
            'price'         => number_format($product->current_price, 2, '.', ''),
            'availability'  => $product->in_stock ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
            'itemCondition' => 'https://schema.org/NewCondition',
        ],
        'aggregateRating' => $product->rating_count > 0 ? [
            '@type'       => 'AggregateRating',
            'ratingValue' => round($product->rating_avg, 1),
            'reviewCount' => $product->rating_count,
        ] : null,
    ]);
@endphp

@push('meta')
<script type="application/ld+json">{!! json_encode($jsonLd, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush

@section('content')
<div class="container">
    <x-breadcrumbs :items="$crumbs" />

    <div class="row g-4 g-xl-5 mb-5">
        {{-- Gallery --}}
        <div class="col-lg-6">
            <div class="pd-gallery">
                <div class="zoom-wrap surface">
                    <img id="mainImage" src="{{ $main?->url ?? $product->thumbnail }}" alt="{{ $main?->alt ?: $product->name }}" class="zoom-img" width="900" height="900" fetchpriority="high">
                    <div class="pcard-badges">
                        @if($product->discount_percent > 0)<span class="pbadge pbadge-sale">Save {{ $product->discount_percent }}%</span>@endif
                        @if($product->is_new_arrival)<span class="pbadge pbadge-new">New</span>@endif
                        @if($product->is_best_seller)<span class="pbadge">Bestseller</span>@endif
                    </div>
                    <div class="zoom-controls" role="group" aria-label="Zoom">
                        <button type="button" id="zoomIn" aria-label="Zoom in"><i class="bi bi-plus-lg"></i></button>
                        <button type="button" id="zoomOut" aria-label="Zoom out"><i class="bi bi-dash-lg"></i></button>
                        <button type="button" id="zoomReset" aria-label="Reset zoom"><i class="bi bi-arrow-counterclockwise"></i></button>
                    </div>
                </div>
                @if($gallery->count() > 1)
                    <div class="pd-thumbs" role="list" aria-label="Product images">
                        @foreach($gallery as $img)
                            <button type="button" class="pd-thumb {{ $loop->first ? 'active' : '' }}" role="listitem" data-src="{{ $img->url }}" data-alt="{{ $img->alt ?: $product->name }}"
                                    aria-label="Show image {{ $loop->iteration }} of {{ $gallery->count() }}" aria-pressed="{{ $loop->first ? 'true' : 'false' }}">
                                <img src="{{ $img->url }}" alt="" width="84" height="84" loading="lazy">
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- Purchase panel --}}
        <div class="col-lg-6">
            <div class="pd-info">
                @if($cat)<a href="{{ route('categories.show', $cat->slug) }}" class="eyebrow">{{ $cat->name }}</a>@endif
                <h1 class="pd-title mt-2 mb-2">{{ $product->name }}</h1>

                @if($product->rating_count > 0)
                    <a href="#reviews" class="rating d-inline-flex align-items-center gap-1 small text-reset mb-2">
                        @for($i = 1; $i <= 5; $i++)<i class="bi {{ $i <= round($product->rating_avg) ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>@endfor
                        <span class="text-muted ms-1">{{ number_format($product->rating_avg, 1) }} · {{ $product->rating_count }} {{ \Illuminate\Support\Str::plural('review', $product->rating_count) }}</span>
                    </a>
                @endif

                <div class="my-3">
                    <div id="pdPrice"><x-price :product="$product" size="fs-3" /></div>
                    @if($product->discount_percent > 0)
                        <div class="small text-accent fw-semibold mt-1" id="pdSave">
                            You save {{ money($product->old_price - $product->current_price) }}
                            @if($product->isOnFlashSale()) · Flash sale ends {{ $product->flash_sale_ends_at->diffForHumans() }}@endif
                        </div>
                    @endif
                </div>

                @if($product->short_description)<p class="text-muted mb-3">{{ $product->short_description }}</p>@endif

                @if($product->collections->isNotEmpty())
                    <div class="d-flex flex-wrap align-items-center gap-2 mb-3 small">
                        <span class="text-muted">Perfect for:</span>
                        @foreach($product->collections as $col)
                            <a href="{{ route('collections.show', $col->slug) }}" class="btn btn-ghost btn-sm py-1">@if($col->icon)<i class="bi bi-{{ $col->icon }} me-1" aria-hidden="true"></i>@endif{{ $col->name }}</a>
                        @endforeach
                    </div>
                @endif

                <p class="mb-3 fw-semibold small" id="pdStock" aria-live="polite">
                    @if(! $product->in_stock)
                        <span class="text-danger"><i class="bi bi-x-circle me-1"></i>Sold out</span>
                        @if($product->is_customizable)<span class="text-muted fw-normal">, but we can make one for you.</span>@endif
                    @elseif($product->track_inventory && $product->is_low_stock)
                        <span class="text-accent"><i class="bi bi-hourglass-split me-1"></i>Only {{ $product->stock }} left, order soon</span>
                    @else
                        <span class="text-success"><i class="bi bi-check-circle me-1"></i>In stock, ready to ship</span>
                    @endif
                </p>

                @if($product->in_stock)
                    <form action="{{ route('cart.add') }}" method="POST" class="js-add-to-cart" id="addToCartForm">@csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        @if($product->variants->isNotEmpty())
                            @include('shop.partials.variant-picker', ['product' => $product])
                        @endif

                        <label for="qtyInput" class="form-label">Quantity</label>
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div class="qty">
                                <button type="button" data-qty="-1" aria-label="Decrease quantity"><i class="bi bi-dash"></i></button>
                                <input id="qtyInput" type="number" name="quantity" value="1" min="1" max="{{ $product->track_inventory && $product->type !== 'variable' ? max(1, min(999, $product->stock)) : 999 }}" inputmode="numeric">
                                <button type="button" data-qty="1" aria-label="Increase quantity"><i class="bi bi-plus"></i></button>
                            </div>
                            <button class="btn btn-brand btn-lg flex-grow-1"><i class="bi bi-bag-plus me-2"></i>Add to cart</button>
                        </div>
                        <button name="buy_now" value="1" class="btn btn-outline-brand btn-lg w-100 mb-3">Buy it now</button>
                    </form>
                @elseif($product->is_customizable)
                    <a href="{{ route('custom.create') }}" class="btn btn-brand btn-lg w-100 mb-3"><i class="bi bi-stars me-2"></i>Request this piece</a>
                @endif

                <div class="d-flex flex-wrap gap-2 mb-4">
                    @auth
                        <form action="{{ route('wishlist.toggle', $product) }}" method="POST">@csrf
                            <button class="btn btn-ghost btn-sm"><i class="bi bi-heart me-1"></i>Wishlist</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-ghost btn-sm"><i class="bi bi-heart me-1"></i>Wishlist</a>
                    @endauth
                    <a href="{{ $waLink }}" target="_blank" rel="noopener" class="btn btn-ghost btn-sm"><i class="bi bi-whatsapp me-1 text-success"></i>Ask a question</a>
                    @if($product->is_customizable && $product->in_stock)
                        <a href="{{ route('custom.create') }}" class="btn btn-ghost btn-sm"><i class="bi bi-stars me-1"></i>Customise</a>
                    @endif
                </div>

                <ul class="pd-perks list-unstyled small mb-4">
                    <li><i class="bi bi-truck"></i>Cash on Delivery available across Nepal</li>
                    <li><i class="bi bi-hand-thumbs-up"></i>Handmade to order quality, checked before dispatch</li>
                    <li><i class="bi bi-shield-check"></i>{{ prepayment_notice() }}</li>
                </ul>

                {{-- Product information --}}
                <div class="accordion accordion-flush pd-accordion" id="pdInfo">
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#pdDesc" aria-expanded="true" aria-controls="pdDesc">Description</button></h2>
                        <div id="pdDesc" class="accordion-collapse collapse show">
                            <div class="accordion-body px-0 pd-description">{!! $product->description ?: '<p class="text-muted">No description available.</p>' !!}</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pdDetails" aria-expanded="false" aria-controls="pdDetails">Product details</button></h2>
                        <div id="pdDetails" class="accordion-collapse collapse">
                            <dl class="accordion-body px-0 row small mb-0">
                                <dt class="col-5 text-muted fw-normal">SKU</dt><dd class="col-7">{{ $product->sku }}</dd>
                                @if($cat)<dt class="col-5 text-muted fw-normal">Category</dt><dd class="col-7"><a href="{{ route('categories.show', $cat->slug) }}">{{ $cat->path_name }}</a></dd>@endif
                                @if($product->weight)<dt class="col-5 text-muted fw-normal">Weight</dt><dd class="col-7">{{ rtrim(rtrim(number_format($product->weight, 2), '0'), '.') }} g</dd>@endif
                                <dt class="col-5 text-muted fw-normal">Availability</dt><dd class="col-7">{{ $product->in_stock ? 'In stock' : 'Sold out' }}</dd>
                                <dt class="col-5 text-muted fw-normal">Customisable</dt><dd class="col-7">{{ $product->is_customizable ? 'Yes, colours and size on request' : 'No' }}</dd>
                            </dl>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header"><button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#pdShipping" aria-expanded="false" aria-controls="pdShipping">Shipping &amp; payment</button></h2>
                        <div id="pdShipping" class="accordion-collapse collapse">
                            <div class="accordion-body px-0 small">
                                <p>Orders are packed by hand and dispatched within 2–4 working days. Delivery charge is shown at checkout.</p>
                                <p class="mb-0">{{ prepayment_notice() }} Smaller orders can be paid fully in cash on delivery. Advance payments are accepted via eSewa, Khalti or bank transfer.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Reviews --}}
    <section id="reviews" class="section pt-0" aria-labelledby="reviewsTitle">
        <div class="row g-4">
            <div class="col-lg-4">
                <h2 id="reviewsTitle" class="h3">Customer reviews</h2>
                @if($product->rating_count > 0)
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <span class="display-6 fw-semibold" style="font-family:var(--font-display)">{{ number_format($product->rating_avg, 1) }}</span>
                        <div>
                            <div class="rating">@for($i = 1; $i <= 5; $i++)<i class="bi {{ $i <= round($product->rating_avg) ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>@endfor</div>
                            <div class="small text-muted">Based on {{ $product->rating_count }} {{ \Illuminate\Support\Str::plural('review', $product->rating_count) }}</div>
                        </div>
                    </div>
                @else
                    <p class="text-muted">No reviews yet.</p>
                @endif

                @auth
                    @if($canReview)
                        <form action="{{ route('products.review', $product->slug) }}" method="POST" class="surface p-3 mt-3">@csrf
                            <h3 class="h6 fw-bold" style="font-family:var(--font-body)">Write a review</h3>
                            <div class="mb-2">
                                <label for="rvRating" class="form-label small">Rating</label>
                                <select id="rvRating" name="rating" class="form-select form-select-sm">
                                    @for($i = 5; $i >= 1; $i--)<option value="{{ $i }}">{{ $i }} {{ \Illuminate\Support\Str::plural('star', $i) }}</option>@endfor
                                </select>
                            </div>
                            <div class="mb-2">
                                <label for="rvTitle" class="form-label small">Title <span class="text-muted fw-normal">(optional)</span></label>
                                <input id="rvTitle" type="text" name="title" maxlength="120" class="form-control form-control-sm">
                            </div>
                            <div class="mb-2">
                                <label for="rvBody" class="form-label small">Your review</label>
                                <textarea id="rvBody" name="body" rows="3" maxlength="2000" class="form-control form-control-sm"></textarea>
                            </div>
                            <button class="btn btn-brand btn-sm">Submit review</button>
                        </form>
                    @endif
                @else
                    <p class="small mt-3"><a href="{{ route('login') }}">Log in</a> to write a review.</p>
                @endauth
            </div>
            <div class="col-lg-8">
                @forelse($reviews as $review)
                    <article class="border-bottom pb-3 mb-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <strong>{{ $review->user->name ?? 'Customer' }}</strong>
                            <span class="rating small" aria-label="{{ $review->rating }} out of 5">@for($i = 1; $i <= 5; $i++)<i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>@endfor</span>
                        </div>
                        <div class="small text-muted mb-1">
                            {{ $review->created_at?->format('M j, Y') }}
                            @if($review->is_verified_purchase)· <span class="text-success"><i class="bi bi-patch-check-fill"></i> Verified purchase</span>@endif
                        </div>
                        @if($review->title)<div class="fw-semibold">{{ $review->title }}</div>@endif
                        <p class="text-muted mb-0">{{ $review->body }}</p>
                    </article>
                @empty
                    <div class="surface p-4 text-center text-muted">Be the first to share what you think of this piece.</div>
                @endforelse
            </div>
        </div>
    </section>

    <x-product-rail :products="$related" title="You may also like" id="railRelated" :link="$cat ? route('categories.show', ($cat->parent ?? $cat)->slug) : null" class="pt-0" />
</div>

{{-- Sticky mobile purchase bar (submits the main form) --}}
@if($product->in_stock)
    <div class="sticky-buy d-lg-none">
        <div class="container d-flex align-items-center gap-3">
            <div class="flex-grow-1 min-w-0">
                <div class="small text-truncate fw-semibold">{{ $product->name }}</div>
                <x-price :product="$product" class="small" />
            </div>
            <button type="submit" form="addToCartForm" class="btn btn-brand"><i class="bi bi-bag-plus me-1"></i>Add to cart</button>
        </div>
    </div>
@endif
@endsection

@push('styles')
<style>
    .pd-gallery{position:sticky;top:150px;}
    @media(max-width:991.98px){.pd-gallery{position:static;}}
    .zoom-wrap{position:relative;overflow:hidden;aspect-ratio:1/1;background:var(--sage-light);}
    .zoom-img{width:100%;height:100%;object-fit:cover;transition:transform .15s ease;transform-origin:center;cursor:zoom-in;display:block;}
    .zoom-img.zoomed{cursor:move;}
    .zoom-controls{position:absolute;bottom:12px;right:12px;display:flex;flex-direction:column;gap:6px;z-index:2;}
    .zoom-controls button{width:38px;height:38px;border:none;border-radius:50%;background:rgba(255,255,255,.94);box-shadow:var(--shadow-sm);color:var(--ink);display:flex;align-items:center;justify-content:center;}
    .zoom-controls button:hover{background:var(--forest);color:#fff;}
    .pd-thumbs{display:flex;gap:.6rem;margin-top:.75rem;overflow-x:auto;padding-bottom:.25rem;}
    .pd-thumb{flex:0 0 auto;width:84px;height:84px;padding:0;border:2px solid transparent;border-radius:var(--radius-sm);overflow:hidden;background:var(--sage-light);}
    .pd-thumb img{width:100%;height:100%;object-fit:cover;}
    .pd-thumb.active{border-color:var(--forest);}
    .pd-title{font-size:clamp(1.75rem,1.3rem + 1.5vw,2.5rem);line-height:1.15;}
    .pd-perks li{display:flex;gap:.6rem;align-items:flex-start;padding:.45rem 0;border-bottom:1px dashed var(--line);}
    .pd-perks i{color:var(--forest);font-size:1rem;}
    .pd-accordion .accordion-button{font-family:var(--font-body);font-weight:700;padding-left:0;padding-right:0;background:none;box-shadow:none;color:var(--ink);}
    .pd-accordion .accordion-item{background:none;border-color:var(--line);}
    .pd-description ul{padding-left:1.1rem;} .pd-description li{margin-bottom:.25rem;}
    .sticky-buy{position:fixed;left:0;right:0;bottom:0;z-index:1035;background:#fff;border-top:1px solid var(--line);box-shadow:0 -6px 20px rgba(43,42,38,.08);padding:.65rem 0;}
    .min-w-0{min-width:0;}
    @media(max-width:991.98px){ .has-sticky-buy .footer{padding-bottom:5.5rem!important;} }
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
    function reset(){ scale = 1; apply(); }

    document.getElementById('zoomIn').addEventListener('click', () => { scale = Math.min(max, scale + step); apply(); });
    document.getElementById('zoomOut').addEventListener('click', () => { scale = Math.max(min, scale - step); apply(); });
    document.getElementById('zoomReset').addEventListener('click', reset);
    img.addEventListener('click', () => { scale = scale >= max ? min : scale + step; apply(); });
    img.addEventListener('mousemove', (e) => {
        if (scale <= 1) return;
        const r = img.getBoundingClientRect();
        img.style.transformOrigin = ((e.clientX - r.left) / r.width * 100) + '% ' + ((e.clientY - r.top) / r.height * 100) + '%';
    });

    // Thumbnail gallery
    document.querySelectorAll('.pd-thumb').forEach(btn => btn.addEventListener('click', () => {
        document.querySelectorAll('.pd-thumb').forEach(b => { b.classList.remove('active'); b.setAttribute('aria-pressed', 'false'); });
        btn.classList.add('active');
        btn.setAttribute('aria-pressed', 'true');
        img.src = btn.dataset.src;
        img.alt = btn.dataset.alt;
        reset();
    }));
})();
</script>
@endpush
