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
            // Campos para cards modernos
            $table->boolean('free_shipping')->default(false)->after('dimensions');
            $table->decimal('rating', 2, 1)->default(0.0)->after('free_shipping');
            $table->integer('rating_count')->default(0)->after('rating');
            $table->integer('installments')->default(1)->after('rating_count');
            $table->decimal('installment_value', 10, 2)->nullable()->after('installments');
            $table->boolean('is_new')->default(false)->after('installment_value');
            $table->boolean('is_sale')->default(false)->after('is_new');
            $table->decimal('sale_price', 10, 2)->nullable()->after('is_sale');
            $table->date('sale_ends_at')->nullable()->after('sale_price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'free_shipping',
                'rating',
                'rating_count',
                'installments',
                'installment_value',
                'is_new',
                'is_sale',
                'sale_price',
                'sale_ends_at'
            ]);
        });
    }
};
