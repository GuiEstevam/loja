<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    public function welcome()
    {
        // Exemplo: pega os 8 produtos mais recentes ou com destaque
        $products = Product::orderByDesc('created_at')->take(8)->get();
        return view('welcome', compact('products'));
    }
}
