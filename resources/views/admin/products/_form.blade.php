@php $p = $product ?? null; @endphp
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Details</h6>
            <div class="mb-2"><label class="form-label small">Name *</label><input type="text" name="name" value="{{ old('name', $p->name ?? '') }}" class="form-control" required></div>
            <div class="row g-2">
                <div class="col-md-6"><label class="form-label small">SKU</label><input type="text" name="sku" value="{{ old('sku', $p->sku ?? '') }}" class="form-control" placeholder="Auto-generated if blank"></div>
                <div class="col-md-6">
                    <label class="form-label small">Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">— None —</option>
                        @foreach($categories->sortBy('path_name') as $c)<option value="{{ $c->id }}" {{ old('category_id', $p->category_id ?? '')==$c->id ? 'selected':'' }}>{{ $c->path_name }}</option>@endforeach
                    </select>
                </div>
            </div>
            <div class="mt-2"><label class="form-label small">Short description</label><textarea name="short_description" rows="2" class="form-control">{{ old('short_description', $p->short_description ?? '') }}</textarea></div>
            <div class="mt-2"><label class="form-label small">Full description (HTML allowed)</label><textarea name="description" rows="6" class="form-control">{{ old('description', $p->description ?? '') }}</textarea></div>
        </div>

        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Pricing</h6>
            <div class="row g-2">
                <div class="col-md-4"><label class="form-label small">Price *</label><input type="number" step="0.01" name="price" value="{{ old('price', $p->price ?? '') }}" class="form-control" required></div>
                <div class="col-md-4"><label class="form-label small">Compare-at price</label><input type="number" step="0.01" name="compare_at_price" value="{{ old('compare_at_price', $p->compare_at_price ?? '') }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label small">Cost price</label><input type="number" step="0.01" name="cost_price" value="{{ old('cost_price', $p->cost_price ?? '') }}" class="form-control"></div>
            </div>
            <hr>
            <h6 class="fw-bold mb-2">Flash Sale</h6>
            <div class="row g-2">
                <div class="col-md-4"><label class="form-label small">Sale price</label><input type="number" step="0.01" name="flash_sale_price" value="{{ old('flash_sale_price', $p->flash_sale_price ?? '') }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label small">Starts</label><input type="datetime-local" name="flash_sale_starts_at" value="{{ old('flash_sale_starts_at', optional($p->flash_sale_starts_at ?? null)->format('Y-m-d\TH:i')) }}" class="form-control"></div>
                <div class="col-md-4"><label class="form-label small">Ends</label><input type="datetime-local" name="flash_sale_ends_at" value="{{ old('flash_sale_ends_at', optional($p->flash_sale_ends_at ?? null)->format('Y-m-d\TH:i')) }}" class="form-control"></div>
            </div>
        </div>

        <div class="card p-3">
            <h6 class="fw-bold mb-3">Images</h6>
            @if($p && $p->images->count())
                {{-- Buttons target standalone forms rendered after the main form (see edit.blade.php) --}}
                <div class="d-flex gap-3 flex-wrap mb-3">
                    @foreach($p->images->sortBy('sort_order') as $img)
                        <div class="text-center" style="width:96px">
                            <img src="{{ $img->url }}" alt="{{ $img->alt ?: $p->name }}" width="96" height="96" class="rounded border {{ $img->is_primary ? 'border-3 border-success' : '' }}" style="object-fit:cover">
                            <div class="d-flex justify-content-center gap-1 mt-1">
                                @if($img->is_primary)
                                    <span class="badge bg-success-subtle text-success">Primary</span>
                                @else
                                    <button type="submit" form="img-primary-{{ $img->id }}" class="btn btn-sm btn-light py-0 px-1" title="Make primary"><i class="bi bi-star"></i></button>
                                @endif
                                <button type="submit" form="img-delete-{{ $img->id }}" class="btn btn-sm btn-light text-danger py-0 px-1" title="Delete image" onclick="return confirm('Delete this image?')"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
            <input type="file" name="images[]" accept="image/jpeg,image/png,image/webp,image/gif" multiple class="form-control @error('images.*') is-invalid @enderror">
            @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
            <small class="text-muted">JPG, PNG, WebP or GIF, max 4 MB each. The first image becomes primary if none is set.</small>
        </div>

        {{-- Colour / size variants (App\Services\ProductVariantService) --}}
        @php
            $variantRows = old('variants', $p ? $p->variants->map(fn ($v) => [
                'id' => $v->id, 'color' => $v->valueFor('color')?->value, 'color_code' => $v->valueFor('color')?->color_code,
                'size' => $v->valueFor('size')?->value, 'price' => $v->price, 'stock' => $v->stock, 'sku' => $v->sku, 'is_active' => $v->is_active,
            ])->all() : []);
        @endphp
        <div class="card p-3 mt-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">Variants <span class="text-muted fw-normal small">colour / size</span></h6>
                <button type="button" class="btn btn-sm btn-outline-brand" data-add-variant><i class="bi bi-plus-lg me-1"></i>Add variant</button>
            </div>
            <p class="small text-muted">Optional. With active variants the product becomes “variable”: customers must pick an option and stock is tracked per variant. Leave price empty to use the product price.</p>
            <input type="hidden" name="variants_present" value="1">
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0" data-variant-table>
                    <thead class="table-light small"><tr><th>Colour</th><th style="width:56px">Swatch</th><th>Size</th><th style="width:110px">Price</th><th style="width:90px">Stock</th><th>SKU</th><th class="text-center">Active</th><th></th></tr></thead>
                    <tbody>
                        @foreach($variantRows as $i => $row)
                            @include('admin.products._variant-row', ['i' => $i, 'row' => $row])
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if($errors->has('variants.*'))<div class="text-danger small mt-2">{{ collect($errors->get('variants.*'))->flatten()->first() }}</div>@endif
            <template data-variant-template>@include('admin.products._variant-row', ['i' => '__I__', 'row' => ['is_active' => true, 'color_code' => '#e8a0b4']])</template>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Inventory</h6>
            <div class="form-check mb-2"><input type="checkbox" name="track_inventory" value="1" id="ti" class="form-check-input" {{ old('track_inventory', $p->track_inventory ?? true) ? 'checked':'' }}><label for="ti" class="form-check-label small">Track inventory</label></div>
            <div class="mb-2"><label class="form-label small">Stock *</label><input type="number" name="stock" value="{{ old('stock', $p->stock ?? 0) }}" class="form-control" required></div>
            <div class="mb-2"><label class="form-label small">Low stock threshold *</label><input type="number" name="low_stock_threshold" value="{{ old('low_stock_threshold', $p->low_stock_threshold ?? 5) }}" class="form-control" required></div>
            <div class="mb-2"><label class="form-label small">Weight (g)</label><input type="number" step="0.01" name="weight" value="{{ old('weight', $p->weight ?? '') }}" class="form-control"></div>
            <div><label class="form-label small">Type *</label>
                <select name="type" class="form-select">
                    @foreach(['simple'=>'Simple','variable'=>'Variable','bundle'=>'Bundle','custom'=>'Custom'] as $k=>$v)
                        <option value="{{ $k }}" {{ old('type', $p->type ?? 'simple')===$k ? 'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Visibility & Flags</h6>
            @foreach(['is_active'=>'Active','is_featured'=>'Featured','is_trending'=>'Trending','is_best_seller'=>'Best Seller','is_new_arrival'=>'New Arrival','is_customizable'=>'Customizable'] as $field=>$label)
                <div class="form-check"><input type="checkbox" name="{{ $field }}" value="1" id="{{ $field }}" class="form-check-input" {{ old($field, $p->$field ?? ($field==='is_active')) ? 'checked':'' }}><label for="{{ $field }}" class="form-check-label small">{{ $label }}</label></div>
            @endforeach
        </div>

        <div class="card p-3 mb-3">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h6 class="fw-bold mb-0">Occasions &amp; Collections</h6>
                <a href="{{ route('admin.collections.index') }}" class="small" target="_blank">Manage</a>
            </div>
            <input type="hidden" name="collections_present" value="1">
            @php $pickedCollections = array_map('intval', old('collection_ids', $p ? $p->collections->pluck('id')->all() : [])); @endphp
            @forelse(($collections ?? collect())->groupBy('type') as $type => $group)
                <div class="small text-muted mt-1">{{ \App\Models\ProductCollection::TYPES[$type] ?? $type }}s</div>
                <div class="d-flex flex-wrap gap-1 mb-2">
                    @foreach($group as $col)
                        <input type="checkbox" class="btn-check" name="collection_ids[]" value="{{ $col->id }}" id="col{{ $col->id }}" autocomplete="off" @checked(in_array($col->id, $pickedCollections))>
                        <label class="btn btn-sm btn-outline-secondary py-0" for="col{{ $col->id }}">@if($col->icon)<i class="bi bi-{{ $col->icon }} me-1"></i>@endif{{ $col->name }}</label>
                    @endforeach
                </div>
            @empty
                <p class="small text-muted mb-0">No occasions yet. <a href="{{ route('admin.collections.create') }}">Create one</a>.</p>
            @endforelse
        </div>

        <div class="card p-3">
            <h6 class="fw-bold mb-3">SEO</h6>
            <div class="mb-2"><label class="form-label small">Meta title</label><input type="text" name="meta_title" value="{{ old('meta_title', $p->meta_title ?? '') }}" class="form-control"></div>
            <div><label class="form-label small">Meta description</label><textarea name="meta_description" rows="2" class="form-control">{{ old('meta_description', $p->meta_description ?? '') }}</textarea></div>
        </div>
    </div>
</div>

<div class="d-flex gap-2 mt-3">
    <button class="btn btn-brand"><i class="bi bi-check-lg me-1"></i>{{ $p ? 'Save Changes' : 'Create Product' }}</button>
    <a href="{{ route('admin.products.index') }}" class="btn btn-light">Cancel</a>
</div>

@push('scripts')
<script>
// Variant rows: add from <template>, remove (existing rows are flagged for deletion, new ones dropped).
(function () {
    const table = document.querySelector('[data-variant-table] tbody');
    const tpl = document.querySelector('[data-variant-template]');
    if (!table || !tpl) return;
    let next = Date.now();
    document.querySelector('[data-add-variant]')?.addEventListener('click', () => {
        table.insertAdjacentHTML('beforeend', tpl.innerHTML.replaceAll('__I__', 'n' + (next++)));
        table.lastElementChild.querySelector('input[type=text]')?.focus();
    });
    table.addEventListener('click', e => {
        const btn = e.target.closest('[data-remove-variant]');
        if (!btn) return;
        const row = btn.closest('[data-variant-row]');
        if (row.querySelector('input[name$="[id]"]')) {
            row.querySelector('[data-delete-flag]').value = '1';
            row.style.display = 'none';
        } else {
            row.remove();
        }
    });
})();
</script>
@endpush
