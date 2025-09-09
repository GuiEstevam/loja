<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // Tentar obter a URL de destino da sessão
        $intendedUrl = session('intended_url');
        
        // Se há uma URL de destino específica, usa ela
        if ($intendedUrl) {
            // Limpar a URL de destino da sessão
            session()->forget('intended_url');
            return redirect($intendedUrl);
        }
        
        // Fallback para redirect()->intended()
        $intended = redirect()->intended();
        
        // Se não há URL de destino específica, redireciona baseado no tipo de usuário
        if ($intended->getTargetUrl() === url('/dashboard')) {
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard');
            } else {
                // Para usuários normais, redireciona para a página inicial
                return redirect()->route('home');
            }
        }
        
        // Se há uma URL de destino específica, usa ela
        return $intended;
    }
}
