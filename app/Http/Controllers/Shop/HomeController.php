<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private readonly ProductRepository $products)
    {
    }

    public function index(): View
    {
        return view('shop.home', [
            'banners'          => Banner::active()->where('position', 'hero')->get(),
            'promoBanners'     => Banner::active()->where('position', 'promo')->take(3)->get(),
            'featured'         => $this->products->featured(8),
            'trending'         => $this->products->trending(8),
            'bestSellers'      => $this->products->bestSellers(8),
            'newArrivals'      => $this->products->newArrivals(8),
            'flashSale'        => $this->products->flashSale(8),
            'flashSaleEndsAt'  => Product::onFlashSale()->min('flash_sale_ends_at'),
            'featuredCategories' => Category::active()->where('is_featured', true)->take(6)->get(),
            'recentlyViewed'   => $this->recentlyViewed(),
        ]);
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

        return Product::active()->whereIn('id', $ids)->with('primaryImage')->get()
            ->sortBy(fn ($p) => $ids->search($p->id))->values();
    }
}
