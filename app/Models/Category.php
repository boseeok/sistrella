<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'parent_id', 'name', 'slug', 'description', 'image', 'icon',
        'is_active', 'is_featured', 'sort_order', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'is_active'   => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Category $category) {
            if (empty($category->slug)) {
                $category->slug = static::uniqueSlug($category->name);
            }
        });
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (static::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id')->orderBy('sort_order');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /** Full "Crochet › Amigurumi › Animals" label for select boxes and details. */
    public function getPathNameAttribute(): string
    {
        return $this->parent ? $this->parent->path_name.' › '.$this->name : $this->name;
    }

    /** The top-level ancestor (product line), e.g. "Crochet". */
    public function getRootAttribute(): Category
    {
        return $this->parent ? $this->parent->root : $this;
    }

    /**
     * IDs of this category plus every nested sub-category, so a parent
     * category page also lists the products filed under its children.
     *
     * @return array<int,int>
     */
    public function descendantAndSelfIds(): array
    {
        $ids   = [$this->id];
        $level = [$this->id];

        while ($level = static::whereIn('parent_id', $level)->pluck('id')->all()) {
            $ids = array_merge($ids, $level);
        }

        return $ids;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeRoots(Builder $query): Builder
    {
        return $query->whereNull('parent_id');
    }

    public function getImageUrlAttribute(): string
    {
        if ($this->image && Str::startsWith($this->image, ['http://', 'https://'])) {
            return $this->image;
        }

        return $this->image
            ? asset('storage/'.$this->image)
            : 'https://placehold.co/400x300/CCFBF1/0D9488?text='.urlencode($this->name);
    }
}
