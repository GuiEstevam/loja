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
        Schema::table('payments', function (Blueprint $table) {
            // Adicionar novos campos necessários
            $table->decimal('amount', 10, 2)->nullable()->after('transaction_id');
            $table->json('gateway_response')->nullable()->after('amount');
            $table->timestamp('processed_at')->nullable()->after('gateway_response');
            $table->timestamp('failed_at')->nullable()->after('processed_at');
            $table->text('failure_reason')->nullable()->after('failed_at');
            
            // Atualizar enum de status para incluir 'failed'
            $table->enum('status', ['pending', 'approved', 'declined', 'refunded', 'failed'])->default('pending')->change();
            
            // Atualizar enum de métodos para incluir novos métodos brasileiros
            $table->enum('method', ['credit_card', 'pix', 'boleto', 'mbway', 'multibanco', 'paypal'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // Remover campos adicionados
            $table->dropColumn([
                'amount',
                'gateway_response',
                'processed_at',
                'failed_at',
                'failure_reason'
            ]);
            
            // Reverter enum de status
            $table->enum('status', ['pending', 'approved', 'declined', 'refunded'])->default('pending')->change();
            
            // Reverter enum de métodos
            $table->enum('method', ['mbway', 'multibanco', 'paypal', 'credit_card'])->change();
        });
    }
};
