@props(['products', 'title', 'id', 'eyebrow' => null, 'link' => null])
{{-- Horizontally scrollable product row (CSS scroll-snap, no carousel library) --}}
@if($products->isNotEmpty())
    <section {{ $attributes->merge(['class' => 'section']) }} aria-labelledby="{{ $id }}-title">
        <x-section-header :title="$title" :eyebrow="$eyebrow" :link="$link" :rail="$id" heading-id="{{ $id }}-title" />
        <div class="rail" id="{{ $id }}" tabindex="0" aria-label="{{ $title }}">
            @foreach($products as $product)
                <x-product-card :product="$product" />
            @endforeach
        </div>
    </section>
@endif
