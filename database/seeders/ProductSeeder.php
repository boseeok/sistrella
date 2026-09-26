<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Services\ProductVariantService;
use Database\Seeders\Concerns\FetchesStockImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds a catalogue of demo crochet products spread across the seeded
 * categories, with a realistic mix of flags (featured / trending / best
 * seller / new arrival) and a few live flash sales. Each product gets 1-3
 * real crochet photos (Unsplash / Pexels / Pixabay, free licences) that are
 * downloaded into storage/app/public/products/stock on first seed.
 */
class ProductSeeder extends Seeder
{
    use FetchesStockImages;

    /**
     * product name => stock photo refs (first one is the primary image)
     */
    private array $photos = [
        // Amigurumi
        'Cuddly Bear Amigurumi'       => ['unsplash:1602773974733-b56200c8653f', 'unsplash:1626241803094-88edd8ae6453', 'pixabay:2016/09/11/20/50/giraffe-1662561'],
        'Tiny Bunny Plush'            => ['unsplash:1744371760034-fb60ebd2b198', 'unsplash:1629019317873-3f603b269723', 'unsplash:1775484105281-a5e677f2ae40'],
        'Crochet Octopus'             => ['pexels:38745727', 'pexels:38914365'],
        'Mini Dinosaur Set'           => ['pexels:38972275', 'unsplash:1766090503766-623b62f0da26', 'pexels:38718822'],
        'Sleepy Cat Doll'             => ['pixabay:2017/03/13/12/52/crochet-doll-2139663', 'unsplash:1686151573986-03b5a79f22a5', 'pixabay:2016/09/11/20/57/pablo-mouse-1662574'],
        'Amigurumi Friends Gift Box'  => ['unsplash:1686150784894-eb52e4023493', 'pexels:38718822'],
        'Penguin Keychain'            => ['unsplash:1753370474663-1b0ad622c5fc', 'unsplash:1753370474846-afc7a13defc4'],
        // Wearables
        'Chunky Knit Beanie'          => ['unsplash:1603321581635-d46915755425', 'unsplash:1723856001946-3b53c9fe3bc9'],
        'Striped Winter Scarf'        => ['unsplash:1457545195570-67f207084966', 'unsplash:1539215398023-f3ac3405795f'],
        'Cozy Cardigan Sweater'       => ['unsplash:1679847628912-4c3e7402abc7', 'unsplash:1627667539472-75fbc7f4654d'],
        'Baby Booties Set'            => ['unsplash:1771046749660-9e73020a142d'],
        'Elephant Pacifier Clip'      => ['unsplash:1784368611050-ebe6372b7af9'],
        'Slouchy Wool Hat'            => ['unsplash:1777898218954-26f1f27f2064', 'unsplash:1777898277718-61e4945f8e6c'],
        // Home decor
        'Boho Plant Hanger'           => ['unsplash:1671212684942-5c8a3dc3234e', 'unsplash:1783943579121-da2fab12bbf9'],
        'Round Coaster Set (4)'       => ['unsplash:1502245610427-c7abdffde91b', 'pexels:5264802'],
        'Macrame Wall Hanging'        => ['unsplash:1786309777642-e205058ad298', 'unsplash:1753370241593-9cc8c17d7434'],
        'Granny Square Cushion Cover' => ['unsplash:1728393287642-13bee7126ae8', 'unsplash:1693387359607-f48d0a824b1e'],
        'Hanging Heart Ornaments'     => ['unsplash:1682456138620-6076ac071b51', 'unsplash:1636039805398-1934cad278dc'],
        'Mandala Table Mat'           => ['unsplash:1780984901511-7a1be4f014fb', 'pixabay:2023/08/20/17/00/crochet-8202792'],
        // Bags & pouches
        'Crochet Tote Bag'            => ['unsplash:1594638963668-52eb9798e8ca', 'unsplash:1565592284032-d3c08f2a53e9'],
        'Floral Coin Pouch'           => ['unsplash:1787432131008-c754654978a4'],
        'Market Mesh Bag'             => ['unsplash:1686285961020-4c46c9f3f7a6', 'unsplash:1629736329185-086161cda231'],
        'Mini Crossbody Bag'          => ['pexels:10820408', 'pexels:10820406'],
        // Flowers & bouquets
        'Eternal Rose Bouquet'        => ['unsplash:1700171458554-46cfd3f2a87a', 'unsplash:1700171518313-5dd219beaaa6', 'unsplash:1700171394718-2457b1190444'],
        'Bunny & Blooms Gift Set'     => ['unsplash:1753370241593-9cc8c17d7434', 'unsplash:1700171518313-5dd219beaaa6'],
        'Single Sunflower Stem'       => ['unsplash:1753366556699-4be495e5bdd6', 'unsplash:1700170447159-9d2d0da133a5'],
        'Tulip Trio'                  => ['unsplash:1768029120664-119e99b02e5b', 'unsplash:1789673571128-27a53c083ead'],
        // Accessories
        'Crochet Hair Scrunchie'      => ['unsplash:1636039805398-1934cad278dc', 'pixabay:2016/09/11/20/44/vintage-1662542'],
        'Beaded Bookmark'             => ['pexels:14186003'],
        'Flower Brooch Pin'           => ['unsplash:1700170928599-d7fc2d4ec97f', 'unsplash:1700170447159-9d2d0da133a5'],
        // Ribbon bouquets
        'Blush Satin Rose Bouquet'    => ['pexels:4639529', 'pexels:28540275', 'unsplash:1613469663063-0874723c644e'],
        'Red Ribbon Rose Bouquet'     => ['pexels:34730769', 'pexels:1765498', 'pexels:11877248'],
        'Pastel Mixed Ribbon Bouquet' => ['pexels:31359533', 'unsplash:1689085055401-2e6a7268d6ca', 'pexels:32091259'],
        'Vintage Ribbon Posy'         => ['pexels:6732262', 'pexels:33572961'],
        'Forever Sunflower Stem'      => ['unsplash:1787427790298-5714d02d4118'],
        'Keepsake Gift Hamper'        => ['pexels:38346287', 'pexels:6270383', 'unsplash:1751603136938-b80e08ac47d7'],
        // Fuzzy wire
        'Crimson Fuzzy Lily Bouquet'  => ['unsplash:1786743628223-09f188f5e868'],
        'Rainbow Chenille Flower Box' => ['pexels:35468616'],
        'Heart Blooms Valentine Set'  => ['pexels:32257698'],
        'Potted Fuzzy Sunflower'      => ['unsplash:1775784517789-73cd9a881caa'],
        'Fuzzy Flower Keychain'       => ['pexels:38363696', 'pexels:8166395'],
        'DIY Fuzzy Flower Kit'        => ['pexels:17893913', 'pexels:8166395'],
    ];

