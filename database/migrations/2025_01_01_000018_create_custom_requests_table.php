<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('custom_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_number')->unique();   // CCR-2026-0001
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();

            $table->string('customer_name');
            $table->string('customer_email')->nullable();
            $table->string('customer_phone', 30);

            $table->string('title');
            $table->text('notes')->nullable();
            $table->string('color')->nullable();
            $table->string('size')->nullable();
            $table->unsignedInteger('quantity')->default(1);
            $table->date('preferred_delivery_date')->nullable();

            // Quotation
            $table->decimal('quoted_price', 12, 2)->nullable();
            $table->text('quote_note')->nullable();
            $table->timestamp('quoted_at')->nullable();

            // Conversion
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('status', [
                'pending',
                'under_review',
                'quoted',
                'accepted',
                'in_production',
                'ready',
                'delivered',
                'rejected',
            ])->default('pending');

            $table->text('admin_notes')->nullable();
            $table->timestamps();

            $table->index(['status', 'created_at']);
        });

        Schema::create('custom_request_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('custom_request_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('type')->default('inspiration'); // inspiration / reference
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('custom_request_images');
        Schema::dropIfExists('custom_requests');
    }
};
