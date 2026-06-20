@extends('layouts.app')
@section('title', 'Submit Payment')

@section('content')
<div class="container" style="max-width:640px">
    <h2 class="section-title mb-4">Submit Payment</h2>

    <div class="card p-4 mb-3">
        <div class="d-flex justify-content-between"><span class="text-muted">Order</span><strong>{{ $order->order_number }}</strong></div>
        <div class="d-flex justify-content-between"><span class="text-muted">Total</span><strong>{{ money($order->grand_total) }}</strong></div>
        @if($order->requires_prepayment)
            <div class="d-flex justify-content-between"><span class="text-muted">Advance due</span><strong class="text-brand">{{ money($order->advance_amount) }}</strong></div>
        @endif
        <div class="d-flex justify-content-between"><span class="text-muted">Paid so far</span><span class="text-success">{{ money($order->amount_paid) }}</span></div>
    </div>

    {{-- Payment instructions --}}
    <div class="card p-4 mb-3">
        <h6 class="fw-bold">Where to pay</h6>
        <ul class="list-unstyled small mb-0">
            @if(setting('bank_name'))<li><strong>Bank:</strong> {{ setting('bank_name') }}</li>@endif
            @if(setting('bank_account_name'))<li><strong>Account name:</strong> {{ setting('bank_account_name') }}</li>@endif
            @if(setting('bank_account_number'))<li><strong>Account no:</strong> {{ setting('bank_account_number') }}</li>@endif
            @if(setting('esewa_id'))<li><strong>eSewa:</strong> {{ setting('esewa_id') }}</li>@endif
            @if(setting('khalti_id'))<li><strong>Khalti:</strong> {{ setting('khalti_id') }}</li>@endif
        </ul>
    </div>

    <div class="card p-4">
        <h6 class="fw-bold mb-3">Confirm your payment</h6>
        <form action="{{ route('orders.pay.submit', $order->order_number) }}" method="POST" enctype="multipart/form-data">@csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label small">Amount paid *</label>
                    <input type="number" step="0.01" name="amount" value="{{ old('amount', $order->requires_prepayment ? $order->advance_amount : $order->remaining_balance) }}" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label small">Method *</label>
                    <select name="method" class="form-select" required>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="esewa">eSewa</option>
                        <option value="khalti">Khalti</option>
                        <option value="cash">Cash</option>
                        <option value="other">Other</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small">Payment type</label>
                    <select name="kind" class="form-select">
                        <option value="advance">Advance</option>
                        <option value="balance">Balance</option>
                        <option value="full">Full payment</option>
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small">Transaction reference</label>
                    <input type="text" name="reference" value="{{ old('reference') }}" class="form-control" placeholder="e.g. txn id">
                </div>
                <div class="col-12">
                    <label class="form-label small">Proof of payment (screenshot)</label>
                    <input type="file" name="proof" accept="image/*" class="form-control">
                </div>
                <div class="col-12">
                    <label class="form-label small">Note</label>
                    <textarea name="note" rows="2" class="form-control" placeholder="Anything we should know?">{{ old('note') }}</textarea>
                </div>
            </div>
            <button class="btn btn-brand w-100 mt-3">Submit for Verification</button>
        </form>
    </div>
</div>
@endsection
