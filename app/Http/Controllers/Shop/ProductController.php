<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Lista produtos ativos para o cliente
    public function index(Request $request)
    {
        $query = Product::where('active', true);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Ordena pelo ID crescente
        $products = $query->orderBy('id', 'asc')->paginate(12);

        return view('shop.products.index', compact('products'));
    }


    // Mostra detalhes de um produto específico
    public function show(Product $product)
    {
        if (!$product->active) {
            abort(404);
        }

        return view('shop.products.show', compact('product'));
    }
}
