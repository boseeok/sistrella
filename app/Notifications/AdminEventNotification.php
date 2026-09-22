<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;

/**
 * Generic in-app (database) notification for staff — one is created per
 * business event (new order, payment submitted, new custom request, etc.)
 * and shown in the admin notification bell / notifications page.
 */
class AdminEventNotification extends Notification
{
    public function __construct(
        public string $title,
        public string $message,
        public string $icon = 'bi-bell',
        public ?string $url = null,
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
            'title'   => $this->title,
            'message' => $this->message,
            'icon'    => $this->icon,
            'url'     => $this->url,
        ];
    }
}
