<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds a catalogue of demo crochet products spread across the seeded
 * categories, with a realistic mix of flags (featured / trending / best
 * seller / new arrival) and a few live flash sales. Product images are left
 * empty on purpose so the Product::thumbnail placeholder renders nicely.
 */
class ProductSeeder extends Seeder
{
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

                Product::updateOrCreate(
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
