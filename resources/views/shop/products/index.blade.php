@extends('layouts.app')
@section('title', $products->currentPage() > 1 ? $metaTitle.' — Page '.$products->currentPage() : $metaTitle)
@section('meta_description', $metaDescription)
@if(! empty($filters['search']))
    @push('meta')<meta name="robots" content="noindex, follow">@endpush
@endif
@if($giftCollection?->image_url)
    @section('og_image', $giftCollection->image_url)
@endif

@php
    $collections = \App\Repositories\ProductRepository::COLLECTIONS;
    $sorts = \App\Repositories\ProductRepository::SORTS;

    $formAction = $giftCollection ? route('collections.show', $giftCollection->slug)
        : ($category ? route('categories.show', $category->slug) : route('shop'));
    $activeCollection = $filters['collection'] ?? null;

    // Ancestors of the current category (any depth), root first.
    $ancestors = collect();
    for ($c = $category?->parent; $c; $c = $c->parent) { $ancestors->prepend($c); }
    $trailIds = $ancestors->pluck('id')->push($category?->id)->filter()->all();

    $crumbs = ['Shop' => route('shop')];
    foreach ($ancestors as $a) $crumbs[$a->name] = route('categories.show', $a->slug);
    if ($category) $crumbs[$category->name] = null;
    elseif ($giftCollection) $crumbs[$giftCollection->name] = null;
    elseif (! empty($filters['search'])) $crumbs['Search'] = null;
    elseif ($activeCollection) $crumbs[$collections[$activeCollection]] = null;

    // Sub-category chips: children of this category, or siblings when it is a leaf.
    $chipSource = $category ? ($category->children->isNotEmpty() ? $category : $category->parent) : null;
    $chips = $chipSource ? $chipSource->children()->active()->get() : collect();

    $intro = $giftCollection ? ($giftCollection->tagline ?: $giftCollection->description) : $category?->description;

    // Removable "active filter" chips
    $active = [];
    if (! empty($filters['search'])) $active[] = ['Search: “'.$filters['search'].'”', 'search'];
    if ($activeCollection && ($category || $giftCollection)) $active[] = [$collections[$activeCollection], 'collection'];
    if ($activeOccasion && ! $giftCollection) $active[] = [$activeOccasion->name, 'occasion'];
    if (isset($filters['min_price'])) $active[] = ['From '.money($filters['min_price'], false), 'min_price'];
    if (isset($filters['max_price'])) $active[] = ['Up to '.money($filters['max_price'], false), 'max_price'];
    if (! empty($filters['in_stock'])) $active[] = ['In stock', 'in_stock'];
@endphp

