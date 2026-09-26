<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Public portfolio demo (DEMO_MODE): notices and login hints are shown,
 * the admin panel can be browsed but not changed, the storefront still works.
 */
class DemoModeTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Http::fake(['*' => Http::response('img', 200, ['Content-Type' => 'image/jpeg'])]);
        config(['crochet.seed_admin_password' => 'demo-pass-123']);
        $this->seed();
    }

    private function enableDemo(bool $readOnly = true): void
    {
        config(['crochet.demo.enabled' => true, 'crochet.demo.admin_read_only' => $readOnly]);
    }

    public function test_demo_notices_are_hidden_by_default(): void
    {
        $this->get('/')->assertOk()->assertDontSee('Portfolio demo');
        $this->get('/admin/login')->assertOk()->assertDontSee('demo-pass-123');
    }

    public function test_demo_shows_notices_and_login_hints(): void
    {
        $this->enableDemo();

        $this->get('/')->assertOk()->assertSee('Portfolio demo');
        $this->get('/login')->assertOk()->assertDontSee('aarati@example.com'); // no customer hint
        $this->get('/admin/login')->assertOk()->assertSee('admin@crochetstore.test')->assertSee('demo-pass-123');

        $this->post(route('admin.login.submit'), ['email' => 'admin@crochetstore.test', 'password' => 'demo-pass-123'])
            ->assertRedirect();
        $this->get('/admin')->assertOk()->assertSee('Read-only demo');
    }

    public function test_admin_is_read_only_in_demo(): void
    {
        $this->enableDemo();
        $admin = User::where('email', 'admin@crochetstore.test')->firstOrFail();
        $product = Product::where('slug', 'tulip-trio')->firstOrFail();

        $this->actingAs($admin);
        foreach (['/admin/products', route('admin.products.edit', $product), '/admin/settings', '/admin/collections'] as $url) {
            $this->get($url)->assertOk(); // browsing works
        }

        $this->from(route('admin.products.edit', $product))
            ->put(route('admin.products.update', $product), ['name' => 'Hacked', 'price' => 1, 'stock' => 1, 'low_stock_threshold' => 1, 'type' => 'simple'])
            ->assertRedirect(route('admin.products.edit', $product))
            ->assertSessionHas('error');
        $this->assertSame('Tulip Trio', $product->fresh()->name);

        $this->delete(route('admin.products.destroy', $product))->assertSessionHas('error');
        $this->assertModelExists($product);

        $this->put(route('admin.settings.update'), ['store_name' => 'Hacked'])->assertSessionHas('error');
        $this->assertNotSame('Hacked', setting('store_name'));
    }

    public function test_admin_can_edit_when_demo_is_not_read_only(): void
    {
        $this->enableDemo(readOnly: false);
        $product = Product::where('slug', 'tulip-trio')->firstOrFail();

        $this->actingAs(User::where('email', 'admin@crochetstore.test')->firstOrFail())
            ->put(route('admin.products.update', $product), [
                'name' => 'Tulip Trio Deluxe', 'price' => 800, 'stock' => 5, 'low_stock_threshold' => 2, 'type' => 'simple', 'is_active' => 1,
            ])->assertSessionHasNoErrors();
        $this->assertSame('Tulip Trio Deluxe', $product->fresh()->name);
    }

    public function test_storefront_still_works_in_demo(): void
    {
        $this->enableDemo();
        $product = Product::where('slug', 'penguin-keychain')->firstOrFail();

        $this->withCredentials()->withCookie(config('session.cookie'), session()->getId())
            ->postJson('/cart/add', ['product_id' => $product->id])->assertOk()->assertJson(['ok' => true]);
    }
}