    /**
     * root-category-slug => [ [name, sub-category, price, compareAt, stock, flags, blurb], ... ]
     * Sub-category is the child name as seeded by CategorySeeder.
     * flags: f=featured t=trending b=best_seller n=new_arrival s=flash_sale c=customizable
     */
    private array $catalogue = [
        'amigurumi' => [
            ['Cuddly Bear Amigurumi', 'Animals', 850, 1100, 14, 'fbt', 'A huggable 22 cm teddy in soft caramel cotton with embroidered eyes, safe for little ones.'],
            ['Tiny Bunny Plush', 'Animals', 650, null, 22, 'tn', 'A palm-sized pink bunny with floppy ears and a stitched smile, made for pockets and nurseries.'],
            ['Crochet Octopus', 'Animals', 720, 900, 9, 'b', 'A cheerful octopus with curly tentacles, a classic comfort toy for newborns and toddlers.'],
            ['Mini Dinosaur Set', 'Animals', 1250, null, 6, 'fc', 'Three pocket-sized dinos in bright colours, ready for adventures and made to order in your colours.'],
            ['Amigurumi Friends Gift Box', 'Animals', 1450, 1700, 7, 'n', 'A trio of tiny woodland friends nestled in a keepsake box, a sweet baby-shower gift.'],
            ['Sleepy Cat Doll', 'Dolls', 780, null, 0, 'n', 'A dreamy cat doll in pyjamas with a tiny pillow, crocheted in soft milk cotton.'],
            ['Penguin Keychain', 'Keychains', 250, 350, 40, 'st', 'A tiny penguin charm for your keys or school bag, with a sturdy metal ring.'],
        ],
        'wearables' => [
            ['Chunky Knit Beanie', 'Beanies & Hats', 600, 800, 18, 'fb', 'A warm ribbed beanie with a fold-over brim, crocheted in soft acrylic-wool blend.'],
            ['Slouchy Wool Hat', 'Beanies & Hats', 700, 950, 8, 's', 'A relaxed bucket-style hat with a ruffled brim in a joyful rainbow of shades.'],
            ['Striped Winter Scarf', 'Scarves', 950, null, 12, 't', 'A long, cosy scarf in muted stripes that wraps twice for chilly Kathmandu mornings.'],
            ['Cozy Cardigan Sweater', 'Sweaters', 2800, 3500, 4, 'fc', 'An oversized granny-stitch cardigan with a relaxed fit, made to your size on request.'],
            ['Baby Booties Set', 'Baby Wear', 450, null, 25, 'n', 'Soft newborn booties and matching hat with satin ribbon ties, gentle on delicate skin.'],
            ['Elephant Pacifier Clip', 'Baby Wear', 380, null, 30, 'n', 'A little elephant clip with wooden beads that keeps baby\'s pacifier close and clean.'],
        ],
        'home-decor' => [
            ['Boho Plant Hanger', 'Plant Hangers', 550, null, 16, 'ft', 'A textured cotton cover that turns any pot or vase into a cosy boho accent.'],
            ['Round Coaster Set (4)', 'Coasters', 480, 600, 30, 'b', 'Four absorbent cotton coasters in earthy tones that protect tables in style.'],
            ['Mandala Table Mat', 'Coasters', 900, 1200, 7, 's', 'A lacy mandala doily that brings a vintage, handmade touch to side tables and trays.'],
            ['Granny Square Cushion Cover', 'Cushion Covers', 1200, null, 10, 'n', 'A colourful 40 × 40 cm cover pieced from classic granny squares, with a button closure.'],
            ['Macrame Wall Hanging', 'Wall Hangings', 1650, 2000, 5, 'fc', 'A delicate lace panel for windows or walls that filters light beautifully.'],
            ['Hanging Heart Ornaments', 'Wall Hangings', 420, null, 20, 'n', 'A garland of three puffy hearts to brighten doors, cribs and festive corners.'],
        ],
        'bags-pouches' => [
            ['Crochet Tote Bag', 'Tote Bags', 1400, 1800, 11, 'fbt', 'A roomy everyday tote with sturdy handles, lined for laptops, books and market runs.'],
            ['Floral Coin Pouch', 'Pouches', 350, null, 28, 'n', 'A sunny little pouch with appliqué flowers for coins, lip balm and earbuds.'],
            ['Mini Crossbody Bag', 'Pouches', 1600, 2000, 6, 's', 'A compact crossbody with leather accents and an adjustable strap for hands-free days.'],
            ['Market Mesh Bag', 'Market Bags', 1100, null, 9, 'tc', 'A stretchy, reusable mesh bag that folds to nothing and carries a week of groceries.'],
        ],
        'flowers-bouquets' => [
            ['Eternal Rose Bouquet', 'Bouquets', 1900, 2400, 8, 'fbs', 'A forever bouquet of mixed crochet blooms that never wilts, perfect for anniversaries.'],
            ['Bunny & Blooms Gift Set', 'Bouquets', 1350, null, 10, 'n', 'A tiny bunny paired with a posy of crochet flowers, an adorable ready-to-gift set.'],
            ['Single Sunflower Stem', 'Single Flowers', 300, null, 35, 'n', 'A bright, long-stemmed sunflower that adds sunshine to any desk or vase.'],
            ['Tulip Trio', 'Single Flowers', 750, 900, 14, 't', 'Three potted crochet tulips in soft pastels, zero watering required.'],
        ],
        'accessories' => [
            ['Crochet Hair Scrunchie', 'Hair Accessories', 180, 250, 50, 'bn', 'A gentle, snag-free scrunchie in soft cotton that is kind to curls and fine hair.'],
            ['Flower Brooch Pin', 'Jewellery', 320, 400, 19, 's', 'A hand-stitched flower brooch to pin on jackets, bags and hats.'],
            ['Beaded Bookmark', 'Bookmarks', 220, null, 26, 't', 'A slim lace bookmark with a beaded tassel, a thoughtful gift for book lovers.'],
        ],
        'ribbon-bouquets' => [
            ['Blush Satin Rose Bouquet', 'Rose Bouquets', 2200, 2600, 0, 'fbn', 'Hand-folded satin ribbon roses with pearl centres, a keepsake bouquet that never wilts. Choose your colour and size.'],
            ['Red Ribbon Rose Bouquet', 'Rose Bouquets', 2400, null, 9, 'ts', 'A classic dozen of deep red satin roses tied with a silk bow, made for anniversaries and Valentine’s Day.'],
            ['Pastel Mixed Ribbon Bouquet', 'Mixed Bouquets', 1800, 2100, 12, 'n', 'Roses, buds and baby’s-breath folded from pastel satin ribbon and wrapped in soft tissue paper.'],
            ['Vintage Ribbon Posy', 'Mixed Bouquets', 1200, null, 15, 't', 'A small hand-tied posy of antique-toned ribbon roses, perfect for a desk, bedside or thank-you gift.'],
            ['Forever Sunflower Stem', 'Mini & Single Stems', 350, null, 40, 'bn', 'A single bright ribbon sunflower wrapped in gift paper, a little burst of sunshine for any occasion.'],
            ['Keepsake Gift Hamper', 'Gift Hampers', 3500, 4200, 6, 'fc', 'A ribbon bouquet, handmade card and a surprise crochet friend in a gift box, personalised with a name tag.'],
        ],
        'fuzzy-wire' => [
            ['Crimson Fuzzy Lily Bouquet', 'Flowers & Bouquets', 1500, 1800, 10, 'fbn', 'Velvety red lilies twisted from chenille wire with beaded stamens, a bouquet with texture you can feel.'],
            ['Rainbow Chenille Flower Box', 'Flowers & Bouquets', 2800, null, 5, 'fc', 'A statement box of chrysanthemums, carnations and daisies shaped from bright fuzzy wire.'],
            ['Heart Blooms Valentine Set', 'Flowers & Bouquets', 1300, null, 14, 's', 'Fuzzy wire roses with little hearts on a gift base, made for Valentine’s Day and anniversaries.'],
            ['Potted Fuzzy Sunflower', 'Potted Blooms', 900, null, 0, 'tn', 'A cheerful chenille-wire sunflower in a mini pot. Pick your petal colour; no watering required.'],
            ['Fuzzy Flower Keychain', 'Keychains & Charms', 250, 300, 0, 'bn', 'A soft pipe-cleaner flower charm for keys, bags and pencil cases, in four sweet colours.'],
            ['DIY Fuzzy Flower Kit', 'Flowers & Bouquets', 950, null, 20, 'n', 'Everything you need to twist six fuzzy flowers at home: chenille stems, beads, pots and a picture guide.'],
        ],
    ];

