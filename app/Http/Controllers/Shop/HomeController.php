<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCollection;
use App\Models\Review;
use App\Repositories\ProductRepository;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private readonly ProductRepository $products)
    {
    }

    public function index(): View
    {
        return view('shop.home', $this->giftCollections() + [
            'banners'          => Banner::active()->where('position', 'hero')->get(),
            'trending'         => $this->products->trending(8),
            'bestSellers'      => $this->products->bestSellers(8),
            'newArrivals'      => $this->products->newArrivals(8),
            'flashSale'        => $this->products->flashSale(8),
            'flashSaleEndsAt'  => Product::onFlashSale()->min('flash_sale_ends_at'),
            'featuredCategories' => $this->categoryTiles(),
            'recentlyViewed'   => $this->recentlyViewed(),
            'reviews'          => Review::where('is_approved', true)->where('rating', '>=', 4)
                ->whereNotNull('body')->whereHas('product', fn ($q) => $q->active())
                ->with(['user:id,name', 'product:id,name,slug'])->latest()->take(3)->get(),
        ]);
    }

    /**
     * Root categories (featured first) with a product count that includes
     * their sub-categories, for the "Shop by category" tiles.
     */
    private function categoryTiles()
    {
        $counts = Product::active()->selectRaw('category_id, COUNT(*) as cnt')
            ->groupBy('category_id')->pluck('cnt', 'category_id');

        // Whole tree in memory so counts include every nesting level.
        $all = Category::active()->get(['id', 'parent_id']);
        $total = function (int $id) use (&$total, $all, $counts): int {
            return ($counts[$id] ?? 0) + $all->where('parent_id', $id)->sum(fn ($c) => $total($c->id));
        };

        return Category::active()->roots()
            ->with(['children' => fn ($q) => $q->active()->orderBy('sort_order')])
            ->orderByDesc('is_featured')->orderBy('sort_order')->take(6)->get()
            ->each(fn (Category $cat) => $cat->setAttribute('product_total', $total($cat->id)));
    }

    /**
     * Occasions and curated collections flagged for the home page. Curated
     * collections borrow a product photo when they have no image of their own.
     */
    private function giftCollections(): array
    {
        $featured = ProductCollection::active()->where('is_featured', true)->ordered()
            ->withCount(['products' => fn ($q) => $q->active()])
            ->with(['products' => fn ($q) => $q->active()->with('primaryImage')->orderByDesc('is_featured')->limit(1)])
            ->get();

        return [
            'occasions' => $featured->where('type', 'occasion')->values(),
            'curated'   => $featured->where('type', 'curated')->values(),
        ];
    }

    /**
     * Resolve recently-viewed products from the session id list.
     */
    private function recentlyViewed()
    {
        $ids = collect(session('recently_viewed', []))->take(8);
        if ($ids->isEmpty()) {
            return collect();
        }

        return Product::active()->whereIn('id', $ids)->with(['primaryImage', 'category'])->get()
            ->sortBy(fn ($p) => $ids->search($p->id))->values();
    }
}
