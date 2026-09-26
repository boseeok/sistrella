<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('shop.pages.about');
    }

    /**
     * XML sitemap of the storefront: static pages, active categories and products.
     */
    public function sitemap(): Response
    {
        $urls = collect([route('home'), route('shop'), route('about'), route('contact'), route('custom.create')])
            ->map(fn ($loc) => ['loc' => $loc, 'lastmod' => null])
            ->merge(Category::active()->get(['slug', 'updated_at'])->map(fn ($c) => [
                'loc' => route('categories.show', $c->slug), 'lastmod' => $c->updated_at,
            ]))
            ->merge(\App\Models\ProductCollection::active()->get(['slug', 'updated_at'])->map(fn ($c) => [
                'loc' => route('collections.show', $c->slug), 'lastmod' => $c->updated_at,
            ]))
            ->merge(Product::active()->get(['slug', 'updated_at'])->map(fn ($p) => [
                'loc' => route('products.show', $p->slug), 'lastmod' => $p->updated_at,
            ]));

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n".'<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';
        foreach ($urls as $url) {
            $xml .= '<url><loc>'.e($url['loc']).'</loc>'
                .($url['lastmod'] ? '<lastmod>'.$url['lastmod']->toAtomString().'</lastmod>' : '')
                .'</url>';
        }

        return response($xml.'</urlset>', 200, ['Content-Type' => 'application/xml']);
    }

    public function contact(): View
    {
        return view('shop.pages.contact');
    }

    public function submitContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => ['required', 'email', 'max:255'],
            'phone'   => ['nullable', 'string', 'max:30'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        ContactMessage::create($data);

        app(\App\Services\AdminNotifier::class)->notify(
            'marketing.manage',
            'New contact message',
            "{$data['name']}: ".\Illuminate\Support\Str::limit($data['message'], 60),
            'bi-envelope',
            route('admin.marketing.messages'),
        );

        return back()->with('success', 'Thank you for reaching out! We will reply soon.');
    }

    public function newsletter(Request $request): RedirectResponse
    {
        $request->validate(['email' => ['required', 'email', 'max:255']]);

        NewsletterSubscriber::updateOrCreate(
            ['email' => $request->email],
            ['is_active' => true, 'subscribed_at' => now()],
        );

        return back()->with('success', 'You are subscribed to our newsletter!');
    }
}
