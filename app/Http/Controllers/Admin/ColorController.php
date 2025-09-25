<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index(Request $request)
    {
        $query = Color::orderBy('name');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('hex_code', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('active', $status == '1');
        }

        $colors = $query->with('products')->paginate(15);

        // Estatísticas gerais
        $stats = [
            'total' => Color::count(),
            'active' => Color::where('active', true)->count(),
            'inactive' => Color::where('active', false)->count(),
        ];

        return view('admin.colors.index', compact('colors', 'stats'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function show(Color $color)
    {
        // Carregar produtos associados com informações adicionais
        $products = $color->products()->with('brand', 'categories')->get();
        
        // Estatísticas
        $stats = [
            'total_products' => $products->count(),
            'active_products' => $products->where('active', true)->count(),
            'inactive_products' => $products->where('active', false)->count(),
            'total_orders' => $products->sum(function($product) {
                return $product->orderItems()->sum('quantity');
            }),
            'total_revenue' => $products->sum(function($product) {
                return $product->orderItems()->sum('price');
            }),
        ];
        
        return view('admin.colors.show', compact('color', 'products', 'stats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'hex_code' => 'nullable|string|max:7',
            'active' => 'boolean'
        ]);
        $data['active'] = $request->has('active') ? true : false;
        $color = Color::create($data);
        return redirect()->route('admin.colors.show', $color)->with('success', 'Cor criada com sucesso!');
    }

    public function edit(Color $color)
    {
        return view('admin.colors.edit', compact('color'));
    }

    public function update(Request $request, Color $color)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'hex_code' => 'nullable|string|max:7',
            'active' => 'boolean'
        ]);
        $data['active'] = $request->has('active') ? true : false;
        $color->update($data);
        return redirect()->route('admin.colors.show', $color)->with('success', 'Cor atualizada com sucesso!');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', 'Cor removida com sucesso!');
    }
}
