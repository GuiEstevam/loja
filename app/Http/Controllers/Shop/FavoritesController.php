<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    /**
     * Exibe a página de favoritos
     */
    public function index()
    {
        return view('shop.favorites.index');
    }
}
