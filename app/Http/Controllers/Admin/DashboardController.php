<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private readonly DashboardService $dashboard)
    {
    }

    public function index(): View
    {
        return view('admin.dashboard', [
            'widgets'           => $this->dashboard->widgets(),
            'recentOrders'      => $this->dashboard->recentOrders(),
            'verificationQueue' => $this->dashboard->verificationQueue(),
            'lowStock'          => $this->dashboard->lowStockProducts(),
            'bestSellers'       => $this->dashboard->bestSellers(),
        ]);
    }

    /**
     * JSON chart series for the dashboard graphs.
     */
    public function chart(string $type): JsonResponse
    {
        $data = match ($type) {
            'sales'     => $this->dashboard->dailySales(),
            'revenue'   => $this->dashboard->monthlyRevenue(),
            'customers' => $this->dashboard->customerGrowth(),
            default     => ['labels' => [], 'values' => []],
        };

        return response()->json($data);
    }
}
