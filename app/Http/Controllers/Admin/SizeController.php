<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index(Request $request)
    {
        $query = Size::orderBy('name');
        
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('name', 'like', "%{$search}%");
        }
        
        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('active', $status == '1');
        }
        
        $sizes = $query->with('products')->paginate(15);
        
        return view('admin.sizes.index', compact('sizes'));
    }

    public function create()
    {
        return view('admin.sizes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'boolean'
        ]);
        
        $data['active'] = $request->has('active') ? true : false;
        $size = Size::create($data);
        
        return redirect()->route('admin.sizes.show', $size)->with('success', 'Tamanho criado com sucesso!');
    }

    public function edit(Size $size)
    {
        return view('admin.sizes.edit', compact('size'));
    }

    public function update(Request $request, Size $size)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'active' => 'boolean'
        ]);
        
        $data['active'] = $request->has('active') ? true : false;
        $size->update($data);
        
        return redirect()->route('admin.sizes.show', $size)->with('success', 'Tamanho atualizado com sucesso!');
    }

    public function show(Size $size)
    {
        $products = $size->products()->with('brand', 'categories')->get();
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
        
        $products = $size->products()->with('brand', 'categories')->paginate(12);
        
        return view('admin.sizes.show', compact('size', 'products', 'stats'));
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('admin.sizes.index')->with('success', 'Tamanho removido com sucesso!');
    }
}
