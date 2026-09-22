@extends('layouts.admin')
@section('title', 'Order '.$order->order_number)
@section('heading', 'Order '.$order->order_number)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-light"><i class="bi bi-chevron-left"></i> Back</a>
    <div class="d-flex gap-2">
        <span class="badge bg-{{ $order->status_color }} fs-6 align-self-center">{{ $order->status_label }}</span>
        <a href="{{ route('admin.orders.invoice', $order->order_number) }}" target="_blank" class="btn btn-sm btn-outline-brand"><i class="bi bi-file-earmark-pdf me-1"></i>Invoice</a>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        {{-- Items --}}
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Items</h6>
            <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                    <thead class="table-light"><tr><th>Product</th><th>SKU</th><th>Price</th><th>Qty</th><th class="text-end">Total</th></tr></thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->name }}@if($item->options)<br><small class="text-muted">{{ collect($item->options)->map(fn($v,$k)=>is_string($k)?"$k: $v":$v)->join(', ') }}</small>@endif</td>
                                <td class="small">{{ $item->sku }}</td>
                                <td>{{ money($item->unit_price) }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td class="text-end">{{ money($item->line_total) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6 ms-auto">
                    <div class="d-flex justify-content-between small"><span class="text-muted">Subtotal</span><span>{{ money($order->subtotal) }}</span></div>
                    @if($order->discount_total > 0)<div class="d-flex justify-content-between small text-success"><span>Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</span><span>−{{ money($order->discount_total) }}</span></div>@endif
                    @if($order->tax_total > 0)<div class="d-flex justify-content-between small"><span class="text-muted">Tax</span><span>{{ money($order->tax_total) }}</span></div>@endif
                    <div class="d-flex justify-content-between small"><span class="text-muted">Shipping</span><span>{{ money($order->shipping_total) }}</span></div>
                    <div class="d-flex justify-content-between fw-bold"><span>Total</span><span>{{ money($order->grand_total) }}</span></div>
                    @if($order->requires_prepayment)
                        <hr class="my-2">
                        <div class="d-flex justify-content-between small"><span>Advance</span><span>{{ money($order->advance_amount) }}</span></div>
                        <div class="d-flex justify-content-between small"><span>COD balance</span><span>{{ money($order->cod_balance) }}</span></div>
                    @endif
                    <div class="d-flex justify-content-between small text-success"><span>Paid</span><span>{{ money($order->amount_paid) }}</span></div>
                    <div class="d-flex justify-content-between small fw-semibold"><span>Balance</span><span>{{ money($order->remaining_balance) }}</span></div>
                </div>
            </div>
        </div>

        {{-- Payments --}}
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Payments</h6>
            @forelse($order->payments as $p)
                <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                    <div class="small">
                        <strong>{{ money($p->amount) }}</strong> · {{ ucfirst($p->kind) }} · {{ str_replace('_',' ',$p->method) }}
                        @if($p->reference)<br><span class="text-muted">Ref: {{ $p->reference }}</span>@endif
                        @if($p->proof_url)<br><a href="{{ $p->proof_url }}" target="_blank">View proof</a>@endif
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $p->status==='verified'?'success':($p->status==='rejected'?'danger':'warning text-dark') }}">{{ $p->status }}</span>
                        @if($p->status === 'submitted')
                            <div class="mt-1 d-flex gap-1">
                                <form action="{{ route('admin.payments.verify', $p) }}" method="POST">@csrf<button class="btn btn-success btn-sm">Verify</button></form>
                                <form action="{{ route('admin.payments.reject', $p) }}" method="POST">@csrf<button class="btn btn-outline-danger btn-sm">Reject</button></form>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <p class="text-muted small mb-0">No payments recorded.</p>
            @endforelse

            {{-- Record offline payment --}}
            <details class="mt-3">
                <summary class="small text-brand" style="cursor:pointer">+ Record a payment</summary>
                <form action="{{ route('admin.payments.record', $order->order_number) }}" method="POST" class="row g-2 mt-1">@csrf
                    <div class="col-md-3"><input type="number" step="0.01" name="amount" class="form-control form-control-sm" placeholder="Amount" required></div>
                    <div class="col-md-3"><select name="kind" class="form-select form-select-sm"><option value="advance">Advance</option><option value="balance">Balance</option><option value="full">Full</option></select></div>
                    <div class="col-md-3"><select name="method" class="form-select form-select-sm"><option value="cash">Cash</option><option value="bank_transfer">Bank</option><option value="esewa">eSewa</option><option value="khalti">Khalti</option><option value="other">Other</option></select></div>
                    <div class="col-md-3"><button class="btn btn-brand btn-sm w-100">Record</button></div>
                </form>
            </details>
        </div>

        {{-- Timeline --}}
        <div class="card p-3">
            <h6 class="fw-bold mb-3">History</h6>
            @foreach($order->statusHistory as $h)
                <div class="d-flex gap-2 mb-2 small">
                    <i class="bi bi-circle-fill text-brand mt-1" style="font-size:.5rem"></i>
                    <div>
                        <strong>{{ \App\Models\Order::STATUSES[$h->to_status] ?? ucfirst($h->to_status) }}</strong>
                        <span class="text-muted">— {{ $h->created_at->format('M d, H:i') }} @if($h->changedBy)by {{ $h->changedBy->name }}@endif</span>
                        @if($h->note)<div class="text-muted">{{ $h->note }}</div>@endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="col-lg-4">
        {{-- Update status --}}
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-3">Update Status</h6>
            <form action="{{ route('admin.orders.status', $order->order_number) }}" method="POST">@csrf @method('PATCH')
                <select name="status" class="form-select form-select-sm mb-2">
                    @foreach($statuses as $key => $label)
                        <option value="{{ $key }}" {{ $order->status === $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                <input type="text" name="note" class="form-control form-control-sm mb-2" placeholder="Note (optional)">
                <button class="btn btn-brand btn-sm w-100">Update</button>
            </form>
        </div>

        {{-- Customer --}}
        <div class="card p-3 mb-3">
            <h6 class="fw-bold mb-2">Customer</h6>
            <p class="small mb-1">{{ $order->customer_name }}</p>
            <p class="small mb-1 text-muted">{{ $order->customer_phone }}</p>
            @if($order->customer_email)<p class="small mb-1 text-muted">{{ $order->customer_email }}</p>@endif
            @if($order->user)<a href="{{ route('admin.customers.show', $order->user) }}" class="small">View customer →</a>@endif
        </div>

        {{-- Shipping --}}
        @if($order->shipping_address)
            <div class="card p-3 mb-3">
                <h6 class="fw-bold mb-2">Shipping Address</h6>
                @php $a = $order->shipping_address; @endphp
                <p class="small text-muted mb-0">{{ collect([$a['line1']??null,$a['line2']??null,$a['city']??null,$a['district']??null,$a['province']??null,$a['postal_code']??null])->filter()->join(', ') }}</p>
            </div>
        @endif

        {{-- Admin notes + tracking --}}
        <div class="card p-3">
            <h6 class="fw-bold mb-2">Internal</h6>
            <form action="{{ route('admin.orders.notes', $order->order_number) }}" method="POST">@csrf @method('PATCH')
                <label class="form-label small">Tracking number</label>
                <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" class="form-control form-control-sm mb-2">
                <label class="form-label small">Admin notes</label>
                <textarea name="admin_notes" rows="3" class="form-control form-control-sm mb-2">{{ $order->admin_notes }}</textarea>
                <button class="btn btn-outline-brand btn-sm w-100">Save</button>
            </form>
        </div>
    </div>
</div>
@endsection
