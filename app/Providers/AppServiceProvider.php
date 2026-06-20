<?php

namespace App\Providers;

use App\Repositories\CategoryRepository;
use App\Services\CartService;
use App\Services\SettingService;
use App\Services\WhatsAppService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Settings are read everywhere; keep one cached instance per request.
        $this->app->singleton(SettingService::class);
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        // Share global storefront chrome (mega menu, cart count, WhatsApp
        // float link) with the customer-facing layout.
        View::composer('layouts.app', function ($view) {
            $user = auth()->user();

            $view->with([
                'menuCategories' => app(CategoryRepository::class)->menuTree(),
                'cartCount'      => app(CartService::class)->itemCount(),
                'whatsappFloat'  => app(WhatsAppService::class)->link(
                    'Hello! I have a question about Crochet Store.'
                ),
                'notifCount'     => $user ? $user->unreadNotifications()->count() : 0,
                'recentNotifs'   => $user ? $user->notifications()->latest()->limit(6)->get() : collect(),
            ]);
        });
    }
}
