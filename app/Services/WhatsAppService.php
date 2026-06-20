<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;

/**
 * Builds wa.me deep links with pre-filled messages for the order
 * confirmation flow, product inquiries and custom-order inquiries.
 * The destination number is admin-configurable via Settings.
 */
class WhatsAppService
{
    public function number(): string
    {
        // wa.me expects digits only, no '+', spaces or dashes.
        return preg_replace('/\D+/', '', (string) setting('whatsapp_number', '977-9761612457'));
    }

    public function link(string $message): string
    {
        return 'https://wa.me/'.$this->number().'?text='.rawurlencode($message);
    }

    /**
     * The order-confirmation message defined by the business spec.
     */
    public function orderMessage(Order $order): string
    {
        $lines = $order->items->map(
            fn ($i) => "- {$i->name} x{$i->quantity} = ".money($i->line_total)
        )->implode("\n");

        $message = <<<TXT
        Hello,

        I would like to place an order.

        Order Number: {$order->order_number}
        Customer Name: {$order->customer_name}
        Phone: {$order->customer_phone}

        Products:
        {$lines}

        Order Total: {$this->amount($order->grand_total)}
        TXT;

        if ($order->requires_prepayment) {
            $message .= "\n\nRequired Advance Payment ({$this->percentLabel($order->prepayment_percent)}):\n"
                .$this->amount($order->advance_amount)
                ."\n\nRemaining COD Balance:\n".$this->amount($order->cod_balance);
        } else {
            $message .= "\n\nPayment: Full Cash On Delivery";
        }

        $message .= "\n\nPlease provide payment details for the advance payment.\n\nThank you.";

        return $message;
    }

    public function orderLink(Order $order): string
    {
        return $this->link($this->orderMessage($order));
    }

    public function productInquiryLink(Product $product): string
    {
        $url = route('products.show', $product->slug);

        $message = "Hello, I want this product:\n{$product->name}\n{$url}\n\nPlease provide more details.";

        return $this->link($message);
    }

    public function customOrderInquiryLink(): string
    {
        $message = "Hello, I'd like to discuss a custom crochet order. Could you help me with the details?";

        return $this->link($message);
    }

    private function amount(float $value): string
    {
        // Plain "NPR 1,200.00" form for messaging (no HTML).
        return setting('currency_symbol', 'NPR').' '.number_format($value, 2);
    }

    private function percentLabel(float $percent): string
    {
        return rtrim(rtrim(number_format($percent, 2), '0'), '.').'%';
    }
}
