<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

/**
 * Seeds a small but realistic crochet category tree (roots + a few children),
 * marking a handful as featured for the homepage.
 */
class CategorySeeder extends Seeder
{
    /**
     * name => [icon, featured, children[]]
     */
    private array $tree = [
        'Amigurumi' => ['bi-emoji-smile', true, ['Animals', 'Dolls', 'Keychains']],
        'Wearables' => ['bi-bag-heart', true, ['Beanies & Hats', 'Scarves', 'Sweaters', 'Baby Wear']],
        'Home Decor' => ['bi-house-heart', true, ['Coasters', 'Plant Hangers', 'Cushion Covers', 'Wall Hangings']],
        'Bags & Pouches' => ['bi-handbag', true, ['Tote Bags', 'Pouches', 'Market Bags']],
        'Flowers & Bouquets' => ['bi-flower1', true, ['Single Flowers', 'Bouquets']],
        'Accessories' => ['bi-stars', false, ['Hair Accessories', 'Jewellery', 'Bookmarks']],
    ];

    public function run(): void
    {
        $sort = 0;

        foreach ($this->tree as $name => [$icon, $featured, $children]) {
            $parent = Category::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($name)],
                [
                    'name'        => $name,
                    'icon'        => $icon,
                    'is_active'   => true,
                    'is_featured' => $featured,
                    'sort_order'  => $sort++,
                    'description' => "Handmade crochet {$name} crafted with premium yarn.",
                ],
            );

            $childSort = 0;
            foreach ($children as $childName) {
                Category::updateOrCreate(
                    ['slug' => \Illuminate\Support\Str::slug($name.' '.$childName)],
                    [
                        'parent_id'   => $parent->id,
                        'name'        => $childName,
                        'is_active'   => true,
                        'sort_order'  => $childSort++,
                    ],
                );
            }
        }
    }
}
