<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WishlistController extends Controller
{
    public function index(): View
    {
        $items = auth()->user()->wishlist()->with('product.primaryImage')->latest()->get();

        return view('shop.wishlist.index', ['items' => $items]);
    }

    public function toggle(Product $product, Request $request): JsonResponse|RedirectResponse
    {
        $user     = auth()->user();
        $existing = $user->wishlist()->where('product_id', $product->id)->first();

        if ($existing) {
            $existing->delete();
            $added = false;
        } else {
            $user->wishlist()->create(['product_id' => $product->id]);
            $added = true;
        }

        if ($request->wantsJson()) {
            return response()->json([
                'ok'      => true,
                'added'   => $added,
                'message' => $added ? 'Added to wishlist.' : 'Removed from wishlist.',
            ]);
        }

        return back()->with('success', $added ? 'Added to wishlist.' : 'Removed from wishlist.');
    }

    public function remove(Product $product): RedirectResponse
    {
        auth()->user()->wishlist()->where('product_id', $product->id)->delete();

        return back()->with('success', 'Removed from wishlist.');
    }
}
