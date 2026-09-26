<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ProductVariant extends Model
{
    protected $fillable = ['product_id', 'sku', 'price', 'stock', 'image', 'is_active'];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function attributeValues(): BelongsToMany
    {
        return $this->belongsToMany(AttributeValue::class, 'attribute_value_product_variant');
    }

    /**
     * Effective price: variant override falls back to the parent product price.
     */
    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->price ?? $this->product->current_price);
    }

    public function getLabelAttribute(): string
    {
        return $this->attributeValues->sortBy('attribute_id')->pluck('value')->implode(' / ');
    }

    /** The value this variant has for an attribute slug ("color", "size"), if any. */
    public function valueFor(string $attributeSlug): ?AttributeValue
    {
        return $this->attributeValues->first(fn ($v) => $v->attribute?->slug === $attributeSlug);
    }

    public function getInStockAttribute(): bool
    {
        return $this->is_active && (! $this->product?->track_inventory || $this->stock > 0);
    }
}