    /**
     * Colour/size variants: product name => [[color, hex, size, price|null, stock, sku], ...].
     * Products listed here become "variable" (stock per variant).
     */
    private array $variants = [
        'Blush Satin Rose Bouquet' => [
            ['Blush Pink', '#F2B8C6', '6 roses', 1600, 8, 'RB-BLUSH-6'],
            ['Blush Pink', '#F2B8C6', '12 roses', 2200, 6, 'RB-BLUSH-12'],
            ['Ivory', '#F4EEDC', '6 roses', 1600, 5, 'RB-IVORY-6'],
            ['Ivory', '#F4EEDC', '12 roses', 2200, 0, 'RB-IVORY-12'],
            ['Ruby Red', '#9B1B30', '12 roses', 2300, 7, 'RB-RUBY-12'],
            ['Ruby Red', '#9B1B30', '24 roses', 3900, 3, 'RB-RUBY-24'],
        ],
        'Potted Fuzzy Sunflower' => [
            ['Sunny Yellow', '#F5C518', null, null, 12, 'FW-SUN-YEL'],
            ['Blush Pink', '#F2B8C6', null, null, 6, 'FW-SUN-PNK'],
            ['Lilac', '#B79CD9', null, null, 4, 'FW-SUN-LIL'],
        ],
        'Fuzzy Flower Keychain' => [
            ['Bubblegum Pink', '#F48FB1', null, null, 25, 'FW-KEY-PNK'],
            ['Lilac', '#B79CD9', null, null, 18, 'FW-KEY-LIL'],
            ['Sky Blue', '#81C7F5', null, null, 15, 'FW-KEY-BLU'],
            ['Sunny Yellow', '#F5C518', null, null, 0, 'FW-KEY-YEL'],
        ],
        'Chunky Knit Beanie' => [
            ['Mustard', '#C9962B', 'Kids', 550, 6, 'CR-BEANIE-MUS-K'],
            ['Mustard', '#C9962B', 'Adult', null, 8, 'CR-BEANIE-MUS-A'],
            ['Forest Green', '#3D4B33', 'Adult', null, 5, 'CR-BEANIE-FOR-A'],
            ['Blush Pink', '#F2B8C6', 'Kids', 550, 4, 'CR-BEANIE-PNK-K'],
        ],
    ];

