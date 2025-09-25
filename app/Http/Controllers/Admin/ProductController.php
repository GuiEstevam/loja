<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['brand', 'categories', 'colors', 'sizes']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhereHas('categories', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Aplicar filtro de baixo estoque se especificado
        if ($request->filled('filtro') && $request->input('filtro') === 'baixo_estoque') {
            $query->where('stock', '<=', 10);
        }

        // Quantidade de itens por página
        $perPage = $request->get('per_page', 5);
        $perPageOptions = [5, 10, 15, 25, 50];

        $products = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Estatísticas gerais
        $stats = [
            'total' => Product::count(),
            'active' => Product::where('active', true)->count(),
            'inactive' => Product::where('active', false)->count(),
            'low_stock' => Product::where('stock', '<=', 10)->count(),
        ];

        // Se for uma requisição Ajax, retornar JSON
        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.products.partials.products-table', compact('products'))->render(),
                'pagination' => view('admin.products.partials.pagination', compact('products'))->render(),
                'info' => [
                    'total' => $products->total(),
                    'from' => $products->firstItem(),
                    'to' => $products->lastItem(),
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'per_page' => $perPage
                ]
            ]);
        }

        return view('admin.products.index', compact('products', 'perPageOptions', 'perPage', 'stats'));
    }

    public function search(Request $request)
    {
        $query = Product::with(['brand', 'categories', 'colors', 'sizes']);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%")
                    ->orWhereHas('categories', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Aplicar filtro de baixo estoque se especificado
        if ($request->filled('filtro') && $request->input('filtro') === 'baixo_estoque') {
            $query->where('stock', '<=', 10);
        }

        // Quantidade de itens por página
        $perPage = $request->get('per_page', 5);
        $perPageOptions = [5, 10, 15, 25, 50];

        $products = $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        // Se foi especificada uma página específica, ir para ela
        if ($request->filled('page')) {
            $page = $request->get('page');
            $products = $query->orderBy('created_at', 'desc')
                ->paginate($perPage, ['*'], 'page', $page)
                ->withQueryString();
        }

        // Debug: verificar se os produtos têm SKU
        foreach ($products as $product) {
            if (empty($product->sku)) {
                Log::warning("Produto {$product->id} não tem SKU definido");
            }
        }

        return response()->json([
            'html' => view('admin.products.partials.products-table', compact('products'))->render(),
            'pagination' => view('admin.products.partials.pagination', compact('products'))->render(),
            'info' => [
                'total' => $products->total(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $perPage
            ]
        ]);
    }

    public function create()
    {
        $brands = Brand::where('active', 1)->orderBy('name')->get();
        $categories = Category::where('active', 1)->orderBy('name')->get();
        $colors = Color::where('active', 1)->orderBy('name')->get();
        $sizes = Size::where('active', 1)->orderBy('name')->get();

        return view('admin.products.create', compact('brands', 'categories', 'colors', 'sizes'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku',
            'brand_id' => 'required|exists:brands,id',
            'image' => 'required|image|max:2048',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'colors' => 'required|array',
            'colors.*' => 'exists:colors,id',
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id',
            'active' => 'boolean',
            'featured' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            'free_shipping' => 'boolean',
            'installments' => 'nullable|integer|min:1|max:12',
            'installment_value' => 'nullable|numeric|min:0',
            'is_new' => 'boolean',
            'is_sale' => 'boolean',
            'sale_price' => 'nullable|numeric|min:0',
            'sale_ends_at' => 'nullable|date'
        ]);

        // Upload de imagem
        if ($request->hasFile('image')) {
            $imageName = Str::random(12) . '.' . $request->image->extension();
            $request->image->move(public_path('products'), $imageName);
            $data['image'] = $imageName;
        }

        $product = Product::create($data);

        // Relacionamentos N:N
        $product->categories()->sync($data['categories']);
        $product->colors()->sync($data['colors']);
        $product->sizes()->sync($data['sizes']);

        return redirect()->route('admin.products.index')->with('success', 'Produto criado!');
    }

    public function edit(Product $product)
    {
        $brands = Brand::where('active', 1)->orderBy('name')->get();
        $categories = Category::where('active', 1)->orderBy('name')->get();
        $colors = Color::where('active', 1)->orderBy('name')->get();
        $sizes = Size::where('active', 1)->orderBy('name')->get();

        // Carrega os relacionamentos para o form
        $product->load('categories', 'colors', 'sizes');

        return view('admin.products.edit', compact('product', 'brands', 'categories', 'colors', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:255|unique:products,sku,' . $product->id,
            'brand_id' => 'required|exists:brands,id',
            'image' => 'nullable|image|max:2048',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
            'colors' => 'required|array',
            'colors.*' => 'exists:colors,id',
            'sizes' => 'required|array',
            'sizes.*' => 'exists:sizes,id',
            'active' => 'boolean',
            'featured' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            'free_shipping' => 'boolean',
            'installments' => 'nullable|integer|min:1|max:12',
            'installment_value' => 'nullable|numeric|min:0',
            'is_new' => 'boolean',
            'is_sale' => 'boolean',
            'sale_price' => 'nullable|numeric|min:0',
            'sale_ends_at' => 'nullable|date'
        ]);

        // Upload de nova imagem (se enviada)
        if ($request->hasFile('image')) {
            $imageName = Str::random(12) . '.' . $request->image->extension();
            $request->image->move(public_path('products'), $imageName);
            $data['image'] = $imageName;
            // Opcional: apague a imagem antiga se desejar
            // if ($product->image) @unlink(public_path('products/' . $product->image));
        } else {
            unset($data['image']);
        }

        $product->update($data);

        // Relacionamentos N:N
        $product->categories()->sync($data['categories']);
        $product->colors()->sync($data['colors']);
        $product->sizes()->sync($data['sizes']);

        return redirect()->route('admin.products.index')->with('success', 'Produto atualizado!');
    }

    public function destroy(Product $product)
    {
        // Opcional: apague a imagem do disco se desejar
        // if ($product->image) @unlink(public_path('products/' . $product->image));
        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Produto removido!');
    }
}
