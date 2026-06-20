<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\CustomRequest;
use App\Services\CustomRequestService;
use App\Services\WhatsAppService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomRequestController extends Controller
{
    public function __construct(private readonly CustomRequestService $service)
    {
    }

    public function create(): View
    {
        return view('shop.custom.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'customer_name'           => ['required', 'string', 'max:255'],
            'customer_email'          => ['nullable', 'email', 'max:255'],
            'customer_phone'          => ['required', 'string', 'max:30'],
            'title'                   => ['required', 'string', 'max:255'],
            'notes'                   => ['nullable', 'string', 'max:2000'],
            'color'                   => ['nullable', 'string', 'max:60'],
            'size'                    => ['nullable', 'string', 'max:60'],
            'quantity'                => ['required', 'integer', 'min:1', 'max:999'],
            'preferred_delivery_date' => ['nullable', 'date', 'after:today'],
            'images'                  => ['nullable', 'array', 'max:6'],
            'images.*'                => ['image', 'max:4096'],
        ]);

        $request = $this->service->create($data, $request->file('images', []));

        return redirect()->route('custom.show', $request->request_number)
            ->with('success', 'Your custom request has been submitted! We will review it shortly.');
    }

    public function show(CustomRequest $customRequest, WhatsAppService $whatsapp): View
    {
        return view('shop.custom.show', [
            'request'      => $customRequest->load('images'),
            'whatsappLink' => $whatsapp->customOrderInquiryLink(),
        ]);
    }
}
