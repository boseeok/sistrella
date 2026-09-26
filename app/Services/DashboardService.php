<?php

namespace App\Services;

use App\Models\Category;
use App\Models\CustomRequest;
use App\Models\Order;
use App\Models\OrderItem;
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
            ->selectRaw($this->monthExpression().' as m, SUM(grand_total) as total')
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
            ->selectRaw($this->monthExpression().' as m, COUNT(*) as cnt')
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

    /**
     * Revenue, units sold, live products and stock per product line
     * (top-level category: Crochet, Ribbon Bouquets, Fuzzy Wire…). New
     * top-level categories appear here automatically.
     *
     * @return \Illuminate\Support\Collection<int, array{name:string, slug:string, revenue:float, units:int, products:int, stock:int}>
     */
    public function productLines(): \Illuminate\Support\Collection
    {
        $categories = Category::all(['id', 'parent_id', 'name', 'slug'])->keyBy('id');
        $rootOf = function (?int $id) use ($categories) {
            for ($c = $categories[$id] ?? null; $c && $c->parent_id; $c = $categories[$c->parent_id] ?? null);

            return $c;
        };

        $sales = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'products.id', '=', 'order_items.product_id')
            ->whereIn('orders.status', self::REVENUE_STATUSES)
            ->groupBy('products.category_id')
            ->selectRaw('products.category_id, SUM(order_items.line_total) as revenue, SUM(order_items.quantity) as units')
            ->get();

        $catalogue = Product::active()->groupBy('category_id')
            ->selectRaw('category_id, COUNT(*) as products, SUM(stock) as stock')->get();

        $lines = $categories->whereNull('parent_id')->sortBy('name')->mapWithKeys(fn ($root) => [$root->id => [
            'name' => $root->name, 'slug' => $root->slug, 'revenue' => 0.0, 'units' => 0, 'products' => 0, 'stock' => 0,
        ]])->all();

        foreach ($sales as $row) {
            if ($root = $rootOf($row->category_id)) {
                $lines[$root->id]['revenue'] += (float) $row->revenue;
                $lines[$root->id]['units']   += (int) $row->units;
            }
        }
        foreach ($catalogue as $row) {
            if ($root = $rootOf($row->category_id)) {
                $lines[$root->id]['products'] += (int) $row->products;
                $lines[$root->id]['stock']    += (int) $row->stock;
            }
        }

        return collect($lines)->sortByDesc('revenue')->values();
    }

    /**
     * "YYYY-MM" of created_at in the current database's SQL dialect
     * (MySQL/MariaDB in production, SQLite for tests and local previews).
     */
    private function monthExpression(): string
    {
        return match (DB::connection()->getDriverName()) {
            'sqlite' => "strftime('%Y-%m', created_at)",
            'pgsql'  => "to_char(created_at, 'YYYY-MM')",
            default  => "DATE_FORMAT(created_at, '%Y-%m')",
        };
    }
}
