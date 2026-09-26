<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('custom_requests', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->after('title')->constrained()->nullOnDelete();   // product line
            $table->foreignId('collection_id')->nullable()->after('category_id')->constrained()->nullOnDelete(); // occasion
            $table->decimal('budget', 12, 2)->nullable()->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('custom_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('collection_id');
            $table->dropConstrainedForeignId('category_id');
            $table->dropColumn('budget');
        });
    }
};
