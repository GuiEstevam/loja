<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            if (!Schema::hasColumn('addresses', 'name')) {
                $table->string('name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('addresses', 'phone')) {
                $table->string('phone')->nullable()->after('name');
            }
            if (!Schema::hasColumn('addresses', 'address_line1')) {
                $table->string('address_line1')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'address_line2')) {
                $table->string('address_line2')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'city')) {
                $table->string('city')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'state')) {
                $table->string('state')->nullable();
            }
            if (!Schema::hasColumn('addresses', 'country')) {
                $table->string('country')->default('BR');
            }
            if (!Schema::hasColumn('addresses', 'zipcode')) {
                $table->string('zipcode')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $table) {
            if (Schema::hasColumn('addresses', 'name')) {
                $table->dropColumn('name');
            }
            if (Schema::hasColumn('addresses', 'phone')) {
                $table->dropColumn('phone');
            }
            if (Schema::hasColumn('addresses', 'address_line1')) {
                $table->dropColumn('address_line1');
            }
            if (Schema::hasColumn('addresses', 'address_line2')) {
                $table->dropColumn('address_line2');
            }
            if (Schema::hasColumn('addresses', 'city')) {
                $table->dropColumn('city');
            }
            if (Schema::hasColumn('addresses', 'state')) {
                $table->dropColumn('state');
            }
            if (Schema::hasColumn('addresses', 'country')) {
                $table->dropColumn('country');
            }
            if (Schema::hasColumn('addresses', 'zipcode')) {
                $table->dropColumn('zipcode');
            }
        });
    }
};
