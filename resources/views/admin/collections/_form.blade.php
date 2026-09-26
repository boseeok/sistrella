@php $c = $collection ?? null; @endphp
<div class="row g-3">
    <div class="col-lg-7">
        <div class="card p-4">
            <div class="row g-2">
                <div class="col-md-4 mb-2">
                    <label class="form-label small">Type *</label>
                    <select name="type" class="form-select">
                        @foreach(\App\Models\ProductCollection::TYPES as $key => $label)
                            <option value="{{ $key }}" @selected(old('type', $c->type ?? $defaultType ?? 'occasion') === $key)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-8 mb-2"><label class="form-label small">Name *</label><input type="text" name="name" value="{{ old('name', $c->name ?? '') }}" maxlength="120" class="form-control @error('name') is-invalid @enderror" required placeholder="e.g. Birthday"></div>
                <div class="col-md-6 mb-2">
                    <label class="form-label small">URL slug</label>
                    <div class="input-group"><span class="input-group-text small">/collections/</span><input type="text" name="slug" value="{{ old('slug', $c->slug ?? '') }}" class="form-control @error('slug') is-invalid @enderror" placeholder="auto from name"></div>
                    @error('slug')<div class="text-danger small">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-2">
                    <label class="form-label small">Icon</label>
                    <input type="text" name="icon" value="{{ old('icon', $c->icon ?? '') }}" class="form-control @error('icon') is-invalid @enderror" placeholder="gift">
                    @error('icon')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-3 mb-2"><label class="form-label small">Sort order</label><input type="number" name="sort_order" value="{{ old('sort_order', $c->sort_order ?? 0) }}" min="0" class="form-control"></div>
                <div class="col-12 mb-2"><label class="form-label small">Tagline</label><input type="text" name="tagline" value="{{ old('tagline', $c->tagline ?? '') }}" maxlength="160" class="form-control" placeholder="Shown under the title"></div>
                <div class="col-12 mb-2"><label class="form-label small">Description</label><textarea name="description" rows="3" maxlength="1000" class="form-control">{{ old('description', $c->description ?? '') }}</textarea></div>
                <div class="col-md-6 mb-2"><label class="form-label small">Meta title</label><input type="text" name="meta_title" value="{{ old('meta_title', $c->meta_title ?? '') }}" class="form-control"></div>
                <div class="col-md-6 mb-2"><label class="form-label small">Meta description</label><input type="text" name="meta_description" value="{{ old('meta_description', $c->meta_description ?? '') }}" class="form-control"></div>
                <div class="col-md-8 mb-2">
                    <label class="form-label small">Image</label>
                    <input type="file" name="image" accept="image/jpeg,image/png,image/webp" class="form-control @error('image') is-invalid @enderror">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if($c?->image_url)<img src="{{ $c->image_url }}" alt="" width="120" class="rounded mt-2" style="aspect-ratio:4/3;object-fit:cover">@endif
                </div>
                <div class="col-md-4 mb-2 pt-4">
                    <div class="form-check"><input type="checkbox" name="is_active" value="1" id="colActive" class="form-check-input" @checked(old('is_active', $c->is_active ?? true))><label for="colActive" class="form-check-label small">Active</label></div>
                    <div class="form-check"><input type="checkbox" name="is_featured" value="1" id="colFeatured" class="form-check-input" @checked(old('is_featured', $c->is_featured ?? true))><label for="colFeatured" class="form-check-label small">Show on home page</label></div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card p-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <label class="form-label small mb-0">Products <span class="text-muted">(<span data-selected-count>{{ count(old('product_ids', $selected)) }}</span> selected)</span></label>
                <input type="search" class="form-control form-control-sm" style="max-width:180px" placeholder="Filter…" data-product-filter>
            </div>
            <div class="border rounded p-2" style="max-height:420px;overflow:auto">
                @php $picked = array_map('intval', old('product_ids', $selected)); @endphp
                @foreach($products as $p)
                    <div class="form-check small" data-product-row="{{ strtolower($p->name.' '.($p->category->name ?? '')) }}">
                        <input class="form-check-input" type="checkbox" name="product_ids[]" value="{{ $p->id }}" id="cp{{ $p->id }}" @checked(in_array($p->id, $picked))>
                        <label class="form-check-label" for="cp{{ $p->id }}">{{ $p->name }} <span class="text-muted">· {{ $p->category->name ?? 'Uncategorised' }}</span>@unless($p->is_active) <span class="badge bg-secondary">hidden</span>@endunless</label>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <button class="btn btn-brand"><i class="bi bi-check-lg me-1"></i>{{ $c ? 'Save Changes' : 'Create' }}</button>
    <a href="{{ route('admin.collections.index') }}" class="btn btn-light">Cancel</a>
</div>

@push('scripts')
<script>
(function () {
    const filter = document.querySelector('[data-product-filter]');
    const count = document.querySelector('[data-selected-count]');
    filter?.addEventListener('input', () => {
        const q = filter.value.trim().toLowerCase();
        document.querySelectorAll('[data-product-row]').forEach(r => r.style.display = r.dataset.productRow.includes(q) ? '' : 'none');
    });
    document.addEventListener('change', e => {
        if (e.target.name === 'product_ids[]' && count) count.textContent = document.querySelectorAll('input[name="product_ids[]"]:checked').length;
    });
})();
</script>
@endpush
