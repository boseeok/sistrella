<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomRequest extends Model
{
    protected $fillable = [
        'request_number', 'user_id',
        'customer_name', 'customer_email', 'customer_phone',
        'title', 'notes', 'color', 'size', 'quantity', 'preferred_delivery_date',
        'quoted_price', 'quote_note', 'quoted_at', 'order_id',
        'status', 'admin_notes',
    ];

    protected $casts = [
        'preferred_delivery_date' => ['required', 'date', 'after_or_equal:today'],
        'quoted_price'            => 'decimal:2',
        'quoted_at'               => 'datetime',
    ];

    public const STATUSES = [
        'pending'       => 'Pending',
        'under_review'  => 'Under Review',
        'quoted'        => 'Quoted',
        'accepted'      => 'Accepted',
        'in_production' => 'In Production',
        'ready'         => 'Ready',
        'delivered'     => 'Delivered',
        'rejected'      => 'Rejected',
    ];

    public const STATUS_COLORS = [
        'pending'       => 'warning',
        'under_review'  => 'info',
        'quoted'        => 'primary',
        'accepted'      => 'primary',
        'in_production' => 'info',
        'ready'         => 'success',
        'delivered'     => 'success',
        'rejected'      => 'danger',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(CustomRequestImage::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    public function getRouteKeyName(): string
    {
        return 'request_number';
    }
}
