<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Category;
use App\Models\CustomRequest;
use App\Models\Product;
use App\Models\ProductCollection;
use App\Models\ProductVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

/**
 * Handmade-gift boutique features: product lines (nested categories),
 * occasions & curated collections, colour/size variants, custom gift
 * requests and the related admin tools.
 */
class GiftBoutiqueTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake('public');
        Http::fake(['*' => Http::response('img', 200, ['Content-Type' => 'image/jpeg'])]);
        $this->seed();
    }

    private function keepSession(): static
    {
        return $this->withCredentials()->withCookie(config('session.cookie'), session()->getId());
    }

    private function admin(): User
    {
        return User::where('email', 'admin@crochetstore.test')->firstOrFail();
    }

    // ---- Product lines & nested categories ------------------------------------------------

    public function test_product_line_pages_include_every_nested_level(): void
    {
        $this->get('/category/crochet?sort=name')->assertOk()
            ->assertSee('Cuddly Bear Amigurumi') // Crochet › Amigurumi › Animals
            ->assertDontSee('Red Ribbon Rose Bouquet');

        $this->get('/category/ribbon-bouquets')->assertOk()
            ->assertSee('Red Ribbon Rose Bouquet')
            ->assertSee('Gift Hampers')             // sub-category chip
            ->assertDontSee('Cuddly Bear Amigurumi');

        // Existing URLs keep working and show the full trail.
        $this->get('/category/amigurumi-animals')->assertOk()
            ->assertSeeInOrder(['Crochet', 'Amigurumi', 'Animals']);
    }

    public function test_admin_can_nest_categories_but_not_create_cycles(): void
    {
        $this->actingAs($this->admin());
        $crochet   = Category::where('slug', 'crochet')->firstOrFail();
        $amigurumi = Category::where('slug', 'amigurumi')->firstOrFail();

        $this->get(route('admin.categories.create'))->assertOk()->assertSee('Crochet › Amigurumi');

        $this->post(route('admin.categories.store'), ['name' => 'Sea Creatures', 'parent_id' => $amigurumi->id, 'is_active' => 1])
            ->assertRedirect();
        $this->assertSame($amigurumi->id, Category::where('name', 'Sea Creatures')->value('parent_id'));

        // Crochet cannot be moved under its own grandchild.
        $this->put(route('admin.categories.update', $crochet), ['name' => 'Crochet', 'parent_id' => $amigurumi->id, 'is_active' => 1])
            ->assertSessionHasErrors('parent_id');
    }

    // ---- Occasions & collections ------------------------------------------------------------

    public function test_occasion_pages_and_filter(): void
    {
        $this->get('/collections/valentines-day')->assertOk()
            ->assertSee("Valentine's Day")
            ->assertSee('Red Ribbon Rose Bouquet')
            ->assertDontSee('Chunky Knit Beanie');

        $this->get('/shop?occasion=baby-shower')->assertOk()
            ->assertSee('Baby Booties Set')
            ->assertDontSee('Red Ribbon Rose Bouquet');

        $this->get('/product/red-ribbon-rose-bouquet')->assertOk()
            ->assertSee('Perfect for:')->assertSee(route('collections.show', 'anniversary'), false);

        ProductCollection::where('slug', 'graduation')->update(['is_active' => false]);
        $this->get('/collections/graduation')->assertNotFound();

        $this->get('/sitemap.xml')->assertSee(route('collections.show', 'birthday'), false);
    }

    public function test_admin_manages_collections_and_product_tags(): void
    {
        $this->actingAs($this->admin());
        $products = Product::whereIn('slug', ['tulip-trio', 'forever-sunflower-stem'])->pluck('id')->all();

        $this->get(route('admin.collections.index'))->assertOk()->assertSee('Birthday');

        $this->post(route('admin.collections.store'), [
            'type' => 'occasion', 'name' => 'Teachers Day', 'icon' => 'bi-mortarboard',
            'tagline' => 'Thank a teacher', 'is_active' => 1, 'is_featured' => 1, 'product_ids' => $products,
        ])->assertSessionHasNoErrors()->assertRedirect(route('admin.collections.index'));

        $collection = ProductCollection::where('slug', 'teachers-day')->firstOrFail();
        $this->assertSame('mortarboard', $collection->icon);
        $this->assertEqualsCanonicalizing($products, $collection->products()->pluck('products.id')->all());
        $this->get('/')->assertSee('Teachers Day');
        $this->get('/collections/teachers-day')->assertOk()->assertSee('Tulip Trio');

        // Tag a product from its edit form.
        $beanie = Product::where('slug', 'baby-booties-set')->firstOrFail();
        $this->put(route('admin.products.update', $beanie), [
            'name' => $beanie->name, 'category_id' => $beanie->category_id, 'price' => $beanie->price,
            'stock' => $beanie->stock, 'low_stock_threshold' => 5, 'type' => 'simple', 'is_active' => 1,
            'collections_present' => 1, 'collection_ids' => [$collection->id],
        ])->assertSessionHasNoErrors();
        $this->assertSame([$collection->id], $beanie->collections()->pluck('collections.id')->all());

        $this->delete(route('admin.collections.destroy', $collection))->assertRedirect();
        $this->assertModelMissing($collection);
        $this->assertModelExists($beanie);
    }

    // ---- Variants ---------------------------------------------------------------------------

    public function test_variant_product_page_and_cart_rules(): void
    {
        $bouquet = Product::where('slug', 'blush-satin-rose-bouquet')->firstOrFail();
        $six     = ProductVariant::where('sku', 'RB-BLUSH-6')->firstOrFail();
        $soldOut = ProductVariant::where('sku', 'RB-IVORY-12')->firstOrFail();
        $foreign = ProductVariant::where('sku', 'FW-KEY-PNK')->firstOrFail(); // belongs to the keychain

        $this->assertSame('variable', $bouquet->type);
        $this->get('/product/blush-satin-rose-bouquet')->assertOk()
            ->assertSee('variant-picker', false)
            ->assertSee('Blush Pink')->assertSee('12 roses')
            ->assertSee('name="variant_id"', false);

        $this->actingAs(User::factory()->create()); // checkout requires an account
        $add = fn (array $data) => $this->postJson('/cart/add', ['product_id' => $bouquet->id] + $data);

        $add([])->assertStatus(422)->assertJson(['ok' => false]);                               // option required
        $add(['variant_id' => $foreign->id])->assertStatus(422);                                // other product's variant
        $add(['variant_id' => $soldOut->id])->assertStatus(422);                                // sold out
        $add(['variant_id' => $six->id, 'quantity' => $six->stock + 1])->assertStatus(422);     // more than in stock
        $add(['variant_id' => $six->id, 'quantity' => 2])->assertOk()->assertJson(['ok' => true, 'count' => 2]);

        $item = CartItem::where('product_variant_id', $six->id)->firstOrFail();
        $this->assertSame(1600.0, $item->unit_price); // variant price, not the product's 2200
        $this->get('/cart')->assertSee('Blush Pink / 6 roses');

        // Checkout decrements the variant's own stock.
        $before = $six->stock;
        $this->post('/checkout/place', [
            'customer_name' => 'Gift Buyer', 'customer_phone' => '9800000000', 'customer_email' => 'buyer@example.com',
            'line1' => 'Thamel', 'city' => 'Kathmandu', 'payment_choice' => 'prepayment',
        ])->assertRedirect();
        $this->assertSame($before - 2, $six->fresh()->stock);
    }

    public function test_in_stock_filter_counts_variant_stock(): void
    {
        // Product-level stock is 0 but its variants are in stock.
        Product::where('slug', 'potted-fuzzy-sunflower')->update(['stock' => 0]);

        $this->get('/category/fuzzy-wire?in_stock=1')->assertOk()->assertSee('Potted Fuzzy Sunflower');
    }

    public function test_admin_edits_variants(): void
    {
        $this->actingAs($this->admin());
        $scarf = Product::where('slug', 'striped-winter-scarf')->firstOrFail();
        $base = [
            'name' => $scarf->name, 'category_id' => $scarf->category_id, 'price' => 950, 'stock' => 12,
            'low_stock_threshold' => 5, 'type' => 'simple', 'is_active' => 1, 'variants_present' => 1,
        ];

        $this->put(route('admin.products.update', $scarf), $base + ['variants' => [
            'n1' => ['color' => 'Oat', 'color_code' => '#d8c7a9', 'size' => 'Long', 'price' => '', 'stock' => 4, 'sku' => '', 'is_active' => 1],
            'n2' => ['color' => 'Rust', 'color_code' => '#9c5530', 'size' => 'Long', 'price' => 1100, 'stock' => 2, 'sku' => 'SCARF-RUST', 'is_active' => 1],
        ]])->assertSessionHasNoErrors();

        $scarf->refresh()->load('variants.attributeValues');
        $this->assertSame('variable', $scarf->type);
        $this->assertCount(2, $scarf->variants);
        $rust = $scarf->variants->firstWhere('sku', 'SCARF-RUST');
        $this->assertSame('Rust / Long', $rust->label);
        $this->assertSame('#9c5530', $rust->valueFor('color')->color_code);
        $this->get(route('admin.products.edit', $scarf))->assertOk()->assertSee('value="SCARF-RUST"', false);

        // Duplicate SKU is rejected.
        $this->put(route('admin.products.update', $scarf), $base + ['variants' => [
            ['id' => $rust->id, 'color' => 'Rust', 'size' => 'Long', 'stock' => 2, 'sku' => 'RB-BLUSH-6', 'is_active' => 1],
        ]])->assertSessionHasErrors('variants.0.sku');

        // Deleting every variant turns the product back into a simple one.
        $this->put(route('admin.products.update', $scarf), $base + ['variants' => $scarf->variants->map(fn ($v) => [
            'id' => $v->id, '_delete' => 1, 'color' => 'x',
        ])->all()])->assertSessionHasNoErrors();
        $this->assertSame(0, $scarf->variants()->count());
        $this->assertSame('simple', $scarf->fresh()->type);
    }

    // ---- Custom gift requests & dashboard --------------------------------------------------

    public function test_custom_request_captures_line_occasion_and_budget(): void
    {
        $line = Category::where('slug', 'ribbon-bouquets')->firstOrFail();
        $birthday = ProductCollection::where('slug', 'birthday')->firstOrFail();

        $this->get('/custom-order?line=ribbon-bouquets&occasion=birthday')->assertOk()
            ->assertSee('value="'.$line->id.'" selected', false)
            ->assertSee('value="'.$birthday->id.'" selected', false);

        $this->post('/custom-order', [
            'customer_name' => 'Asha', 'customer_phone' => '9811111111', 'title' => 'Lilac ribbon roses with name tag',
            'category_id' => $line->id, 'collection_id' => $birthday->id, 'budget' => 2500, 'quantity' => 1,
        ])->assertRedirect();

        $request = CustomRequest::where('title', 'Lilac ribbon roses with name tag')->firstOrFail();
        $this->assertSame($line->id, $request->category_id);
        $this->assertSame($birthday->id, $request->collection_id);
        $this->assertEquals(2500, $request->budget);

        $this->actingAs($this->admin())->get(route('admin.custom.show', $request->request_number))->assertOk()
            ->assertSee('Ribbon Bouquets')->assertSee('Birthday');

        // Only top-level lines / occasions are accepted.
        $this->post('/custom-order', [
            'customer_name' => 'X', 'customer_phone' => '1', 'title' => 'Y', 'quantity' => 1,
            'category_id' => Category::where('slug', 'amigurumi')->value('id'),
        ])->assertSessionHasErrors('category_id');
    }

    public function test_dashboard_shows_product_lines(): void
    {
        $this->actingAs($this->admin())->get('/admin')->assertOk()
            ->assertSee('Product Lines')
            ->assertSee('Crochet')->assertSee('Ribbon Bouquets')->assertSee('Fuzzy Wire')
            ->assertSee(route('admin.collections.index'), false);
    }
}
