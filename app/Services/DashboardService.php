<?php

namespace App\Services;

use App\Models\CustomRequest;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Aggregates the metrics and chart series that power the admin dashboard
 * and reports. All revenue figures are based on confirmed/fulfilled orders.
 */
class DashboardService
{
    private const REVENUE_STATUSES = ['partially_paid', 'confirmed', 'processing', 'shipped', 'delivered'];

    public function widgets(): array
    {
        $revenue = Order::whereIn('status', self::REVENUE_STATUSES);

        return [
            'total_sales'        => (clone $revenue)->sum('grand_total'),
            'collected'          => Order::sum('amount_paid'),
            'orders_total'       => Order::count(),
            'orders_today'       => Order::whereDate('created_at', today())->count(),
            'orders_pending'     => Order::whereIn('status', ['pending_payment', 'payment_submitted'])->count(),
            'customers'          => User::whereHas('roles', fn ($q) => $q->where('name', 'customer'))->count(),
            'products'           => Product::count(),
            'low_stock'          => Product::lowStock()->count(),
            'pending_custom'     => CustomRequest::whereIn('status', ['pending', 'under_review'])->count(),
            'verification_queue' => Order::awaitingVerification()->count(),
        ];
    }

    /**
     * Daily sales for the last N days (for the line chart).
     */
    public function dailySales(int $days = 14): array
    {
        $rows = Order::whereIn('status', self::REVENUE_STATUSES)
            ->where('created_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->selectRaw('DATE(created_at) as d, SUM(grand_total) as total, COUNT(*) as cnt')
            ->groupBy('d')->pluck('total', 'd');

        $labels = [];
        $values = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date     = today()->subDays($i);
            $labels[] = $date->format('M d');
            $values[] = round((float) ($rows[$date->toDateString()] ?? 0), 2);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Monthly revenue for the last 12 months (bar chart).
     */
    public function monthlyRevenue(): array
    {
        $rows = Order::whereIn('status', self::REVENUE_STATUSES)
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as m, SUM(grand_total) as total")
            ->groupBy('m')->pluck('total', 'm');

        $labels = [];
        $values = [];
        for ($i = 11; $i >= 0; $i--) {
            $month    = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $values[] = round((float) ($rows[$month->format('Y-m')] ?? 0), 2);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    /**
     * Customer growth (new customers per month, last 12 months).
     */
    public function customerGrowth(): array
    {
        $rows = User::where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as m, COUNT(*) as cnt")
            ->groupBy('m')->pluck('cnt', 'm');

        $labels = [];
        $values = [];
        for ($i = 11; $i >= 0; $i--) {
            $month    = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $values[] = (int) ($rows[$month->format('Y-m')] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    public function bestSellers(int $limit = 5)
    {
        return Product::orderByDesc('sales_count')->limit($limit)->get(['id', 'name', 'sku', 'sales_count', 'price', 'stock']);
    }

    public function lowStockProducts(int $limit = 8)
    {
        return Product::lowStock()->orderBy('stock')->limit($limit)->get(['id', 'name', 'sku', 'stock', 'low_stock_threshold']);
    }

    public function recentOrders(int $limit = 8)
    {
        return Order::with('user')->latest()->limit($limit)->get();
    }

    public function verificationQueue(int $limit = 8)
    {
        return Order::awaitingVerification()->with('payments')->latest()->limit($limit)->get();
    }
}
