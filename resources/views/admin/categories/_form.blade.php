@php $c = $category ?? null; @endphp
<div class="card p-4" style="max-width:640px">
    <div class="mb-3"><label class="form-label small">Name *</label><input type="text" name="name" value="{{ old('name', $c->name ?? '') }}" class="form-control" required></div>
    <div class="mb-3">
        <label class="form-label small">Parent category</label>
        <select name="parent_id" class="form-select">
            <option value="">— None (top level) —</option>
            @foreach($parents as $parent)<option value="{{ $parent->id }}" {{ old('parent_id', $c->parent_id ?? '')==$parent->id ? 'selected':'' }}>{{ $parent->name }}</option>@endforeach
        </select>
    </div>
    <div class="row g-2">
        <div class="col-md-6 mb-3"><label class="form-label small">Icon (Bootstrap Icon class)</label><input type="text" name="icon" value="{{ old('icon', $c->icon ?? '') }}" class="form-control" placeholder="bi-flower1"></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Sort order</label><input type="number" name="sort_order" value="{{ old('sort_order', $c->sort_order ?? 0) }}" class="form-control"></div>
    </div>
    <div class="mb-3"><label class="form-label small">Description</label><textarea name="description" rows="3" class="form-control">{{ old('description', $c->description ?? '') }}</textarea></div>
    <div class="mb-3"><label class="form-label small">Image</label><input type="file" name="image" accept="image/*" class="form-control">
        @if($c && $c->image)<img src="{{ $c->image_url }}" width="60" class="rounded mt-2">@endif
    </div>
    <div class="form-check"><input type="checkbox" name="is_active" value="1" id="ia" class="form-check-input" {{ old('is_active', $c->is_active ?? true) ? 'checked':'' }}><label for="ia" class="form-check-label small">Active</label></div>
    <div class="form-check mb-3"><input type="checkbox" name="is_featured" value="1" id="if" class="form-check-input" {{ old('is_featured', $c->is_featured ?? false) ? 'checked':'' }}><label for="if" class="form-check-label small">Featured on homepage</label></div>
    <div class="d-flex gap-2">
        <button class="btn btn-brand">{{ $c ? 'Save Changes' : 'Create Category' }}</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-light">Cancel</a>
    </div>
</div>
