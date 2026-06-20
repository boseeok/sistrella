<?php

namespace App\Services;

use App\Models\User;
use App\Notifications\AdminEventNotification;
use Illuminate\Support\Facades\Notification;

/**
 * Fans out an in-app notification to the staff members allowed to act on it.
 * Recipients = active users whose role is "admin" (super-user) or whose role
 * carries the given permission. Keeps the admin notification feed relevant.
 */
class AdminNotifier
{
    public function notify(string $permission, string $title, string $message, string $icon = 'bi-bell', ?string $url = null): void
    {
        $recipients = User::where('is_active', true)
            ->whereHas('roles', function ($q) use ($permission) {
                $q->where('name', 'admin')
                    ->orWhereHas('permissions', fn ($p) => $p->where('name', $permission));
            })
            ->get();

        if ($recipients->isNotEmpty()) {
            Notification::send($recipients, new AdminEventNotification($title, $message, $icon, $url));
        }
    }
}
