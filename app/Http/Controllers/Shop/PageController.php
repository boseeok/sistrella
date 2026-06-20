<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageController extends Controller
{
    public function about(): View
    {
        return view('shop.pages.about');
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
