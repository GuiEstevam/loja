<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index(Request $request)
    {
        $query = Brand::orderBy('name');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $status = $request->input('status');
            $query->where('active', $status == '1');
        }

        $brands = $query->with('products')->paginate(15);

        // Estatísticas gerais
        $stats = [
            'total' => Brand::count(),
            'active' => Brand::where('active', true)->count(),
            'inactive' => Brand::where('active', false)->count(),
        ];

        return view('admin.brands.index', compact('brands', 'stats'));
    }

    public function create()
    {
        return view('admin.brands.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug',
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean',
            'logo' => 'nullable|image|',
        ]);

        // Garantir que o campo active seja boolean
        $data['active'] = $request->has('active') ? true : false;

        if ($request->hasFile('logo')) {
            $logoName = uniqid() . '.' . $request->logo->extension();
            $request->logo->move(public_path('brands'), $logoName); // Salva em public/brands
            $data['logo'] = $logoName;
        }

        $brand = Brand::create($data);

        return redirect()->route('admin.brands.show', $brand)->with('success', 'Marca criada com sucesso!');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function show(Request $request, Brand $brand)
    {
        $perPage = $request->get('per_page', 5);
        $products = $brand->products()
            ->with('categories', 'colors', 'sizes')
            ->paginate($perPage);

        return view('admin.brands.show', compact('brand', 'products'));
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug,' . $brand->id,
            'description' => 'nullable|string|max:1000',
            'active' => 'boolean',
            'logo' => 'nullable|image|',
        ]);

        // Garantir que o campo active seja boolean
        $data['active'] = $request->has('active') ? true : false;

        if ($request->hasFile('logo')) {
            $logoName = uniqid() . '.' . $request->logo->extension();
            $request->logo->move(public_path('brands'), $logoName);
            $data['logo'] = $logoName;
            // Remove logo antiga se existir
            if ($brand->logo && file_exists(public_path('brands/' . $brand->logo))) {
                unlink(public_path('brands/' . $brand->logo));
            }
        }

        $brand->update($data);

        return redirect()->route('admin.brands.show', $brand)->with('success', 'Marca atualizada com sucesso!');
    }

    public function destroy(Brand $brand)
    {
        // Remove o logo se existir
        if ($brand->logo && file_exists(public_path('brands/' . $brand->logo))) {
            unlink(public_path('brands/' . $brand->logo));
        }
        
        $brand->delete();
        
        return redirect()->route('admin.brands.index')->with('success', 'Marca removida com sucesso!');
    }
}
