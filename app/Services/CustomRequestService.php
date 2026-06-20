<?php

namespace App\Services;

use App\Models\CustomRequest;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * Manages the custom crochet request workflow: intake (with inspiration
 * image uploads), admin review/quotation, and conversion of an accepted
 * quote into a real order that follows the standard prepayment policy.
 */
class CustomRequestService
{
    public function __construct(
        private readonly OrderService $orders,
        private readonly PrepaymentService $prepayment,
        private readonly AdminNotifier $adminNotifier,
    ) {
    }

    public function create(array $data, array $images = []): CustomRequest
    {
        return DB::transaction(function () use ($data, $images) {
            $request = CustomRequest::create([
                'request_number'          => $this->generateNumber(),
                'user_id'                 => Auth::id(),
                'customer_name'           => $data['customer_name'],
                'customer_email'          => $data['customer_email'] ?? Auth::user()?->email,
                'customer_phone'          => $data['customer_phone'],
                'title'                   => $data['title'],
                'notes'                   => $data['notes'] ?? null,
                'color'                   => $data['color'] ?? null,
                'size'                    => $data['size'] ?? null,
                'quantity'                => $data['quantity'] ?? 1,
                'preferred_delivery_date' => ['required', 'date', 'after_or_equal:today'],
                'status'                  => 'pending',
            ]);

            foreach ($images as $image) {
                $path = $image->store('custom-requests', 'public');
                $request->images()->create(['path' => $path, 'type' => 'inspiration']);
            }

            $this->adminNotifier->notify(
                'custom.manage',
                'New custom request',
                "{$request->customer_name} requested: {$request->title} ({$request->request_number}).",
                'bi-stars',
                route('admin.custom.show', $request->request_number),
            );

            return $request->load('images');
        });
    }

    public function quote(CustomRequest $request, float $price, ?string $note = null): CustomRequest
    {
        $request->update([
            'quoted_price' => $price,
            'quote_note'   => $note,
            'quoted_at'    => now(),
            'status'       => 'quoted',
        ]);

        return $request;
    }

    public function setStatus(CustomRequest $request, string $status): CustomRequest
    {
        $request->update(['status' => $status]);

        return $request;
    }

    /**
     * Convert an accepted custom request into an order. The order total is
     * the quoted price and follows the same prepayment policy as the shop.
     */
    public function convertToOrder(CustomRequest $request): Order
    {
        return DB::transaction(function () use ($request) {
            $total = (float) $request->quoted_price;
            $pp    = $this->prepayment->breakdown($total);

            $order = Order::create([
                'order_number'         => $this->orders->generateOrderNumber(),
                'user_id'              => $request->user_id,
                'customer_name'        => $request->customer_name,
                'customer_email'       => $request->customer_email,
                'customer_phone'       => $request->customer_phone,
                'subtotal'             => $total,
                'grand_total'          => $total,
                'requires_prepayment'  => $pp['requires_prepayment'],
                'prepayment_threshold' => $pp['threshold'],
                'prepayment_percent'   => $pp['percent'],
                'advance_amount'       => $pp['advance_amount'],
                'cod_balance'          => $pp['cod_balance'],
                'payment_method'       => $pp['payment_method'],
                'status'               => $pp['requires_prepayment'] ? 'pending_payment' : 'confirmed',
                'notes'                => "Custom order from request {$request->request_number}: {$request->title}",
            ]);

            $order->items()->create([
                'name'       => "Custom: {$request->title}",
                'sku'        => $request->request_number,
                'options'    => array_filter(['Color' => $request->color, 'Size' => $request->size]),
                'unit_price' => $total / max(1, $request->quantity),
                'quantity'   => $request->quantity,
                'line_total' => $total,
            ]);

            $order->statusHistory()->create([
                'to_status'  => $order->status,
                'note'       => 'Created from custom request.',
                'changed_by' => Auth::id(),
            ]);

            $request->update(['status' => 'accepted', 'order_id' => $order->id]);

            return $order;
        });
    }

    public function generateNumber(): string
    {
        $year   = now()->year;
        $prefix = "CCR-{$year}-";
        $last   = CustomRequest::where('request_number', 'like', $prefix.'%')->orderByDesc('id')->value('request_number');
        $seq    = $last ? ((int) substr($last, strlen($prefix))) + 1 : 1;

        return $prefix.str_pad((string) $seq, 4, '0', STR_PAD_LEFT);
    }
}
