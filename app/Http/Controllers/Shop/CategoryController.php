<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Category;

class CategoryController extends Controller
{
    public function show(Category $category)
    {
        // Carrega produtos ativos da categoria
        $products = $category->products()->where('active', 1)->with('brand', 'colors', 'sizes')->paginate(12);
        return view('shop.categories.show', compact('category', 'products'));
    }
}
