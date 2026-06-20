<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable = ['name', 'display_name', 'description', 'is_system'];

    protected $casts = ['is_system' => 'boolean'];

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function givePermissionTo(string|array $permissions): void
    {
        $ids = Permission::whereIn('name', (array) $permissions)->pluck('id');
        $this->permissions()->syncWithoutDetaching($ids);
    }

    public function syncPermissions(array $permissions): void
    {
        $ids = Permission::whereIn('name', $permissions)->pluck('id');
        $this->permissions()->sync($ids);
    }
}
