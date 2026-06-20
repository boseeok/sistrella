<?php

/**
 * Crochet Store — business configuration.
 *
 * These values are the immutable fallback defaults. The live, admin-editable
 * values live in the `settings` table and are surfaced through the
 * App\Services\SettingService (cached). Always read business settings via
 * `setting('key')` so the admin UI stays authoritative; use this file only
 * as the seed/fallback source.
 */

return [

    'store' => [
        'name'       => env('APP_NAME', 'Crochet Store'),
        'phone'      => env('STORE_PHONE', '977-9761612457'),
        'email'      => env('STORE_EMAIL', 'hello@crochetstore.test'),
        'address'    => 'Kathmandu, Nepal',
    ],

    'currency' => [
        'code'   => env('STORE_CURRENCY', 'NPR'),
        'symbol' => env('STORE_CURRENCY_SYMBOL', 'NPR'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Prepayment Policy
    |--------------------------------------------------------------------------
    | The core business rule of the platform:
    |   - Orders with a total <= threshold  => full Cash On Delivery allowed.
    |   - Orders with a total >  threshold  => a mandatory advance (percent of
    |     the total) must be paid to confirm the order; the remainder is COD.
    */
    'prepayment' => [
        'threshold' => (float) env('PREPAYMENT_THRESHOLD', 500),
        'percent'   => (float) env('PREPAYMENT_PERCENT', 50),
        'message'   => 'Orders above NPR :threshold require :percent% advance payment to confirm your order.',
    ],

    'whatsapp' => [
        'number'   => env('WHATSAPP_NUMBER', '977-9761612457'),
        'base_url' => 'https://wa.me/',
    ],

    'social' => [
        'instagram' => env('INSTAGRAM_URL', 'https://instagram.com/crochetstore'),
        'facebook'  => env('FACEBOOK_URL', 'https://facebook.com/crochetstore'),
    ],

    'inventory' => [
        'low_stock_threshold' => (int) env('LOW_STOCK_THRESHOLD', 5),
    ],

    'tax' => [
        'rate' => (float) env('TAX_RATE', 0), // percentage, e.g. 13 for VAT
    ],
];
