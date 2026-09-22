<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cart,
        private readonly OrderService $orders,
    ) {
    }

    public function index(): View|RedirectResponse
    {
        $cart   = $this->cart->current(false);
        $totals = $this->cart->totals($cart);

        if ($totals['item_count'] === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('shop.checkout.index', [
            'cart'      => $cart,
            'totals'    => $totals,
            'addresses' => auth()->user()?->addresses ?? collect(),
        ]);
    }

    /**
     * Place the order, then route the customer to the appropriate next step:
     *   - prepayment required  => WhatsApp redirect (advance payment flow)
     *   - full COD             => order confirmation page
     */
    public function place(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_name'  => ['required', 'string', 'max:255'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'line1'          => ['required', 'string', 'max:255'],
            'line2'          => ['nullable', 'string', 'max:255'],
            'city'           => ['required', 'string', 'max:120'],
            'district'       => ['nullable', 'string', 'max:120'],
            'province'       => ['nullable', 'string', 'max:120'],
            'postal_code'    => ['nullable', 'string', 'max:20'],
            'notes'          => ['nullable', 'string', 'max:1000'],
            'payment_choice' => ['required', 'in:prepayment,cod'],
        ]);

        $shipping = [
            'full_name'   => $data['customer_name'],
            'phone'       => $data['customer_phone'],
            'line1'       => $data['line1'],
            'line2'       => $data['line2'] ?? null,
            'city'        => $data['city'],
            'district'    => $data['district'] ?? null,
            'province'    => $data['province'] ?? null,
            'postal_code' => $data['postal_code'] ?? null,
            'country'     => 'Nepal',
        ];

        $order = $this->orders->placeFromCart([
            'customer_name'    => $data['customer_name'],
            'customer_email'   => $data['customer_email'] ?? null,
            'customer_phone'   => $data['customer_phone'],
            'shipping_address' => $shipping,
            'notes'            => $data['notes'] ?? null,
        ]);

        // Prepayment orders are sent through WhatsApp to arrange the advance.
        if ($order->requires_prepayment) {
            return redirect()->route('orders.whatsapp', $order->order_number);
        }

        return redirect()->route('orders.confirmation', $order->order_number)
            ->with('success', 'Your order has been placed! Pay cash on delivery.');
    }
}
