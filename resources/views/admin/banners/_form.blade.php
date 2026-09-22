@php $b = $banner ?? null; @endphp
<div class="card p-4" style="max-width:680px">
    <div class="mb-3"><label class="form-label small">Title</label><input type="text" name="title" value="{{ old('title', $b->title ?? '') }}" class="form-control"></div>
    <div class="mb-3"><label class="form-label small">Subtitle</label><input type="text" name="subtitle" value="{{ old('subtitle', $b->subtitle ?? '') }}" class="form-control"></div>
    <div class="row g-2">
        <div class="col-md-6 mb-3"><label class="form-label small">Position *</label>
            <select name="position" class="form-select">
                @foreach(['hero'=>'Hero','promo'=>'Promo','sidebar'=>'Sidebar'] as $k=>$v)
                    <option value="{{ $k }}" {{ old('position', $b->position ?? 'hero')===$k ? 'selected':'' }}>{{ $v }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6 mb-3"><label class="form-label small">Sort order</label><input type="number" name="sort_order" value="{{ old('sort_order', $b->sort_order ?? 0) }}" class="form-control"></div>
    </div>
    <div class="row g-2">
        <div class="col-md-6 mb-3"><label class="form-label small">Link</label><input type="text" name="link" value="{{ old('link', $b->link ?? '') }}" class="form-control" placeholder="/shop"></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Button text</label><input type="text" name="button_text" value="{{ old('button_text', $b->button_text ?? '') }}" class="form-control"></div>
    </div>
    <div class="mb-3"><label class="form-label small">Image</label><input type="file" name="image" accept="image/*" class="form-control">
        @if($b && $b->image)<img src="{{ $b->image_url }}" width="120" class="rounded mt-2">@endif
    </div>
    <div class="form-check mb-3"><input type="checkbox" name="is_active" value="1" id="ia" class="form-check-input" {{ old('is_active', $b->is_active ?? true) ? 'checked':'' }}><label for="ia" class="form-check-label small">Active</label></div>
    <div class="d-flex gap-2">
        <button class="btn btn-brand">{{ $b ? 'Save Changes' : 'Create Banner' }}</button>
        <a href="{{ route('admin.banners.index') }}" class="btn btn-light">Cancel</a>
    </div>
</div>
