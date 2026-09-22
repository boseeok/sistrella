<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\OrderRepository;
use App\Services\InvoiceService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepository $orders,
        private readonly OrderService $orderService,
        private readonly InvoiceService $invoices,
    ) {
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['status', 'search', 'from', 'to']);

        return view('admin.orders.index', [
            'orders'   => $this->orders->adminList($filters),
            'filters'  => $filters,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function show(Order $order): View
    {
        $order->load(['items.product', 'payments.verifier', 'statusHistory.changedBy', 'user', 'coupon']);

        return view('admin.orders.show', [
            'order'    => $order,
            'statuses' => Order::STATUSES,
        ]);
    }

    public function updateStatus(Order $order, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string'],
            'note'   => ['nullable', 'string', 'max:500'],
        ]);

        $this->orderService->transitionTo($order, $data['status'], $data['note'] ?? null);

        return back()->with('success', 'Order status updated.');
    }

    public function updateNotes(Order $order, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'admin_notes'     => ['nullable', 'string', 'max:2000'],
            'tracking_number' => ['nullable', 'string', 'max:120'],
        ]);

        $order->update($data);

        return back()->with('success', 'Order details saved.');
    }

    public function invoice(Order $order)
    {
        return $this->invoices->stream($order);
    }
}
