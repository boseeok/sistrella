<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
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
        'Penguin Keychain'            => ['unsplash:1753370474663-1b0ad622c5fc', 'unsplash:1753370474846-afc7a13defc4'],
        // Wearables
        'Chunky Knit Beanie'          => ['unsplash:1603321581635-d46915755425', 'unsplash:1723856001946-3b53c9fe3bc9'],
        'Striped Winter Scarf'        => ['unsplash:1457545195570-67f207084966', 'unsplash:1539215398023-f3ac3405795f'],
        'Cozy Cardigan Sweater'       => ['unsplash:1679847628912-4c3e7402abc7', 'unsplash:1627667539472-75fbc7f4654d'],
        'Baby Booties Set'            => ['unsplash:1771046749660-9e73020a142d'],
        'Slouchy Wool Hat'            => ['unsplash:1777898218954-26f1f27f2064', 'unsplash:1777898277718-61e4945f8e6c'],
        // Home decor
        'Boho Plant Hanger'           => ['unsplash:1671212684942-5c8a3dc3234e', 'unsplash:1783943579121-da2fab12bbf9'],
        'Round Coaster Set (4)'       => ['unsplash:1502245610427-c7abdffde91b', 'pexels:5264802'],
        'Macrame Wall Hanging'        => ['unsplash:1786309777642-e205058ad298', 'unsplash:1753370241593-9cc8c17d7434'],
        'Granny Square Cushion Cover' => ['unsplash:1728393287642-13bee7126ae8', 'unsplash:1693387359607-f48d0a824b1e'],
        'Mandala Table Mat'           => ['unsplash:1780984901511-7a1be4f014fb', 'pixabay:2023/08/20/17/00/crochet-8202792'],
        // Bags & pouches
        'Crochet Tote Bag'            => ['unsplash:1594638963668-52eb9798e8ca', 'unsplash:1565592284032-d3c08f2a53e9'],
        'Floral Coin Pouch'           => ['unsplash:1787432131008-c754654978a4'],
        'Market Mesh Bag'             => ['unsplash:1686285961020-4c46c9f3f7a6', 'unsplash:1629736329185-086161cda231'],
        'Mini Crossbody Bag'          => ['pexels:10820408', 'pexels:10820406'],
        // Flowers & bouquets
        'Eternal Rose Bouquet'        => ['unsplash:1700171458554-46cfd3f2a87a', 'unsplash:1700171518313-5dd219beaaa6', 'unsplash:1700171394718-2457b1190444'],
        'Single Sunflower Stem'       => ['unsplash:1753366556699-4be495e5bdd6', 'unsplash:1700170447159-9d2d0da133a5'],
        'Tulip Trio'                  => ['unsplash:1768029120664-119e99b02e5b', 'unsplash:1789673571128-27a53c083ead'],
        // Accessories
        'Crochet Hair Scrunchie'      => ['unsplash:1636039805398-1934cad278dc', 'pixabay:2016/09/11/20/44/vintage-1662542'],
        'Beaded Bookmark'             => ['pexels:14186003'],
        'Flower Brooch Pin'           => ['unsplash:1700170928599-d7fc2d4ec97f', 'unsplash:1784368611050-ebe6372b7af9'],
    ];

    /**
     * category-slug => [ [name, price, compareAt, stock, flags...], ... ]
     * flags: f=featured t=trending b=best_seller n=new_arrival s=flash_sale c=customizable
     */
    private array $catalogue = [
        'amigurumi' => [
            ['Cuddly Bear Amigurumi', 850, 1100, 14, 'fbt'],
            ['Tiny Bunny Plush', 650, null, 22, 'tn'],
            ['Crochet Octopus', 720, 900, 9, 'b'],
            ['Mini Dinosaur Set', 1250, null, 6, 'fc'],
            ['Sleepy Cat Doll', 780, null, 0, 'n'],
            ['Penguin Keychain', 250, 350, 40, 'st'],
        ],
        'wearables' => [
            ['Chunky Knit Beanie', 600, 800, 18, 'fb'],
            ['Striped Winter Scarf', 950, null, 12, 't'],
            ['Cozy Cardigan Sweater', 2800, 3500, 4, 'fc'],
            ['Baby Booties Set', 450, null, 25, 'n'],
            ['Slouchy Wool Hat', 700, 950, 8, 's'],
        ],
        'home-decor' => [
            ['Boho Plant Hanger', 550, null, 16, 'ft'],
            ['Round Coaster Set (4)', 480, 600, 30, 'b'],
            ['Macrame Wall Hanging', 1650, 2000, 5, 'fc'],
            ['Granny Square Cushion Cover', 1200, null, 10, 'n'],
            ['Mandala Table Mat', 900, 1200, 7, 's'],
        ],
        'bags-pouches' => [
            ['Crochet Tote Bag', 1400, 1800, 11, 'fbt'],
            ['Floral Coin Pouch', 350, null, 28, 'n'],
            ['Market Mesh Bag', 1100, null, 9, 'tc'],
            ['Mini Crossbody Bag', 1600, 2000, 6, 's'],
        ],
        'flowers-bouquets' => [
            ['Eternal Rose Bouquet', 1900, 2400, 8, 'fbs'],
            ['Single Sunflower Stem', 300, null, 35, 'n'],
            ['Tulip Trio', 750, 900, 14, 't'],
        ],
        'accessories' => [
            ['Crochet Hair Scrunchie', 180, 250, 50, 'bn'],
            ['Beaded Bookmark', 220, null, 26, 't'],
            ['Flower Brooch Pin', 320, 400, 19, 's'],
        ],
    ];

    public function run(): void
    {
        $now = now();

        foreach ($this->catalogue as $slug => $items) {
            $category = Category::where('slug', $slug)->first();
            if (! $category) {
                continue;
            }

            foreach ($items as [$name, $price, $compareAt, $stock, $flags]) {
                $flash = str_contains($flags, 's');

                $product = Product::updateOrCreate(
                    ['slug' => Str::slug($name)],
                    [
                        'category_id'          => $category->id,
                        'name'                 => $name,
                        'short_description'    => "Handmade {$name} crocheted with soft, premium yarn — a perfect gift.",
                        'description'           => $this->description($name),
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
                    ],
                );

                $product->images()->delete();
                foreach ($this->photos[$name] ?? [] as $index => $ref) {
                    $product->images()->create([
                        'path' => $this->stockImage($ref),
                        'alt' => "{$name} handmade crochet item",
                        'is_primary' => $index === 0,
                        'sort_order' => $index,
                    ]);
                }
            }
        }
    }

    private function description(string $name): string
    {
        return "<p>This <strong>{$name}</strong> is lovingly handmade by our artisans using high-quality, "
            ."skin-friendly cotton yarn. Each piece is unique and crafted to last.</p>"
            ."<ul><li>100% handmade crochet</li><li>Premium soft yarn</li>"
            ."<li>Spot clean / gentle hand wash</li><li>Makes a thoughtful gift</li></ul>"
            ."<p>Colours may vary slightly from the photos due to the handmade nature and screen settings.</p>";
    }
}
