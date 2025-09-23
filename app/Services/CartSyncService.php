<?php

namespace App\Services;

use App\Models\CartItem;
use App\Models\Favorite;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CartSyncService
{
    /**
     * Sincronizar carrinho local com banco de dados
     */
    public function syncCartToDatabase(array $localCart, ?User $user = null, ?string $sessionId = null): void
    {
        if (!$user && !$sessionId) {
            return;
        }

        DB::transaction(function () use ($localCart, $user, $sessionId) {
            // Limpar carrinho atual do usuário/sessão
            $query = CartItem::query();
            if ($user) {
                $query->where('user_id', $user->id);
            } else {
                $query->where('session_id', $sessionId);
            }
            $query->delete();

            // Adicionar itens do carrinho local
            foreach ($localCart as $cartKey => $item) {
                $this->parseCartKey($cartKey, $item, function ($productId, $color, $size) use ($item, $user, $sessionId) {
                    CartItem::create([
                        'user_id' => $user?->id,
                        'session_id' => $sessionId,
                        'product_id' => $productId,
                        'quantity' => $item['quantity'],
                        'color' => $color,
                        'color_hex' => $item['color_hex'] ?? null,
                        'size' => $size,
                        'price' => $item['price'],
                    ]);
                });
            }
        });
    }

    /**
     * Carregar carrinho do banco de dados
     */
    public function loadCartFromDatabase(?User $user = null, ?string $sessionId = null): array
    {
        if (!$user && !$sessionId) {
            return [];
        }

        $cartItems = CartItem::with('product')
            ->forUserOrSession($user?->id, $sessionId)
            ->get();

        $cart = [];
        foreach ($cartItems as $item) {
            $cartKey = $item->cart_key;
            $cart[$cartKey] = [
                'id' => $item->product_id,
                'name' => $item->product->name,
                'sku' => $item->product->sku,
                'price' => $item->price,
                'image' => $item->product->image,
                'brand' => $item->product->brand?->name,
                'categories' => $item->product->categories?->pluck('name')->toArray() ?? [],
                'color' => $item->color,
                'color_hex' => $item->color_hex,
                'size' => $item->size,
                'quantity' => $item->quantity,
            ];
        }

        return $cart;
    }

    /**
     * Mesclar carrinho local com carrinho do banco
     */
    public function mergeCarts(array $localCart, ?User $user = null, ?string $sessionId = null): array
    {
        $dbCart = $this->loadCartFromDatabase($user, $sessionId);

        // Validar e limpar carrinho local primeiro
        $validatedLocalCart = $this->validateCartItems($localCart);

        // Mesclar carrinhos (local tem prioridade)
        foreach ($validatedLocalCart as $cartKey => $localItem) {
            if (isset($dbCart[$cartKey])) {
                // Se existe no banco, usar a maior quantidade
                $dbCart[$cartKey]['quantity'] = max($localItem['quantity'], $dbCart[$cartKey]['quantity']);
            } else {
                // Se não existe no banco, adicionar do local
                $dbCart[$cartKey] = $localItem;
            }
        }

        // Validar carrinho final antes de retornar
        return $this->validateCartItems($dbCart);
    }

    /**
     * Validar itens do carrinho
     */
    private function validateCartItems(array $cart): array
    {
        $validatedCart = [];

        foreach ($cart as $cartKey => $item) {
            // Verificar se o item tem as propriedades necessárias
            if (!is_array($item) || empty($item)) {
                continue;
            }

            // Validar propriedades essenciais
            $hasValidId = isset($item['id']) && is_numeric($item['id']) && $item['id'] > 0;
            $hasValidName = isset($item['name']) && is_string($item['name']) && trim($item['name']) !== '';
            $hasValidPrice = isset($item['price']) && is_numeric($item['price']) && $item['price'] > 0;
            $hasValidQuantity = isset($item['quantity']) && is_numeric($item['quantity']) && $item['quantity'] > 0;

            if ($hasValidId && $hasValidName && $hasValidPrice && $hasValidQuantity) {
                $validatedCart[$cartKey] = [
                    'id' => (int) $item['id'],
                    'name' => trim($item['name']),
                    'price' => (float) $item['price'],
                    'quantity' => (int) $item['quantity'],
                    'image' => $item['image'] ?? 'default.jpg',
                    'sku' => $item['sku'] ?? null,
                    'brand' => $item['brand'] ?? null,
                    'categories' => $item['categories'] ?? [],
                    'color' => $item['color'] ?? null,
                    'color_hex' => $item['color_hex'] ?? null,
                    'size' => $item['size'] ?? null,
                ];
            }
        }

        return $validatedCart;
    }

    /**
     * Sincronizar favoritos local com banco de dados
     */
    public function syncFavoritesToDatabase(array $localFavorites, ?User $user = null, ?string $sessionId = null): void
    {
        if (!$user && !$sessionId) {
            return;
        }

        DB::transaction(function () use ($localFavorites, $user, $sessionId) {
            // Limpar favoritos atuais do usuário/sessão
            $query = Favorite::query();
            if ($user) {
                $query->where('user_id', $user->id);
            } else {
                $query->where('session_id', $sessionId);
            }
            $query->delete();

            // Adicionar favoritos locais
            foreach ($localFavorites as $favorite) {
                Favorite::create([
                    'user_id' => $user?->id,
                    'session_id' => $sessionId,
                    'product_id' => $favorite['id'],
                ]);
            }
        });
    }

    /**
     * Carregar favoritos do banco de dados
     */
    public function loadFavoritesFromDatabase(?User $user = null, ?string $sessionId = null): array
    {
        if (!$user && !$sessionId) {
            return [];
        }

        $favorites = Favorite::with('product')
            ->forUserOrSession($user?->id, $sessionId)
            ->get();

        return $favorites->map(function ($favorite) {
            return [
                'id' => $favorite->product_id,
                'name' => $favorite->product->name,
                'price' => $favorite->product->price,
                'image' => $favorite->product->image,
                'brand' => $favorite->product->brand?->name,
            ];
        })->toArray();
    }

    /**
     * Mesclar favoritos local com favoritos do banco
     */
    public function mergeFavorites(array $localFavorites, ?User $user = null, ?string $sessionId = null): array
    {
        $dbFavorites = $this->loadFavoritesFromDatabase($user, $sessionId);

        // Criar array de IDs para comparação
        $localIds = array_column($localFavorites, 'id');
        $dbIds = array_column($dbFavorites, 'id');

        // Combinar arrays únicos
        $allIds = array_unique(array_merge($localIds, $dbIds));

        $mergedFavorites = [];
        foreach ($allIds as $id) {
            // Priorizar dados do banco se existir
            $dbFavorite = collect($dbFavorites)->firstWhere('id', $id);
            $localFavorite = collect($localFavorites)->firstWhere('id', $id);

            $mergedFavorites[] = $dbFavorite ?: $localFavorite;
        }

        return array_values($mergedFavorites);
    }

    /**
     * Parse da chave do carrinho para extrair produto, cor e tamanho
     */
    private function parseCartKey(string $cartKey, array $item, callable $callback): void
    {
        // Formato: "1" ou "1-c1" ou "1-c1-s2"
        $parts = explode('-', $cartKey);
        $productId = (int) $parts[0];

        $color = null;
        $size = null;

        if (isset($parts[1]) && str_starts_with($parts[1], 'c')) {
            $color = substr($parts[1], 1);
        }

        if (isset($parts[2]) && str_starts_with($parts[2], 's')) {
            $size = substr($parts[2], 1);
        }

        $callback($productId, $color, $size);
    }
}
