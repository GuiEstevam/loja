<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Primeiro, atualizar dados existentes para valores válidos
        DB::table('orders')->where('status', '!=', 'pending')->update(['status' => 'pending']);
        
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('status', [
                'pending',      // Aguardando pagamento
                'paid',         // Pago
                'processing',   // Processando
                'shipped',      // Enviado
                'delivered',    // Entregue
                'cancelled',    // Cancelado
                'refunded'      // Reembolsado
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });
    }
};
