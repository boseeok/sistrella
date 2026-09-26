@props(['product', 'headingLevel' => 'h3'])
{{-- Storefront product card: image, badges, wishlist, quick add, title, rating, price --}}
@php
    // One wishlist lookup per request, shared by every card on the page.
    $wishlisted = once(fn () => auth()->check()
        ? \App\Models\Wishlist::where('user_id', auth()->id())->pluck('product_id')->flip()
        : collect());
    $url = route('products.show', $product->slug);
    $isWished = $wishlisted->has($product->id);
@endphp
<article {{ $attributes->merge(['class' => 'pcard']) }}>
    <div class="pcard-media">
        <img src="{{ $product->thumbnail }}" alt="{{ $product->primaryImage?->alt ?: $product->name }}" loading="lazy" decoding="async" width="600" height="600">

        <div class="pcard-badges">
            @if(! $product->in_stock)
                <span class="pbadge pbadge-muted">Sold out</span>
            @elseif($product->discount_percent > 0)
                <span class="pbadge pbadge-sale">{{ $product->isOnFlashSale() ? 'Flash' : 'Sale' }} −{{ $product->discount_percent }}%</span>
            @endif
            @if($product->is_new_arrival)
                <span class="pbadge pbadge-new">New</span>
            @elseif($product->is_best_seller)
                <span class="pbadge">Bestseller</span>
            @endif
        </div>

        <div class="pcard-wish">
            @auth
                <form action="{{ route('wishlist.toggle', $product) }}" method="POST">@csrf
                    <button class="btn {{ $isWished ? 'active' : '' }}" aria-pressed="{{ $isWished ? 'true' : 'false' }}"
                            aria-label="{{ $isWished ? 'Remove '.$product->name.' from wishlist' : 'Save '.$product->name.' to wishlist' }}">
                        <i class="bi {{ $isWished ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn d-inline-flex align-items-center justify-content-center" aria-label="Log in to save {{ $product->name }}"><i class="bi bi-heart"></i></a>
            @endauth
        </div>

        @if($product->in_stock && $product->type !== 'variable')
            <form action="{{ route('cart.add') }}" method="POST" class="js-add-to-cart pcard-quick">@csrf
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button class="btn btn-brand btn-sm w-100"><i class="bi bi-bag-plus me-1"></i>Quick add</button>
            </form>
        @endif
    </div>

    <div class="pcard-body">
        @if($product->category)
            <span class="pcard-cat">{{ $product->category->name }}</span>
        @endif
        <{{ $headingLevel }} class="pcard-title"><a href="{{ $url }}">{{ $product->name }}</a></{{ $headingLevel }}>

        @if($product->rating_count > 0)
            <div class="rating small" aria-label="Rated {{ number_format($product->rating_avg, 1) }} out of 5">
                @for($i = 1; $i <= 5; $i++)<i class="bi {{ $i <= round($product->rating_avg) ? 'bi-star-fill' : 'bi-star' }}" aria-hidden="true"></i>@endfor
                <span class="text-muted ms-1">({{ $product->rating_count }})</span>
            </div>
        @endif

        <div class="pcard-foot">
            <x-price :product="$product" />
            @if($product->in_stock && $product->type !== 'variable')
                <form action="{{ route('cart.add') }}" method="POST" class="js-add-to-cart pcard-add d-md-none">@csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <button class="btn btn-outline-brand btn-icon btn-sm" style="width:34px;height:34px" aria-label="Add {{ $product->name }} to cart"><i class="bi bi-plus-lg"></i></button>
                </form>
            @endif
        </div>
    </div>
</article>
