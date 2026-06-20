<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Related products + bundle composition (self-referencing many-to-many)
        Schema::create('product_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->foreignId('linked_product_id')->constrained('products')->cascadeOnDelete();
            $table->enum('type', ['related', 'bundle', 'cross_sell', 'up_sell'])->default('related');
            $table->unsignedInteger('quantity')->default(1); // for bundles
            $table->timestamps();

            $table->unique(['product_id', 'linked_product_id', 'type'], 'product_link_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_links');
    }
};
