<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\CartSyncService;
use Symfony\Component\HttpFoundation\Response;

class SyncCartOnLogin
{
    protected $cartSyncService;

    public function __construct(CartSyncService $cartSyncService)
    {
        $this->cartSyncService = $cartSyncService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Sincronizar apenas após login bem-sucedido
        if (Auth::check() && $request->is('login') && $response->getStatusCode() === 302) {
            $this->syncUserData($request);
        }

        return $response;
    }

    /**
     * Sincronizar dados do usuário após login
     */
    protected function syncUserData(Request $request): void
    {
        try {
            $user = Auth::user();
            $sessionId = session()->getId();

            // Sincronizar carrinho
            $localCart = json_decode($request->cookie('cart', '{}'), true) ?:
                json_decode($request->header('X-Cart-Data', '{}'), true) ?: [];

            if (!empty($localCart)) {
                $mergedCart = $this->cartSyncService->mergeCarts($localCart, $user, $sessionId);
                $this->cartSyncService->syncCartToDatabase($mergedCart, $user, $sessionId);
            }

            // Sincronizar favoritos
            $localFavorites = json_decode($request->cookie('favorites', '[]'), true) ?:
                json_decode($request->header('X-Favorites-Data', '[]'), true) ?: [];

            if (!empty($localFavorites)) {
                $mergedFavorites = $this->cartSyncService->mergeFavorites($localFavorites, $user, $sessionId);
                $this->cartSyncService->syncFavoritesToDatabase($mergedFavorites, $user, $sessionId);
            }
        } catch (\Exception $e) {
            // Log do erro mas não interrompe o login
            \Log::error('Erro ao sincronizar dados do usuário: ' . $e->getMessage());
        }
    }
}
