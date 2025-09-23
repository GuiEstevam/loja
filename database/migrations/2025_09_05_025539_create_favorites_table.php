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
        Schema::create('favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable(); // Para usuários não logados
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Índices para performance
            $table->index(['user_id', 'product_id']);
            $table->index(['session_id', 'product_id']);
            $table->unique(['user_id', 'product_id'], 'unique_user_favorite');
            $table->unique(['session_id', 'product_id'], 'unique_session_favorite');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('favorites');
    }
};
