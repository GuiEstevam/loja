<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'stock',
        'image',
        'active',
        'sku',
        'brand_id',
        'featured',
        'weight',
        'dimensions',
        'free_shipping',
        'rating',
        'rating_count',
        'installments',
        'installment_value',
        'is_new',
        'is_sale',
        'sale_price',
        'sale_ends_at'
    ];

    protected $casts = [
        'free_shipping' => 'boolean',
        'featured' => 'boolean',
        'is_new' => 'boolean',
        'is_sale' => 'boolean',
        'rating' => 'decimal:1',
        'installment_value' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'sale_ends_at' => 'date',
    ];

    // Relacionamento com itens de pedido
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Novos relacionamentos
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->approved();
    }

    /**
     * Métodos para estatísticas de reviews
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->approvedReviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    public function getRatingDistributionAttribute(): array
    {
        $distribution = [];
        
        for ($rating = 1; $rating <= 5; $rating++) {
            $distribution[$rating] = $this->approvedReviews()
                ->where('rating', $rating)
                ->count();
        }

        return $distribution;
    }

    /**
     * Verificar se usuário pode avaliar este produto
     */
    public function canUserReview($userId): bool
    {
        return Review::canUserReviewProduct($userId, $this->id);
    }
}
