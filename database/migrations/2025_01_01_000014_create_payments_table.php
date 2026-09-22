<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->enum('kind', ['advance', 'balance', 'full', 'refund'])->default('advance');
            $table->decimal('amount', 12, 2);
            $table->enum('method', ['esewa', 'khalti', 'bank_transfer', 'cash', 'other'])->default('bank_transfer');
            $table->string('reference')->nullable();   // transaction id provided by customer
            $table->string('proof_path')->nullable();  // uploaded screenshot
            $table->enum('status', ['submitted', 'verified', 'rejected'])->default('submitted');
            $table->text('note')->nullable();
            $table->text('admin_note')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            $table->index(['order_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
