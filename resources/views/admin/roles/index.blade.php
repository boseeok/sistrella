@extends('layouts.admin')
@section('title', 'Roles')
@section('heading', 'Roles & Permissions')

@section('content')
<div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.roles.create') }}" class="btn btn-brand btn-sm"><i class="bi bi-plus-lg me-1"></i>New Role</a></div>
<div class="card p-0">
    <div class="table-responsive">
        <table class="table align-middle mb-0">
            <thead class="table-light"><tr><th>Role</th><th>Users</th><th>Permissions</th><th>Type</th><th class="text-end">Actions</th></tr></thead>
            <tbody>
                @foreach($roles as $r)
                    <tr>
                        <td><strong>{{ $r->display_name }}</strong><br><small class="text-muted">{{ $r->description }}</small></td>
                        <td>{{ $r->users_count }}</td>
                        <td>{{ $r->permissions_count }}</td>
                        <td>{!! $r->is_system ? '<span class="badge bg-secondary">System</span>' : '<span class="badge bg-light text-dark">Custom</span>' !!}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.roles.edit', $r) }}" class="btn btn-sm btn-outline-brand"><i class="bi bi-pencil"></i></a>
                            @unless($r->is_system)
                                <form action="{{ route('admin.roles.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete role?')">@csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
