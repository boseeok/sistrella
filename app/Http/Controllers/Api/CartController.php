<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(private readonly CartService $cart)
    {
    }

    public function index(): JsonResponse
    {
        $cart = $this->cart->current(false);

        return response()->json([
            'items'  => $cart?->items()->with('product:id,name,slug,price')->get() ?? [],
            'totals' => $this->cart->totals($cart),
        ]);
    }

    public function add(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
            'quantity'   => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);

        $product = Product::active()->findOrFail($data['product_id']);

        if (! $product->in_stock) {
            return response()->json(['message' => 'Product is out of stock.'], 422);
        }

        $item = $this->cart->addItem($product, $data['quantity'] ?? 1, $data['variant_id'] ?? null);

        return response()->json([
            'message' => 'Added to cart.',
            'item'    => $item,
            'totals'  => $this->cart->totals(),
        ], 201);
    }

    public function remove(CartItem $item): JsonResponse
    {
        $cart = $this->cart->current(false);
        abort_unless($cart && $item->cart_id === $cart->id, 403);

        $this->cart->removeItem($item);

        return response()->json([
            'message' => 'Item removed.',
            'totals'  => $this->cart->totals(),
        ]);
    }
}
