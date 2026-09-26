<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Banner extends Model
{
    protected $fillable = [
        'title', 'subtitle', 'image', 'mobile_image', 'link',
        'button_text', 'position', 'is_active', 'sort_order',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true)->orderBy('sort_order');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return $this->image
            ? asset('storage/'.$this->image)
            : 'https://placehold.co/1600x600/0D9488/ffffff?text='.urlencode($this->title ?? 'Crochet Store');
    }
}
