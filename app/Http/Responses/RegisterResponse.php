<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;

class RegisterResponse implements RegisterResponseContract
{
    public function toResponse($request)
    {
        $user = $request->user();

        if ($user && $user->hasRole('admin')) {
            return redirect()->intended(route('admin.dashboard'));
        } else {
            return redirect()->intended(route('shop.dashboard'));
        }
    }
}
