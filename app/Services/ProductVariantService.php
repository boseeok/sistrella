<?php

namespace App\Services;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Creates/updates a product's colour & size variants from the admin form,
 * on top of the generic attributes / attribute_values tables (so more
 * option types, e.g. "Material", can be added later without schema changes).
 */
class ProductVariantService
{
    /**
     * @param  array<int, array{id?:int|string|null, color?:string|null, color_code?:string|null, size?:string|null, price?:numeric|null, stock?:int|string|null, sku?:string|null, is_active?:mixed, _delete?:mixed}>  $rows
     */
    public function sync(Product $product, array $rows): void
    {
        DB::transaction(function () use ($product, $rows) {
            foreach ($rows as $row) {
                $variant = ! empty($row['id']) ? $product->variants()->find($row['id']) : null;

                if (! empty($row['_delete'])) {
                    $variant?->delete();
                    continue;
                }

                $values = array_filter([
                    $this->value('Color', $row['color'] ?? null, $row['color_code'] ?? null),
                    $this->value('Size', $row['size'] ?? null),
                ]);

                if (! $values) {
                    continue; // a variant needs at least a colour or a size
                }

                $variant ??= new ProductVariant(['product_id' => $product->id]);
                $variant->fill([
                    'sku'       => filled($row['sku'] ?? null) ? $row['sku'] : ($variant->sku ?: $this->sku($product, $values)),
                    'price'     => filled($row['price'] ?? null) ? $row['price'] : null,
                    'stock'     => (int) ($row['stock'] ?? 0),
                    'is_active' => ! empty($row['is_active']),
                ])->save();

                $variant->attributeValues()->sync(collect($values)->pluck('id'));
            }

            // Products with live variants are "variable" (stock tracked per variant).
            $hasVariants = $product->variants()->where('is_active', true)->exists();
            if ($hasVariants && $product->type === 'simple') {
                $product->update(['type' => 'variable']);
            } elseif (! $hasVariants && $product->type === 'variable') {
                $product->update(['type' => 'simple']);
            }
        });
    }

    private function value(string $attributeName, ?string $value, ?string $colorCode = null): ?AttributeValue
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $attribute = Attribute::firstOrCreate(['slug' => Str::slug($attributeName)], ['name' => $attributeName]);
        $attrValue = AttributeValue::firstOrCreate(['attribute_id' => $attribute->id, 'value' => $value]);

        if ($colorCode && preg_match('/^#[0-9a-f]{6}$/i', $colorCode) && $attrValue->color_code !== $colorCode) {
            $attrValue->update(['color_code' => $colorCode]);
        }

        return $attrValue;
    }

    /** @param array<int, AttributeValue> $values */
    private function sku(Product $product, array $values): string
    {
        $base = $product->sku.'-'.Str::upper(collect($values)->map(fn ($v) => Str::substr(Str::slug($v->value, ''), 0, 3))->join('-'));
        $sku = $base;
        for ($i = 2; ProductVariant::where('sku', $sku)->exists(); $i++) {
            $sku = "{$base}-{$i}";
        }

        return $sku;
    }
}
