<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o usuário está autenticado
        if (auth()->check()) {
            $user = auth()->user();
            
            // Se o usuário não está ativo
            if (!$user->active) {
                // Fazer logout do usuário
                auth()->logout();
                
                // Invalidar a sessão
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                // Redirecionar para login com mensagem de erro
                return redirect()->route('login')->with('error', 'Sua conta foi desativada. Entre em contato com o suporte.');
            }
        }
        
        return $next($request);
    }
}
