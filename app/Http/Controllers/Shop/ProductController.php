<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCollection;
use App\Repositories\ProductRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly ProductRepository $products)
    {
    }

    public function index(Request $request): View
    {
        return $this->listing($request);
    }

    public function search(Request $request): View
    {
        return $this->listing($request);
    }

    public function category(Category $category, Request $request): View
    {
        abort_unless($category->is_active, 404);

        return $this->listing($request, $category->load(['parent.parent', 'children' => fn ($q) => $q->active()]));
    }

    /**
     * Occasion or curated collection page: /collections/{slug}.
     */
    public function collection(ProductCollection $collection, Request $request): View
    {
        abort_unless($collection->is_active, 404);

        return $this->listing($request, null, $collection);
    }

    /**
     * Shared catalogue listing for /shop, /search, /category/{slug} and /collections/{slug}.
     */
    private function listing(Request $request, ?Category $category = null, ?ProductCollection $giftCollection = null): View
    {
        $filters = $this->filters($request);

        // Legacy ?category_id= links still work on /shop.
        if (! $category && $request->filled('category_id')) {
            $category = Category::active()->find($request->integer('category_id'));
        }

        if ($category) {
            $filters['category_ids'] = $category->descendantAndSelfIds();
        }

        if ($giftCollection) {
            $filters['occasion'] = $giftCollection->slug;
        }

        $occasions = ProductCollection::active()->ordered()->get(['id', 'type', 'name', 'slug', 'icon']);
        $activeOccasion = $giftCollection ?? $occasions->firstWhere('slug', $filters['occasion'] ?? null);

        $merch = ProductRepository::COLLECTIONS[$filters['collection'] ?? ''] ?? null;
        $heading = $giftCollection->name ?? $category->name
            ?? ($filters['search'] ?? null ? 'Results for “'.$filters['search'].'”' : ($merch ?? ($activeOccasion ? $activeOccasion->name.' gifts' : 'Shop All')));

        $described = $giftCollection ?? $category;

        return view('shop.products.index', [
            'products'        => $this->products->filtered($filters),
            'categories'      => Category::active()->roots()->orderBy('sort_order')
                ->with(['children' => fn ($q) => $q->active()->with(['children' => fn ($c) => $c->active()])])->get(),
            'occasions'       => $occasions,
            'activeOccasion'  => $activeOccasion,
            'filters'         => $filters,
            'category'        => $category,
            'giftCollection'  => $giftCollection,
            'heading'         => $heading,
            'metaTitle'       => $described?->meta_title ?: $heading,
            'metaDescription' => $described?->meta_description
                ?: ($described?->description ?: "Shop {$heading}: handmade crochet, ribbon bouquets and fuzzy wire gifts, made by hand in Nepal."),
        ]);
    }

    /**
     * Whitelist and normalise catalogue query-string filters.
     */
    private function filters(Request $request): array
    {
        // Only scalar strings are accepted; ?sort[]=x style input is ignored.
        $str = fn (string $key) => is_string($v = $request->query($key)) ? trim($v) : null;
        $num = fn (string $key) => is_numeric($v = $str($key)) ? max(0, (float) $v) : null;

        return array_filter([
            'search'     => Str::limit((string) $str('search'), 100, ''),
            'collection' => isset(ProductRepository::COLLECTIONS[$str('collection') ?? '']) ? $str('collection') : null,
            'occasion'   => preg_match('/^[a-z0-9-]{1,80}$/', (string) $str('occasion')) ? $str('occasion') : null,
            'sort'       => isset(ProductRepository::SORTS[$str('sort') ?? '']) ? $str('sort') : null,
            'min_price'  => $num('min_price'),
            'max_price'  => $num('max_price'),
            'in_stock'   => $request->boolean('in_stock') ?: null,
        ], fn ($v) => $v !== null && $v !== '');
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->increment('views');
        $this->rememberView($product);

        return view('shop.products.show', [
            'product'  => $product->load([
                'images', 'category.parent.parent', 'approvedReviews.user',
                'variants' => fn ($q) => $q->where('is_active', true)->with('attributeValues.attribute'),
                'collections' => fn ($q) => $q->active()->ordered(),
            ]),
            'related'  => $this->products->relatedTo($product),
            'canReview' => $this->customerCanReview($product),
        ]);
    }

    public function review(Product $product, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'title'  => ['nullable', 'string', 'max:120'],
            'body'   => ['nullable', 'string', 'max:2000'],
        ]);

        $verified = \App\Models\OrderItem::where('product_id', $product->id)
            ->whereHas('order', fn ($q) => $q->where('user_id', auth()->id())->where('status', 'delivered'))
            ->exists();

        $product->reviews()->updateOrCreate(
            ['user_id' => auth()->id()],
            $data + ['is_verified_purchase' => $verified, 'is_approved' => false],
        );

        return back()->with('success', 'Thank you! Your review will appear once approved.');
    }

    private function rememberView(Product $product): void
    {
        $viewed = collect(session('recently_viewed', []))
            ->reject(fn ($id) => $id === $product->id)
            ->prepend($product->id)
            ->take(12)
            ->values()
            ->all();

        session(['recently_viewed' => $viewed]);
    }

    private function customerCanReview(Product $product): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return ! $product->reviews()->where('user_id', auth()->id())->exists();
    }
}
