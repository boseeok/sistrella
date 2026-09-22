<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly ProductRepository $products)
    {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['search', 'category_id', 'min_price', 'max_price', 'in_stock', 'sort']);

        return view('shop.products.index', [
            'products'   => $this->products->filtered($filters),
            'categories' => Category::active()->roots()->with('children')->get(),
            'filters'    => $filters,
        ]);
    }

    public function search(Request $request): View
    {
        return $this->index($request);
    }

    public function category(Category $category, Request $request): View
    {
        $filters = array_merge(
            $request->only(['search', 'min_price', 'max_price', 'in_stock', 'sort']),
            ['category_id' => $category->id],
        );

        return view('shop.products.index', [
            'products'   => $this->products->filtered($filters),
            'categories' => Category::active()->roots()->with('children')->get(),
            'filters'    => $filters,
            'category'   => $category,
        ]);
    }

    public function show(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->increment('views');
        $this->rememberView($product);

        return view('shop.products.show', [
            'product'  => $product->load(['images', 'category', 'variants.attributeValues.attribute', 'approvedReviews.user']),
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
