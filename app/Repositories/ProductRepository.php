<?php

namespace App\Repositories;

use App\Models\Category;
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

    /** Storefront sort options: value => label. */
    public const SORTS = [
        'latest'     => 'Newest',
        'popular'    => 'Best selling',
        'price_asc'  => 'Price: low to high',
        'price_desc' => 'Price: high to low',
        'rating'     => 'Top rated',
        'name'       => 'Name A–Z',
    ];

    /** Merchandising collections: value => label. */
    public const COLLECTIONS = [
        'new'         => 'New Arrivals',
        'featured'    => 'Featured',
        'sale'        => 'On Sale',
        'bestsellers' => 'Best Sellers',
        'trending'    => 'Trending',
    ];

    /**
     * Storefront catalogue with filtering, search and sorting.
     *
     * Supported filters: search, category_ids (array) or category_id,
     * collection (new/featured/sale…), occasion (collection slug),
     * min_price, max_price, in_stock, sort, per_page.
     */
    public function filtered(array $filters): LengthAwarePaginator
    {
        $q = $this->query()->active()->with(['primaryImage', 'category']);

        $this->applySearch($q, is_string($filters['search'] ?? null) ? $filters['search'] : null);

        // category_id (single, used by the JSON API) includes its sub-categories too.
        if (empty($filters['category_ids']) && is_numeric($filters['category_id'] ?? null)) {
            $filters['category_ids'] = Category::find($filters['category_id'])?->descendantAndSelfIds() ?? [0];
        }

        if (! empty($filters['category_ids'])) {
            $q->whereIn('category_id', $filters['category_ids']);
        }

        // Occasion / curated collection (by slug), e.g. ?occasion=birthday
        if (! empty($filters['occasion'])) {
            $q->whereHas('collections', fn ($c) => $c->active()->where('slug', $filters['occasion']));
        }

        match ($filters['collection'] ?? null) {
            'new'         => $q->newArrivals(),
            'featured'    => $q->featured(),
            'bestsellers' => $q->bestSellers(),
            'trending'    => $q->trending(),
            'sale'        => $q->where(fn ($s) => $s->whereColumn('compare_at_price', '>', 'price')
                ->orWhere(fn ($f) => $f->onFlashSale())),
            default       => null,
        };

        if (is_numeric($filters['min_price'] ?? null)) {
            $q->where('price', '>=', $filters['min_price']);
        }

        if (is_numeric($filters['max_price'] ?? null)) {
            $q->where('price', '<=', $filters['max_price']);
        }

        if (! empty($filters['in_stock'])) {
            $q->where(fn ($s) => $s->where('track_inventory', false)
                ->orWhere(fn ($simple) => $simple->where('type', '!=', 'variable')->where('stock', '>', 0))
                ->orWhere(fn ($variable) => $variable->where('type', 'variable')
                    ->whereHas('variants', fn ($v) => $v->where('is_active', true)->where('stock', '>', 0))));
        }

        match ($filters['sort'] ?? 'latest') {
            'price_asc'  => $q->orderBy('price'),
            'price_desc' => $q->orderByDesc('price'),
            'popular'    => $q->orderByDesc('sales_count'),
            'rating'     => $q->orderByDesc('rating_avg'),
            'name'       => $q->orderBy('name'),
            default      => $q->latest()->orderByDesc('id'),
        };

        return $q->paginate(min((int) ($filters['per_page'] ?? 12), 48))->withQueryString();
    }

    /**
     * Every word must match at least one searchable field (name, SKU,
     * descriptions or category name), so "pink bunny" finds "Tiny Bunny
     * Plush" described as pink.
     */
    private function applySearch($q, ?string $search): void
    {
        // LIKE wildcards are stripped rather than escaped: escape syntax differs
        // between MySQL and SQLite, and nobody searches for a literal "%".
        $words = collect(preg_split('/\s+/', str_replace(['%', '_', '\\'], ' ', (string) $search)))
            ->filter(fn ($w) => mb_strlen($w) > 1)
            ->take(6);

        foreach ($words as $word) {
            $like = "%{$word}%";

            $q->where(fn ($sub) => $sub->where('name', 'like', $like)
                ->orWhere('sku', 'like', $like)
                ->orWhere('short_description', 'like', $like)
                ->orWhere('description', 'like', $like)
                ->orWhereHas('category', fn ($c) => $c->where('name', 'like', $like)
                    ->orWhereHas('parent', fn ($p) => $p->where('name', 'like', $like))));
        }
    }

    /**
     * @return Collection<int,Product>
     */
    public function featured(int $limit = 8): Collection
    {
        return $this->query()->active()->featured()->with(['primaryImage', 'category'])->latest()->limit($limit)->get();
    }

    public function trending(int $limit = 8): Collection
    {
        return $this->query()->active()->trending()->with(['primaryImage', 'category'])->latest()->limit($limit)->get();
    }

    public function bestSellers(int $limit = 8): Collection
    {
        return $this->query()->active()->bestSellers()->with(['primaryImage', 'category'])->orderByDesc('sales_count')->limit($limit)->get();
    }

    public function newArrivals(int $limit = 8): Collection
    {
        return $this->query()->active()->newArrivals()->with(['primaryImage', 'category'])->latest()->limit($limit)->get();
    }

    public function flashSale(int $limit = 8): Collection
    {
        return $this->query()->active()->onFlashSale()->with(['primaryImage', 'category'])->limit($limit)->get();
    }

    public function lowStock(int $limit = 10): Collection
    {
        return $this->query()->lowStock()->orderBy('stock')->limit($limit)->get();
    }

    /**
     * Products from the same category first, topped up from the wider parent
     * category so small sub-categories still get a full row.
     */
    public function relatedTo(Product $product, int $limit = 4): Collection
    {
        $scopeIds = $product->category?->parent
            ? $product->category->parent->descendantAndSelfIds()
            : [$product->category_id];

        return $this->query()->active()
            ->whereIn('category_id', $scopeIds)
            ->where('id', '!=', $product->id)
            ->with(['primaryImage', 'category'])
            ->orderByRaw('CASE WHEN category_id = ? THEN 0 ELSE 1 END', [$product->category_id])
            ->inRandomOrder()
            ->limit($limit)
            ->get();
    }
}
