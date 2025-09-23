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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); // Para usuários não logados
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->string('color')->nullable(); // Nome da cor selecionada
            $table->string('color_hex')->nullable(); // Código hex da cor
            $table->string('size')->nullable(); // Tamanho selecionado
            $table->decimal('price', 10, 2); // Preço no momento da adição
            $table->timestamps();

            // Índices para performance
            $table->index(['user_id', 'product_id']);
            $table->index(['session_id', 'product_id']);
            $table->unique(['user_id', 'product_id', 'color', 'size'], 'unique_user_product_variation');
            $table->unique(['session_id', 'product_id', 'color', 'size'], 'unique_session_product_variation');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
