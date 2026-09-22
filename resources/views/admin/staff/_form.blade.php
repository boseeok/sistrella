@php $st = $staff ?? null; $assigned = $assigned ?? []; @endphp
<div class="card p-4" style="max-width:680px">
    <div class="row g-2">
        <div class="col-md-6 mb-3"><label class="form-label small">Name *</label><input type="text" name="name" value="{{ old('name', $st->name ?? '') }}" class="form-control" required></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Email *</label><input type="email" name="email" value="{{ old('email', $st->email ?? '') }}" class="form-control" required></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Phone</label><input type="text" name="phone" value="{{ old('phone', $st->phone ?? '') }}" class="form-control"></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Password {{ $st ? '(leave blank to keep)' : '*' }}</label><input type="password" name="password" class="form-control" {{ $st ? '' : 'required' }}></div>
        <div class="col-md-6 mb-3"><label class="form-label small">Confirm password</label><input type="password" name="password_confirmation" class="form-control"></div>
    </div>

    <label class="form-label small fw-semibold">Roles *</label>
    <div class="mb-3">
        @foreach($roles as $role)
            <div class="form-check">
                <input type="checkbox" name="roles[]" value="{{ $role->name }}" id="r_{{ $role->id }}" class="form-check-input" {{ in_array($role->name, old('roles', $assigned)) ? 'checked':'' }}>
                <label for="r_{{ $role->id }}" class="form-check-label small">{{ $role->display_name }}</label>
            </div>
        @endforeach
    </div>

    @if($st)
        <div class="form-check mb-3"><input type="checkbox" name="is_active" value="1" id="ia" class="form-check-input" {{ old('is_active', $st->is_active) ? 'checked':'' }}><label for="ia" class="form-check-label small">Active</label></div>
    @endif

    <div class="d-flex gap-2">
        <button class="btn btn-brand">{{ $st ? 'Save Changes' : 'Add Staff' }}</button>
        <a href="{{ route('admin.staff.index') }}" class="btn btn-light">Cancel</a>
    </div>
</div>
