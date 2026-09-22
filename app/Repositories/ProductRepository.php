<?php

namespace App\Repositories;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository extends BaseRepository
{
    protected function model(): string
    {
        return Product::class;
    }

    public function findBySlug(string $slug): ?Product
    {
        return $this->query()
            ->with(['images', 'category', 'variants.attributeValues.attribute', 'approvedReviews.user'])
            ->where('slug', $slug)
            ->firstOrFail();
    }

    /**
     * Storefront catalogue with filtering, search and sorting.
     */
    public function filtered(array $filters): LengthAwarePaginator
    {
        $q = $this->query()->active()->with(['primaryImage', 'category']);

        if (! empty($filters['search'])) {
            $term = $filters['search'];
            $q->where(function ($sub) use ($term) {
                $sub->where('name', 'like', "%{$term}%")
                    ->orWhere('short_description', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%");
            });
        }

        if (! empty($filters['category_id'])) {
            $q->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['min_price'])) {
            $q->where('price', '>=', $filters['min_price']);
        }

        if (! empty($filters['max_price'])) {
            $q->where('price', '<=', $filters['max_price']);
        }

        if (! empty($filters['in_stock'])) {
            $q->where(fn ($s) => $s->where('track_inventory', false)->orWhere('stock', '>', 0));
        }

        match ($filters['sort'] ?? 'latest') {
            'price_asc'  => $q->orderBy('price'),
            'price_desc' => $q->orderByDesc('price'),
            'popular'    => $q->orderByDesc('sales_count'),
            'rating'     => $q->orderByDesc('rating_avg'),
            'name'       => $q->orderBy('name'),
            default      => $q->latest(),
        };

        return $q->paginate($filters['per_page'] ?? 12)->withQueryString();
    }

    /**
     * @return Collection<int,Product>
     */
    public function featured(int $limit = 8): Collection
    {
        return $this->query()->active()->featured()->with('primaryImage')->latest()->limit($limit)->get();
    }

    public function trending(int $limit = 8): Collection
    {
        return $this->query()->active()->trending()->with('primaryImage')->latest()->limit($limit)->get();
    }

    public function bestSellers(int $limit = 8): Collection
    {
        return $this->query()->active()->bestSellers()->with('primaryImage')->orderByDesc('sales_count')->limit($limit)->get();
    }

    public function newArrivals(int $limit = 8): Collection
    {
        return $this->query()->active()->newArrivals()->with('primaryImage')->latest()->limit($limit)->get();
    }

    public function flashSale(int $limit = 8): Collection
    {
        return $this->query()->active()->onFlashSale()->with('primaryImage')->limit($limit)->get();
    }

    public function lowStock(int $limit = 10): Collection
    {
        return $this->query()->lowStock()->orderBy('stock')->limit($limit)->get();
    }

    public function relatedTo(Product $product, int $limit = 4): Collection
    {
        return $this->query()->active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with('primaryImage')
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
