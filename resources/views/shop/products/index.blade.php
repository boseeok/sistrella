@extends('layouts.app')
@section('title', isset($category) ? $category->name : 'Shop')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
            <li class="breadcrumb-item active">{{ $category->name ?? 'Shop' }}</li>
        </ol>
    </nav>

    <div class="row g-4">
        {{-- Sidebar filters --}}
        <div class="col-lg-3">
            <div class="card p-3 mb-3">
                <h6 class="fw-bold mb-3">Categories</h6>
                <ul class="list-unstyled small mb-0">
                    <li class="mb-1"><a href="{{ route('shop') }}" class="{{ !request('category_id') && !isset($category) ? 'fw-bold text-brand' : 'text-dark' }}">All Products</a></li>
                    @foreach($categories as $cat)
                        <li class="mb-1">
                            <a href="{{ route('categories.show', $cat->slug) }}" class="{{ (isset($category) && $category->id === $cat->id) ? 'fw-bold text-brand' : 'text-dark' }}">{{ $cat->name }}</a>
                            @if($cat->children->count())
                                <ul class="list-unstyled ms-3 mt-1">
                                    @foreach($cat->children as $child)
                                        <li><a href="{{ route('categories.show', $child->slug) }}" class="text-muted">{{ $child->name }}</a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="card p-3">
                <h6 class="fw-bold mb-3">Filter</h6>
                <form action="{{ isset($category) ? route('categories.show', $category->slug) : route('shop') }}" method="GET">
                    @if(request('search'))<input type="hidden" name="search" value="{{ request('search') }}">@endif
                    <div class="mb-2">
                        <label class="form-label small">Min price</label>
                        <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" class="form-control form-control-sm" min="0">
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">Max price</label>
                        <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" class="form-control form-control-sm" min="0">
                    </div>
                    <div class="form-check mb-3">
                        <input type="checkbox" name="in_stock" value="1" id="inStock" class="form-check-input" {{ !empty($filters['in_stock']) ? 'checked' : '' }}>
                        <label for="inStock" class="form-check-label small">In stock only</label>
                    </div>
                    <button class="btn btn-brand btn-sm w-100">Apply</button>
                </form>
            </div>
        </div>

        {{-- Product grid --}}
        <div class="col-lg-9">
            <div class="d-flex flex-wrap justify-content-between align-items-center mb-3">
                <div>
                    <h4 class="section-title mb-0">{{ $category->name ?? (request('search') ? 'Results for "'.request('search').'"' : 'All Products') }}</h4>
                    <small class="text-muted">{{ $products->total() }} item(s)</small>
                </div>
                <form method="GET" class="d-flex align-items-center gap-2">
                    @foreach(($filters ?? []) as $k => $v)
                        @if($k !== 'sort' && $v !== null && $k !== 'category_id')<input type="hidden" name="{{ $k }}" value="{{ $v }}">@endif
                    @endforeach
                    <label class="small text-muted">Sort</label>
                    <select name="sort" class="form-select form-select-sm" onchange="this.form.submit()" style="width:auto">
                        @foreach(['latest'=>'Newest','price_asc'=>'Price: Low to High','price_desc'=>'Price: High to Low','popular'=>'Most Popular','rating'=>'Top Rated','name'=>'Name'] as $val=>$label)
                            <option value="{{ $val }}" {{ ($filters['sort'] ?? 'latest') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

            @if($products->count())
                <div class="row g-3 g-md-4">
                    @foreach($products as $product)
                        <div class="col-6 col-md-4">@include('partials.product-card')</div>
                    @endforeach
                </div>
                <div class="mt-4">{{ $products->links() }}</div>
            @else
                <div class="card p-5 text-center">
                    <i class="bi bi-search text-muted" style="font-size:2.5rem"></i>
                    <p class="mt-3 mb-0 text-muted">No products found. Try adjusting your filters.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
