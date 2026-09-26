@extends('layouts.app')
@section('main_class', 'pb-2')

@section('content')
@php
    $heroes = ($banners ?? collect())->values();
    $fallbackHero = asset('images/hero-crochet.jpg');
@endphp

{{-- Hero: admin-managed banners (Admin › Banners, position "hero") --}}
<section class="container pt-3 pt-md-4" aria-label="Featured">
    {{-- Auto-advances every 5 s; pauses while the cursor is over the slider (Bootstrap pause: hover) --}}
    <div id="heroCarousel" class="carousel slide carousel-fade hero-wrap" @if($heroes->count() > 1) data-bs-ride="carousel" data-bs-interval="5000" data-bs-pause="hover" @endif>
        <div class="carousel-inner">
            @forelse($heroes as $hero)
                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                    @include('shop.partials.hero-slide', ['hero' => $hero, 'heading' => $loop->first ? 'h1' : 'h2'])
                </div>
            @empty
                <div class="carousel-item active">
                    @include('shop.partials.hero-slide', ['hero' => null, 'heading' => 'h1'])
                </div>
            @endforelse
        </div>
        @if($heroes->count() > 1)
            <button class="hero-arrow hero-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev" aria-label="Previous slide"><i class="bi bi-chevron-left" aria-hidden="true"></i></button>
            <button class="hero-arrow hero-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next" aria-label="Next slide"><i class="bi bi-chevron-right" aria-hidden="true"></i></button>
            <div class="carousel-indicators hero-dots">
                @foreach($heroes as $hero)
                    <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}" aria-label="Slide {{ $loop->iteration }}" @if($loop->first) aria-current="true" @endif></button>
                @endforeach
            </div>
        @endif
    </div>
</section>

{{-- Value propositions --}}
<section class="container mt-4" aria-label="Why shop with us">
    <ul class="value-props list-unstyled row row-cols-2 row-cols-lg-4 g-3 mb-0">
        <li class="col"><div class="vp"><i class="bi bi-hand-thumbs-up" aria-hidden="true"></i><div><strong>100% handmade</strong><span>By makers in Nepal</span></div></div></li>
        <li class="col"><div class="vp"><i class="bi bi-truck" aria-hidden="true"></i><div><strong>Cash on Delivery</strong><span>On orders up to {{ money(prepayment_threshold(), false) }}</span></div></div></li>
        <li class="col"><div class="vp"><i class="bi bi-stars" aria-hidden="true"></i><div><strong>Personalised gifts</strong><span>Your colours, names &amp; notes</span></div></div></li>
        <li class="col"><div class="vp"><i class="bi bi-gift" aria-hidden="true"></i><div><strong>Gift-ready</strong><span>Wrapped with care</span></div></div></li>
    </ul>
</section>

