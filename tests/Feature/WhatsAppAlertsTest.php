<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Services\SettingService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * WhatsApp alerts to the shop owner (CallMeBot) for orders, payments,
 * custom requests and contact messages, plus the admin settings for them.
 */
class WhatsAppAlertsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Http::fake([
            'api.callmebot.com/*' => fn (Request $r) => $r['apikey'] === 'bad-key'
                ? Http::response('<p>APIKey is invalid. You need to get a new one</p>', 200)
                : Http::response('<p>Message queued. You will receive it in a few seconds.</p>', 200),
            '*' => Http::response('img', 200, ['Content-Type' => 'image/jpeg']),
        ]);
        $this->seed(); // demo orders are seeded while alerts are off: nothing is sent
    }

    private function enableAlerts(string $key = 'good-key'): void
    {
        app(SettingService::class)->setMany(['whatsapp_alerts_enabled' => true, 'callmebot_api_key' => $key]);
    }

    private function callMeBotRequests()
    {
        return Http::recorded(fn (Request $r) => str_contains($r->url(), 'api.callmebot.com'));
    }

    private function placeOrder(): void
    {
        $this->actingAs(User::factory()->create()); // checkout requires an account
        $product = Product::where('slug', 'single-sunflower-stem')->firstOrFail();
        $this->postJson('/cart/add', ['product_id' => $product->id])->assertOk();

        $this->post('/checkout/place', [
            'customer_name' => 'Asha Rai', 'customer_phone' => '9811111111',
            'line1' => 'Lazimpat', 'city' => 'Kathmandu', 'payment_choice' => 'cod',
        ])->assertRedirect();
    }

    public function test_no_alerts_while_disabled(): void
    {
        $this->placeOrder();

        $this->assertCount(0, $this->callMeBotRequests());
    }

    public function test_new_order_sends_whatsapp_alert_to_the_store_number(): void
    {
        $this->enableAlerts();
        $this->placeOrder();

        Http::assertSent(fn (Request $r) => str_contains($r->url(), 'api.callmebot.com')
            && $r['phone'] === '+9779761612457'            // store WhatsApp number from settings
            && $r['apikey'] === 'good-key'
            && str_contains($r['text'], 'New order placed')
            && str_contains($r['text'], 'Asha Rai')
            && str_contains($r['text'], '/admin/orders/'));
    }

    public function test_contact_message_and_custom_request_send_alerts(): void
    {
        $this->enableAlerts();
        app(SettingService::class)->set('whatsapp_alert_number', '+977 9800000001', 'string', 'social');

        $this->post('/contact', ['name' => 'Bina', 'email' => 'bina@example.com', 'message' => 'Do you ship to Pokhara?'])->assertRedirect();
        $this->post('/custom-order', ['customer_name' => 'Kiran', 'customer_phone' => '9812345678', 'title' => 'Name keychain', 'quantity' => 1])->assertRedirect();

        $texts = collect($this->callMeBotRequests())->map(fn ($pair) => $pair[0]['text']);
        $this->assertTrue($texts->contains(fn ($t) => str_contains($t, 'New contact message') && str_contains($t, 'Pokhara')));
        $this->assertTrue($texts->contains(fn ($t) => str_contains($t, 'New custom request') && str_contains($t, 'Name keychain')));
        Http::assertSent(fn (Request $r) => str_contains($r->url(), 'api.callmebot.com') && $r['phone'] === '+9779800000001');
    }

    public function test_admin_saves_alert_settings_and_key_is_kept_and_hidden(): void
    {
        $admin = User::where('email', 'admin@crochetstore.test')->firstOrFail();
        $base = app(SettingService::class)->all();

        $this->actingAs($admin)->put(route('admin.settings.update'), $base + [
            'whatsapp_alerts_enabled' => 1, 'callmebot_api_key' => 'secret-123', 'whatsapp_alert_number' => '',
        ])->assertSessionHasNoErrors();
        $this->assertTrue((bool) setting('whatsapp_alerts_enabled'));
        $this->assertSame('secret-123', setting('callmebot_api_key'));

        // Saving again with an empty key box keeps the stored key.
        $this->put(route('admin.settings.update'), app(SettingService::class)->all() + ['whatsapp_alerts_enabled' => 1, 'callmebot_api_key' => ''])
            ->assertSessionHasNoErrors();
        $this->assertSame('secret-123', setting('callmebot_api_key'));

        $this->get(route('admin.settings.index'))->assertOk()
            ->assertSee('WhatsApp Alerts')
            ->assertSee('saved — leave blank to keep')
            ->assertDontSee('secret-123');
    }

    public function test_send_test_message_reports_success_and_failure(): void
    {
        $admin = User::where('email', 'admin@crochetstore.test')->firstOrFail();

        $this->enableAlerts('good-key');
        $this->actingAs($admin)->post(route('admin.settings.whatsapp-test'))
            ->assertSessionHas('success', fn ($m) => str_contains($m, '+9779761612457'));

        $this->enableAlerts('bad-key');
        $this->post(route('admin.settings.whatsapp-test'))
            ->assertSessionHas('error', fn ($m) => str_contains($m, 'APIKey is invalid'));
    }
}