    /**
     * Wording per product line (catalogue key) for alt text and descriptions.
     */
    private array $lines = [
        'ribbon-bouquets' => ['ribbon bouquet', 'folded petal by petal from satin ribbon'],
        'fuzzy-wire'      => ['fuzzy wire craft', 'twisted and shaped by hand from soft chenille wire'],
    ];

    /**
     * root-category-slug => [size, material, care] used for the details list.
     */
    private array $specs = [
        'amigurumi'        => ['Approx. 10–22 cm', '100% milk cotton, polyester fibre fill', 'Spot clean or hand wash cold, dry flat'],
        'wearables'        => ['Free size / made to measure on request', 'Soft acrylic-wool blend', 'Hand wash cold, reshape and dry flat'],
        'home-decor'       => ['See photos for dimensions', '100% cotton yarn', 'Shake out dust; hand wash cold if needed'],
        'bags-pouches'     => ['See photos for dimensions', 'Durable cotton cord, fabric lining', 'Spot clean, air dry'],
        'flowers-bouquets' => ['Stems approx. 25–35 cm', 'Cotton yarn with wire-supported stems', 'Dust gently with a soft brush'],
        'accessories'      => ['Small', 'Cotton yarn, metal findings where applicable', 'Spot clean'],
        'ribbon-bouquets'  => ['Approx. 25–40 cm tall (varies by size)', 'Satin ribbon, faux pearls, floral wire, gift wrap', 'Keep dry; dust with a soft brush or hairdryer on cool'],
        'fuzzy-wire'       => ['Approx. 8–35 cm (see photos)', 'Chenille (pipe-cleaner) wire, beads, craft pot where shown', 'Keep dry; fluff gently with fingers to refresh'],
    ];

