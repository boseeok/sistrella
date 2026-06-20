@extends('layouts.admin')
@section('title', 'Settings')
@section('heading', 'Store Settings')

@php $s = fn($k, $d = '') => $settings[$k] ?? $d; @endphp

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST">@csrf @method('PUT')
<div class="row g-3">
    <div class="col-lg-6">
        <div class="card p-4 mb-3">
            <h6 class="fw-bold mb-3">Store Identity</h6>
            <div class="mb-2"><label class="form-label small">Store name *</label><input type="text" name="store_name" value="{{ $s('store_name') }}" class="form-control" required></div>
            <div class="mb-2"><label class="form-label small">Tagline</label><input type="text" name="store_tagline" value="{{ $s('store_tagline') }}" class="form-control"></div>
            <div class="mb-2"><label class="form-label small">Email *</label><input type="email" name="store_email" value="{{ $s('store_email') }}" class="form-control" required></div>
            <div class="mb-2"><label class="form-label small">Phone *</label><input type="text" name="store_phone" value="{{ $s('store_phone') }}" class="form-control" required></div>
            <div><label class="form-label small">Address</label><input type="text" name="store_address" value="{{ $s('store_address') }}" class="form-control"></div>
        </div>

        <div class="card p-4 mb-3">
            <h6 class="fw-bold mb-3">Prepayment Policy</h6>
            <div class="prepay-note p-2 small mb-3" style="background:#ccfbf1;border-left:4px solid #0D9488;border-radius:.4rem">Orders above the threshold require the advance %; smaller orders are full COD.</div>
            <div class="row g-2">
                <div class="col-md-6 mb-2"><label class="form-label small">Threshold *</label><input type="number" step="0.01" name="prepayment_threshold" value="{{ $s('prepayment_threshold') }}" class="form-control" required></div>
                <div class="col-md-6 mb-2"><label class="form-label small">Advance percent *</label><input type="number" step="0.01" name="prepayment_percent" value="{{ $s('prepayment_percent') }}" class="form-control" required></div>
                <div class="col-md-6 mb-2"><label class="form-label small">Tax rate (%) *</label><input type="number" step="0.01" name="tax_rate" value="{{ $s('tax_rate', '0') }}" class="form-control" required></div>
                <div class="col-md-6 mb-2"><label class="form-label small">Flat shipping *</label><input type="number" step="0.01" name="flat_shipping" value="{{ $s('flat_shipping', '0') }}" class="form-control" required></div>
            </div>
        </div>

        <div class="card p-4">
            <h6 class="fw-bold mb-3">Currency & Inventory</h6>
            <div class="row g-2">
                <div class="col-md-6 mb-2"><label class="form-label small">Currency code *</label><input type="text" name="currency_code" value="{{ $s('currency_code', 'NPR') }}" class="form-control" required></div>
                <div class="col-md-6 mb-2"><label class="form-label small">Currency symbol *</label><input type="text" name="currency_symbol" value="{{ $s('currency_symbol', 'NPR') }}" class="form-control" required></div>
                <div class="col-md-6"><label class="form-label small">Low stock threshold *</label><input type="number" name="low_stock_threshold" value="{{ $s('low_stock_threshold', '5') }}" class="form-control" required></div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card p-4 mb-3">
            <h6 class="fw-bold mb-3">Payment Details</h6>
            <div class="mb-2"><label class="form-label small">Bank name</label><input type="text" name="bank_name" value="{{ $s('bank_name') }}" class="form-control"></div>
            <div class="mb-2"><label class="form-label small">Account name</label><input type="text" name="bank_account_name" value="{{ $s('bank_account_name') }}" class="form-control"></div>
            <div class="mb-2"><label class="form-label small">Account number</label><input type="text" name="bank_account_number" value="{{ $s('bank_account_number') }}" class="form-control"></div>
            <div class="row g-2">
                <div class="col-md-6 mb-2"><label class="form-label small">eSewa ID</label><input type="text" name="esewa_id" value="{{ $s('esewa_id') }}" class="form-control"></div>
                <div class="col-md-6"><label class="form-label small">Khalti ID</label><input type="text" name="khalti_id" value="{{ $s('khalti_id') }}" class="form-control"></div>
            </div>
        </div>

        <div class="card p-4">
            <h6 class="fw-bold mb-3">Contact & Social</h6>
            <div class="mb-2"><label class="form-label small">WhatsApp number *</label><input type="text" name="whatsapp_number" value="{{ $s('whatsapp_number') }}" class="form-control" required></div>
            <div class="mb-2"><label class="form-label small">Instagram URL</label><input type="text" name="instagram_url" value="{{ $s('instagram_url') }}" class="form-control"></div>
            <div class="mb-2"><label class="form-label small">Facebook URL</label><input type="text" name="facebook_url" value="{{ $s('facebook_url') }}" class="form-control"></div>
            <div><label class="form-label small">TikTok URL</label><input type="text" name="tiktok_url" value="{{ $s('tiktok_url') }}" class="form-control"></div>
        </div>
    </div>
</div>
<div class="mt-3"><button class="btn btn-brand"><i class="bi bi-check-lg me-1"></i>Save Settings</button></div>
</form>
@endsection