@section('content')
<div class="container">
    <x-breadcrumbs :items="$crumbs" />

    <header class="mb-4">
        <h1 class="mb-2" style="font-size:clamp(1.75rem,1.3rem + 1.6vw,2.5rem)">{{ $heading }}</h1>
        @if($intro)
            <p class="text-muted mb-0" style="max-width:62ch">{{ $intro }}</p>
        @endif

        @if($chips->isNotEmpty())
            <nav class="d-flex flex-wrap gap-2 mt-3" aria-label="Sub-categories">
                @if($chipSource)
                    <a href="{{ route('categories.show', $chipSource->slug) }}" class="btn btn-sm {{ $category->id === $chipSource->id ? 'btn-brand' : 'btn-ghost' }}">All {{ $chipSource->name }}</a>
                @endif
                @foreach($chips as $chip)
                    <a href="{{ route('categories.show', $chip->slug) }}" class="btn btn-sm {{ $category->id === $chip->id ? 'btn-brand' : 'btn-ghost' }}">{{ $chip->name }}</a>
                @endforeach
            </nav>
        @elseif($giftCollection)
            @php $siblings = $occasions->where('type', $giftCollection->type); @endphp
            <nav class="d-flex flex-wrap gap-2 mt-3" aria-label="{{ $giftCollection->type === 'occasion' ? 'Occasions' : 'Collections' }}">
                @foreach($siblings as $sib)
                    <a href="{{ route('collections.show', $sib->slug) }}" class="btn btn-sm {{ $sib->id === $giftCollection->id ? 'btn-brand' : 'btn-ghost' }}">@if($sib->icon)<i class="bi bi-{{ $sib->icon }} me-1" aria-hidden="true"></i>@endif{{ $sib->name }}</a>
                @endforeach
            </nav>
        @elseif(! $category)
            <nav class="d-flex flex-wrap gap-2 mt-3" aria-label="Collections">
                <a href="{{ route('shop', array_filter(['search' => $filters['search'] ?? null])) }}" class="btn btn-sm {{ ! $activeCollection ? 'btn-brand' : 'btn-ghost' }}">All</a>
                @foreach($collections as $key => $label)
                    <a href="{{ route('shop', array_filter(['collection' => $key, 'search' => $filters['search'] ?? null])) }}" class="btn btn-sm {{ $activeCollection === $key ? 'btn-brand' : 'btn-ghost' }}">{{ $label }}</a>
                @endforeach
            </nav>
        @endif
    </header>

    <div class="row g-4">
        {{-- Filters: sidebar on desktop, off-canvas drawer on mobile --}}
        <aside class="col-lg-3">
            <div class="offcanvas-lg offcanvas-start" tabindex="-1" id="shopFilters" aria-labelledby="shopFiltersLabel">
                <div class="offcanvas-header border-bottom d-lg-none">
                    <h2 class="h5 mb-0" id="shopFiltersLabel">Filters</h2>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#shopFilters" aria-label="Close filters"></button>
                </div>
                <div class="offcanvas-body d-block p-3 p-lg-0">
                    <div class="surface p-3 mb-3">
                        <h2 class="h6 fw-bold mb-3" style="font-family:var(--font-body)">Categories</h2>
                        <ul class="list-unstyled small mb-0 filter-cats">
                            <li><a href="{{ route('shop') }}" class="{{ ! $category ? 'active' : '' }}">All products</a></li>
                            @foreach($categories as $cat)
                                <li>
                                    <a href="{{ route('categories.show', $cat->slug) }}" class="{{ $category?->id === $cat->id ? 'active' : '' }}">{{ $cat->name }}</a>
                                    {{-- Expand the branch that contains the current category --}}
                                    @if(in_array($cat->id, $trailIds) && $cat->children->isNotEmpty())
                                        <ul class="list-unstyled ms-3 mt-1 mb-1">
                                            @foreach($cat->children as $child)
                                                <li>
                                                    <a href="{{ route('categories.show', $child->slug) }}" class="{{ $category->id === $child->id ? 'active' : '' }}">{{ $child->name }}</a>
                                                    @if(in_array($child->id, $trailIds) && $child->children->isNotEmpty())
                                                        <ul class="list-unstyled ms-3 mt-1 mb-1">
                                                            @foreach($child->children as $grand)
                                                                <li><a href="{{ route('categories.show', $grand->slug) }}" class="{{ $category->id === $grand->id ? 'active' : '' }}">{{ $grand->name }}</a></li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <form action="{{ $formAction }}" method="GET" class="surface p-3">
                        @if(! empty($filters['search']))<input type="hidden" name="search" value="{{ $filters['search'] }}">@endif
                        @if(! empty($filters['sort']))<input type="hidden" name="sort" value="{{ $filters['sort'] }}">@endif

                        <fieldset class="mb-3">
                            <legend class="h6 fw-bold mb-2" style="font-family:var(--font-body);font-size:.95rem">Collection</legend>
                            <div class="form-check small">
                                <input class="form-check-input" type="radio" name="collection" value="" id="col-all" {{ ! $activeCollection ? 'checked' : '' }}>
                                <label class="form-check-label" for="col-all">All</label>
                            </div>
                            @foreach($collections as $key => $label)
                                <div class="form-check small">
                                    <input class="form-check-input" type="radio" name="collection" value="{{ $key }}" id="col-{{ $key }}" {{ $activeCollection === $key ? 'checked' : '' }}>
                                    <label class="form-check-label" for="col-{{ $key }}">{{ $label }}</label>
                                </div>
                            @endforeach
                        </fieldset>

                        @if(! $giftCollection && $occasions->where('type', 'occasion')->isNotEmpty())
                            <div class="mb-3">
                                <label for="occasionSelect" class="h6 fw-bold mb-2 d-block" style="font-family:var(--font-body);font-size:.95rem">Occasion</label>
                                <select id="occasionSelect" name="occasion" class="form-select form-select-sm">
                                    <option value="">Any occasion</option>
                                    @foreach($occasions->where('type', 'occasion') as $occ)
                                        <option value="{{ $occ->slug }}" @selected(($filters['occasion'] ?? null) === $occ->slug)>{{ $occ->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endif

                        <fieldset class="mb-3">
                            <legend class="h6 fw-bold mb-2" style="font-family:var(--font-body);font-size:.95rem">Price ({{ setting('currency_symbol', 'NPR') }})</legend>
                            <div class="d-flex align-items-center gap-2">
                                <label for="minPrice" class="visually-hidden">Minimum price</label>
                                <input id="minPrice" type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" class="form-control form-control-sm" min="0" step="50" placeholder="Min" inputmode="numeric">
                                <span class="text-muted">–</span>
                                <label for="maxPrice" class="visually-hidden">Maximum price</label>
                                <input id="maxPrice" type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" class="form-control form-control-sm" min="0" step="50" placeholder="Max" inputmode="numeric">
                            </div>
                        </fieldset>

                        <div class="form-check form-switch mb-3 small">
                            <input type="checkbox" role="switch" name="in_stock" value="1" id="inStock" class="form-check-input" {{ ! empty($filters['in_stock']) ? 'checked' : '' }}>
                            <label for="inStock" class="form-check-label">In stock only</label>
                        </div>

                        <div class="d-grid gap-2">
                            <button class="btn btn-brand btn-sm">Apply filters</button>
                            <a href="{{ $formAction }}" class="btn btn-link btn-sm text-muted">Clear all</a>
                        </div>
                    </form>
                </div>
            </div>
        </aside>

        {{-- Results --}}
        <div class="col-lg-9">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                <div class="d-flex align-items-center gap-2">
                    <button class="btn btn-ghost btn-sm d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#shopFilters" aria-controls="shopFilters">
                        <i class="bi bi-sliders me-1"></i>Filters @if(count($active))<span class="badge bg-brand ms-1">{{ count($active) }}</span>@endif
                    </button>
                    <p class="small text-muted mb-0" aria-live="polite">
                        @if($products->total())
                            Showing {{ $products->firstItem() }}–{{ $products->lastItem() }} of {{ $products->total() }}
                        @else
                            No products
                        @endif
                    </p>
                </div>
                <form method="GET" action="{{ $formAction }}" class="d-flex align-items-center gap-2">
                    @foreach(['search', 'collection', 'occasion', 'min_price', 'max_price', 'in_stock'] as $k)
                        @if(isset($filters[$k]))<input type="hidden" name="{{ $k }}" value="{{ $filters[$k] }}">@endif
                    @endforeach
                    <label for="sortSelect" class="small text-muted text-nowrap">Sort by</label>
                    <select id="sortSelect" name="sort" class="form-select form-select-sm" data-autosubmit style="width:auto">
                        @foreach($sorts as $val => $label)
                            <option value="{{ $val }}" {{ ($filters['sort'] ?? 'latest') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <noscript><button class="btn btn-sm btn-ghost">Sort</button></noscript>
                </form>
            </div>

            @if(count($active))
                <div class="d-flex flex-wrap gap-2 mb-3" aria-label="Active filters">
                    @foreach($active as [$label, $key])
                        <a href="{{ request()->fullUrlWithoutQuery([$key, 'page']) }}" class="btn btn-sm btn-ghost">{{ $label }} <i class="bi bi-x-lg ms-1" aria-hidden="true"></i><span class="visually-hidden">(remove)</span></a>
                    @endforeach
                    <a href="{{ $formAction }}" class="btn btn-sm btn-link text-muted">Clear all</a>
                </div>
            @endif

            @if($products->count())
                <div class="row row-cols-2 row-cols-md-3 g-3 g-md-4">
                    @foreach($products as $product)
                        <div class="col"><x-product-card :product="$product" heading-level="h2" /></div>
                    @endforeach
                </div>
                <div class="mt-5 d-flex justify-content-center">{{ $products->onEachSide(1)->links() }}</div>
            @else
                <div class="surface p-5 text-center">
                    <i class="bi bi-search text-muted" style="font-size:2.5rem" aria-hidden="true"></i>
                    <h2 class="h4 mt-3">No matches found</h2>
                    <p class="text-muted">Try a different search term, widen the price range or browse another category.</p>
                    <div class="d-flex justify-content-center gap-2 flex-wrap">
                        @if(count($active))<a href="{{ $formAction }}" class="btn btn-outline-brand">Clear filters</a>@endif
                        <a href="{{ route('shop') }}" class="btn btn-brand">Browse all products</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .filter-cats li{margin-bottom:.3rem;}
    .filter-cats a{color:var(--ink);} .filter-cats a:hover{color:var(--terracotta);}
    .filter-cats a.active{color:var(--forest);font-weight:700;}
    .filter-cats ul a{color:var(--muted);}
    @media(min-width:992px){ #shopFilters{position:sticky;top:150px;} }
</style>
@endpush
