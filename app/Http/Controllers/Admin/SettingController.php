<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use App\Services\WhatsAppAlertService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(private readonly SettingService $settings)
    {
    }

    public function index(WhatsAppAlertService $whatsapp): View
    {
        return view('admin.settings.index', [
            'settings'        => $this->settings->all(),
            'alertRecipient'  => $whatsapp->recipient(),
            'hasCallMeBotKey' => filled(setting('callmebot_api_key')),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_name'           => ['required', 'string', 'max:120'],
            'store_tagline'        => ['nullable', 'string', 'max:255'],
            'store_email'          => ['required', 'email', 'max:120'],
            'store_phone'          => ['required', 'string', 'max:40'],
            'store_address'        => ['nullable', 'string', 'max:255'],
            'currency_code'        => ['required', 'string', 'max:10'],
            'currency_symbol'      => ['required', 'string', 'max:10'],
            'prepayment_threshold' => ['required', 'numeric', 'min:0'],
            'prepayment_percent'   => ['required', 'numeric', 'min:0', 'max:100'],
            'tax_rate'             => ['required', 'numeric', 'min:0', 'max:100'],
            'flat_shipping'        => ['required', 'numeric', 'min:0'],
            'low_stock_threshold'  => ['required', 'integer', 'min:0'],
            'bank_name'            => ['nullable', 'string', 'max:120'],
            'bank_account_name'    => ['nullable', 'string', 'max:120'],
            'bank_account_number'  => ['nullable', 'string', 'max:60'],
            'esewa_id'             => ['nullable', 'string', 'max:60'],
            'khalti_id'            => ['nullable', 'string', 'max:60'],
            'whatsapp_number'      => ['required', 'string', 'max:40'],
            'instagram_url'        => ['nullable', 'string', 'max:255'],
            'facebook_url'         => ['nullable', 'string', 'max:255'],
            'tiktok_url'           => ['nullable', 'string', 'max:255'],
            // WhatsApp alerts to the owner (CallMeBot)
            'whatsapp_alert_number' => ['nullable', 'string', 'max:40', 'regex:/^[+\d\s()-]*$/'],
            'callmebot_api_key'     => ['nullable', 'string', 'max:100'],
        ], ['whatsapp_alert_number.regex' => 'The alert number may only contain digits, spaces, +, - and brackets.']);

        // Cast numerics so SettingService stores the right type.
        foreach (['prepayment_threshold', 'prepayment_percent', 'tax_rate', 'flat_shipping'] as $k) {
            $data[$k] = (float) $data[$k];
        }
        $data['low_stock_threshold'] = (int) $data['low_stock_threshold'];

        $data['whatsapp_alerts_enabled'] = $request->boolean('whatsapp_alerts_enabled');
        $data['whatsapp_alert_number'] = $data['whatsapp_alert_number'] ?? '';

        // The API key is never shown again: an empty box keeps the saved key.
        if (blank($data['callmebot_api_key'] ?? null)) {
            unset($data['callmebot_api_key']);
        }

        $this->settings->setMany($data);

        return back()->with('success', 'Settings saved.');
    }

    /**
     * Send a test WhatsApp alert with the saved settings.
     */
    public function testWhatsApp(WhatsAppAlertService $whatsapp): RedirectResponse
    {
        $result = $whatsapp->send(setting('store_name', 'Sistrella').': test alert from Admin › Settings. WhatsApp alerts are working!');

        return back()->with(
            $result['ok'] ? 'success' : 'error',
            $result['ok']
                ? 'Test message sent to '.$whatsapp->recipient().' — check WhatsApp.'
                : 'WhatsApp test failed: '.$result['message']
        );
    }
}
