<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartSyncService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SyncController extends Controller
{
    protected $cartSyncService;

    public function __construct(CartSyncService $cartSyncService)
    {
        $this->cartSyncService = $cartSyncService;
    }

    /**
     * Sincronizar carrinho
     */
    public function syncCart(Request $request)
    {
        $localCart = $request->input('cart', []);
        $user = Auth::user();
        $sessionId = session()->getId();

        try {
            // Mesclar carrinhos
            $mergedCart = $this->cartSyncService->mergeCarts($localCart, $user, $sessionId);

            // Sincronizar com banco
            $this->cartSyncService->syncCartToDatabase($mergedCart, $user, $sessionId);

            return response()->json([
                'success' => true,
                'message' => 'Carrinho sincronizado com sucesso',
                'cart' => $mergedCart
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao sincronizar carrinho: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Sincronizar favoritos
     */
    public function syncFavorites(Request $request)
    {
        $localFavorites = $request->input('favorites', []);
        $user = Auth::user();
        $sessionId = session()->getId();

        try {
            // Mesclar favoritos
            $mergedFavorites = $this->cartSyncService->mergeFavorites($localFavorites, $user, $sessionId);

            // Sincronizar com banco
            $this->cartSyncService->syncFavoritesToDatabase($mergedFavorites, $user, $sessionId);

            return response()->json([
                'success' => true,
                'message' => 'Favoritos sincronizados com sucesso',
                'favorites' => $mergedFavorites
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao sincronizar favoritos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Carregar dados do banco
     */
    public function loadData(Request $request)
    {
        $user = Auth::user();
        $sessionId = session()->getId();

        try {
            $cart = $this->cartSyncService->loadCartFromDatabase($user, $sessionId);
            $favorites = $this->cartSyncService->loadFavoritesFromDatabase($user, $sessionId);

            return response()->json([
                'success' => true,
                'cart' => $cart,
                'favorites' => $favorites
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao carregar dados: ' . $e->getMessage()
            ], 500);
        }
    }
}
