<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();          // CRS-2026-0001
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // null = guest

            // Customer snapshot (works for guest checkout too)
            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone', 30);

            // Address snapshot
            $table->json('shipping_address')->nullable();
            $table->json('billing_address')->nullable();

            // Money breakdown
            $table->decimal('subtotal', 12, 2)->default(0);
            $table->decimal('discount_total', 12, 2)->default(0);
            $table->decimal('tax_total', 12, 2)->default(0);
            $table->decimal('shipping_total', 12, 2)->default(0);
            $table->decimal('grand_total', 12, 2)->default(0);

            // Prepayment policy snapshot (the heart of the business rule)
            $table->boolean('requires_prepayment')->default(false);
            $table->decimal('prepayment_threshold', 12, 2)->default(0);
            $table->decimal('prepayment_percent', 5, 2)->default(0);
            $table->decimal('advance_amount', 12, 2)->default(0);   // amount to pay now
            $table->decimal('cod_balance', 12, 2)->default(0);      // amount due on delivery
            $table->decimal('amount_paid', 12, 2)->default(0);      // confirmed by admin

            // Coupon
            $table->foreignId('coupon_id')->nullable()->constrained()->nullOnDelete();
            $table->string('coupon_code')->nullable();

            // Payment method & status
            $table->enum('payment_method', ['cod', 'prepayment', 'full_prepaid'])->default('cod');
            $table->enum('status', [
                'pending_payment',   // awaiting advance (orders > threshold)
                'payment_submitted', // customer uploaded proof
                'partially_paid',    // admin verified advance
                'confirmed',
                'processing',
                'shipped',
                'delivered',
                'cancelled',
                'refunded',
            ])->default('pending_payment');

            $table->text('notes')->nullable();          // customer note
            $table->text('admin_notes')->nullable();
            $table->string('tracking_number')->nullable();

            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('shipped_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
