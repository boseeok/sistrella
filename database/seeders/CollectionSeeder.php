<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductCollection;
use Illuminate\Database\Seeder;

/**
 * Seeds gift occasions and curated collections, then tags the demo products.
 * Idempotent (matched by slug); product tags are only added, never removed,
 * so assignments made in the admin survive a re-seed.
 */
class CollectionSeeder extends Seeder
{
    /** name => [icon, tagline, product slugs] */
    private array $occasions = [
        'Birthday'        => ['balloon', 'Bright, cheerful gifts to make their day.', ['tiny-bunny-plush', 'mini-dinosaur-set', 'pastel-mixed-ribbon-bouquet', 'forever-sunflower-stem', 'rainbow-chenille-flower-box', 'potted-fuzzy-sunflower', 'keepsake-gift-hamper', 'cuddly-bear-amigurumi', 'tulip-trio']],
        'Anniversary'     => ['heart', 'Forever flowers for a forever kind of love.', ['red-ribbon-rose-bouquet', 'blush-satin-rose-bouquet', 'eternal-rose-bouquet', 'crimson-fuzzy-lily-bouquet', 'keepsake-gift-hamper', 'vintage-ribbon-posy']],
        "Valentine's Day" => ['balloon-heart', 'Say it with roses that never wilt.', ['red-ribbon-rose-bouquet', 'heart-blooms-valentine-set', 'blush-satin-rose-bouquet', 'eternal-rose-bouquet', 'hanging-heart-ornaments', 'cuddly-bear-amigurumi']],
        'Graduation'      => ['mortarboard', 'Celebrate the milestone with a keepsake.', ['forever-sunflower-stem', 'single-sunflower-stem', 'pastel-mixed-ribbon-bouquet', 'crochet-tote-bag', 'beaded-bookmark', 'potted-fuzzy-sunflower']],
        "Mother's Day"    => ['flower3', 'Handmade thank-yous for the women who raised us.', ['blush-satin-rose-bouquet', 'tulip-trio', 'boho-plant-hanger', 'vintage-ribbon-posy', 'granny-square-cushion-cover', 'crochet-tote-bag']],
        'Baby Shower'     => ['stars', 'Soft, safe and sweet for the newest arrival.', ['baby-booties-set', 'elephant-pacifier-clip', 'tiny-bunny-plush', 'crochet-octopus', 'amigurumi-friends-gift-box']],
        'Thank You'       => ['emoji-smile', 'Small gestures, big gratitude.', ['vintage-ribbon-posy', 'fuzzy-flower-keychain', 'round-coaster-set-4', 'crochet-hair-scrunchie', 'forever-sunflower-stem']],
        'Dashain & Tihar' => ['brightness-high', 'Festive colour for family, friends and home.', ['rainbow-chenille-flower-box', 'mandala-table-mat', 'hanging-heart-ornaments', 'keepsake-gift-hamper', 'eternal-rose-bouquet']],
    ];

    /** name => [icon, tagline, product slugs|null, price ceiling|null] */
    private array $curated = [
        'Forever Flowers'     => ['flower1', 'Blooms in crochet, satin ribbon and fuzzy wire that never wilt.', ['eternal-rose-bouquet', 'tulip-trio', 'single-sunflower-stem', 'blush-satin-rose-bouquet', 'red-ribbon-rose-bouquet', 'pastel-mixed-ribbon-bouquet', 'crimson-fuzzy-lily-bouquet', 'rainbow-chenille-flower-box', 'potted-fuzzy-sunflower'], null],
        'Gifts Under NPR 1,000' => ['tag', 'Thoughtful handmade gifts that are easy on the wallet.', null, 1000],
        'Ready-to-Gift Sets'  => ['gift', 'Boxed and wrapped, ready to hand over.', ['keepsake-gift-hamper', 'amigurumi-friends-gift-box', 'bunny-blooms-gift-set', 'heart-blooms-valentine-set', 'diy-fuzzy-flower-kit'], null],
    ];

    public function run(): void
    {
        $sort = 0;
        foreach ($this->occasions as $name => [$icon, $tagline, $slugs]) {
            $this->upsert('occasion', $name, $icon, $tagline, $sort++)
                ->products()->syncWithoutDetaching(Product::whereIn('slug', $slugs)->pluck('id'));
        }

        $sort = 0;
        foreach ($this->curated as $name => [$icon, $tagline, $slugs, $maxPrice]) {
            $ids = $maxPrice
                ? Product::active()->where('price', '<=', $maxPrice)->pluck('id')
                : Product::whereIn('slug', $slugs)->pluck('id');

            $this->upsert('curated', $name, $icon, $tagline, $sort++)->products()->syncWithoutDetaching($ids);
        }
    }

    private function upsert(string $type, string $name, string $icon, string $tagline, int $sort): ProductCollection
    {
        $collection = ProductCollection::firstOrNew(['slug' => \Illuminate\Support\Str::slug(str_replace(["'", ','], '', $name))]);

        // Keep admin edits (name, tagline, images…) once the record exists.
        if (! $collection->exists) {
            $collection->fill([
                'type' => $type, 'name' => $name, 'icon' => $icon, 'tagline' => $tagline,
                'is_active' => true, 'is_featured' => true, 'sort_order' => $sort,
            ])->save();
        }

        return $collection;
    }
}
