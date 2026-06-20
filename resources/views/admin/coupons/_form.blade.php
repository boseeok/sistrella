@php $c = $coupon ?? null; @endphp
<div class="card p-4" style="max-width:680px">
    <div class="row g-2">
        <div class="col-md-6 mb-3"><label class="form-label small">Code *</label><input type="text" name="code" value="{{ old('code', $c->code ?? '') }}" class="form-control text-uppercase" required></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Type *</label>
            <select name="type" class="form-select">
                <option value="percent" {{ old('type', $c->type ?? '')==='percent' ? 'selected':'' }}>Percent (%)</option>
                <option value="fixed" {{ old('type', $c->type ?? '')==='fixed' ? 'selected':'' }}>Fixed amount</option>
            </select>
        </div>
    </div>
    <div class="mb-3"><label class="form-label small">Description</label><input type="text" name="description" value="{{ old('description', $c->description ?? '') }}" class="form-control"></div>
    <div class="row g-2">
        <div class="col-md-4 mb-3"><label class="form-label small">Value *</label><input type="number" step="0.01" name="value" value="{{ old('value', $c->value ?? '') }}" class="form-control" required></div>
        <div class="col-md-4 mb-3"><label class="form-label small">Min order amount</label><input type="number" step="0.01" name="min_order_amount" value="{{ old('min_order_amount', $c->min_order_amount ?? '') }}" class="form-control"></div>
        <div class="col-md-4 mb-3"><label class="form-label small">Max discount (cap)</label><input type="number" step="0.01" name="max_discount_amount" value="{{ old('max_discount_amount', $c->max_discount_amount ?? '') }}" class="form-control"></div>
    </div>
    <div class="row g-2">
        <div class="col-md-6 mb-3"><label class="form-label small">Total usage limit</label><input type="number" name="usage_limit" value="{{ old('usage_limit', $c->usage_limit ?? '') }}" class="form-control"></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Per-user limit</label><input type="number" name="usage_limit_per_user" value="{{ old('usage_limit_per_user', $c->usage_limit_per_user ?? '') }}" class="form-control"></div>
    </div>
    <div class="row g-2">
        <div class="col-md-6 mb-3"><label class="form-label small">Starts at</label><input type="datetime-local" name="starts_at" value="{{ old('starts_at', optional($c->starts_at ?? null)->format('Y-m-d\TH:i')) }}" class="form-control"></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Expires at</label><input type="datetime-local" name="expires_at" value="{{ old('expires_at', optional($c->expires_at ?? null)->format('Y-m-d\TH:i')) }}" class="form-control"></div>
    </div>
    <div class="form-check mb-3"><input type="checkbox" name="is_active" value="1" id="ia" class="form-check-input" {{ old('is_active', $c->is_active ?? true) ? 'checked':'' }}><label for="ia" class="form-check-label small">Active</label></div>
    <div class="d-flex gap-2">
        <button class="btn btn-brand">{{ $c ? 'Save Changes' : 'Create Coupon' }}</button>
        <a href="{{ route('admin.coupons.index') }}" class="btn btn-light">Cancel</a>
    </div>
</div>
