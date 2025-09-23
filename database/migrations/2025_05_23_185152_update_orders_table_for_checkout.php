<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (!Schema::hasColumn('orders', 'name')) {
                $table->string('name')->nullable();
            }
            if (!Schema::hasColumn('orders', 'email')) {
                $table->string('email')->nullable();
            }
            if (!Schema::hasColumn('orders', 'phone')) {
                $table->string('phone')->nullable();
            }
            if (!Schema::hasColumn('orders', 'country')) {
                $table->string('country', 2)->default('BR');
            }
            if (!Schema::hasColumn('orders', 'zip')) {
                $table->string('zip')->nullable();
            }
            if (!Schema::hasColumn('orders', 'street')) {
                $table->string('street')->nullable();
            }
            if (!Schema::hasColumn('orders', 'number')) {
                $table->string('number')->nullable();
            }
            if (!Schema::hasColumn('orders', 'complement')) {
                $table->string('complement')->nullable();
            }
            if (!Schema::hasColumn('orders', 'neighborhood')) {
                $table->string('neighborhood')->nullable();
            }
            if (!Schema::hasColumn('orders', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('orders', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('orders', 'address')) {
                $table->text('address')->nullable();
            }
            if (!Schema::hasColumn('orders', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            if (!Schema::hasColumn('orders', 'notes')) {
                $table->text('notes')->nullable();
            }
            if (!Schema::hasColumn('orders', 'subtotal')) {
                $table->decimal('subtotal', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('orders', 'discount')) {
                $table->decimal('discount', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('orders', 'total')) {
                $table->decimal('total', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('orders', 'status')) {
                $table->string('status')->default('pending');
            }
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('orders', 'email')) {
                $table->dropColumn('email');
            }
            if (Schema::hasColumn('orders', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('orders', 'country')) {
                $table->dropColumn('country');
            }
            if (Schema::hasColumn('orders', 'zip')) {
                $table->dropColumn('zip');
            }
            if (Schema::hasColumn('orders', 'street')) {
                $table->dropColumn('street');
            }
            if (Schema::hasColumn('orders', 'number')) {
                $table->dropColumn('number');
            }
            if (Schema::hasColumn('orders', 'complement')) {
                $table->dropColumn('complement');
            }
            if (Schema::hasColumn('orders', 'neighborhood')) {
                $table->dropColumn('neighborhood');
            }
            if (Schema::hasColumn('orders', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('orders', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('orders', 'address')) {
                $table->dropColumn('address');
            }
            if (Schema::hasColumn('orders', 'payment_method')) {
                $table->dropColumn('payment_method');
            }
            if (Schema::hasColumn('orders', 'notes')) {
                $table->dropColumn('notes');
            }
            if (Schema::hasColumn('orders', 'subtotal')) {
                $table->dropColumn('subtotal');
            }
            if (Schema::hasColumn('orders', 'discount')) {
                $table->dropColumn('discount');
            }
            if (Schema::hasColumn('orders', 'total')) {
                $table->dropColumn('total');
            }
            if (Schema::hasColumn('orders', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
