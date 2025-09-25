<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::orderBy('name');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        $categories = $query->with('products')->paginate(15);

        // Estatísticas gerais
        $stats = [
            'total' => Category::count(),
            'active' => Category::where('active', true)->count(),
            'inactive' => Category::where('active', false)->count(),
        ];

        return view('admin.categories.index', compact('categories', 'stats'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug',
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean'
        ]);
        
        // Garantir que o campo active seja boolean
        $data['active'] = $request->has('active') ? true : false;
        
        $category = Category::create($data);
        return redirect()->route('admin.categories.show', $category)->with('success', 'Categoria criada com sucesso!');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function show(Request $request, Category $category)
    {
        $perPage = $request->get('per_page', 5);
        $products = $category->products()->with('brand', 'colors', 'sizes')->paginate($perPage);
        return view('admin.categories.show', compact('category', 'products'));
    }


    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $category->id,
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean'
        ]);
        
        // Garantir que o campo active seja boolean
        $data['active'] = $request->has('active') ? true : false;
        
        $category->update($data);
        return redirect()->route('admin.categories.show', $category)->with('success', 'Categoria atualizada com sucesso!');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Categoria removida com sucesso!');
    }
}
