<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'quantity',
        'color',
        'color_hex',
        'size',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope para buscar itens por usuário ou sessão
     */
    public function scopeForUserOrSession($query, $userId = null, $sessionId = null)
    {
        if ($userId) {
            return $query->where('user_id', $userId);
        }

        return $query->where('session_id', $sessionId);
    }

    /**
     * Gerar chave única para o carrinho
     */
    public function getCartKeyAttribute(): string
    {
        $key = $this->product_id;
        if ($this->color) {
            $key .= '-c' . $this->color;
        }
        if ($this->size) {
            $key .= '-s' . $this->size;
        }
        return $key;
    }
}
