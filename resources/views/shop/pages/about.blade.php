@extends('layouts.app')
@php
    // All content is editable in Admin › Page Content › About Page.
    $store    = setting('store_name', 'Sistrella');
    $title    = str_replace('{store}', $store, setting('about_title') ?: 'About {store}');
    $subtitle = str_replace('{store}', $store, setting('about_subtitle') ?: (string) setting('store_tagline', ''));
    $features = collect([1, 2, 3])->map(fn ($n) => [
        'icon'  => \Illuminate\Support\Str::after((string) setting("about_feature{$n}_icon"), 'bi-'), // tolerate legacy "bi-" values
        'title' => setting("about_feature{$n}_title"),
        'text'  => setting("about_feature{$n}_text"),
    ])->filter(fn ($f) => filled($f['title']));
@endphp
@section('title', $title)
@section('meta_description', \Illuminate\Support\Str::limit(trim(strip_tags(rich_text(setting('about_body')))), 155) ?: $subtitle)

@section('content')
<div class="container" style="max-width:880px">
    <div class="hero p-4 p-md-5 mb-4 text-center">
        <i class="bi bi-flower2 text-brand" style="font-size:3rem" aria-hidden="true"></i>
        <h1 class="section-title">{{ $title }}</h1>
        @if($subtitle)<p class="lead text-muted mb-0">{{ $subtitle }}</p>@endif
    </div>

    <div class="card p-4 p-md-5">
        <div class="rich-text">{!! rich_text(setting('about_body')) !!}</div>

        @if($features->isNotEmpty())
            @if(setting('about_features_title'))<h2 class="h5 fw-bold mt-4">{{ setting('about_features_title') }}</h2>@endif
            <div class="row g-3 mt-1">
                @foreach($features as $feature)
                    <div class="col-md-4">
                        <div class="card p-3 h-100 text-center">
                            <i class="bi bi-{{ $feature['icon'] }} text-brand fs-3" aria-hidden="true"></i>
                            <div class="fw-semibold mt-2">{{ $feature['title'] }}</div>
                            @if($feature['text'])<small class="text-muted">{{ $feature['text'] }}</small>@endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="text-center mt-4">
            <a href="{{ route('shop') }}" class="btn btn-brand">Explore Our Products</a>
            <a href="{{ route('contact') }}" class="btn btn-outline-brand">Contact Us</a>
        </div>
    </div>
</div>
@endsection
