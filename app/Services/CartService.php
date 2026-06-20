<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Auth;

/**
 * Database-backed cart that works for both logged-in users and guests
 * (guests are keyed by session id). On login the guest cart is merged
 * into the user's cart. Exposes a single totals() method that produces
 * the money breakdown — including the prepayment split — used everywhere.
 */
class CartService
{
    public function __construct(
        private readonly CouponService $coupons,
        private readonly PrepaymentService $prepayment,
    ) {
    }

    /**
     * Resolve (or lazily create) the active cart for the current visitor.
     */
    public function current(bool $create = true): ?Cart
    {
        if ($userId = Auth::id()) {
            $cart = Cart::firstOrNew(['user_id' => $userId]);
        } else {
            $cart = Cart::firstOrNew(['session_id' => session()->getId()]);
        }

        if (! $cart->exists && $create) {
            $cart->save();
        }

        return $cart->exists ? $cart : ($create ? $cart : null);
    }

    public function addItem(Product $product, int $quantity = 1, ?int $variantId = null, array $options = []): CartItem
    {
        $cart = $this->current();

        $variant = $variantId ? ProductVariant::find($variantId) : null;

        $item = $cart->allItems()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variantId)
            ->where('saved_for_later', false)
            ->first();

        if ($item) {
            $item->quantity += $quantity;
            $item->save();

            return $item;
        }

        return $cart->allItems()->create([
            'product_id'         => $product->id,
            'product_variant_id' => $variant?->id,
            'quantity'           => max(1, $quantity),
            'options'            => $options ?: ($variant ? ['variant' => $variant->label] : null),
        ]);
    }

    public function updateQuantity(CartItem $item, int $quantity): void
    {
        if ($quantity <= 0) {
            $item->delete();

            return;
        }

        $item->update(['quantity' => $quantity]);
    }

    public function removeItem(CartItem $item): void
    {
        $item->delete();
    }

    public function saveForLater(CartItem $item): void
    {
        $item->update(['saved_for_later' => true]);
    }

    public function moveToCart(CartItem $item): void
    {
        $item->update(['saved_for_later' => false]);
    }

    public function clear(): void
    {
        $this->current()?->allItems()->delete();
        $this->current()?->update(['coupon_id' => null, 'coupon_code' => null]);
    }

    public function applyCoupon(string $code): array
    {
        $cart   = $this->current();
        $result = $this->coupons->validate($code, $this->subtotal($cart), Auth::id());

        if ($result['ok']) {
            $cart->update(['coupon_id' => $result['coupon']->id, 'coupon_code' => $result['coupon']->code]);
        }

        return $result;
    }

    public function removeCoupon(): void
    {
        $this->current()?->update(['coupon_id' => null, 'coupon_code' => null]);
    }

    public function itemCount(): int
    {
        $cart = $this->current(false);

        return $cart ? (int) $cart->items()->sum('quantity') : 0;
    }

    public function subtotal(?Cart $cart = null): float
    {
        $cart ??= $this->current(false);
        if (! $cart) {
            return 0;
        }

        return (float) $cart->items->sum(fn (CartItem $i) => $i->line_total);
    }

    /**
     * Full money breakdown for the active cart.
     *
     * @return array<string,mixed>
     */
    public function totals(?Cart $cart = null): array
    {
        $cart ??= $this->current(false);

        if (! $cart) {
            return $this->emptyTotals();
        }

        $cart->loadMissing('items.product', 'items.variant');

        $subtotal = $this->subtotal($cart);

        // Coupon
        $discount = 0.0;
        $coupon   = $cart->coupon;
        if ($coupon && $coupon->isValid($subtotal)) {
            $discount = $coupon->discountFor($subtotal);
        }

        $discounted = max(0, $subtotal - $discount);

        // Tax & shipping
        $taxRate  = (float) setting('tax_rate', 0);
        $tax      = round($discounted * ($taxRate / 100), 2);
        $shipping = (float) setting('flat_shipping', 0);

        $grandTotal = round($discounted + $tax + $shipping, 2);

        $prepayment = $this->prepayment->breakdown($grandTotal);

        return [
            'item_count'  => (int) $cart->items->sum('quantity'),
            'subtotal'    => round($subtotal, 2),
            'discount'    => round($discount, 2),
            'coupon_code' => $coupon?->code,
            'tax_rate'    => $taxRate,
            'tax'         => $tax,
            'shipping'    => $shipping,
            'grand_total' => $grandTotal,
            'prepayment'  => $prepayment,
        ];
    }

    /**
     * Merge a guest cart into the user's cart after login.
     */
    public function mergeGuestCart(string $guestSessionId, int $userId): void
    {
        $guestCart = Cart::where('session_id', $guestSessionId)->first();
        if (! $guestCart) {
            return;
        }

        $userCart = Cart::firstOrCreate(['user_id' => $userId]);

        foreach ($guestCart->allItems as $item) {
            $existing = $userCart->allItems()
                ->where('product_id', $item->product_id)
                ->where('product_variant_id', $item->product_variant_id)
                ->where('saved_for_later', $item->saved_for_later)
                ->first();

            if ($existing) {
                $existing->increment('quantity', $item->quantity);
            } else {
                $item->update(['cart_id' => $userCart->id]);
            }
        }

        $guestCart->delete();
    }

    private function emptyTotals(): array
    {
        $prepayment = $this->prepayment->breakdown(0);

        return [
            'item_count'  => 0,
            'subtotal'    => 0.0,
            'discount'    => 0.0,
            'coupon_code' => null,
            'tax_rate'    => (float) setting('tax_rate', 0),
            'tax'         => 0.0,
            'shipping'    => 0.0,
            'grand_total' => 0.0,
            'prepayment'  => $prepayment,
        ];
    }
}
