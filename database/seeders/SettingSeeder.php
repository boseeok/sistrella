<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

/**
 * Seeds the admin-editable business settings. These are the live values
 * surfaced through SettingService / the setting() helper; config/crochet.php
 * remains the fallback when a key is missing.
 */
class SettingSeeder extends Seeder
{
    /**
     * key => [value, type, group]
     */
    private array $settings = [
        // Store identity
        'store_name'    => ['Sistrella', 'string', 'general'],
        'store_tagline' => ['Handmade with love, one stitch at a time', 'string', 'general'],
        'store_email'   => ['hello@crochetstore.test', 'string', 'general'],
        'store_phone'   => ['977-9761612457', 'string', 'general'],
        'store_address' => ['Kathmandu, Nepal', 'string', 'general'],

        // Currency
        'currency_code'   => ['NPR', 'string', 'general'],
        'currency_symbol' => ['NPR', 'string', 'general'],

        // Prepayment policy (the signature business rule)
        'prepayment_threshold' => ['500', 'float', 'payments'],
        'prepayment_percent'   => ['50', 'float', 'payments'],

        // Tax & shipping
        'tax_rate'      => ['0', 'float', 'payments'],
        'flat_shipping' => ['100', 'float', 'payments'],

        // Bank / payment instructions shown on the advance-payment screen
        #'bank_name'           => ['Nepal Investment Bank', 'string', 'payments'],
        #'bank_account_name'   => ['Crochet Store Pvt. Ltd.', 'string', 'payments'],
        #'bank_account_number' => ['0123456789012', 'string', 'payments'],
        'esewa_id'            => ['977-9761612457', 'string', 'payments'],
        'khalti_id'           => ['977-9761612457', 'string', 'payments'],

        // Inventory
        'low_stock_threshold' => ['5', 'integer', 'inventory'],

        // Contact / social
        'whatsapp_number' => ['+977 9803404215', 'string', 'social'],
        'instagram_url'   => ['https://instagram.com/crochetstore', 'string', 'social'],
        'facebook_url'    => ['https://facebook.com/crochetstore', 'string', 'social'],
        'tiktok_url'      => ['', 'string', 'social'],
    ];

    public function run(): void
    {
        foreach ($this->settings as $key => [$value, $type, $group]) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type, 'group' => $group],
            );
        }
    }
}
