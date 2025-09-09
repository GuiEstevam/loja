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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            
            // Relacionamentos
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            
            // Dados da avaliação
            $table->integer('rating')->unsigned(); // 1-5 estrelas
            $table->string('title')->nullable(); // Título da avaliação
            $table->text('comment')->nullable(); // Comentário detalhado
            
            // Status e moderação
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->boolean('verified_purchase')->default(false); // Compra verificada
            $table->integer('helpful_count')->default(0); // Contador de "útil"
            
            // Timestamps
            $table->timestamps();
            
            // Índices e constraints
            $table->unique(['user_id', 'product_id']); // Um review por usuário por produto
            $table->index(['product_id', 'status']); // Para buscar reviews aprovadas por produto
            $table->index(['user_id', 'status']); // Para buscar reviews do usuário
            $table->index(['rating']); // Para filtros por rating
            $table->index(['created_at']); // Para ordenação por data
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};