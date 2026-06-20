<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
    protected $fillable = [
        'user_id', 'type', 'label', 'full_name', 'phone',
        'line1', 'line2', 'city', 'district', 'province',
        'postal_code', 'country', 'is_default',
    ];

    protected $casts = ['is_default' => 'boolean'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getFormattedAttribute(): string
    {
        return collect([
            $this->line1, $this->line2, $this->city,
            $this->district, $this->province, $this->postal_code, $this->country,
        ])->filter()->implode(', ');
    }

    public function toSnapshot(): array
    {
        return [
            'full_name'   => $this->full_name,
            'phone'       => $this->phone,
            'line1'       => $this->line1,
            'line2'       => $this->line2,
            'city'        => $this->city,
            'district'    => $this->district,
            'province'    => $this->province,
            'postal_code' => $this->postal_code,
            'country'     => $this->country,
        ];
    }
}
