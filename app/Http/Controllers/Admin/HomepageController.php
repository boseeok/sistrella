<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use App\Support\HtmlSanitizer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Editable storefront page content: the home page "Custom orders" section
 * and the About page. Values are stored as settings with config/crochet.php
 * defaults, so pages render before anything is saved. Rich-text fields come
 * from the Quill editor and are sanitized on save (and again on output).
 */
class HomepageController extends Controller
{
    public function __construct(private readonly SettingService $settings)
    {
    }

    public function edit(Request $request): View
    {
        return view('admin.homepage.edit', [
            'tab'   => $request->query('tab') === 'about' ? 'about' : 'home',
            'icons' => config('crochet.about_icons'),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'promo_eyebrow'     => ['nullable', 'string', 'max:60'],
            'promo_title'       => ['required', 'string', 'max:120'],
            'promo_text'        => ['nullable', 'string', 'max:20000'],
            'promo_points'      => ['nullable', 'string', 'max:600'],
            'promo_button_text' => ['required', 'string', 'max:60'],
            'promo_button_link' => ['required', 'string', 'max:255', 'regex:#^(/|https?://)#'],
            'promo_image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
        ], [
            'promo_button_link.regex' => 'The button link must start with / (e.g. /custom-order) or http(s)://.',
        ]);

        $data['promo_text'] = $this->richText($data['promo_text'] ?? '');

        // Image: replace on upload, clear on request, otherwise keep the current one.
        unset($data['promo_image']);
        $oldImage = $this->settings->get('promo_image');
        if ($request->hasFile('promo_image')) {
            $data['promo_image'] = $request->file('promo_image')->store('settings', 'public');
        } elseif ($request->boolean('promo_image_remove')) {
            $data['promo_image'] = '';
        }
        if (array_key_exists('promo_image', $data) && is_string($oldImage) && str_starts_with($oldImage, 'settings/')) {
            Storage::disk('public')->delete($oldImage);
        }

        $this->settings->setMany(array_map(fn ($v) => $v ?? '', $data));

        return redirect()->route('admin.homepage.edit')->with('success', 'Home page section updated.');
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        $icon = ['required', Rule::in(array_keys(config('crochet.about_icons')))];

        $data = $request->validate([
            'about_title'          => ['nullable', 'string', 'max:120'],
            'about_subtitle'       => ['nullable', 'string', 'max:255'],
            'about_body'           => ['nullable', 'string', 'max:20000'],
            'about_features_title' => ['nullable', 'string', 'max:120'],
            'about_feature1_icon'  => $icon,
            'about_feature1_title' => ['nullable', 'string', 'max:60'],
            'about_feature1_text'  => ['nullable', 'string', 'max:160'],
            'about_feature2_icon'  => $icon,
            'about_feature2_title' => ['nullable', 'string', 'max:60'],
            'about_feature2_text'  => ['nullable', 'string', 'max:160'],
            'about_feature3_icon'  => $icon,
            'about_feature3_title' => ['nullable', 'string', 'max:60'],
            'about_feature3_text'  => ['nullable', 'string', 'max:160'],
        ]);

        $data['about_body'] = $this->richText($data['about_body'] ?? '');

        $this->settings->setMany(array_map(fn ($v) => $v ?? '', $data));

        return redirect()->route('admin.homepage.edit', ['tab' => 'about'])->with('success', 'About page updated.');
    }

    /**
     * Sanitize editor HTML; an editor left empty ("<p><br></p>") is stored as ''.
     */
    private function richText(string $html): string
    {
        if ($html === strip_tags($html)) {
            return trim($html);
        }

        $clean = HtmlSanitizer::clean($html);

        return trim(strip_tags($clean)) === '' ? '' : $clean;
    }
}
