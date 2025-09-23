<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'method',
        'status',
        'transaction_id',
        'amount',
        'gateway_response',
        'processed_at',
        'failed_at',
        'failure_reason'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'processed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];

    // Constantes para status
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_DECLINED = 'declined';
    const STATUS_REFUNDED = 'refunded';
    const STATUS_FAILED = 'failed';

    // Constantes para métodos de pagamento
    const METHOD_CREDIT_CARD = 'credit_card';
    const METHOD_PIX = 'pix';
    const METHOD_BOLETO = 'boleto';
    const METHOD_MBWAY = 'mbway';
    const METHOD_MULTIBANCO = 'multibanco';
    const METHOD_PAYPAL = 'paypal';

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Verifica se o pagamento foi aprovado
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    /**
     * Verifica se o pagamento está pendente
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    /**
     * Verifica se o pagamento falhou
     */
    public function isFailed(): bool
    {
        return in_array($this->status, [self::STATUS_DECLINED, self::STATUS_FAILED]);
    }

    /**
     * Marca o pagamento como aprovado
     */
    public function markAsApproved(string $transactionId = null, array $gatewayResponse = []): void
    {
        $this->update([
            'status' => self::STATUS_APPROVED,
            'transaction_id' => $transactionId,
            'gateway_response' => $gatewayResponse,
            'processed_at' => now(),
            'failed_at' => null,
            'failure_reason' => null,
        ]);
    }

    /**
     * Marca o pagamento como falhado
     */
    public function markAsFailed(string $reason = null, array $gatewayResponse = []): void
    {
        $this->update([
            'status' => self::STATUS_FAILED,
            'gateway_response' => $gatewayResponse,
            'failed_at' => now(),
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Marca o pagamento como reembolsado
     */
    public function markAsRefunded(string $transactionId = null, array $gatewayResponse = []): void
    {
        $this->update([
            'status' => self::STATUS_REFUNDED,
            'transaction_id' => $transactionId,
            'gateway_response' => $gatewayResponse,
            'processed_at' => now(),
        ]);
    }
}
