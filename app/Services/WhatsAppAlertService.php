<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Sends short WhatsApp alerts to the shop owner's own number through
 * CallMeBot's free API (https://www.callmebot.com). Configured in
 * Admin › Settings › WhatsApp Alerts (env values are the fallback).
 *
 * Alerts are sent after the response has been returned, so a slow or failing
 * WhatsApp call never delays or breaks the customer's checkout.
 */
class WhatsAppAlertService
{
    private const ENDPOINT = 'https://api.callmebot.com/whatsapp.php';

    public function enabled(): bool
    {
        return (bool) setting('whatsapp_alerts_enabled')
            && $this->recipient() !== null
            && filled(setting('callmebot_api_key'));
    }

    /** International number with "+" (CallMeBot format), e.g. +9779761612457. */
    public function recipient(): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) (setting('whatsapp_alert_number') ?: setting('whatsapp_number')));

        return strlen($digits) >= 8 ? '+'.$digits : null;
    }

    /**
     * Queue an alert to be sent once the current response is finished.
     * Skipped while seeding / running console commands, so demo data
     * never floods the owner's phone.
     */
    public function queue(string $text): void
    {
        if (! $this->enabled() || (app()->runningInConsole() && ! app()->runningUnitTests())) {
            return;
        }

        app()->terminating(fn () => $this->send($text));
    }

    /**
     * Send immediately. Returns ['ok' => bool, 'message' => string] for the
     * admin "send test" button; failures are logged, never thrown.
     */
    public function send(string $text): array
    {
        if (! $this->recipient() || blank(setting('callmebot_api_key'))) {
            return ['ok' => false, 'message' => 'Add the WhatsApp number and your CallMeBot API key first.'];
        }

        try {
            $response = Http::timeout(15)->get(self::ENDPOINT, [
                'phone'  => $this->recipient(),
                'text'   => Str::limit($text, 1000),
                'apikey' => setting('callmebot_api_key'),
            ]);

            $body = Str::squish(strip_tags($response->body()));
            $ok = $response->successful() && ! preg_match('/invalid|error|not (activated|allowed)|wrong/i', $body);

            if (! $ok) {
                Log::warning('WhatsApp alert failed', ['status' => $response->status(), 'response' => Str::limit($body, 300)]);
            }

            return ['ok' => $ok, 'message' => Str::limit($body ?: 'HTTP '.$response->status(), 200)];
        } catch (\Throwable $e) {
            Log::warning('WhatsApp alert failed', ['error' => $e->getMessage()]);

            return ['ok' => false, 'message' => 'Could not reach CallMeBot: '.$e->getMessage()];
        }
    }
}
