@extends('layouts.admin')
@section('title', 'Staff')
@section('heading', 'Staff Members')

@section('content')
<div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.staff.create') }}" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>Add Staff</a></div>
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Name</th><th>Email</th><th>Roles</th><th>Status</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @forelse($staff as $u)
                    <tr>
                        <td><div class="d-flex align-items-center gap-2"><img src="{{ $u->avatar_url }}" width="34" height="34" class="rounded-circle"><span class="small fw-semibold">{{ $u->name }}</span></div></td>
                        <td class="small text-muted">{{ $u->email }}</td>
                        <td class="small">@foreach($u->roles as $role)<span class="badge bg-light text-dark">{{ $role->display_name }}</span> @endforeach</td>
                        <td>{!! $u->is_active ? '<span class="badge bg-success">Active</span>' : '<span class="badge bg-danger">Inactive</span>' !!}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.staff.edit', $u) }}" class="btn btn-sm btn-outline-brand"><i class="bi bi-pencil"></i></a>
                            @if($u->id !== auth()->id())
                                <form action="{{ route('admin.staff.destroy', $u) }}" method="POST" class="d-inline" onsubmit="return confirm('Remove this staff member?')">@csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted py-4">No staff yet.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
<div class="mt-3">{{ $staff->links() }}</div>
@endsection
