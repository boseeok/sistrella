<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Services\DashboardService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\View\View;

class ReportController extends Controller
{
    private const REVENUE_STATUSES = ['partially_paid', 'confirmed', 'processing', 'shipped', 'delivered'];

    public function __construct(private readonly DashboardService $dashboard)
    {
    }

    public function index(): View
    {
        return view('admin.reports.index', [
            'widgets'        => $this->dashboard->widgets(),
            'dailySales'     => $this->dashboard->dailySales(),
            'monthlyRevenue' => $this->dashboard->monthlyRevenue(),
        ]);
    }

    public function sales(Request $request): View
    {
        $from = $request->date('from') ?? now()->startOfMonth();
        $to   = $request->date('to') ?? now()->endOfDay();

        $orders = Order::whereBetween('created_at', [$from, $to->copy()->endOfDay()])
            ->latest()->paginate(30)->withQueryString();

        $summary = Order::whereBetween('created_at', [$from, $to->copy()->endOfDay()])
            ->selectRaw('COUNT(*) as orders, COALESCE(SUM(grand_total),0) as revenue, COALESCE(SUM(amount_paid),0) as collected')
            ->whereIn('status', self::REVENUE_STATUSES)
            ->first();

        return view('admin.reports.sales', [
            'orders'  => $orders,
            'summary' => $summary,
            'from'    => $from->toDateString(),
            'to'      => $to->toDateString(),
        ]);
    }

    public function inventory(): View
    {
        return view('admin.reports.inventory', [
            'lowStock'   => Product::lowStock()->with('category')->orderBy('stock')->get(),
            'outOfStock' => Product::where('track_inventory', true)->where('stock', '<=', 0)->with('category')->get(),
            'stockValue' => Product::where('track_inventory', true)
                ->selectRaw('COALESCE(SUM(stock * COALESCE(cost_price, 0)),0) as value, COALESCE(SUM(stock),0) as units')
                ->first(),
        ]);
    }

    public function customers(): View
    {
        $top = User::whereHas('roles', fn ($q) => $q->where('name', 'customer'))
            ->withCount('orders')
            ->withSum('orders', 'grand_total')
            ->orderByDesc('orders_sum_grand_total')
            ->limit(20)
            ->get();

        return view('admin.reports.customers', [
            'topCustomers' => $top,
            'growth'       => $this->dashboard->customerGrowth(),
        ]);
    }

    /**
     * Export a report as CSV (orders | inventory | customers).
     */
    public function export(string $type): StreamedResponse
    {
        $filename = "report-{$type}-".now()->format('Y-m-d').'.csv';

        [$headers, $rows] = match ($type) {
            'inventory' => $this->inventoryRows(),
            'customers' => $this->customerRows(),
            default     => $this->orderRows(),
        };

        return response()->streamDownload(function () use ($headers, $rows) {
            $out = fopen('php://output', 'w');
            fputcsv($out, $headers);
            foreach ($rows as $row) {
                fputcsv($out, $row);
            }
            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function orderRows(): array
    {
        $rows = Order::latest()->get()->map(fn (Order $o) => [
            $o->order_number, $o->customer_name, $o->customer_phone,
            $o->status_label, $o->grand_total, $o->amount_paid,
            $o->created_at->toDateTimeString(),
        ])->all();

        return [['Order', 'Customer', 'Phone', 'Status', 'Total', 'Paid', 'Date'], $rows];
    }

    private function inventoryRows(): array
    {
        $rows = Product::with('category')->get()->map(fn (Product $p) => [
            $p->sku, $p->name, $p->category?->name, $p->stock, $p->low_stock_threshold, $p->price,
        ])->all();

        return [['SKU', 'Name', 'Category', 'Stock', 'Low Threshold', 'Price'], $rows];
    }

    private function customerRows(): array
    {
        $rows = User::whereHas('roles', fn ($q) => $q->where('name', 'customer'))
            ->withCount('orders')->withSum('orders', 'grand_total')->get()
            ->map(fn (User $u) => [
                $u->name, $u->email, $u->phone, $u->orders_count, $u->orders_sum_grand_total ?? 0,
            ])->all();

        return [['Name', 'Email', 'Phone', 'Orders', 'Lifetime Value'], $rows];
    }
}
