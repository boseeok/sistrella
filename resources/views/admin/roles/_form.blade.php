@php $r = $role ?? null; $assigned = $assigned ?? []; @endphp
<div class="card p-4">
    <div class="row g-2">
        <div class="col-md-6 mb-3"><label class="form-label small">Display name *</label><input type="text" name="display_name" value="{{ old('display_name', $r->display_name ?? '') }}" class="form-control" required></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Description</label><input type="text" name="description" value="{{ old('description', $r->description ?? '') }}" class="form-control"></div>
    </div>

    <h6 class="fw-bold mb-2">Permissions</h6>
    <div class="row g-3">
        @foreach($permissions as $group => $perms)
            <div class="col-md-4">
                <div class="border rounded p-2 h-100">
                    <div class="fw-semibold small mb-2">{{ $group }}</div>
                    @foreach($perms as $perm)
                        <div class="form-check">
                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" id="p_{{ $perm->id }}" class="form-check-input" {{ in_array($perm->name, $assigned) ? 'checked':'' }}>
                            <label for="p_{{ $perm->id }}" class="form-check-label small">{{ $perm->display_name }}</label>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>

    <div class="d-flex gap-2 mt-3">
        <button class="btn btn-brand">{{ $r ? 'Save Changes' : 'Create Role' }}</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-light">Cancel</a>
    </div>
</div>
