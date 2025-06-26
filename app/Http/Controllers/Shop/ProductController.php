<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Lista produtos ativos para o cliente com filtros
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'categories', 'colors', 'sizes'])
            ->where('active', true);

        // Busca por nome
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filtro por marca
        if ($request->filled('brand')) {
            $query->where('brand_id', $request->brand);
        }

        // Filtro por categoria
        if ($request->filled('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('id', $request->category);
            });
        }

        // Filtro por cores (array)
        if ($request->filled('colors')) {
            $colors = is_array($request->colors) ? $request->colors : [$request->colors];
            $query->whereHas('colors', function ($q) use ($colors) {
                $q->whereIn('colors.id', $colors);
            });
        }

        // Filtro por tamanhos (array)
        if ($request->filled('sizes')) {
            $sizes = is_array($request->sizes) ? $request->sizes : [$request->sizes];
            $query->whereHas('sizes', function ($q) use ($sizes) {
                $q->whereIn('sizes.id', $sizes);
            });
        }

        // Ordenação
        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Carrega subentidades para os filtros
        $brands = Brand::where('active', 1)->orderBy('name')->get();
        $categories = Category::where('active', 1)->orderBy('name')->get();
        $colors = Color::where('active', 1)->orderBy('name')->get();
        $sizes = Size::where('active', 1)->orderBy('name')->get();

        return view('shop.products.index', compact('products', 'brands', 'categories', 'colors', 'sizes'));
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
