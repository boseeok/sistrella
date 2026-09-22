{{-- Reusable product card. Expects $product --}}
<div class="card product-card">
    <div class="position-relative">
        <a href="{{ route('products.show', $product->slug) }}">
            <img src="{{ $product->thumbnail }}" alt="{{ $product->name }}" class="pimg" loading="lazy">
        </a>
        @if($product->discount_percent > 0)
            <span class="badge badge-sale position-absolute top-0 start-0 m-2">-{{ $product->discount_percent }}%</span>
        @endif
        @if($product->isOnFlashSale())
            <span class="badge bg-danger position-absolute top-0 end-0 m-2"><i class="bi bi-lightning-fill"></i> Flash</span>
        @endif
        @unless($product->in_stock)
            <span class="badge bg-secondary position-absolute bottom-0 start-0 m-2">Out of stock</span>
        @endunless
    </div>
    <div class="card-body d-flex flex-column p-3">
        @if($product->category)
            <small class="text-muted text-uppercase" style="font-size:.7rem;letter-spacing:.5px">{{ $product->category->name }}</small>
        @endif
        <a href="{{ route('products.show', $product->slug) }}" class="text-dark fw-semibold mb-1 text-decoration-none" style="min-height:2.6em">{{ \Illuminate\Support\Str::limit($product->name, 48) }}</a>

        @if($product->rating_count > 0)
            <div class="rating small mb-1">
                @for($i = 1; $i <= 5; $i++)
                    <i class="bi {{ $i <= round($product->rating_avg) ? 'bi-star-fill' : 'bi-star' }}"></i>
                @endfor
                <span class="text-muted">({{ $product->rating_count }})</span>
            </div>
        @endif

        <div class="mt-auto">
            <div class="mb-2">
                <span class="price">{{ money($product->current_price) }}</span>
                @if($product->old_price)<span class="old-price ms-1">{{ money($product->old_price) }}</span>@endif
            </div>
            <div class="d-flex gap-2">
                @if($product->in_stock)
                    <form action="{{ route('cart.add') }}" method="POST" class="js-add-to-cart flex-grow-1">@csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <button class="btn btn-brand btn-sm w-100"><i class="bi bi-bag-plus me-1"></i>Add</button>
                    </form>
                @else
                    <a href="{{ route('products.show', $product->slug) }}" class="btn btn-outline-secondary btn-sm flex-grow-1">View</a>
                @endif
                @auth
                    <form action="{{ route('wishlist.toggle', $product) }}" method="POST">@csrf
                        <button class="btn btn-outline-brand btn-sm" title="Wishlist"><i class="bi bi-heart"></i></button>
                    </form>
                @endauth
            </div>
        </div>
    </div>
</div>
