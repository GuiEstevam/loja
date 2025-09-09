<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        // Se o usuário estava em uma página específica (como carrinho), redireciona para lá
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
