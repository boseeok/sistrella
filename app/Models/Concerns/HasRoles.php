<?php

namespace App\Models\Concerns;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

/**
 * Lightweight, dependency-free RBAC for the User model.
 *
 * Roles are assigned to users; permissions are assigned to roles. A user's
 * effective permission set is the union of all permissions across their roles.
 * Results are memoised per-request to keep authorization checks cheap.
 */
trait HasRoles
{
    protected ?Collection $cachedPermissionNames = null;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string|array $roles): bool
    {
        $names = $this->roles->pluck('name');

        foreach ((array) $roles as $role) {
            if ($names->contains($role)) {
                return true;
            }
        }

        return false;
    }

    public function assignRole(string|Role $role): static
    {
        $role = $role instanceof Role ? $role : Role::where('name', $role)->firstOrFail();
        $this->roles()->syncWithoutDetaching($role);
        $this->cachedPermissionNames = null;

        return $this;
    }

    public function syncRoles(array $roles): static
    {
        $ids = Role::whereIn('name', $roles)->pluck('id');
        $this->roles()->sync($ids);
        $this->load('roles');
        $this->cachedPermissionNames = null;

        return $this;
    }

    public function permissionNames(): Collection
    {
        if ($this->cachedPermissionNames !== null) {
            return $this->cachedPermissionNames;
        }

        return $this->cachedPermissionNames = $this->roles
            ->loadMissing('permissions')
            ->flatMap(fn (Role $role) => $role->permissions->pluck('name'))
            ->unique()
            ->values();
    }

    public function hasPermission(string $permission): bool
    {
        // The "admin" role is a super-user and bypasses granular checks.
        if ($this->hasRole('admin')) {
            return true;
        }

        return $this->permissionNames()->contains($permission);
    }

    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    public function isAdmin(): bool
    {
        return $this->hasAnyRole(['admin', 'manager']);
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->hasRole($roles);
    }
}
