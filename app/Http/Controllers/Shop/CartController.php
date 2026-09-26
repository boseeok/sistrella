<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\CartItem;
use App\Models\Product;
use App\Repositories\ProductRepository;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly ProductRepository $products,
    ) {
    }

    public function index(): View
    {
        $cart   = $this->cart->current(false);
        $totals = $this->cart->totals($cart);

        return view('shop.cart.index', [
            'cart'        => $cart,
            'totals'      => $totals,
            'saved'       => $cart?->savedItems()->with('product.primaryImage')->get() ?? collect(),
            // Shown in the empty-cart state to help shoppers get started.
            'suggestions' => $totals['item_count'] === 0 ? $this->products->bestSellers(4) : collect(),
        ]);
    }

    public function add(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
            'quantity'   => ['nullable', 'integer', 'min:1', 'max:999'],
        ]);

        $product = Product::active()->findOrFail($data['product_id']);
        $quantity = $data['quantity'] ?? 1;

        // The variant must belong to this product (its price is what the cart charges).
        $variant = isset($data['variant_id'])
            ? $product->variants()->where('is_active', true)->with('attributeValues')->find($data['variant_id'])
            : null;

        if (isset($data['variant_id']) && ! $variant) {
            return $this->respond($request, false, 'Please choose a valid option for this product.');
        }

        if (! $variant && $product->variants()->where('is_active', true)->exists()) {
            return $this->respond($request, false, 'Please choose a colour / size first.');
        }

        if ($variant ? ! $variant->in_stock : ! $product->in_stock) {
            return $this->respond($request, false, 'Sorry, this '.($variant ? 'option' : 'product').' is out of stock.');
        }

        if ($variant && $product->track_inventory && $quantity > $variant->stock) {
            return $this->respond($request, false, "Only {$variant->stock} left in {$variant->label}.");
        }

        $this->cart->addItem($product, $quantity, $variant?->id);

        // "Buy now" skips the cart page and goes straight to checkout.
        if ($request->boolean('buy_now') && ! $request->wantsJson()) {
            return redirect()->route('checkout.index');
        }

        return $this->respond($request, true, "\"{$product->name}\" added to your cart.");
    }

    public function update(CartItem $item, Request $request): JsonResponse|RedirectResponse
    {
        $this->authorizeItem($item);
        $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:999']]);

        $this->cart->updateQuantity($item, (int) $request->quantity);

        return $this->respond($request, true, 'Cart updated.');
    }

    public function remove(CartItem $item, Request $request): JsonResponse|RedirectResponse
    {
        $this->authorizeItem($item);
        $this->cart->removeItem($item);

        return $this->respond($request, true, 'Item removed.');
    }

    public function saveForLater(CartItem $item, Request $request): RedirectResponse
    {
        $this->authorizeItem($item);
        $this->cart->saveForLater($item);

        return back()->with('success', 'Saved for later.');
    }

    public function moveToCart(CartItem $item, Request $request): RedirectResponse
    {
        $this->authorizeItem($item);
        $this->cart->moveToCart($item);

        return back()->with('success', 'Moved to cart.');
    }

    public function applyCoupon(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate(['code' => ['required', 'string', 'max:50']]);
        $result = $this->cart->applyCoupon($request->code);

        if ($request->wantsJson()) {
            return response()->json($result + ['totals' => $this->cart->totals()]);
        }

        return back()->with($result['ok'] ? 'success' : 'error', $result['message']);
    }

    public function removeCoupon(Request $request): JsonResponse|RedirectResponse
    {
        $this->cart->removeCoupon();

        return $this->respond($request, true, 'Coupon removed.');
    }

    public function count(): JsonResponse
    {
        return response()->json(['count' => $this->cart->itemCount()]);
    }

    private function respond(Request $request, bool $ok, string $message): JsonResponse|RedirectResponse
    {
        if ($request->wantsJson()) {
            return response()->json([
                'ok'      => $ok,
                'message' => $message,
                'count'   => $this->cart->itemCount(),
                'totals'  => $this->cart->totals(),
            ], $ok ? 200 : 422);
        }

        return back()->with($ok ? 'success' : 'error', $message);
    }

    /**
     * Ensure the cart item belongs to the visitor's active cart.
     */
    private function authorizeItem(CartItem $item): void
    {
        $cart = $this->cart->current(false);
        abort_unless($cart && $item->cart_id === $cart->id, 403);
    }
}
