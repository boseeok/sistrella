<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class OrderRepository extends BaseRepository
{
    protected function model(): string
    {
        return Order::class;
    }

    public function findByNumber(string $number): ?Order
    {
        return $this->query()
            ->with(['items.product', 'payments', 'statusHistory.changedBy', 'user'])
            ->where('order_number', $number)
            ->first();
    }

    public function adminList(array $filters): LengthAwarePaginator
    {
        $q = $this->query()->with(['user', 'items'])->withCount('items');

        if (! empty($filters['status'])) {
            $q->where('status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $term = $filters['search'];
            $q->where(function ($sub) use ($term) {
                $sub->where('order_number', 'like', "%{$term}%")
                    ->orWhere('customer_name', 'like', "%{$term}%")
                    ->orWhere('customer_phone', 'like', "%{$term}%")
                    ->orWhere('customer_email', 'like', "%{$term}%");
            });
        }

        if (! empty($filters['from'])) {
            $q->whereDate('created_at', '>=', $filters['from']);
        }
        if (! empty($filters['to'])) {
            $q->whereDate('created_at', '<=', $filters['to']);
        }

        return $q->latest()->paginate($filters['per_page'] ?? 20)->withQueryString();
    }

    public function forUser(int $userId, int $perPage = 10): LengthAwarePaginator
    {
        return $this->query()->where('user_id', $userId)
            ->withCount('items')->latest()->paginate($perPage);
    }
}
