<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'order_id',
        'rating',
        'title',
        'comment',
        'status',
        'verified_purchase',
        'helpful_count'
    ];

    protected $casts = [
        'verified_purchase' => 'boolean',
        'helpful_count' => 'integer',
        'rating' => 'integer',
    ];

    // Constantes para status
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Constantes para rating
    const RATING_MIN = 1;
    const RATING_MAX = 5;

    /**
     * Relacionamentos
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scopes para consultas
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeVerified($query)
    {
        return $query->where('verified_purchase', true);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Métodos helper para status
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isVerified(): bool
    {
        return $this->verified_purchase;
    }

    /**
     * Métodos para moderação
     */
    public function approve(): bool
    {
        return $this->update(['status' => self::STATUS_APPROVED]);
    }

    public function reject(): bool
    {
        return $this->update(['status' => self::STATUS_REJECTED]);
    }

    /**
     * Métodos para rating
     */
    public function getRatingStarsAttribute(): string
    {
        $stars = '';
        for ($i = 1; $i <= self::RATING_MAX; $i++) {
            $stars .= $i <= $this->rating ? '★' : '☆';
        }
        return $stars;
    }

    public function getRatingLabelAttribute(): string
    {
        $labels = [
            1 => 'Muito Ruim',
            2 => 'Ruim',
            3 => 'Regular',
            4 => 'Bom',
            5 => 'Excelente'
        ];

        return $labels[$this->rating] ?? 'N/A';
    }

    /**
     * Métodos para contador de útil
     */
    public function markAsHelpful(): bool
    {
        return $this->increment('helpful_count');
    }

    public function markAsNotHelpful(): bool
    {
        if ($this->helpful_count > 0) {
            return $this->decrement('helpful_count');
        }
        return true;
    }

    /**
     * Métodos estáticos para estatísticas
     */
    public static function getAverageRatingForProduct($productId): float
    {
        return self::approved()
            ->forProduct($productId)
            ->avg('rating') ?? 0;
    }

    public static function getTotalReviewsForProduct($productId): int
    {
        return self::approved()
            ->forProduct($productId)
            ->count();
    }

    public static function getRatingDistributionForProduct($productId): array
    {
        $distribution = [];
        
        for ($rating = self::RATING_MIN; $rating <= self::RATING_MAX; $rating++) {
            $distribution[$rating] = self::approved()
                ->forProduct($productId)
                ->byRating($rating)
                ->count();
        }

        return $distribution;
    }

    /**
     * Verificar se usuário pode avaliar produto
     */
    public static function canUserReviewProduct($userId, $productId): bool
    {
        // Verificar se já existe review
        $existingReview = self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();

        if ($existingReview) {
            return false;
        }

        // Verificar se usuário comprou o produto
        $hasPurchased = Order::where('user_id', $userId)
            ->where('status', '!=', 'cancelled')
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();

        return $hasPurchased;
    }

    /**
     * Boot do modelo
     */
    protected static function boot()
    {
        parent::boot();

        // Auto-aprovar reviews de compras verificadas
        static::creating(function ($review) {
            if ($review->verified_purchase) {
                $review->status = self::STATUS_APPROVED;
            }
        });
    }
}