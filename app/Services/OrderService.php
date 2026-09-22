<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

/**
 * Owns the order lifecycle: placing an order from the active cart (applying
 * the prepayment policy and snapshotting line items), and moving an order
 * through its status workflow with an audit history + stock adjustments.
 */
class OrderService
{
    public function __construct(
        private readonly CartService $cart,
        private readonly PrepaymentService $prepayment,
        private readonly ActivityLogger $logger,
        private readonly AdminNotifier $adminNotifier,
    ) {
    }

    /**
     * Create an order from the current cart.
     *
     * @param  array{customer_name:string, customer_email?:string, customer_phone:string,
     *               shipping_address?:array, notes?:string}  $data
     */
    public function placeFromCart(array $data): Order
    {
        $cart   = $this->cart->current(false);
        $totals = $this->cart->totals($cart);

        if (! $cart || $totals['item_count'] === 0) {
            throw ValidationException::withMessages(['cart' => 'Your cart is empty.']);
        }

        return DB::transaction(function () use ($cart, $totals, $data) {
            $pp = $totals['prepayment'];

            $order = Order::create([
                'order_number'         => $this->generateOrderNumber(),
                'user_id'              => Auth::id(),
                'customer_name'        => $data['customer_name'],
                'customer_email'       => $data['customer_email'] ?? Auth::user()?->email,
                'customer_phone'       => $data['customer_phone'],
                'shipping_address'     => $data['shipping_address'] ?? null,
                'billing_address'      => $data['billing_address'] ?? ($data['shipping_address'] ?? null),
                'subtotal'             => $totals['subtotal'],
                'discount_total'       => $totals['discount'],
                'tax_total'            => $totals['tax'],
                'shipping_total'       => $totals['shipping'],
                'grand_total'          => $totals['grand_total'],
                'requires_prepayment'  => $pp['requires_prepayment'],
                'prepayment_threshold' => $pp['threshold'],
                'prepayment_percent'   => $pp['percent'],
                'advance_amount'       => $pp['advance_amount'],
                'cod_balance'          => $pp['cod_balance'],
                'amount_paid'          => 0,
                'coupon_id'            => $cart->coupon_id,
                'coupon_code'          => $totals['coupon_code'],
                'payment_method'       => $data['payment_method'] ?? $pp['payment_method'],
                // Orders requiring prepayment start awaiting the advance;
                // pure-COD orders are confirmed immediately.
                'status'               => $pp['requires_prepayment'] ? 'pending_payment' : 'confirmed',
                'notes'                => $data['notes'] ?? null,
                'confirmed_at'         => $pp['requires_prepayment'] ? null : now(),
            ]);

            // Snapshot line items + decrement stock
            foreach ($cart->items as $item) {
                $product = $item->product;

                $order->items()->create([
                    'product_id'         => $product->id,
                    'product_variant_id' => $item->product_variant_id,
                    'name'               => $product->name,
                    'sku'                => $item->variant?->sku ?? $product->sku,
                    'options'            => $item->options,
                    'unit_price'         => $item->unit_price,
                    'quantity'           => $item->quantity,
                    'line_total'         => $item->line_total,
                ]);

                $this->decrementStock($product, $item->product_variant_id, $item->quantity);
                $product->increment('sales_count', $item->quantity);
            }

            // Record coupon usage
            if ($cart->coupon_id) {
                $cart->coupon?->increment('used_count');
                if (Auth::id()) {
                    DB::table('coupon_user')->insert([
                        'coupon_id'  => $cart->coupon_id,
                        'user_id'    => Auth::id(),
                        'order_id'   => $order->id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $order->statusHistory()->create([
                'from_status' => null,
                'to_status'   => $order->status,
                'note'        => 'Order placed.',
                'changed_by'  => Auth::id(),
            ]);

            // Empty the cart now that it has become an order
            $this->cart->clear();

            // Confirm to the customer that their order was received.
            if ($order->user) {
                $order->user->notify(new \App\Notifications\OrderStatusNotification(
                    $order,
                    'Order placed',
                    "Thanks! We've received your order {$order->order_number} ("
                        .money($order->grand_total).'). '
                        .($order->requires_prepayment ? 'Please pay the advance to confirm it.' : "It's confirmed for Cash on Delivery."),
                    'bi-bag-check',
                ));
            }

            // Alert staff that a new order has come in.
            $this->adminNotifier->notify(
                'orders.view',
                'New order placed',
                "Order {$order->order_number} ({$order->customer_name}) — ".money($order->grand_total).'.',
                'bi-receipt',
                route('admin.orders.show', $order->order_number),
            );

            return $order->fresh(['items']);
        });
    }

    /**
     * Move an order to a new status, append history and react to side-effects.
     */
    public function transitionTo(Order $order, string $status, ?string $note = null): Order
    {
        if (! array_key_exists($status, Order::STATUSES)) {
            throw ValidationException::withMessages(['status' => 'Unknown order status.']);
        }

        $from = $order->status;

        if ($from === $status) {
            return $order;
        }

        $payload = ['status' => $status];

        match ($status) {
            'confirmed' => $payload['confirmed_at'] = $order->confirmed_at ?? now(),
            'shipped'   => $payload['shipped_at'] = now(),
            'delivered' => $payload['delivered_at'] = now(),
            'cancelled' => $payload['cancelled_at'] = now(),
            default     => null,
        };

        // Restock when an order is cancelled or refunded.
        if (in_array($status, ['cancelled', 'refunded'], true) && ! in_array($from, ['cancelled', 'refunded'], true)) {
            $this->restock($order);
        }

        $order->update($payload);

        $order->statusHistory()->create([
            'from_status' => $from,
            'to_status'   => $status,
            'note'        => $note,
            'changed_by'  => Auth::id(),
        ]);

        $this->logger->log('order.status', "Order {$order->order_number}: {$from} → {$status}", $order, [
            'from' => $from, 'to' => $status,
        ]);

        // Notify the customer (registered users only) of the status change.
        if ($order->user) {
            $order->user->notify(new \App\Notifications\OrderStatusNotification(
                $order,
                'Order '.$order->status_label,
                "Your order {$order->order_number} is now {$order->status_label}.",
                $this->statusIcon($status),
            ));
        }

        return $order->fresh();
    }

    private function statusIcon(string $status): string
    {
        return match ($status) {
            'confirmed', 'partially_paid' => 'bi-check-circle',
            'processing'                  => 'bi-gear',
            'shipped'                     => 'bi-truck',
            'delivered'                   => 'bi-box-seam',
            'cancelled', 'refunded'       => 'bi-x-circle',
            default                       => 'bi-bell',
        };
    }

    public function generateOrderNumber(): string
    {
        $year   = now()->year;
        $prefix = "CRS-{$year}-";

        $last = Order::where('order_number', 'like', $prefix.'%')
            ->orderByDesc('id')
            ->value('order_number');

        $seq = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }

    private function decrementStock(Product $product, ?int $variantId, int $qty): void
    {
        if ($variantId) {
            $product->variants()->whereKey($variantId)->where('stock', '>=', $qty)->decrement('stock', $qty);

            return;
        }

        if ($product->track_inventory) {
            $product->decrement('stock', min($qty, $product->stock));
        }
    }

    private function restock(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->product_variant_id) {
                $item->variant?->increment('stock', $item->quantity);
            } elseif ($item->product && $item->product->track_inventory) {
                $item->product->increment('stock', $item->quantity);
            }
            $item->product?->decrement('sales_count', min($item->quantity, $item->product->sales_count));
        }
    }
}