    public function run(): void
    {
        $now = now();

        foreach ($this->catalogue as $slug => $items) {
            $root = Category::where('slug', $slug)->first();
            if (! $root) {
                continue;
            }

            foreach ($items as [$name, $sub, $price, $compareAt, $stock, $flags, $blurb]) {
                // Only add missing products: never reset stock, prices or photos of
                // products that already exist (they may have real sales and edits).
                if (Product::withTrashed()->where('slug', Str::slug($name))->exists()) {
                    continue;
                }

                $flash    = str_contains($flags, 's');
                $category = Category::where('slug', Str::slug($root->name.' '.$sub))->first() ?? $root;

                $product = Product::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'category_id'          => $category->id,
                        'name'                 => $name,
                        'short_description'    => $blurb,
                        'description'          => $this->description($name, $blurb, $this->specs[$slug], $this->lines[$slug][1] ?? 'crocheted stitch by stitch'),
                        'price'                => $price,
                        'compare_at_price'     => $compareAt,
                        'cost_price'           => round($price * 0.55, 2),
                        'track_inventory'      => true,
                        'stock'                => $stock,
                        'low_stock_threshold'  => 5,
                        'type'                 => 'simple',
                        'is_active'            => true,
                        'is_featured'          => str_contains($flags, 'f'),
                        'is_trending'          => str_contains($flags, 't'),
                        'is_best_seller'       => str_contains($flags, 'b'),
                        'is_new_arrival'       => str_contains($flags, 'n'),
                        'is_customizable'      => str_contains($flags, 'c'),
                        'flash_sale_price'     => $flash ? round($price * 0.8, 2) : null,
                        'flash_sale_starts_at' => $flash ? $now->copy()->subDay() : null,
                        'flash_sale_ends_at'   => $flash ? $now->copy()->addDays(3) : null,
                        'sales_count'          => str_contains($flags, 'b') ? rand(40, 200) : rand(0, 30),
                        'views'                => rand(20, 800),
                        'weight'               => rand(80, 600),
                        'meta_description'     => Str::limit($blurb, 155),
                    ],
                );

                $noun = $this->lines[$slug][0] ?? 'crochet';
                $product->images()->delete();
                foreach ($this->photos[$name] ?? [] as $index => $ref) {
                    $product->images()->create([
                        'path'       => $this->stockImage($ref),
                        'alt'        => $index === 0 ? "{$name}, handmade {$noun}" : "{$name}, handmade {$noun}, view ".($index + 1),
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }

                $this->seedVariants($product, $this->variants[$name] ?? []);
            }
        }
    }

