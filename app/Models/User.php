<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Order;
use App\Models\Address;
use App\Models\LoyaltyPoint;
use App\Models\Discount;
use App\Models\CartItem;
use App\Models\Favorite;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasRoles;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relacionamento: um usuário pode ter vários pedidos
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // Relacionamento: um usuário pode ter vários endereços
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    // Relacionamento: um usuário pode ter um registro de pontos de fidelidade
    public function loyaltyPoints()
    {
        return $this->hasOne(LoyaltyPoint::class);
    }

    // Relacionamento: um usuário pode usar vários cupons/descontos (caso queira registrar uso)
    public function discounts()
    {
        return $this->belongsToMany(Discount::class, 'user_discount');
    }

    // Relacionamento: um usuário pode ter vários itens no carrinho
    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    // Relacionamento: um usuário pode ter vários favoritos
    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    // Relacionamento: um usuário pode ter várias reviews
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->approved();
    }

    /**
     * Métodos para estatísticas de reviews do usuário
     */
    public function getTotalReviewsAttribute(): int
    {
        return $this->reviews()->count();
    }

    public function getApprovedReviewsAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    public function getAverageRatingGivenAttribute(): float
    {
        return $this->reviews()->avg('rating') ?? 0;
    }
}
