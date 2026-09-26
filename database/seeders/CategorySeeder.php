<?php

namespace Database\Seeders;

use App\Models\Category;
use Database\Seeders\Concerns\FetchesStockImages;
use Illuminate\Database\Seeder;

/**
 * Seeds a small but realistic crochet category tree (roots + a few children),
 * marking a handful as featured for the homepage. Every category gets a real
 * crochet photo (Unsplash / Pexels / Pixabay).
 */
class CategorySeeder extends Seeder
{
    use FetchesStockImages;

    /**
     * category name => stock photo ref
     */
    private array $images = [
        'Amigurumi'          => 'pexels:10894124',
        'Animals'            => 'unsplash:1629019317873-3f603b269723',
        'Dolls'              => 'pixabay:2017/03/13/12/52/crochet-doll-2139663',
        'Keychains'          => 'unsplash:1753370474663-1b0ad622c5fc',
        'Wearables'          => 'unsplash:1777898218954-26f1f27f2064',
        'Beanies & Hats'     => 'unsplash:1603321581635-d46915755425',
        'Scarves'            => 'unsplash:1457545195570-67f207084966',
        'Sweaters'           => 'unsplash:1679847628912-4c3e7402abc7',
        'Baby Wear'          => 'unsplash:1771046749660-9e73020a142d',
        'Home Decor'         => 'unsplash:1728393287642-13bee7126ae8',
        'Coasters'           => 'unsplash:1502245610427-c7abdffde91b',
        'Plant Hangers'      => 'unsplash:1671212684942-5c8a3dc3234e',
        'Cushion Covers'     => 'unsplash:1693387359607-f48d0a824b1e',
        'Wall Hangings'      => 'unsplash:1786309777642-e205058ad298',
        'Bags & Pouches'     => 'unsplash:1565592284032-d3c08f2a53e9',
        'Tote Bags'          => 'unsplash:1594638963668-52eb9798e8ca',
        'Pouches'            => 'unsplash:1787432131008-c754654978a4',
        'Market Bags'        => 'unsplash:1686285961020-4c46c9f3f7a6',
        'Flowers & Bouquets' => 'unsplash:1700171394718-2457b1190444',
        'Single Flowers'     => 'unsplash:1753366556699-4be495e5bdd6',
        'Bouquets'           => 'unsplash:1700171458554-46cfd3f2a87a',
        'Accessories'        => 'unsplash:1636039805398-1934cad278dc',
        'Hair Accessories'   => 'pixabay:2016/09/11/20/44/vintage-1662542',
        'Jewellery'          => 'unsplash:1784368611050-ebe6372b7af9',
        'Bookmarks'          => 'pexels:14186003',
    ];

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
                    'image'       => $this->stockImage($this->images[$name], 'categories/stock'),
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
                        'image'       => $this->stockImage($this->images[$childName], 'categories/stock'),
                        'is_active'   => true,
                        'sort_order'  => $childSort++,
                    ],
                );
            }
        }
    }
}