    /**
     * Create/update seeded variants (matched by SKU so re-seeding never duplicates),
     * then keep the product's own stock equal to the variant total for reporting.
     */
    private function seedVariants(Product $product, array $rows): void
    {
        if (! $rows) {
            return;
        }

        app(ProductVariantService::class)->sync($product, array_map(fn ($r) => [
            'id'         => ProductVariant::where('sku', $r[5])->where('product_id', $product->id)->value('id'),
            'color'      => $r[0],
            'color_code' => $r[1],
            'size'       => $r[2],
            'price'      => $r[3],
            'stock'      => $r[4],
            'sku'        => $r[5],
            'is_active'  => true,
        ], $rows));

        $product->update(['stock' => $product->variants()->where('is_active', true)->sum('stock')]);
    }

    /**
     * @param  array{0:string,1:string,2:string}  $specs  size, material, care
     */
    private function description(string $name, string $blurb, array $specs, string $made = 'crocheted stitch by stitch'): string
    {
        [$size, $material, $care] = $specs;

        return "<p>{$blurb}</p>"
            ."<p>Every <strong>{$name}</strong> is {$made} by our makers in Nepal, "
            .'so no two pieces are exactly alike.</p>'
            .'<ul>'
            ."<li><strong>Size:</strong> {$size}</li>"
            ."<li><strong>Material:</strong> {$material}</li>"
            ."<li><strong>Care:</strong> {$care}</li>"
            .'<li><strong>Made by hand:</strong> allow slight variations in colour and shape</li>'
            .'</ul>';
    }
}
