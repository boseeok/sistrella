<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Customer-facing flows against the full demo catalogue
 * (stock-photo downloads are faked, so this runs offline).
 */
class StorefrontTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Http::fake(['*' => Http::response('img', 200, ['Content-Type' => 'image/jpeg'])]);
        $this->seed();
    }

    /**
     * Guest carts are keyed by session id; the test client doesn't keep
     * cookies between requests, so send the session cookie like a browser.
     */
    private function keepSession(): static
    {
        return $this->withCredentials()->withCookie(config('session.cookie'), session()->getId());
    }

    public function test_home_page_renders_merchandising_sections(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('<h1', false)
            ->assertSee('Shop our handmade collections')
            ->assertSee('Ribbon Bouquets')
            ->assertSee('Fuzzy Wire')
            ->assertSee('Shop by occasion')
            ->assertSee(route('collections.show', 'valentines-day'), false)
            ->assertDontSee('Curated collections')
            ->assertSee('New arrivals')
            ->assertDontSee('Featured pieces')
            ->assertSee('Amigurumi')
            ->assertSee('rel="canonical"', false);
    }

    public function test_shop_listing_supports_collections_sorting_and_price_filters(): void
    {
        $this->get('/shop')->assertOk()->assertSee('Shop All');

        $this->get('/shop?collection=new&per_page=48')->assertOk()->assertSee('New Arrivals')
            ->assertSee('pbadge-new', false)
            ->assertDontSee('Cozy Cardigan Sweater'); // not flagged as new

        $this->get('/shop?collection=sale&sort=price_asc')->assertOk()
            ->assertSee('Penguin Keychain')       // cheapest item on sale
            ->assertDontSee('Tiny Bunny Plush');  // not on sale

        $this->get('/shop?min_price=1500')->assertOk()
            ->assertSee('Cozy Cardigan Sweater')
            ->assertDontSee('Penguin Keychain');

        // Malformed query strings are ignored instead of erroring.
        $this->get('/shop?sort[]=x&search[]=y&min_price=abc')->assertOk();
    }

    public function test_parent_category_includes_sub_category_products(): void
    {
        $this->get('/category/amigurumi')->assertOk()
            ->assertSee('Cuddly Bear Amigurumi')   // Amigurumi › Animals
            ->assertSee('Penguin Keychain')        // Amigurumi › Keychains
            ->assertDontSee('Chunky Knit Beanie');

        $this->get('/category/amigurumi-animals')->assertOk()
            ->assertSee('Cuddly Bear Amigurumi')
            ->assertDontSee('Penguin Keychain');
    }

    public function test_every_leaf_category_has_products(): void
    {
        Category::doesntHave('children')->withCount('products')->get()
            ->each(fn ($c) => $this->assertGreaterThan(0, $c->products_count, "{$c->slug} is empty"));
    }

    public function test_search_matches_multiple_words_across_fields(): void
    {
        $this->get('/search?search=pink+bunny')->assertOk()
            ->assertSee('Tiny Bunny Plush')
            ->assertSee('noindex', false);

        // Category (parent) name matches
        $this->get('/search?search=wearables')->assertOk()->assertSee('Striped Winter Scarf');

        $this->get('/search?search=zzzznotfound')->assertOk()->assertSee('No matches found');
    }

    public function test_product_page_shows_gallery_details_and_structured_data(): void
    {
        $this->get('/product/cuddly-bear-amigurumi')->assertOk()
            ->assertSee('Cuddly Bear Amigurumi')
            ->assertSee('application/ld+json', false)
            ->assertSee('"@type":"Product"', false)
            ->assertSee('Add to cart')
            ->assertSee('Buy it now')
            ->assertSee('Product details')
            ->assertSee('You may also like');
    }

    public function test_inactive_products_are_not_viewable(): void
    {
        Product::where('slug', 'tulip-trio')->update(['is_active' => false]);

        $this->get('/product/tulip-trio')->assertNotFound();
        $this->get('/shop?search=tulip')->assertDontSee('Tulip Trio');
    }

    public function test_cart_add_update_remove_and_empty_state(): void
    {
        $product = Product::where('slug', 'tiny-bunny-plush')->first();

        $this->keepSession()->postJson('/cart/add', ['product_id' => $product->id, 'quantity' => 2])
            ->assertOk()->assertJson(['ok' => true, 'count' => 2]);

        $item = CartItem::where('product_id', $product->id)->firstOrFail();

        $this->get('/cart')->assertOk()->assertSee('Tiny Bunny Plush')->assertSee('Order summary');

        $this->patch("/cart/items/{$item->id}", ['quantity' => 3])->assertRedirect();
        $this->assertSame(3, $item->fresh()->quantity);

        $this->delete("/cart/items/{$item->id}")->assertRedirect();
        $this->get('/cart')->assertOk()->assertSee('Your cart is empty')->assertSee('Customer favourites');
    }

    public function test_out_of_stock_products_cannot_be_added(): void
    {
        $product = Product::where('slug', 'sleepy-cat-doll')->first(); // seeded with stock 0

        $this->keepSession()->postJson('/cart/add', ['product_id' => $product->id])->assertStatus(422);
    }

    public function test_buy_now_goes_straight_to_checkout(): void
    {
        $this->actingAs(User::factory()->create());
        $product = Product::where('slug', 'penguin-keychain')->first();

        $this->post('/cart/add', ['product_id' => $product->id, 'buy_now' => 1])
            ->assertRedirect(route('checkout.index'));

        $this->get('/checkout')->assertOk()->assertSee('Penguin Keychain')->assertSee('Place order');
    }

    public function test_guests_must_log_in_before_placing_an_order(): void
    {
        $product = Product::where('slug', 'single-sunflower-stem')->first();
        $this->keepSession()->postJson('/cart/add', ['product_id' => $product->id])->assertOk(); // browsing & cart stay open

        $ordersBefore = Order::count();
        $this->post('/checkout/place', [
            'customer_name' => 'Guest', 'customer_phone' => '9800000000',
            'line1' => 'Kalanki', 'city' => 'Kathmandu', 'payment_choice' => 'cod',
        ])->assertRedirect(route('login'));
        $this->assertSame($ordersBefore, Order::count());

        // The normal path: cart → "Log in to checkout" → login page with a hint.
        $this->get('/cart')->assertSee('Log in to checkout');
        $this->get('/checkout')->assertRedirect(route('login'));
        $this->get('/login')->assertSee('to place your order');

        // Signing up returns them to checkout with their cart intact.
        $this->post('/register', [
            'name' => 'New Buyer', 'email' => 'buyer@example.com',
            'password' => 'Secret-pass-123', 'password_confirmation' => 'Secret-pass-123',
        ])->assertRedirect(route('checkout.index'));
        $this->get('/checkout')->assertOk()->assertSee('Single Sunflower Stem');
    }

    public function test_logged_in_customer_can_place_a_cash_on_delivery_order(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'buyer@example.com']));
        $product = Product::where('slug', 'single-sunflower-stem')->first();
        $this->postJson('/cart/add', ['product_id' => $product->id])->assertOk();

        $response = $this->post('/checkout/place', [
            'customer_name'  => 'Test Customer',
            'customer_phone' => '9800000000',
            'customer_email' => 'buyer@example.com',
            'line1'          => 'Kalanki',
            'city'           => 'Kathmandu',
            'province'       => 'Bagmati',
            'payment_choice' => 'cod',
        ]);

        $order = Order::where('customer_email', 'buyer@example.com')->firstOrFail();
        $response->assertRedirect(route('orders.confirmation', $order->order_number));
        $this->get(route('orders.confirmation', $order->order_number))->assertOk()->assertSee('Thank you for your order');
    }

    public function test_checkout_validates_required_fields(): void
    {
        $this->actingAs(User::factory()->create());
        $product = Product::where('slug', 'single-sunflower-stem')->first();
        $this->postJson('/cart/add', ['product_id' => $product->id])->assertOk();

        $this->from('/checkout')->post('/checkout/place', ['payment_choice' => 'cod'])
            ->assertRedirect('/checkout')
            ->assertSessionHasErrors(['customer_name', 'customer_phone', 'line1', 'city']);
    }

    public function test_customer_can_register_and_log_in(): void
    {
        $this->post('/register', [
            'name' => 'New Customer', 'email' => 'new@example.com',
            'password' => 'Secret-pass-123', 'password_confirmation' => 'Secret-pass-123',
        ])->assertRedirect();
        $this->assertAuthenticated();

        $this->post('/logout');
        $this->post('/login', ['email' => 'new@example.com', 'password' => 'wrong'])->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_api_category_filter_includes_sub_categories(): void
    {
        $amigurumi = Category::where('slug', 'amigurumi')->firstOrFail();

        $names = collect($this->getJson("/api/v1/products?category_id={$amigurumi->id}&per_page=48")
            ->assertOk()->json('data'))->pluck('name');

        $this->assertContains('Cuddly Bear Amigurumi', $names);
        $this->assertNotContains('Chunky Knit Beanie', $names);
    }

    public function test_sitemap_lists_products_and_categories(): void
    {
        $this->get('/sitemap.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml')
            ->assertSee(route('products.show', 'crochet-tote-bag'), false)
            ->assertSee(route('categories.show', 'home-decor'), false);
    }
}
