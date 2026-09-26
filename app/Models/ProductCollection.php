<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

/**
 * A merchandising group of products that cuts across categories:
 * an occasion (Birthday, Valentine's…) or a curated collection
 * (Gift sets, Under NPR 1000…). Table: collections.
 */
class ProductCollection extends Model
{
    public const TYPES = ['occasion' => 'Occasion', 'curated' => 'Curated collection'];

    protected $table = 'collections';

    protected $fillable = [
        'type', 'name', 'slug', 'tagline', 'description', 'image', 'icon',
        'is_active', 'is_featured', 'sort_order', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (ProductCollection $collection) {
            if (empty($collection->slug)) {
                $base = Str::slug($collection->name) ?: 'collection';
                $slug = $base;
                for ($i = 2; static::where('slug', $slug)->whereKeyNot($collection->id)->exists(); $i++) {
                    $slug = "{$base}-{$i}";
                }
                $collection->slug = $slug;
            }
        });
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'collection_product', 'collection_id', 'product_id');
    }

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeOccasions(Builder $q): Builder
    {
        return $q->where('type', 'occasion');
    }

    public function scopeCurated(Builder $q): Builder
    {
        return $q->where('type', 'curated');
    }

    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('sort_order')->orderBy('name');
    }

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return Str::startsWith($this->image, ['http://', 'https://']) ? $this->image : asset('storage/'.$this->image);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
