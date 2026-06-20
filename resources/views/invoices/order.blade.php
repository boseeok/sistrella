<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        * { font-family: DejaVu Sans, sans-serif; }
        body { color: #333; font-size: 12px; margin: 0; }
        .wrap { padding: 24px 32px; }
        .brand { color: #0D9488; font-size: 22px; font-weight: bold; }
        .muted { color: #888; }
        .right { text-align: right; }
        table { width: 100%; border-collapse: collapse; }
        .head td { vertical-align: top; padding-bottom: 16px; }
        .items th { background: #0D9488; color: #fff; padding: 8px; text-align: left; font-size: 11px; }
        .items td { padding: 8px; border-bottom: 1px solid #eee; }
        .totals td { padding: 4px 8px; }
        .totals .label { color: #666; }
        .grand { font-size: 15px; font-weight: bold; color: #0D9488; }
        .badge { display: inline-block; padding: 3px 10px; border-radius: 10px; background: #CCFBF1; color: #0D9488; font-size: 11px; }
        .box { background: #CCFBF1; border-left: 4px solid #0D9488; padding: 10px 14px; margin-top: 14px; border-radius: 4px; }
        .footer { margin-top: 30px; text-align: center; color: #999; font-size: 10px; border-top: 1px solid #eee; padding-top: 12px; }
    </style>
</head>
<body>
<div class="wrap">
    <table class="head">
        <tr>
            <td>
                <div class="brand">{{ setting('store_name', 'Crochet Store') }}</div>
                <div class="muted">{{ setting('store_tagline') }}</div>
                <div class="muted" style="margin-top:6px">
                    {{ setting('store_address') }}<br>
                    {{ setting('store_phone') }} · {{ setting('store_email') }}
                </div>
            </td>
            <td class="right">
                <div style="font-size:18px;font-weight:bold;">INVOICE</div>
                <div class="muted">{{ $order->order_number }}</div>
                <div class="muted">{{ $order->created_at->format('M d, Y') }}</div>
                <div style="margin-top:6px"><span class="badge">{{ $order->status_label }}</span></div>
            </td>
        </tr>
    </table>

    <table style="margin-bottom:16px">
        <tr>
            <td style="width:50%;vertical-align:top">
                <strong>Bill To</strong><br>
                {{ $order->customer_name }}<br>
                {{ $order->customer_phone }}<br>
                {{ $order->customer_email }}
            </td>
            <td style="width:50%;vertical-align:top">
                @if($order->shipping_address)
                    @php $a = $order->shipping_address; @endphp
                    <strong>Ship To</strong><br>
                    {{ collect([$a['line1']??null,$a['line2']??null,$a['city']??null,$a['district']??null,$a['province']??null,$a['postal_code']??null,$a['country']??null])->filter()->join(', ') }}
                @endif
            </td>
        </tr>
    </table>

    <table class="items">
        <thead>
            <tr><th>Item</th><th>SKU</th><th class="right">Price</th><th class="right">Qty</th><th class="right">Total</th></tr>
        </thead>
        <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->name }}
                        @if($item->options)<br><span class="muted" style="font-size:10px">{{ collect($item->options)->map(fn($v,$k)=>is_string($k)?"$k: $v":$v)->join(', ') }}</span>@endif
                    </td>
                    <td class="muted">{{ $item->sku }}</td>
                    <td class="right">{{ money($item->unit_price) }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">{{ money($item->line_total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table style="margin-top:12px">
        <tr>
            <td style="width:55%"></td>
            <td style="width:45%">
                <table class="totals">
                    <tr><td class="label">Subtotal</td><td class="right">{{ money($order->subtotal) }}</td></tr>
                    @if($order->discount_total > 0)<tr><td class="label">Discount @if($order->coupon_code)({{ $order->coupon_code }})@endif</td><td class="right">−{{ money($order->discount_total) }}</td></tr>@endif
                    @if($order->tax_total > 0)<tr><td class="label">Tax</td><td class="right">{{ money($order->tax_total) }}</td></tr>@endif
                    <tr><td class="label">Shipping</td><td class="right">{{ money($order->shipping_total) }}</td></tr>
                    <tr><td class="grand">Grand Total</td><td class="right grand">{{ money($order->grand_total) }}</td></tr>
                </table>
            </td>
        </tr>
    </table>

    @if($order->requires_prepayment)
        <div class="box">
            <strong>Prepayment Breakdown</strong><br>
            Advance ({{ rtrim(rtrim(number_format($order->prepayment_percent,2),'0'),'.') }}%): <strong>{{ money($order->advance_amount) }}</strong> &nbsp;·&nbsp;
            Balance on delivery: {{ money($order->cod_balance) }}<br>
            Amount paid: {{ money($order->amount_paid) }} &nbsp;·&nbsp; Balance remaining: {{ money($order->remaining_balance) }}
        </div>
    @else
        <div class="box">Payment method: <strong>Full Cash on Delivery</strong></div>
    @endif

    <div class="footer">
        Thank you for shopping with {{ setting('store_name', 'Crochet Store') }}! Handmade with love.<br>
        For questions, contact us at {{ setting('store_phone') }} or {{ setting('store_email') }}.
    </div>
</div>
</body>
</html>
