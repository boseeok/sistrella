<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\InvoiceService;
use App\Services\PaymentService;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly WhatsAppService $whatsapp,
        private readonly PaymentService $payments,
        private readonly InvoiceService $invoices,
    ) {
    }

    public function confirmation(Order $order): View
    {
        $this->guard($order);

        return view('shop.orders.confirmation', [
            'order'        => $order->load('items'),
            'whatsappLink' => $this->whatsapp->orderLink($order),
        ]);
    }

    /**
     * STEP 3 of the prepayment flow: show the breakdown, then bounce the
     * customer to WhatsApp with the pre-filled order message.
     */
    public function whatsapp(Order $order): View
    {
        $this->guard($order);

        return view('shop.orders.whatsapp', [
            'order'        => $order->load('items'),
            'whatsappLink' => $this->whatsapp->orderLink($order),
        ]);
    }

    public function invoice(Order $order)
    {
        $this->guard($order);

        return $this->invoices->download($order);
    }

    public function payForm(Order $order): View
    {
        $this->guard($order);

        return view('shop.orders.pay', ['order' => $order]);
    }

    public function submitPayment(Order $order, Request $request): RedirectResponse
    {
        $this->guard($order);

        $data = $request->validate([
            'amount'    => ['required', 'numeric', 'min:1'],
            'method'    => ['required', 'in:esewa,khalti,bank_transfer,cash,other'],
            'reference' => ['nullable', 'string', 'max:120'],
            'note'      => ['nullable', 'string', 'max:500'],
            'proof'     => ['nullable', 'image', 'max:4096'],
            'kind'      => ['nullable', 'in:advance,balance,full'],
        ]);

        $this->payments->submit($order, $data, $request->file('proof'));

        return redirect()->route('orders.confirmation', $order->order_number)
            ->with('success', 'Payment submitted. We will verify it shortly.');
    }

    public function trackForm(): View
    {
        return view('shop.orders.track');
    }

    public function track(Request $request): View|RedirectResponse
    {
        $request->validate([
            'order_number' => ['required', 'string'],
            'phone'        => ['required', 'string'],
        ]);

        $order = Order::with(['items', 'statusHistory'])
            ->where('order_number', $request->order_number)
            ->where('customer_phone', $request->phone)
            ->first();

        if (! $order) {
            return back()->with('error', 'No matching order found. Check your order number and phone.');
        }

        return view('shop.orders.track', ['order' => $order]);
    }

    /**
     * Owners (or matching guest by session) may view an order; otherwise
     * only the confirmation right after placing is accessible.
     */
    private function guard(Order $order): void
    {
        if (auth()->check() && $order->user_id === auth()->id()) {
            return;
        }

        // Guests can view orders they just placed (kept in session flash list).
        $recent = (array) session('recent_orders', []);
        if (in_array($order->id, $recent, true)) {
            return;
        }

        // Allow first-view access then remember it for the guest session.
        if ($order->user_id === null) {
            session()->push('recent_orders', $order->id);

            return;
        }

        abort_unless(auth()->check() && $order->user_id === auth()->id(), 403);
    }
}
