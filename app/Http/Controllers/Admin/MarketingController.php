<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MarketingController extends Controller
{
    public function index(): View
    {
        return view('admin.marketing.index', [
            'subscriberCount' => NewsletterSubscriber::where('is_active', true)->count(),
            'unreadMessages'  => ContactMessage::where('is_read', false)->count(),
            'recentMessages'  => ContactMessage::latest()->limit(5)->get(),
        ]);
    }

    public function subscribers(): View
    {
        return view('admin.marketing.subscribers', [
            'subscribers' => NewsletterSubscriber::latest()->paginate(30),
        ]);
    }

    public function messages(): View
    {
        return view('admin.marketing.messages', [
            'messages' => ContactMessage::latest()->paginate(20),
        ]);
    }

    public function markRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => ! $message->is_read]);

        return back()->with('success', 'Message updated.');
    }
}
