<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Log;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'subtotal',
        'discount',
        'total',
        'status',
        'name',
        'email',
        'phone',
        'address',         // Endereço completo montado
        'country',
        'zip',             // CEP/Código Postal
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'payment_method',
        'notes',
    ];

    // Constantes para status
    const STATUS_PENDING = 'pending';           // Aguardando pagamento
    const STATUS_PAID = 'paid';                 // Pago
    const STATUS_PROCESSING = 'processing';     // Processando
    const STATUS_SHIPPED = 'shipped';           // Enviado
    const STATUS_DELIVERED = 'delivered';       // Entregue
    const STATUS_CANCELLED = 'cancelled';       // Cancelado
    const STATUS_REFUNDED = 'refunded';        // Reembolsado

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Verifica se o pedido pode transicionar para um novo status
     */
    public function canTransitionTo(string $newStatus): bool
    {
        $allowedTransitions = [
            self::STATUS_PENDING => [self::STATUS_PAID, self::STATUS_CANCELLED],
            self::STATUS_PAID => [self::STATUS_PROCESSING, self::STATUS_CANCELLED],
            self::STATUS_PROCESSING => [self::STATUS_SHIPPED, self::STATUS_CANCELLED],
            self::STATUS_SHIPPED => [self::STATUS_DELIVERED],
            self::STATUS_DELIVERED => [self::STATUS_REFUNDED],
            self::STATUS_CANCELLED => [],
            self::STATUS_REFUNDED => [],
        ];

        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }

    /**
     * Atualiza o status do pedido com validação
     */
    public function updateStatus(string $newStatus, string $reason = null): bool
    {
        if (!$this->canTransitionTo($newStatus)) {
            throw new \Exception("Transição de status inválida de '{$this->status}' para '{$newStatus}'");
        }

        $oldStatus = $this->status;
        $this->update(['status' => $newStatus]);

        // Log da mudança de status
        $this->logStatusChange($oldStatus, $newStatus, $reason);

        return true;
    }

    /**
     * Log de mudança de status
     */
    private function logStatusChange(string $oldStatus, string $newStatus, string $reason = null): void
    {
        Log::info('Status do pedido alterado', [
            'order_id' => $this->id,
            'user_id' => $this->user_id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'reason' => $reason,
            'changed_at' => now()->toISOString()
        ]);
    }

    /**
     * Verifica se o pedido está pago
     */
    public function isPaid(): bool
    {
        return in_array($this->status, [self::STATUS_PAID, self::STATUS_PROCESSING, self::STATUS_SHIPPED, self::STATUS_DELIVERED]);
    }

    /**
     * Verifica se o pedido pode ser cancelado
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_PAID, self::STATUS_PROCESSING]);
    }

    /**
     * Verifica se o pedido pode ser reembolsado
     */
    public function canBeRefunded(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    /**
     * Obtém o status formatado para exibição
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            self::STATUS_PENDING => 'Aguardando Pagamento',
            self::STATUS_PAID => 'Pago',
            self::STATUS_PROCESSING => 'Processando',
            self::STATUS_SHIPPED => 'Enviado',
            self::STATUS_DELIVERED => 'Entregue',
            self::STATUS_CANCELLED => 'Cancelado',
            self::STATUS_REFUNDED => 'Reembolsado',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Obtém a classe CSS para o status
     */
    public function getStatusClassAttribute(): string
    {
        $classes = [
            self::STATUS_PENDING => 'status-pending',
            self::STATUS_PAID => 'status-paid',
            self::STATUS_PROCESSING => 'status-processing',
            self::STATUS_SHIPPED => 'status-shipped',
            self::STATUS_DELIVERED => 'status-delivered',
            self::STATUS_CANCELLED => 'status-cancelled',
            self::STATUS_REFUNDED => 'status-refunded',
        ];

        return $classes[$this->status] ?? 'status-unknown';
    }
}
