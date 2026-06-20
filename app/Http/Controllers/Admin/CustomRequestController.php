<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomRequest;
use App\Services\CustomRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomRequestController extends Controller
{
    public function __construct(private readonly CustomRequestService $service)
    {
    }

    public function index(Request $request): View
    {
        $query = CustomRequest::with('user')->latest();

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        return view('admin.custom-requests.index', [
            'requests' => $query->paginate(20)->withQueryString(),
            'statuses' => CustomRequest::STATUSES,
            'filters'  => $request->only('status'),
        ]);
    }

    public function show(CustomRequest $customRequest): View
    {
        return view('admin.custom-requests.show', [
            'request'  => $customRequest->load(['images', 'user', 'order']),
            'statuses' => CustomRequest::STATUSES,
        ]);
    }

    public function updateStatus(CustomRequest $customRequest, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'status'      => ['required', 'string'],
            'admin_notes' => ['nullable', 'string', 'max:2000'],
        ]);

        $this->service->setStatus($customRequest, $data['status']);
        if (array_key_exists('admin_notes', $data)) {
            $customRequest->update(['admin_notes' => $data['admin_notes']]);
        }

        return back()->with('success', 'Request updated.');
    }

    public function quote(CustomRequest $customRequest, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'quoted_price' => ['required', 'numeric', 'min:0'],
            'quote_note'   => ['nullable', 'string', 'max:1000'],
        ]);

        $this->service->quote($customRequest, (float) $data['quoted_price'], $data['quote_note'] ?? null);

        return back()->with('success', 'Quotation sent to the customer.');
    }

    public function convert(CustomRequest $customRequest): RedirectResponse
    {
        if (! $customRequest->quoted_price) {
            return back()->with('error', 'Add a quotation before converting to an order.');
        }

        $order = $this->service->convertToOrder($customRequest);

        return redirect()->route('admin.orders.show', $order->order_number)
            ->with('success', "Custom request converted to order {$order->order_number}.");
    }
}
