<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Admin access control and catalogue CRUD (products, images, categories, banners).
 */
class AdminCatalogTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Http::fake(['*' => Http::response('img', 200, ['Content-Type' => 'image/jpeg'])]);
        $this->seed();

        $this->admin = User::where('email', 'admin@crochetstore.test')->firstOrFail();
    }

    public function test_admin_area_is_protected(): void
    {
        $this->get('/admin')->assertRedirect(route('admin.login'));

        $customer = User::whereHas('roles', fn ($q) => $q->where('name', 'customer'))->firstOrFail();
        $this->actingAs($customer)->get('/admin')->assertForbidden();
    }

    public function test_admin_can_log_in_and_view_dashboard(): void
    {
        $this->post(route('admin.login.submit'), ['email' => $this->admin->email, 'password' => 'password'])
            ->assertRedirect();

        $this->get('/admin')->assertOk();
        foreach (['products', 'categories', 'banners', 'orders', 'customers', 'inventory', 'coupons', 'payments', 'reports', 'settings'] as $section) {
            $this->get("/admin/{$section}")->assertOk();
        }
        $this->get(route('admin.products.create'))->assertOk();
        foreach (['sales', 'revenue', 'customers'] as $type) {
            $this->get(route('admin.dashboard.chart', $type))->assertOk();
        }
    }

    public function test_product_crud_with_image_management(): void
    {
        $this->actingAs($this->admin);
        $category = Category::where('slug', 'amigurumi-animals')->firstOrFail();

        $this->post(route('admin.products.store'), [
            'name' => 'Test Fox Plush', 'category_id' => $category->id, 'price' => 999,
            'stock' => 5, 'low_stock_threshold' => 2, 'type' => 'simple', 'is_active' => 1,
            'images' => [UploadedFile::fake()->image('fox.jpg'), UploadedFile::fake()->image('fox2.png')],
        ])->assertRedirect();

        $product = Product::where('name', 'Test Fox Plush')->firstOrFail();
        $this->assertCount(2, $product->images);
        $this->assertTrue($product->images()->orderBy('sort_order')->first()->is_primary);
        Storage::disk('public')->assertExists($product->images->first()->path);

        $this->get(route('admin.products.edit', $product))->assertOk()->assertSee('Make primary', false);
        $this->get(route('products.show', $product->slug))->assertOk()->assertSee('Test Fox Plush');

        // Make the second image primary, then delete it; primary falls back to the other image.
        $second = $product->images()->where('is_primary', false)->firstOrFail();
        $this->patch(route('admin.products.images.primary', [$product, $second]))->assertRedirect();
        $this->assertTrue($second->fresh()->is_primary);

        $this->delete(route('admin.products.images.destroy', [$product, $second]))->assertRedirect();
        $this->assertModelMissing($second);
        Storage::disk('public')->assertMissing($second->path);
        $this->assertTrue($product->images()->first()->is_primary);

        $this->put(route('admin.products.update', $product), [
            'name' => 'Test Fox Plush XL', 'category_id' => $category->id, 'price' => 1200,
            'stock' => 3, 'low_stock_threshold' => 2, 'type' => 'simple', 'is_active' => 1,
        ])->assertRedirect();
        $this->assertSame('Test Fox Plush XL', $product->fresh()->name);

        $this->delete(route('admin.products.destroy', $product))->assertRedirect(route('admin.products.index'));
        $this->get(route('products.show', $product->slug))->assertNotFound();
    }

    public function test_image_belonging_to_another_product_cannot_be_deleted(): void
    {
        $this->actingAs($this->admin);
        [$a, $b] = Product::has('images')->take(2)->get();

        $this->delete(route('admin.products.images.destroy', [$a, $b->images->first()]))->assertNotFound();
    }

    public function test_product_uploads_must_be_images(): void
    {
        $this->actingAs($this->admin);
        $product = Product::firstOrFail();

        $this->put(route('admin.products.update', $product), [
            'name' => $product->name, 'price' => 100, 'stock' => 1, 'low_stock_threshold' => 1, 'type' => 'simple',
            'images' => [UploadedFile::fake()->create('evil.php', 10, 'application/x-php')],
        ])->assertSessionHasErrors('images.0');
    }

    public function test_category_crud(): void
    {
        $this->actingAs($this->admin);
        $parent = Category::where('slug', 'accessories')->firstOrFail();

        $this->post(route('admin.categories.store'), [
            'name' => 'Keyrings', 'parent_id' => $parent->id, 'is_active' => 1,
            'image' => UploadedFile::fake()->image('keyrings.jpg'),
        ])->assertRedirect();

        $category = Category::where('name', 'Keyrings')->firstOrFail();
        $this->get(route('categories.show', $category->slug))->assertOk()->assertSee('Keyrings');

        $this->put(route('admin.categories.update', $category), ['name' => 'Key Rings', 'parent_id' => $parent->id, 'is_active' => 1])
            ->assertRedirect();
        $this->assertSame('Key Rings', $category->fresh()->name);

        $this->delete(route('admin.categories.destroy', $category))->assertRedirect();
        $this->assertModelMissing($category);
    }

    public function test_home_custom_order_section_is_editable_from_home_page_admin(): void
    {
        // Defaults come from config/crochet.php until an admin saves.
        $this->get('/')->assertOk()->assertSee('Made just for you')->assertSee('Gift wrapping on request');

        $this->actingAs($this->admin);
        $this->get('/admin')->assertSee(route('admin.homepage.edit'), false);
        $this->get(route('admin.homepage.edit'))->assertOk()
            ->assertSee('Custom Orders Section')
            ->assertSee('value="Made just for you"', false);

        $payload = [
            'promo_eyebrow'     => 'Bespoke',
            'promo_title'       => 'Your idea, our hook',
            'promo_text'        => 'Tell us what you dream of.',
            'promo_points'      => "Any colour\n\nAny size",
            'promo_button_text' => 'Get a quote',
            'promo_button_link' => '/contact',
            'promo_image'       => UploadedFile::fake()->image('promo.jpg', 1200, 800),
        ];
        $this->put(route('admin.homepage.update'), $payload)->assertSessionHasNoErrors()->assertRedirect();

        $image = setting('promo_image');
        Storage::disk('public')->assertExists($image);

        $this->get('/')->assertOk()
            ->assertSee('Your idea, our hook')->assertSee('Bespoke')->assertSee('Any size')
            ->assertSee('href="'.url('/contact').'" class="btn btn-brand btn-lg">Get a quote', false)
            ->assertSee('storage/'.$image, false)
            ->assertDontSee('Made just for you');

        // Removing the image falls back to the hero banner photo and deletes the file.
        $this->put(route('admin.homepage.update'), array_merge($payload, ['promo_image' => null, 'promo_image_remove' => 1]))
            ->assertSessionHasNoErrors();
        Storage::disk('public')->assertMissing($image);

        // Links must be relative paths or http(s) URLs.
        $this->put(route('admin.homepage.update'), array_merge($payload, ['promo_image' => null, 'promo_button_link' => 'javascript:alert(1)']))
            ->assertSessionHasErrors('promo_button_link');
    }

    public function test_about_page_is_editable_with_sanitized_rich_text(): void
    {
        // Defaults (from config) render before anything is saved.
        $this->get('/about')->assertOk()->assertSee('About Sistrella')->assertSee('Why shop with us?')->assertSee('100% Handmade');

        $this->actingAs($this->admin);
        $this->get(route('admin.homepage.edit', ['tab' => 'about']))->assertOk()
            ->assertSee('name="about_body"', false)
            ->assertSee('quill@2.0.3', false);

        $this->put(route('admin.homepage.about'), [
            'about_title'          => 'Our {store} story',
            'about_subtitle'       => 'Stitched in Kathmandu',
            'about_body'           => '<h2 style="color: rgb(156, 85, 48)">Hello</h2><p onclick="steal()">We <span style="font-family: Fraunces; font-size: 22px">love</span> yarn.</p><script>alert(1)</script><a href="javascript:alert(1)">x</a>',
            'about_features_title' => 'Promises',
            'about_feature1_icon'  => 'gift', 'about_feature1_title' => 'Gift ready', 'about_feature1_text' => 'Wrapped with care.',
            'about_feature2_icon'  => 'truck', 'about_feature2_title' => '', 'about_feature2_text' => '',
            'about_feature3_icon'  => 'heart', 'about_feature3_title' => 'Made with love', 'about_feature3_text' => '',
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.homepage.edit', ['tab' => 'about']));

        // Stored HTML is already clean.
        $this->assertStringNotContainsString('script', setting('about_body'));
        $this->assertStringNotContainsString('onclick', setting('about_body'));

        $this->get('/about')->assertOk()
            ->assertSee('Our Sistrella story')
            ->assertSee('Stitched in Kathmandu')
            ->assertSee('<h2 style="color: rgb(156, 85, 48)">Hello</h2>', false)
            ->assertSee('<span style="font-family: Fraunces; font-size: 22px">love</span>', false)
            ->assertDontSee('alert(1)', false)
            ->assertDontSee('steal()', false)
            ->assertSee('bi-gift', false)->assertSee('Gift ready')
            ->assertDontSee('Cash on Delivery</div>', false) // card 2 hidden (empty title)
            ->assertSee('Made with love');

        $this->put(route('admin.homepage.about'), ['about_feature1_icon' => 'bomb', 'about_feature2_icon' => 'truck', 'about_feature3_icon' => 'heart'])
            ->assertSessionHasErrors('about_feature1_icon');
    }

    public function test_banner_create_shows_on_home(): void
    {
        $this->actingAs($this->admin);
        Banner::query()->update(['is_active' => false]);

        $this->post(route('admin.banners.store'), [
            'title' => 'Winter Knits Are Here', 'subtitle' => 'Cosy up', 'position' => 'hero',
            'is_active' => 1, 'image' => UploadedFile::fake()->image('winter.jpg', 1600, 700),
        ])->assertRedirect();

        $this->get('/')->assertOk()->assertSee('Winter Knits Are Here');
    }
}
