<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('slug')->unique()->after('name');
            $table->string('sku')->unique()->nullable()->after('price');
            $table->foreignId('brand_id')->nullable()->constrained()->onDelete('set null')->after('sku');
            $table->boolean('featured')->default(false)->after('active');
            $table->decimal('weight', 8, 3)->nullable()->after('featured');
            $table->string('dimensions')->nullable()->after('weight');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['slug', 'sku', 'featured', 'weight', 'dimensions']);
            $table->dropForeign(['brand_id']);
            $table->dropColumn('brand_id');
        });
    }
};
