<?php

namespace Database\Seeders\Concerns;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

/**
 * Downloads free-licence crochet photos (Unsplash / Pexels / Pixabay) into the
 * public disk so the demo catalogue is served locally. Files are cached, so
 * re-seeding doesn't re-download. If a download fails the remote URL is
 * returned instead (models accept both relative paths and absolute URLs).
 */
trait FetchesStockImages
{
    /**
     * @param  string  $ref  "unsplash:<photo-id>", "pexels:<id>" or "pixabay:<yyyy/mm/dd/hh/mm/slug-id>"
     */
    protected function stockImage(string $ref, string $dir = 'products/stock'): string
    {
        [$source, $id] = explode(':', $ref, 2);

        $url = match ($source) {
            'unsplash' => "https://images.unsplash.com/photo-{$id}?auto=format&fit=crop&w=900&h=900&q=80",
            'pexels'   => "https://images.pexels.com/photos/{$id}/pexels-photo-{$id}.jpeg?auto=compress&cs=tinysrgb&w=900&h=900&fit=crop",
            'pixabay'  => "https://cdn.pixabay.com/photo/{$id}_1280.jpg",
        };

        $path = $dir.'/'.$source.'-'.basename($id).'.jpg';
        $disk = Storage::disk('public');

        if ($disk->exists($path)) {
            return $path;
        }

        try {
            $response = Http::withHeaders(['User-Agent' => 'Mozilla/5.0 (CrochetStore seeder)'])
                ->timeout(60)
                ->retry(2, 500)
                ->get($url);

            if ($response->successful() && str_starts_with((string) $response->header('Content-Type'), 'image/')) {
                $disk->put($path, $response->body());

                return $path;
            }
        } catch (\Throwable $e) {
            // fall through to the remote URL
        }

        $this->command?->warn("  Could not download {$ref}, using remote URL.");

        return $url;
    }
}
