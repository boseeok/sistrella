<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_number', 'user_id',
        'customer_name', 'customer_email', 'customer_phone',
        'shipping_address', 'billing_address',
        'subtotal', 'discount_total', 'tax_total', 'shipping_total', 'grand_total',
        'requires_prepayment', 'prepayment_threshold', 'prepayment_percent',
        'advance_amount', 'cod_balance', 'amount_paid',
        'coupon_id', 'coupon_code', 'payment_method', 'status',
        'notes', 'admin_notes', 'tracking_number',
        'confirmed_at', 'shipped_at', 'delivered_at', 'cancelled_at',
    ];

    protected $casts = [
        'shipping_address'     => 'array',
        'billing_address'      => 'array',
        'subtotal'             => 'decimal:2',
        'discount_total'       => 'decimal:2',
        'tax_total'            => 'decimal:2',
        'shipping_total'       => 'decimal:2',
        'grand_total'          => 'decimal:2',
        'prepayment_threshold' => 'decimal:2',
        'prepayment_percent'   => 'decimal:2',
        'advance_amount'       => 'decimal:2',
        'cod_balance'          => 'decimal:2',
        'amount_paid'          => 'decimal:2',
        'requires_prepayment'  => 'boolean',
        'confirmed_at'         => 'datetime',
        'shipped_at'           => 'datetime',
        'delivered_at'         => 'datetime',
        'cancelled_at'         => 'datetime',
    ];

    public const STATUSES = [
        'pending_payment'   => 'Pending Payment',
        'payment_submitted' => 'Payment Submitted',
        'partially_paid'    => 'Partially Paid',
        'confirmed'         => 'Confirmed',
        'processing'        => 'Processing',
        'shipped'           => 'Shipped',
        'delivered'         => 'Delivered',
        'cancelled'         => 'Cancelled',
        'refunded'          => 'Refunded',
    ];

    /** Bootstrap-flavoured badge colours keyed by status. */
    public const STATUS_COLORS = [
        'pending_payment'   => 'warning',
        'payment_submitted' => 'info',
        'partially_paid'    => 'primary',
        'confirmed'         => 'primary',
        'processing'        => 'info',
        'shipped'           => 'secondary',
        'delivered'         => 'success',
        'cancelled'         => 'danger',
        'refunded'          => 'dark',
    ];

    // ---------------------------------------------------------------------
    // Relationships
    // ---------------------------------------------------------------------

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(OrderStatusHistory::class)->latest();
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    // ---------------------------------------------------------------------
    // Scopes
    // ---------------------------------------------------------------------

    public function scopeStatus(Builder $q, string $status): Builder
    {
        return $q->where('status', $status);
    }

    public function scopeAwaitingVerification(Builder $q): Builder
    {
        return $q->where('status', 'payment_submitted');
    }

    // ---------------------------------------------------------------------
    // Helpers
    // ---------------------------------------------------------------------

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? ucfirst($this->status);
    }

    public function getStatusColorAttribute(): string
    {
        return self::STATUS_COLORS[$this->status] ?? 'secondary';
    }

    public function getRemainingBalanceAttribute(): float
    {
        return max(0, (float) $this->grand_total - (float) $this->amount_paid);
    }

    public function getRouteKeyName(): string
    {
        return 'order_number';
    }
}
