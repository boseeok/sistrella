<?php

namespace App\Services;

use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

/**
 * Renders downloadable / streamable PDF invoices for orders, including the
 * prepayment breakdown so the customer sees exactly what to pay now and on
 * delivery.
 */
class InvoiceService
{
    public function pdf(Order $order)
    {
        $order->loadMissing('items', 'payments', 'user');

        return Pdf::loadView('invoices.order', [
            'order' => $order,
        ])->setPaper('a4');
    }

    public function download(Order $order): Response
    {
        return $this->pdf($order)->download("invoice-{$order->order_number}.pdf");
    }

    public function stream(Order $order): Response
    {
        return $this->pdf($order)->stream("invoice-{$order->order_number}.pdf");
    }
}
