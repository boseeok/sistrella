@props(['title', 'eyebrow' => null, 'link' => null, 'linkText' => 'View all', 'rail' => null, 'headingId' => null])
{{-- Section heading with optional eyebrow, "view all" link and rail scroll buttons --}}
<div {{ $attributes->merge(['class' => 'section-head']) }}>
    <div>
        @if($eyebrow)<div class="eyebrow mb-1">{{ $eyebrow }}</div>@endif
        <h2 @if($headingId) id="{{ $headingId }}" @endif>{{ $title }}</h2>
    </div>
    <div class="d-flex align-items-center gap-2">
        @if($link)<a href="{{ $link }}" class="link-arrow small">{{ $linkText }} <i class="bi bi-arrow-right" aria-hidden="true"></i></a>@endif
        @if($rail)
            <button type="button" class="btn btn-ghost btn-icon d-none d-md-inline-flex" data-rail-prev="{{ $rail }}" aria-label="Scroll {{ $title }} left"><i class="bi bi-chevron-left"></i></button>
            <button type="button" class="btn btn-ghost btn-icon d-none d-md-inline-flex" data-rail-next="{{ $rail }}" aria-label="Scroll {{ $title }} right"><i class="bi bi-chevron-right"></i></button>
        @endif
    </div>
</div>
