@props(['product', 'size' => null])
{{-- Current price, struck-through original when discounted --}}
@php $onSale = $product->discount_percent > 0; @endphp
<div {{ $attributes->merge(['class' => 'd-flex flex-wrap align-items-baseline gap-2']) }}>
    <span class="price {{ $onSale ? 'price-sale' : '' }} {{ $size }}">
        @if($onSale)<span class="visually-hidden">Sale price</span>@endif{{ money($product->current_price) }}
    </span>
    @if($onSale)
        <span class="old-price"><span class="visually-hidden">Original price</span>{{ money($product->old_price) }}</span>
    @endif
</div>