<div class="container">
    {{-- Product lines (top-level categories). New lines added in Admin › Categories appear here automatically. --}}
    @if(($featuredCategories ?? collect())->isNotEmpty())
        <section class="section" aria-labelledby="catTitle">
            <x-section-header title="Shop our handmade collections" eyebrow="Explore" :link="route('shop')" link-text="Shop all" heading-id="catTitle" />
            <div class="row g-3 g-md-4 row-cols-1 row-cols-md-{{ min(3, $featuredCategories->count()) }}">
                @foreach($featuredCategories as $cat)
                    <div class="col">
                        <div class="line-card">
                            <a href="{{ route('categories.show', $cat->slug) }}" class="cat-tile line-tile">
                                <img src="{{ $cat->image_url }}" alt="" loading="lazy" width="600" height="600">
                                <div class="cat-tile-body">
                                    <h3>{{ $cat->name }}</h3>
                                    <span>{{ $cat->product_total }} {{ \Illuminate\Support\Str::plural('piece', $cat->product_total) }} · Shop now <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
                                </div>
                            </a>
                            @if($cat->children->isNotEmpty())
                                <nav class="d-flex flex-wrap gap-2 mt-2" aria-label="{{ $cat->name }} sub-categories">
                                    @foreach($cat->children->take(6) as $child)
                                        <a href="{{ route('categories.show', $child->slug) }}" class="btn btn-ghost btn-sm py-1">{{ $child->name }}</a>
                                    @endforeach
                                </nav>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <x-product-rail :products="$newArrivals" title="New arrivals" eyebrow="Just made" id="railNew" :link="route('shop', ['collection' => 'new'])" class="pt-0" />

    {{-- Shop by occasion (Admin › Occasions & Collections) --}}
    @if(($occasions ?? collect())->isNotEmpty())
        <section class="section pt-0" aria-labelledby="occasionTitle">
            <x-section-header title="Shop by occasion" eyebrow="Find the perfect gift" heading-id="occasionTitle" />
            <div class="row row-cols-2 row-cols-sm-4 row-cols-lg-{{ min(8, max(4, $occasions->count())) }} g-2 g-md-3">
                @foreach($occasions as $occ)
                    <div class="col">
                        <a href="{{ route('collections.show', $occ->slug) }}" class="occasion-tile">
                            @if($occ->image_url)
                                <img src="{{ $occ->image_url }}" alt="" loading="lazy" width="80" height="80">
                            @else
                                <span class="occasion-icon"><i class="bi bi-{{ $occ->icon ?: 'gift' }}" aria-hidden="true"></i></span>
                            @endif
                            <span class="fw-semibold">{{ $occ->name }}</span>
                            <small class="text-muted">{{ $occ->products_count }} {{ \Illuminate\Support\Str::plural('gift', $occ->products_count) }}</small>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif
</div>

{{-- Promo: custom orders (text, button and image editable in Admin › Settings) --}}
@php
    $promoImg = setting('promo_image')
        ? asset('storage/'.setting('promo_image'))
        : ($heroes->get(1)?->image_url ?? $heroes->first()?->image_url ?? $fallbackHero);
    $promoPoints = collect(preg_split('/\R/', (string) setting('promo_points')))->map(fn ($p) => trim($p))->filter();
    $promoLink = (string) setting('promo_button_link', '/custom-order');
    $promoLink = str_starts_with($promoLink, '/') ? url($promoLink) : $promoLink;
