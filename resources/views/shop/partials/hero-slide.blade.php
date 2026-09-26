{{-- One hero slide. $hero is a Banner or null (fallback copy); $heading is h1 for the first slide only --}}
<div class="hero-slide">
    <div class="hero-copy">
        <span class="eyebrow mb-3">Handmade in Nepal</span>
        <{{ $heading }} class="hero-title">{{ $hero->title ?? 'Handmade gifts, made with love' }}</{{ $heading }}>
        <p>{{ $hero->subtitle ?? 'Crochet friends, satin ribbon bouquets and fuzzy wire blooms — each made by hand, for every occasion.' }}</p>
        <div class="d-flex flex-wrap gap-2 mt-2">
            <a href="{{ $hero?->link ?: route('shop') }}" class="btn btn-brand btn-lg">{{ $hero?->button_text ?: 'Shop the collection' }}</a>
            <a href="{{ route('custom.create') }}" class="btn btn-ghost btn-lg">Customize with us</a>
        </div>
    </div>
    <div class="hero-media">
        <img src="{{ $hero?->image_url ?? asset('images/hero-crochet.jpg') }}" alt="{{ $hero->title ?? 'Handmade crochet pieces' }}" width="900" height="700" @unless($heading === 'h1') loading="lazy" @endunless fetchpriority="{{ $heading === 'h1' ? 'high' : 'auto' }}">
    </div>
</div>
