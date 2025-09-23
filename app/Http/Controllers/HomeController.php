<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;

class HomeController extends Controller
{
    public function welcome()
    {
        // Busca categorias cujo slug seja feminino ou masculino
        $femininoCategories = Category::where('slug', 'feminino')->get();
        $masculinoCategories = Category::where('slug', 'masculino')->get();

        // Ou, caso queira listar todas as categorias ativas em cada slider:
        // $femininoCategories = Category::where('type', 'feminino')->orderBy('name')->get();
        // $masculinoCategories = Category::where('type', 'masculino')->orderBy('name')->get();

        $brands = Brand::where('active', 1)->orderBy('name')->take(12)->get();
        $products = Product::with('brand')->orderByDesc('created_at')->take(12)->get();

        return view('welcome', compact(
            'femininoCategories',
            'masculinoCategories',
            'brands',
            'products'
        ));
    }
}
