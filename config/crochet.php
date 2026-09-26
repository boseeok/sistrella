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
        'phone'      => env('STORE_PHONE', '9779800000000'),
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
        'number'   => env('WHATSAPP_NUMBER', '9779800000000'),
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

    /*
    |--------------------------------------------------------------------------
    | Home page "custom orders" promo section (editable in Admin › Settings)
    |--------------------------------------------------------------------------
    | points: one bullet per line. image: public-disk path; empty = use the
    | second hero banner's photo.
    */
    'promo' => [
        'eyebrow'     => 'Custom orders',
        'title'       => 'Made just for you',
        'text'        => 'Have a colour palette, a character or a gift in mind? Share your idea and a photo, and our makers will send a quote within a day.',
        'points'      => "Pick any yarn colours and sizes\nPersonalised names and details\nGift wrapping on request",
        'button_text' => 'Start a custom order',
        'button_link' => '/custom-order',
        'image'       => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | About page (editable in Admin › Page Content). "{store}" = store name;
    | empty title/subtitle fall back to "About {store}" and the tagline.
    |--------------------------------------------------------------------------
    */
    'about' => [
        'title'          => '',
        'subtitle'       => '',
        'body'           => '<p>Welcome to {store} — a small, passion-driven studio creating handmade crochet pieces from the heart of Nepal. Every item in our shop is crafted by hand using premium, skin-friendly yarn, making each piece unique and full of character.</p>'
            .'<p>From cuddly amigurumi and cozy wearables to charming home decor and everlasting crochet bouquets, we pour love and patience into every stitch. We also welcome custom orders — if you can dream it, we\'ll do our best to crochet it.</p>',
        'features_title' => 'Why shop with us?',
        'feature1_icon'  => 'hand-thumbs-up',
        'feature1_title' => '100% Handmade',
        'feature1_text'  => 'Crafted with care, never mass-produced.',
        'feature2_icon'  => 'truck',
        'feature2_title' => 'Cash on Delivery',
        'feature2_text'  => 'Convenient COD on eligible orders.',
        'feature3_icon'  => 'whatsapp',
        'feature3_title' => 'Friendly Support',
        'feature3_text'  => 'Chat with us anytime on WhatsApp.',
    ],

    /** Icons offered for the About page feature cards (Bootstrap Icons names). */
    'about_icons' => [
        'hand-thumbs-up' => 'Thumbs up', 'truck' => 'Delivery truck', 'whatsapp' => 'WhatsApp',
        'stars' => 'Stars', 'heart' => 'Heart', 'gift' => 'Gift', 'shield-check' => 'Shield',
        'flower1' => 'Flower', 'award' => 'Award', 'palette' => 'Palette', 'clock' => 'Clock', 'geo-alt' => 'Location',
    ],
];
