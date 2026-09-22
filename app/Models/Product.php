<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'category_id', 'name', 'slug', 'sku', 'short_description', 'description',
        'price', 'compare_at_price', 'cost_price',
        'track_inventory', 'stock', 'low_stock_threshold',
        'type', 'is_active', 'is_featured', 'is_trending', 'is_best_seller',
        'is_new_arrival', 'is_customizable',
        'flash_sale_price', 'flash_sale_starts_at', 'flash_sale_ends_at',
        'views', 'sales_count', 'rating_avg', 'rating_count',
        'weight', 'meta_title', 'meta_description',
    ];

    protected $casts = [
        'price'                => 'decimal:2',
        'compare_at_price'     => 'decimal:2',
        'cost_price'           => 'decimal:2',
        'flash_sale_price'     => 'decimal:2',
        'flash_sale_starts_at' => 'datetime',
        'flash_sale_ends_at'   => 'datetime',
        'track_inventory'      => 'boolean',
        'is_active'            => 'boolean',
        'is_featured'          => 'boolean',
        'is_trending'          => 'boolean',
        'is_best_seller'       => 'boolean',
        'is_new_arrival'       => 'boolean',
        'is_customizable'      => 'boolean',
        'rating_avg'           => 'decimal:2',
    ];

    protected static function booted(): void
    {
        static::creating(function (Product $product) {
            if (empty($product->slug)) {
                $product->slug = static::uniqueSlug($product->name);
            }
            if (empty($product->sku)) {
                $product->sku = static::generateSku($product->name);
            }
        });
    }

    public static function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $i = 1;
        while (static::withTrashed()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }

    /**
     * Auto-generate a human-readable, unique SKU: CRS-ABC-XXXX.
     */
    public static function generateSku(string $name): string
    {
        $prefix = 'CRS-'.strtoupper(Str::substr(Str::slug($name, ''), 0, 3) ?: 'PRD');
        do {
            $sku = $prefix.'-'.strtoupper(Str::random(4));
        } while (static::withTrashed()->where('sku', $sku)->exists());

        return $sku;
    }

    // ---------------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------------

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_primary', true);
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews(): HasMany
    {
        return $this->hasMany(Review::class)->where('is_approved', true);
    }

    public function relatedProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_links', 'product_id', 'linked_product_id')
            ->withPivot('type', 'quantity')
            ->wherePivot('type', 'related');
    }

    public function bundleItems(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_links', 'product_id', 'linked_product_id')
            ->withPivot('type', 'quantity')
            ->wherePivot('type', 'bundle');
    }

    // ---------------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------------

    public function scopeActive(Builder $q): Builder
    {
        return $q->where('is_active', true);
    }

    public function scopeFeatured(Builder $q): Builder
    {
        return $q->where('is_featured', true);
    }

    public function scopeTrending(Builder $q): Builder
    {
        return $q->where('is_trending', true);
    }

    public function scopeBestSellers(Builder $q): Builder
    {
        return $q->where('is_best_seller', true);
    }

    public function scopeNewArrivals(Builder $q): Builder
    {
        return $q->where('is_new_arrival', true);
    }

    public function scopeOnFlashSale(Builder $q): Builder
    {
        $now = now();

        return $q->whereNotNull('flash_sale_price')
            ->where('flash_sale_starts_at', '<=', $now)
            ->where('flash_sale_ends_at', '>=', $now);
    }

    public function scopeLowStock(Builder $q): Builder
    {
        return $q->where('track_inventory', true)
            ->whereColumn('stock', '<=', 'low_stock_threshold');
    }

    // ---------------------------------------------------------------------
    // Accessors / business helpers
    // ---------------------------------------------------------------------

    public function isOnFlashSale(): bool
    {
        return $this->flash_sale_price
            && $this->flash_sale_starts_at
            && $this->flash_sale_ends_at
            && Carbon::now()->between($this->flash_sale_starts_at, $this->flash_sale_ends_at);
    }

    /**
     * The price a customer actually pays (flash sale wins if active).
     */
    public function getCurrentPriceAttribute(): float
    {
        return (float) ($this->isOnFlashSale() ? $this->flash_sale_price : $this->price);
    }

    public function getOldPriceAttribute(): ?float
    {
        if ($this->isOnFlashSale()) {
            return (float) $this->price;
        }

        return $this->compare_at_price ? (float) $this->compare_at_price : null;
    }

    public function getDiscountPercentAttribute(): int
    {
        $old = $this->old_price;
        if ($old && $old > $this->current_price) {
            return (int) round((($old - $this->current_price) / $old) * 100);
        }

        return 0;
    }

    public function getInStockAttribute(): bool
    {
        if (! $this->track_inventory) {
            return true;
        }

        if ($this->type === 'variable') {
            return $this->variants->sum('stock') > 0;
        }

        return $this->stock > 0;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->track_inventory && $this->stock <= $this->low_stock_threshold;
    }

    public function getThumbnailAttribute(): string
    {
        $img = $this->relationLoaded('primaryImage') ? $this->primaryImage : $this->primaryImage()->first();
        $img = $img ?: ($this->relationLoaded('images') ? $this->images->first() : $this->images()->first());

        return $img
            ? asset('storage/'.$img->path)
            : $this->placeholderImage();
    }

    /**
     * On-brand local SVG placeholder (sage/cream/terracotta) used when a
     * product has no uploaded image. Returned as a self-contained data URI
     * so it always renders without any external request.
     */
    public function placeholderImage(): string
    {
        $name    = htmlspecialchars(Str::limit($this->name, 24), ENT_QUOTES);
        $initial = htmlspecialchars(Str::upper(Str::substr(trim($this->name), 0, 1)) ?: '✿', ENT_QUOTES);

        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="600" height="600" viewBox="0 0 600 600">'
            .'<rect width="600" height="600" fill="#F6F3ED"/>'
            .'<circle cx="300" cy="245" r="125" fill="#DCE3CE"/>'
            .'<text x="300" y="245" font-family="Quicksand,Poppins,Arial,sans-serif" font-size="130" font-weight="700" fill="#3D4B33" text-anchor="middle" dominant-baseline="central">'.$initial.'</text>'
            .'<text x="300" y="430" font-family="Poppins,Arial,sans-serif" font-size="34" font-weight="600" fill="#3D4B33" text-anchor="middle">'.$name.'</text>'
            .'<text x="300" y="482" font-family="Poppins,Arial,sans-serif" font-size="22" fill="#B26B3C" text-anchor="middle" letter-spacing="2">HANDMADE CROCHET</text>'
            .'</svg>';

        return 'data:image/svg+xml;charset=UTF-8,'.rawurlencode($svg);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Recompute cached rating aggregates from approved reviews.
     */
    public function refreshRatingStats(): void
    {
        $stats = $this->approvedReviews()
            ->selectRaw('COUNT(*) as cnt, COALESCE(AVG(rating), 0) as avg')
            ->first();

        $this->forceFill([
            'rating_count' => (int) ($stats->cnt ?? 0),
            'rating_avg'   => round((float) ($stats->avg ?? 0), 2),
        ])->saveQuietly();
    }
}