@endphp
<section class="promo-split my-4" aria-labelledby="customTitle">
    <div class="container">
        <div class="row g-0 align-items-stretch overflow-hidden rounded-4 bg-white border">
            <div class="col-md-6 order-md-2">
                <img src="{{ $promoImg }}" alt="{{ setting('promo_title') }}" class="w-100 h-100 object-fit-cover" loading="lazy" style="min-height:280px">
            </div>
            <div class="col-md-6 d-flex align-items-center">
                <div class="p-4 p-lg-5">
                    @if(setting('promo_eyebrow'))<div class="eyebrow mb-2">{{ setting('promo_eyebrow') }}</div>@endif
                    <h2 id="customTitle" class="mb-3">{{ setting('promo_title') }}</h2>
                    @if(setting('promo_text'))<div class="rich-text text-muted mb-4">{!! rich_text(setting('promo_text')) !!}</div>@endif
                    @if($promoPoints->isNotEmpty())
                        <ul class="list-unstyled small mb-4">
                            @foreach($promoPoints as $point)
                                <li class="{{ $loop->last ? '' : 'mb-2' }}"><i class="bi bi-check2-circle text-brand me-2" aria-hidden="true"></i>{{ $point }}</li>
                            @endforeach
                        </ul>
                    @endif
                    <a href="{{ $promoLink }}" class="btn btn-brand btn-lg">{{ setting('promo_button_text') }}</a>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="container">
    {{-- Curated collections (Admin › Occasions & Collections, type "curated") --}}
    @if(($curated ?? collect())->isNotEmpty())
        <section class="section" aria-labelledby="curatedTitle">
            <x-section-header title="Curated collections" eyebrow="Hand-picked" heading-id="curatedTitle" />
            <div class="row g-3 g-md-4 row-cols-1 row-cols-md-{{ min(3, $curated->count()) }}">
                @foreach($curated as $col)
                    @php $cover = $col->image_url ?? $col->products->first()?->thumbnail; @endphp
                    <div class="col">
                        <a href="{{ route('collections.show', $col->slug) }}" class="curated-card">
                            <span class="curated-media">@if($cover)<img src="{{ $cover }}" alt="" loading="lazy" width="600" height="400">@endif</span>
                            <span class="curated-body">
                                <span class="h5 d-block mb-1" style="font-family:var(--font-display)">{{ $col->name }}</span>
                                @if($col->tagline)<span class="text-muted small d-block mb-2">{{ $col->tagline }}</span>@endif
                                <span class="link-arrow small">Explore {{ $col->products_count }} {{ \Illuminate\Support\Str::plural('piece', $col->products_count) }} <i class="bi bi-arrow-right" aria-hidden="true"></i></span>
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- Flash sale --}}
    @if(($flashSale ?? collect())->isNotEmpty())
        <section class="section" aria-labelledby="railFlash-title">
            <x-section-header title="Flash sale" eyebrow="Limited time" :link="route('shop', ['collection' => 'sale'])" rail="railFlash" heading-id="railFlash-title" />
            @if($flashSaleEndsAt)
                <p class="text-muted small mt-n2 mb-3"><i class="bi bi-clock me-1"></i>Ends {{ \Illuminate\Support\Carbon::parse($flashSaleEndsAt)->diffForHumans() }}</p>
            @endif
            <div class="rail" id="railFlash" tabindex="0" aria-label="Flash sale">
                @foreach($flashSale as $product)<x-product-card :product="$product" />@endforeach
            </div>
        </section>
    @endif

    {{-- Best sellers --}}
    @if($bestSellers->isNotEmpty())
        <section class="section pt-2" aria-labelledby="bestTitle">
            <x-section-header title="Most loved" eyebrow="Best sellers" :link="route('shop', ['collection' => 'bestsellers'])" heading-id="bestTitle" />
            <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-3 g-md-4">
                @foreach($bestSellers->take(8) as $product)
                    <div class="col"><x-product-card :product="$product" /></div>
                @endforeach
            </div>
        </section>
    @endif

    <x-product-rail :products="$trending" title="Trending now" eyebrow="Popular this week" id="railTrending" :link="route('shop', ['collection' => 'trending'])" class="pt-2" />

    {{-- Customer reviews (approved reviews from the database) --}}
    @if(($reviews ?? collect())->isNotEmpty())
        <section class="section pt-2" aria-labelledby="reviewsTitle">
            <x-section-header title="Kind words from customers" eyebrow="Reviews" heading-id="reviewsTitle" />
            <div class="row g-3 g-md-4">
                @foreach($reviews as $review)
                    <div class="col-md-4">
                        <figure class="surface h-100 p-4 mb-0">
                            <div class="rating mb-2" aria-label="{{ $review->rating }} out of 5 stars">
                                @for($i = 1; $i <= 5; $i++)<i class="bi {{ $i <= $review->rating ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>@endfor
                            </div>
                            <blockquote class="mb-3">
                                @if($review->title)<p class="fw-semibold mb-1">{{ $review->title }}</p>@endif
                                <p class="text-muted mb-0">“{{ \Illuminate\Support\Str::limit($review->body, 180) }}”</p>
                            </blockquote>
                            <figcaption class="small">
                                <strong>{{ \Illuminate\Support\Str::before($review->user->name ?? 'Customer', ' ') }}</strong>
                                <span class="text-muted">on <a href="{{ route('products.show', $review->product->slug) }}">{{ $review->product->name }}</a></span>
                            </figcaption>
                        </figure>
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    <x-product-rail :products="$recentlyViewed" title="Recently viewed" id="railRecent" class="pt-2" />
</div>
@endsection

