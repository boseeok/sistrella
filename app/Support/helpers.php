<?php

use App\Services\SettingService;

if (! function_exists('setting')) {
    /**
     * Read a cached business setting (DB-backed, config fallback).
     */
    function setting(string $key, mixed $default = null): mixed
    {
        return app(SettingService::class)->get($key, $default);
    }
}

if (! function_exists('money')) {
    /**
     * Format an amount with the store currency symbol, e.g. "NPR 1,250.00".
     */
    function money(float|int|string|null $amount, bool $decimals = true): string
    {
        $symbol = setting('currency_symbol', 'NPR');
        $value  = number_format((float) $amount, $decimals ? 2 : 0);

        return trim($symbol.' '.$value);
    }
}

if (! function_exists('prepayment_threshold')) {
    function prepayment_threshold(): float
    {
        return (float) setting('prepayment_threshold', 500);
    }
}

if (! function_exists('prepayment_percent')) {
    function prepayment_percent(): float
    {
        return (float) setting('prepayment_percent', 50);
    }
}

if (! function_exists('prepayment_notice')) {
    /**
     * The customer-facing prepayment policy message, with live values.
     */
    function prepayment_notice(): string
    {
        return sprintf(
            'Orders above %s require %s%% advance payment to confirm your order.',
            money(prepayment_threshold(), false),
            // number_format first: (string) 50.0 is "50", and trimming zeros from it gave "5".
            rtrim(rtrim(number_format(prepayment_percent(), 2, '.', ''), '0'), '.'),
        );
    }
}

if (! function_exists('rich_text')) {
    /**
     * Render admin-editable rich text safely. HTML is passed through the
     * allow-list sanitizer; plain text becomes paragraphs. "{store}" is
     * replaced with the store name.
     */
    function rich_text(?string $content, bool $replaceTokens = true): string
    {
        $content = (string) $content;
        if ($replaceTokens) {
            $content = str_replace('{store}', e(setting('store_name', 'our studio')), $content);
        }

        if ($content === strip_tags($content)) {
            return collect(preg_split('/\R{2,}/', trim($content)))
                ->filter(fn ($p) => trim($p) !== '')
                ->map(fn ($p) => '<p>'.nl2br(e(trim($p)), false).'</p>')
                ->join('');
        }

        return \App\Support\HtmlSanitizer::clean($content);
    }
}
