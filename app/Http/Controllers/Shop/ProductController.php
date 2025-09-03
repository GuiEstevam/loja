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

        // Busca por nome, descrição ou categoria
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('categories', function ($categoryQuery) use ($searchTerm) {
                        $categoryQuery->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('brand', function ($brandQuery) use ($searchTerm) {
                        $brandQuery->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Aplicar todos os filtros
        $this->applyFilters($query, $request);

        // Ordenação
        $this->applySorting($query, $request);

        $products = $query->paginate(12)->withQueryString();

        // Carrega subentidades para os filtros
        $brands = Brand::where('active', 1)->orderBy('name')->get();
        $categories = Category::where('active', 1)->orderBy('name')->get();
        $colors = Color::where('active', 1)->orderBy('name')->get();
        $sizes = Size::where('active', 1)->orderBy('name')->get();

        // Estatísticas para os filtros
        $stats = [
            'total_products' => Product::where('active', true)->count(),
            'new_products' => Product::where('active', true)->where('is_new', true)->count(),
            'sale_products' => Product::where('active', true)->where('is_sale', true)->count(),
            'free_shipping_products' => Product::where('active', true)->where('free_shipping', true)->count(),
            'min_price' => Product::where('active', true)->min('price'),
            'max_price' => Product::where('active', true)->max('price'),
        ];

        $hasActiveFilters = $this->hasActiveFilters($request);

        return view('shop.products.index', compact('products', 'brands', 'categories', 'colors', 'sizes', 'stats', 'hasActiveFilters'));
    }

    // Busca AJAX de produtos
    public function search(Request $request)
    {
        $query = Product::with(['brand', 'categories', 'colors', 'sizes'])
            ->where('active', true);

        // Busca por nome, descrição ou categoria
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                    ->orWhere('description', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('categories', function ($categoryQuery) use ($searchTerm) {
                        $categoryQuery->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('brand', function ($brandQuery) use ($searchTerm) {
                        $brandQuery->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        // Aplicar todos os filtros
        $this->applyFilters($query, $request);

        // Ordenação
        $this->applySorting($query, $request);

        $products = $query->paginate(12);

        // Estatísticas atualizadas
        $stats = [
            'total_products' => Product::where('active', true)->count(),
            'new_products' => Product::where('active', true)->where('is_new', true)->count(),
            'sale_products' => Product::where('active', true)->where('is_sale', true)->count(),
            'free_shipping_products' => Product::where('active', true)->where('free_shipping', true)->count(),
            'min_price' => Product::where('active', true)->min('price'),
            'max_price' => Product::where('active', true)->max('price'),
        ];

        $hasActiveFilters = $this->hasActiveFilters($request);

        return response()->json([
            'html' => view('shop.products.partials.products-grid', compact('products'))->render(),
            'stats' => $stats,
            'hasActiveFilters' => $hasActiveFilters,
            'pagination' => view('vendor.pagination.custom', ['paginator' => $products])->render(),
            'results_count' => "Mostrando {$products->firstItem()}-{$products->lastItem()} de {$products->total()} produtos" . ($hasActiveFilters ? ' (filtrados)' : '')
        ]);
    }

    // Helper para aplicar filtros
    private function applyFilters($query, Request $request)
    {
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

        // Filtro por cores
        if ($request->filled('colors')) {
            $colors = is_array($request->colors) ? $request->colors : [$request->colors];
            $query->whereHas('colors', function ($q) use ($colors) {
                $q->whereIn('colors.id', $colors);
            });
        }

        // Filtro por tamanhos
        if ($request->filled('sizes')) {
            $sizes = is_array($request->sizes) ? $request->sizes : [$request->sizes];
            $query->whereHas('sizes', function ($q) use ($sizes) {
                $q->whereIn('sizes.id', $sizes);
            });
        }

        // Filtro por faixa de preço
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Filtro por produtos novos
        if ($request->filled('is_new') && $request->is_new) {
            $query->where('is_new', true);
        }

        // Filtro por produtos em promoção
        if ($request->filled('is_sale') && $request->is_sale) {
            $query->where('is_sale', true);
        }

        // Filtro por frete grátis
        if ($request->filled('free_shipping') && $request->free_shipping) {
            $query->where('free_shipping', true);
        }

        // Filtro por rating mínimo
        if ($request->filled('min_rating')) {
            $query->where('rating', '>=', $request->min_rating);
        }
    }

    // Helper para aplicar ordenação
    private function applySorting($query, Request $request)
    {
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
            case 'rating_desc':
                $query->orderBy('rating', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'oldest':
                $query->orderBy('created_at', 'asc');
                break;
            default:
                $query->orderBy('id', 'desc');
        }
    }

    // Mostra detalhes de um produto específico
    public function show(Product $product)
    {
        if (!$product->active) {
            abort(404);
        }

        return view('shop.products.show', compact('product'));
    }

    // Helper para verificar se há filtros ativos
    private function hasActiveFilters(Request $request): bool
    {
        return $request->filled('search') ||
            $request->filled('brand') ||
            $request->filled('category') ||
            ($request->has('colors') && is_array($request->colors) && count($request->colors) > 0) ||
            ($request->has('sizes') && is_array($request->sizes) && count($request->sizes) > 0) ||
            $request->filled('min_price') ||
            $request->filled('max_price') ||
            $request->boolean('is_new') ||
            $request->boolean('is_sale') ||
            $request->boolean('free_shipping') ||
            $request->filled('min_rating');
    }
}
