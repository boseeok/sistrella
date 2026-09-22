<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\PaymentService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(private readonly PaymentService $payments)
    {
    }

    public function index(Request $request): View
    {
        $query = Payment::with('order')->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        return view('admin.payments.index', [
            'payments' => $query->paginate(20)->withQueryString(),
            'filters'  => $request->only('status'),
        ]);
    }

    /**
     * The verification queue: payments awaiting admin action.
     */
    public function queue(): View
    {
        return view('admin.payments.queue', [
            'payments' => Payment::with('order')
                ->where('status', 'submitted')
                ->latest()
                ->paginate(20),
        ]);
    }

    public function verify(Payment $payment, Request $request): RedirectResponse
    {
        $request->validate(['admin_note' => ['nullable', 'string', 'max:500']]);

        $this->payments->verify($payment, $request->get('admin_note'));

        return back()->with('success', 'Payment verified and order updated.');
    }

    public function reject(Payment $payment, Request $request): RedirectResponse
    {
        $request->validate(['admin_note' => ['nullable', 'string', 'max:500']]);

        $this->payments->reject($payment, $request->get('admin_note'));

        return back()->with('success', 'Payment rejected.');
    }

    /**
     * Admin records a payment received offline (e.g. cash) on behalf of a
     * customer. It is created already verified.
     */
    public function record(Order $order, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'amount'    => ['required', 'numeric', 'min:1'],
            'kind'      => ['required', 'in:advance,balance,full,refund'],
            'method'    => ['required', 'in:esewa,khalti,bank_transfer,cash,other'],
            'reference' => ['nullable', 'string', 'max:120'],
            'note'      => ['nullable', 'string', 'max:500'],
        ]);

        $payment = $order->payments()->create($data + ['status' => 'submitted']);
        $this->payments->verify($payment, 'Recorded by admin.');

        return back()->with('success', 'Payment recorded and verified.');
    }
}