@push('styles')
<style>
    .hero-wrap{border-radius:calc(var(--radius) * 1.6);overflow:hidden;background:var(--sage-light);}
    .hero-slide{display:grid;grid-template-columns:1.05fr 1fr;min-height:clamp(380px,48vw,520px);}
    .hero-copy{padding:clamp(1.75rem,4vw,4rem);display:flex;flex-direction:column;justify-content:center;background:linear-gradient(135deg,#EEF1E4,#F7F4EE);}
    .hero-copy .hero-title{font-size:clamp(2rem,1.2rem + 3vw,3.4rem);line-height:1.08;margin-bottom:1rem;}
    .hero-copy p{font-size:1.08rem;color:var(--muted);max-width:34ch;}
    .hero-media{position:relative;}
    .hero-media img{width:100%;height:100%;object-fit:cover;}
    .hero-arrow{position:absolute;top:50%;transform:translateY(-50%);z-index:3;width:44px;height:44px;border-radius:50%;border:0;
        background:rgba(255,255,255,.92);color:var(--ink);box-shadow:var(--shadow-sm);display:flex;align-items:center;justify-content:center;
        opacity:0;transition:opacity .2s,background .2s;}
    .hero-wrap:hover .hero-arrow,.hero-arrow:focus-visible{opacity:1;}
    .hero-arrow:hover{background:var(--forest);color:#fff;}
    .hero-prev{left:1rem;} .hero-next{right:1rem;}
    @media(hover:none){.hero-arrow{display:none;}}
    .hero-dots{margin-bottom:1rem;} .hero-dots [data-bs-target]{width:9px;height:9px;border-radius:50%;background:var(--forest);border:0;}
    @media(max-width:767.98px){
        .hero-slide{grid-template-columns:1fr;}
        .hero-media{order:-1;height:240px;}
    }
    .line-tile{aspect-ratio:4/3;}
    .line-tile .cat-tile-body h3{font-size:1.5rem;}
    .occasion-tile{display:flex;flex-direction:column;align-items:center;gap:.35rem;text-align:center;padding:1.1rem .5rem;height:100%;
        background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);color:var(--ink);transition:border-color .2s,transform .2s,box-shadow .2s;}
    .occasion-tile:hover{border-color:var(--sage);transform:translateY(-3px);box-shadow:var(--shadow-sm);color:var(--ink);}
    .occasion-icon{width:56px;height:56px;border-radius:50%;background:var(--accent-soft);color:var(--terracotta);display:flex;align-items:center;justify-content:center;font-size:1.5rem;}
    .occasion-tile img{width:56px;height:56px;border-radius:50%;object-fit:cover;}
    .curated-card{display:flex;flex-direction:column;height:100%;background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);overflow:hidden;color:var(--ink);transition:box-shadow .2s,transform .2s;}
    .curated-card:hover{box-shadow:var(--shadow);transform:translateY(-3px);color:var(--ink);}
    .curated-media{display:block;aspect-ratio:3/2;background:var(--sage-light);overflow:hidden;}
    .curated-media img{width:100%;height:100%;object-fit:cover;transition:transform .5s;}
    .curated-card:hover .curated-media img{transform:scale(1.05);}
    .curated-body{display:block;padding:1rem 1.1rem 1.2rem;}
    .vp{display:flex;gap:.8rem;align-items:center;background:var(--surface);border:1px solid var(--line);border-radius:var(--radius);padding:.95rem 1rem;height:100%;}
    .vp i{font-size:1.45rem;color:var(--forest);width:44px;height:44px;border-radius:50%;background:var(--sage-light);display:flex;align-items:center;justify-content:center;flex-shrink:0;}
    .vp strong{display:block;font-size:.9rem;} .vp span{font-size:.8rem;color:var(--muted);}
    @media(max-width:575.98px){.vp{flex-direction:column;align-items:flex-start;gap:.5rem;}}
</style>
@endpush
