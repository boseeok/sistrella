@extends('layouts.admin')
@section('title', 'Page Content')
@section('heading', 'Page Content')

@php $s = fn($k) => old($k, setting($k)); @endphp

@section('content')
<ul class="nav nav-tabs mb-3">
    <li class="nav-item"><a class="nav-link {{ $tab === 'home' ? 'active' : '' }}" href="{{ route('admin.homepage.edit') }}"><i class="bi bi-house-heart me-1"></i>Home: Custom Orders</a></li>
    <li class="nav-item"><a class="nav-link {{ $tab === 'about' ? 'active' : '' }}" href="{{ route('admin.homepage.edit', ['tab' => 'about']) }}"><i class="bi bi-info-circle me-1"></i>About Page</a></li>
</ul>

@if($tab === 'home')
<form action="{{ route('admin.homepage.update') }}" method="POST" enctype="multipart/form-data">@csrf @method('PUT')
    <div class="card p-4">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h6 class="fw-bold mb-0">Custom Orders Section</h6>
            <a href="{{ route('home') }}#customTitle" target="_blank" class="small">View on store <i class="bi bi-box-arrow-up-right"></i></a>
        </div>
        <p class="small text-muted mb-3">The "Made just for you" block shown on the store home page, below New arrivals.</p>

        <div class="row g-3">
            <div class="col-lg-7">
                <div class="row g-2">
                    <div class="col-md-5">
                        <label class="form-label small">Small heading</label>
                        <input type="text" name="promo_eyebrow" value="{{ $s('promo_eyebrow') }}" maxlength="60" class="form-control @error('promo_eyebrow') is-invalid @enderror">
                    </div>
                    <div class="col-md-7">
                        <label class="form-label small">Title *</label>
                        <input type="text" name="promo_title" value="{{ $s('promo_title') }}" maxlength="120" class="form-control @error('promo_title') is-invalid @enderror" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label small">Description</label>
                        <x-rich-editor name="promo_text" :value="setting('promo_text')" min-height="160px" placeholder="Describe your custom-order service…" />
                    </div>
                    <div class="col-12">
                        <label class="form-label small">Bullet points <span class="text-muted">(one per line)</span></label>
                        <textarea name="promo_points" rows="3" maxlength="600" class="form-control @error('promo_points') is-invalid @enderror">{{ $s('promo_points') }}</textarea>
                    </div>
                    <div class="col-md-5">
                        <label class="form-label small">Button text *</label>
                        <input type="text" name="promo_button_text" value="{{ $s('promo_button_text') }}" maxlength="60" class="form-control @error('promo_button_text') is-invalid @enderror" required>
                    </div>
                    <div class="col-md-7">
                        <label class="form-label small">Button link *</label>
                        <input type="text" name="promo_button_link" value="{{ $s('promo_button_link') }}" class="form-control @error('promo_button_link') is-invalid @enderror" placeholder="/custom-order" required>
                        @error('promo_button_link')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <label class="form-label small">Image</label>
                @php $promoImage = setting('promo_image'); @endphp
                @if($promoImage)
                    <img src="{{ asset('storage/'.$promoImage) }}" alt="Current section image" class="d-block rounded border mb-2" style="width:100%;max-height:200px;object-fit:cover">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="promo_image_remove" value="1" id="promoImageRemove">
                        <label class="form-check-label small" for="promoImageRemove">Remove image (use the hero slider photo instead)</label>
                    </div>
                @else
                    <p class="small text-muted mb-2">No custom image: the section uses the photo from your second hero banner.</p>
                @endif
                <input type="file" name="promo_image" accept="image/jpeg,image/png,image/webp" class="form-control @error('promo_image') is-invalid @enderror">
                @error('promo_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                <small class="text-muted">JPG, PNG or WebP, max 4 MB. Landscape photos work best.</small>
            </div>
        </div>
    </div>

    <div class="mt-3"><button class="btn btn-brand"><i class="bi bi-check-lg me-1"></i>Save Home Section</button></div>
</form>
@else
<form action="{{ route('admin.homepage.about') }}" method="POST">@csrf @method('PUT')
    <div class="card p-4 mb-3">
        <div class="d-flex justify-content-between align-items-center mb-1">
            <h6 class="fw-bold mb-0">Page Header &amp; Story</h6>
            <a href="{{ route('about') }}" target="_blank" class="small">View on store <i class="bi bi-box-arrow-up-right"></i></a>
        </div>
        <p class="small text-muted mb-3">Type <code>{store}</code> anywhere to insert your store name ({{ setting('store_name') }}).</p>
        <div class="row g-2">
            <div class="col-md-6">
                <label class="form-label small">Page title</label>
                <input type="text" name="about_title" value="{{ $s('about_title') }}" maxlength="120" class="form-control @error('about_title') is-invalid @enderror" placeholder="About {{ setting('store_name') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label small">Subtitle</label>
                <input type="text" name="about_subtitle" value="{{ $s('about_subtitle') }}" maxlength="255" class="form-control @error('about_subtitle') is-invalid @enderror" placeholder="{{ setting('store_tagline') }}">
            </div>
            <div class="col-12">
                <label class="form-label small">Page content</label>
                <x-rich-editor name="about_body" :value="setting('about_body')" min-height="280px" placeholder="Tell customers your story…" />
            </div>
        </div>
    </div>

    <div class="card p-4">
        <h6 class="fw-bold mb-3">Feature Cards</h6>
        <div class="mb-3" style="max-width:420px">
            <label class="form-label small">Section heading</label>
            <input type="text" name="about_features_title" value="{{ $s('about_features_title') }}" maxlength="120" class="form-control">
        </div>
        <div class="row g-3">
            @foreach([1, 2, 3] as $n)
                <div class="col-lg-4">
                    <div class="border rounded p-3 h-100">
                        <div class="small fw-semibold mb-2">Card {{ $n }}</div>
                        <label class="form-label small">Icon</label>
                        <select name="about_feature{{ $n }}_icon" class="form-select form-select-sm mb-2 @error('about_feature'.$n.'_icon') is-invalid @enderror">
                            @foreach($icons as $icon => $label)
                                <option value="{{ $icon }}" @selected(\Illuminate\Support\Str::after((string) $s('about_feature'.$n.'_icon'), 'bi-') === $icon)>{{ $label }}</option>
                            @endforeach
                        </select>
                        <label class="form-label small">Title</label>
                        <input type="text" name="about_feature{{ $n }}_title" value="{{ $s('about_feature'.$n.'_title') }}" maxlength="60" class="form-control form-control-sm mb-2">
                        <label class="form-label small">Text</label>
                        <textarea name="about_feature{{ $n }}_text" rows="2" maxlength="160" class="form-control form-control-sm">{{ $s('about_feature'.$n.'_text') }}</textarea>
                    </div>
                </div>
            @endforeach
        </div>
        <p class="small text-muted mt-2 mb-0">Leave a card's title empty to hide it.</p>
    </div>

    <div class="mt-3"><button class="btn btn-brand"><i class="bi bi-check-lg me-1"></i>Save About Page</button></div>
</form>
@endif
@endsection
