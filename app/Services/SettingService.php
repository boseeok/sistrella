<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;

/**
 * Central, cached access point for admin-editable business settings.
 *
 * Values are stored in the `settings` table and cached as a single map.
 * Reads fall back to config/crochet.php so the app works before the table
 * is seeded. Always read settings through this service (or the global
 * `setting()` helper) so the admin Settings screen stays authoritative.
 */
class SettingService
{
    private const CACHE_KEY = 'crochet.settings';

    /** @var array<string,mixed>|null */
    private ?array $map = null;

    /**
     * Get a single setting, falling back to a config/crochet.php default.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $map = $this->all();

        if (array_key_exists($key, $map)) {
            return $map[$key];
        }

        return $default ?? $this->configFallback($key);
    }

    /**
     * @return array<string,mixed>
     */
    public function all(): array
    {
        if ($this->map !== null) {
            return $this->map;
        }

        return $this->map = Cache::rememberForever(self::CACHE_KEY, function () {
            return Setting::all()->mapWithKeys(fn (Setting $s) => [
                $s->key => $s->casted_value,
            ])->toArray();
        });
    }

    public function set(string $key, mixed $value, string $type = 'string', string $group = 'general'): void
    {
        Setting::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : (string) $value,
                'type'  => $type,
                'group' => $group,
            ],
        );

        $this->flush();
    }

    /**
     * @param array<string,mixed> $values
     */
    public function setMany(array $values): void
    {
        foreach ($values as $key => $value) {
            $type = match (true) {
                is_bool($value)               => 'boolean',
                is_int($value)                => 'integer',
                is_float($value)              => 'float',
                is_array($value)              => 'json',
                default                       => 'string',
            };

            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => is_array($value) ? json_encode($value) : (string) $value, 'type' => $type],
            );
        }

        $this->flush();
    }

    public function flush(): void
    {
        $this->map = null;
        Cache::forget(self::CACHE_KEY);
    }

    private function configFallback(string $key): mixed
    {
        return match ($key) {
            'store_name'           => config('crochet.store.name'),
            'store_phone'          => config('crochet.store.phone'),
            'store_email'          => config('crochet.store.email'),
            'store_address'        => config('crochet.store.address'),
            'currency_symbol'      => config('crochet.currency.symbol'),
            'currency_code'        => config('crochet.currency.code'),
            'prepayment_threshold' => config('crochet.prepayment.threshold'),
            'prepayment_percent'   => config('crochet.prepayment.percent'),
            'whatsapp_number'      => config('crochet.whatsapp.number'),
            'instagram_url'        => config('crochet.social.instagram'),
            'facebook_url'         => config('crochet.social.facebook'),
            'low_stock_threshold'  => config('crochet.inventory.low_stock_threshold'),
            'tax_rate'             => config('crochet.tax.rate'),
            default                => null,
        };
    }
}
