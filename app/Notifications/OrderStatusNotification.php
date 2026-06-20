<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Notifications\Notification;

/**
 * In-app (database) notification sent to a customer when something happens
 * to their order — status change, payment verified, etc. Rendered in the
 * storefront notification bell and the account notifications page.
 */
class OrderStatusNotification extends Notification
{
    public function __construct(
        public Order $order,
        public string $title,
        public string $message,
        public string $icon = 'bi-bell',
    ) {
    }

    /**
     * @return array<int,string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * @return array<string,mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_number' => $this->order->order_number,
            'status'       => $this->order->status,
            'title'        => $this->title,
            'message'      => $this->message,
            'icon'         => $this->icon,
            'url'          => route('account.orders.show', $this->order->order_number),
        ];
    }
}
