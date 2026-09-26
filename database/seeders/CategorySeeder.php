<?php

namespace Database\Seeders;

use App\Models\Category;
use Database\Seeders\Concerns\FetchesStockImages;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds the boutique category tree: one top-level category per product line
 * (Crochet, Ribbon Bouquets, Fuzzy Wire) with nested sub-categories. The tree
 * can be any depth, so new lines (jewellery, candles, gift boxes…) are just
 * new entries here or in Admin › Categories. Idempotent: matched by slug, so
 * existing categories keep their URLs and products.
 */
class CategorySeeder extends Seeder
{
    use FetchesStockImages;

    /**
     * Each node: name, slug (optional, defaults to slug(name)), icon, featured,
     * image (stock photo ref), description, children.
     */
    private function tree(): array
    {
        return [
            [
                'name' => 'Crochet', 'icon' => 'bi-flower2', 'featured' => true, 'image' => 'pexels:10894124',
                'description' => 'Hand-crocheted friends, wearables, home decor and forever flowers, stitched one loop at a time.',
                'children' => [
                    ['name' => 'Amigurumi', 'slug' => 'amigurumi', 'icon' => 'bi-emoji-smile', 'featured' => true, 'image' => 'pexels:10894124',
                        'description' => 'Huggable crochet animals, dolls and keychains, stuffed by hand and made to be loved for years.',
                        'children' => [
                            ['name' => 'Animals', 'slug' => 'amigurumi-animals', 'image' => 'unsplash:1629019317873-3f603b269723'],
                            ['name' => 'Dolls', 'slug' => 'amigurumi-dolls', 'image' => 'pixabay:2017/03/13/12/52/crochet-doll-2139663'],
                            ['name' => 'Keychains', 'slug' => 'amigurumi-keychains', 'image' => 'unsplash:1753370474663-1b0ad622c5fc'],
                        ]],
                    ['name' => 'Wearables', 'slug' => 'wearables', 'icon' => 'bi-bag-heart', 'featured' => true, 'image' => 'unsplash:1777898218954-26f1f27f2064',
                        'description' => 'Cosy hats, scarves, sweaters and baby wear crocheted in soft, skin-friendly yarns.',
                        'children' => [
                            ['name' => 'Beanies & Hats', 'slug' => 'wearables-beanies-hats', 'image' => 'unsplash:1603321581635-d46915755425'],
                            ['name' => 'Scarves', 'slug' => 'wearables-scarves', 'image' => 'unsplash:1457545195570-67f207084966'],
                            ['name' => 'Sweaters', 'slug' => 'wearables-sweaters', 'image' => 'unsplash:1679847628912-4c3e7402abc7'],
                            ['name' => 'Baby Wear', 'slug' => 'wearables-baby-wear', 'image' => 'unsplash:1771046749660-9e73020a142d'],
                        ]],
                    ['name' => 'Home Decor', 'slug' => 'home-decor', 'icon' => 'bi-house-heart', 'featured' => true, 'image' => 'unsplash:1728393287642-13bee7126ae8',
                        'description' => 'Coasters, cushion covers, plant hangers and wall pieces that bring handmade warmth to every room.',
                        'children' => [
                            ['name' => 'Coasters', 'slug' => 'home-decor-coasters', 'image' => 'unsplash:1502245610427-c7abdffde91b'],
                            ['name' => 'Plant Hangers', 'slug' => 'home-decor-plant-hangers', 'image' => 'unsplash:1671212684942-5c8a3dc3234e'],
                            ['name' => 'Cushion Covers', 'slug' => 'home-decor-cushion-covers', 'image' => 'unsplash:1693387359607-f48d0a824b1e'],
                            ['name' => 'Wall Hangings', 'slug' => 'home-decor-wall-hangings', 'image' => 'unsplash:1786309777642-e205058ad298'],
                        ]],
                    ['name' => 'Bags & Pouches', 'slug' => 'bags-pouches', 'icon' => 'bi-handbag', 'featured' => true, 'image' => 'unsplash:1565592284032-d3c08f2a53e9',
                        'description' => 'Sturdy totes, market bags and pouches for everyday carry, stitched to last.',
                        'children' => [
                            ['name' => 'Tote Bags', 'slug' => 'bags-pouches-tote-bags', 'image' => 'unsplash:1594638963668-52eb9798e8ca'],
                            ['name' => 'Pouches', 'slug' => 'bags-pouches-pouches', 'image' => 'unsplash:1787432131008-c754654978a4'],
                            ['name' => 'Market Bags', 'slug' => 'bags-pouches-market-bags', 'image' => 'unsplash:1686285961020-4c46c9f3f7a6'],
                        ]],
                    ['name' => 'Flowers & Bouquets', 'slug' => 'flowers-bouquets', 'icon' => 'bi-flower1', 'featured' => true, 'image' => 'unsplash:1700171394718-2457b1190444',
                        'description' => 'Crochet forever blooms that never wilt: single stems, bouquets and gift sets.',
                        'children' => [
                            ['name' => 'Single Flowers', 'slug' => 'flowers-bouquets-single-flowers', 'image' => 'unsplash:1753366556699-4be495e5bdd6'],
                            ['name' => 'Bouquets', 'slug' => 'flowers-bouquets-bouquets', 'image' => 'unsplash:1700171458554-46cfd3f2a87a'],
                        ]],
                    ['name' => 'Accessories', 'slug' => 'accessories', 'icon' => 'bi-stars', 'featured' => false, 'image' => 'unsplash:1636039805398-1934cad278dc',
                        'description' => 'Scrunchies, brooches, bookmarks and small gifts with a handmade touch.',
                        'children' => [
                            ['name' => 'Hair Accessories', 'slug' => 'accessories-hair-accessories', 'image' => 'pixabay:2016/09/11/20/44/vintage-1662542'],
                            ['name' => 'Jewellery', 'slug' => 'accessories-jewellery', 'image' => 'unsplash:1784368611050-ebe6372b7af9'],
                            ['name' => 'Bookmarks', 'slug' => 'accessories-bookmarks', 'image' => 'pexels:14186003'],
                        ]],
                ],
            ],
            [
                'name' => 'Ribbon Bouquets', 'icon' => 'bi-gift', 'featured' => true, 'image' => 'pexels:4639529',
                'description' => 'Satin ribbon roses and keepsake bouquets, folded by hand in your colours. They last forever and never wilt.',
                'children' => [
                    ['name' => 'Rose Bouquets', 'image' => 'pexels:28540275'],
                    ['name' => 'Mixed Bouquets', 'image' => 'pexels:31359533'],
                    ['name' => 'Mini & Single Stems', 'image' => 'unsplash:1787427790298-5714d02d4118'],
                    ['name' => 'Gift Hampers', 'image' => 'pexels:38346287'],
                ],
            ],
            [
                'name' => 'Fuzzy Wire', 'icon' => 'bi-brightness-alt-high', 'featured' => true, 'image' => 'unsplash:1786743628223-09f188f5e868',
                'description' => 'Soft chenille-wire flowers, potted blooms and charms: bright, playful and made to keep.',
                'children' => [
                    ['name' => 'Flowers & Bouquets', 'slug' => 'fuzzy-wire-flowers-bouquets', 'image' => 'pexels:35468616'],
                    ['name' => 'Potted Blooms', 'image' => 'unsplash:1775784517789-73cd9a881caa'],
                    ['name' => 'Keychains & Charms', 'image' => 'pexels:38363696'],
                ],
            ],
        ];
    }

    public function run(): void
    {
        foreach ($this->tree() as $sort => $node) {
            $this->seedNode($node, null, $sort);
        }
    }

    private function seedNode(array $node, ?Category $parent, int $sort): void
    {
        // Children default to "parent-name child-name" slugs so equal names under
        // different parents (e.g. two "Flowers & Bouquets") never collide.
        $slug = $node['slug'] ?? Str::slug(($parent ? $parent->name.' ' : '').$node['name']);

        $category = Category::updateOrCreate(['slug' => $slug], array_filter([
            'parent_id'   => $parent?->id,
            'name'        => $node['name'],
            'image'       => isset($node['image']) ? $this->stockImage($node['image'], 'categories/stock') : null,
            'icon'        => $node['icon'] ?? null,
            'description' => $node['description'] ?? null,
            'is_active'   => true,
            'is_featured' => $node['featured'] ?? false,
            'sort_order'  => $sort,
        ], fn ($v) => $v !== null) + ['parent_id' => $parent?->id]);

        foreach ($node['children'] ?? [] as $childSort => $child) {
            $this->seedNode($child, $category, $childSort);
        }
    }
}
