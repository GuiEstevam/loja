<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Buscar dados do usuário
        $orders = $user->orders()->latest()->take(5)->get();
        $addresses = $user->addresses()->take(3)->get();

        // Estatísticas
        $totalOrders = $user->orders()->count();
        $pendingOrders = $user->orders()->where('status', 'pending')->count();
        $totalSpent = $user->orders()->where('status', '!=', 'cancelled')->sum('total');
        $savedAddresses = $user->addresses()->count();

        return view('shop.dashboard', compact(
            'user',
            'orders',
            'addresses',
            'totalOrders',
            'pendingOrders',
            'totalSpent',
            'savedAddresses'
        ));
    }
}
