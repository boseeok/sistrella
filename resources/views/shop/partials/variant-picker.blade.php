{{--
    Colour swatches + size buttons for a product's active variants.
    Picks the matching variant into the hidden variant_id, and updates the
    price (#pdPrice), stock line (#pdStock) and quantity limit client-side.
    The server re-validates the variant (CartController@add).
--}}
@php
    $variants = $product->variants->where('is_active', true)->values();
    $optionsFor = fn (string $slug) => $variants->map(fn ($v) => $v->valueFor($slug))->filter()->unique('id')->values();
    $colors = $optionsFor('color');
    $sizes  = $optionsFor('size');
    $track  = $product->track_inventory;

    $map = $variants->map(fn ($v) => [
        'id'      => $v->id,
        'color'   => $v->valueFor('color')?->id,
        'size'    => $v->valueFor('size')?->id,
        'label'   => $v->label,
        'price'   => money($v->effective_price),
        'stock'   => $track ? (int) $v->stock : null,
        'inStock' => $v->in_stock,
    ])->values();
    $default = $variants->first(fn ($v) => $v->in_stock) ?? $variants->first();
@endphp

<div class="variant-picker mb-3" data-variants='@json($map)' data-low="{{ $product->low_stock_threshold }}">
    <input type="hidden" name="variant_id" value="{{ $default?->id }}">

    @if($colors->isNotEmpty())
        <fieldset class="mb-3">
            <legend class="form-label mb-2">Colour: <span class="fw-normal text-muted" data-selected-label="color">{{ $default?->valueFor('color')?->value }}</span></legend>
            <div class="d-flex flex-wrap gap-2">
                @foreach($colors as $c)
                    <label class="swatch" title="{{ $c->value }}">
                        <input type="radio" name="opt_color" value="{{ $c->id }}" data-axis="color" data-name="{{ $c->value }}" class="visually-hidden" @checked($default?->valueFor('color')?->id === $c->id)>
                        <span class="swatch-dot" style="background: {{ $c->color_code ?: '#d9d4c7' }}"></span>
                        <span class="visually-hidden">{{ $c->value }}</span>
                    </label>
                @endforeach
            </div>
        </fieldset>
    @endif

    @if($sizes->isNotEmpty())
        <fieldset class="mb-2">
            <legend class="form-label mb-2">Size: <span class="fw-normal text-muted" data-selected-label="size">{{ $default?->valueFor('size')?->value }}</span></legend>
            <div class="d-flex flex-wrap gap-2">
                @foreach($sizes as $s)
                    <label class="size-chip">
                        <input type="radio" name="opt_size" value="{{ $s->id }}" data-axis="size" data-name="{{ $s->value }}" class="visually-hidden" @checked($default?->valueFor('size')?->id === $s->id)>
                        <span>{{ $s->value }}</span>
                    </label>
                @endforeach
            </div>
        </fieldset>
    @endif
</div>

@once
@push('styles')
<style>
    .swatch{cursor:pointer;display:inline-flex;}
    .swatch-dot{width:34px;height:34px;border-radius:50%;border:2px solid #fff;box-shadow:0 0 0 1px var(--line);display:block;transition:box-shadow .15s;}
    .swatch input:checked + .swatch-dot{box-shadow:0 0 0 2px var(--forest);}
    .swatch input:focus-visible + .swatch-dot{outline:3px solid rgba(156,85,48,.45);outline-offset:3px;}
    .size-chip{cursor:pointer;}
    .size-chip span{display:inline-block;min-width:48px;text-align:center;padding:.45rem .8rem;border:1px solid var(--line);border-radius:999px;background:#fff;font-weight:600;font-size:.85rem;}
    .size-chip input:checked + span{border-color:var(--forest);background:var(--forest);color:#fff;}
    .size-chip input:focus-visible + span{outline:3px solid rgba(156,85,48,.45);outline-offset:2px;}
    .variant-picker .unavailable{opacity:.4;text-decoration:line-through;}
</style>
@endpush
@push('scripts')
<script>
(function () {
    const picker = document.querySelector('.variant-picker');
    if (!picker) return;
    const variants = JSON.parse(picker.dataset.variants);
    const low = +picker.dataset.low || 0;
    const hidden = picker.querySelector('input[name=variant_id]');
    const form = picker.closest('form');
    const qty = form.querySelector('input[name=quantity]');
    const buttons = [...form.querySelectorAll('button:not([type=button])'), ...document.querySelectorAll('button[form="' + form.id + '"]')];
    const axes = [...new Set([...picker.querySelectorAll('[data-axis]')].map(i => i.dataset.axis))];
    const selected = axis => picker.querySelector('input[data-axis="' + axis + '"]:checked')?.value;
    const priceBox = document.getElementById('pdPrice');
    const stockBox = document.getElementById('pdStock');
    const originalPrice = priceBox ? priceBox.innerHTML : '';
    const esc = s => String(s).replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));

    function match() {
        return variants.find(v => axes.every(a => String(v[a]) === selected(a)));
    }

    function update() {
        axes.forEach(a => {
            const label = picker.querySelector('[data-selected-label="' + a + '"]');
            const input = picker.querySelector('input[data-axis="' + a + '"]:checked');
            if (label) label.textContent = input ? input.dataset.name : '';
        });

        // Grey out options with no in-stock variant given the other selections.
        picker.querySelectorAll('input[data-axis]').forEach(input => {
            const a = input.dataset.axis;
            const ok = variants.some(v => String(v[a]) === input.value && v.inStock &&
                axes.every(o => o === a || !selected(o) || String(v[o]) === selected(o)));
            input.parentElement.classList.toggle('unavailable', !ok);
        });

        const v = match();
        hidden.value = v ? v.id : '';
        const buyable = !!(v && v.inStock);
        buttons.forEach(b => b.disabled = !buyable);

        if (priceBox) {
            priceBox.innerHTML = v ? '<span class="price fs-3">' + v.price + '</span>' : originalPrice;
        }
        // The product-level "You save" line doesn't apply to an individual variant's price.
        const save = document.getElementById('pdSave');
        if (save) save.hidden = !!v;
        if (stockBox) {
            let html;
            if (!v) html = '<span class="text-muted">This combination isn’t available, try another option.</span>';
            else if (!v.inStock) html = '<span class="text-danger"><i class="bi bi-x-circle me-1"></i>' + esc(v.label) + ' is sold out</span>';
            else if (v.stock !== null && v.stock <= low) html = '<span class="text-accent"><i class="bi bi-hourglass-split me-1"></i>Only ' + v.stock + ' left in ' + esc(v.label) + '</span>';
            else html = '<span class="text-success"><i class="bi bi-check-circle me-1"></i>In stock, ready to ship</span>';
            stockBox.innerHTML = html;
        }
        if (qty) {
            qty.max = v && v.stock !== null ? Math.max(1, Math.min(999, v.stock)) : 999;
            if (+qty.value > +qty.max) qty.value = qty.max;
        }
    }

    picker.addEventListener('change', update);
    update();
})();
</script>
@endpush
@endonce
