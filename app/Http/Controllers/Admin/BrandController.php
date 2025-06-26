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

        $brands = $query->paginate(15);

        return view('admin.brands.index', compact('brands'));
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
            'active' => 'boolean',
            'logo' => 'nullable|image|',
        ]);

        if ($request->hasFile('logo')) {
            $logoName = uniqid() . '.' . $request->logo->extension();
            $request->logo->move(public_path('brands'), $logoName); // Salva em public/brands
            $data['logo'] = $logoName;
        }

        Brand::create($data);

        return redirect()->route('admin.brands.index')->with('success', 'Marca criada!');
    }

    public function edit(Brand $brand)
    {
        return view('admin.brands.edit', compact('brand'));
    }

    public function show(Brand $brand)
    {
        $products = $brand->products()
            ->with('categories', 'colors', 'sizes')
            ->paginate(12);

        return view('admin.brands.show', compact('brand', 'products'));
    }

    public function update(Request $request, Brand $brand)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:brands,slug,' . $brand->id,
            'active' => 'boolean',
            'logo' => 'nullable|image|',
        ]);

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

        return redirect()->route('admin.brands.index')->with('success', 'Marca atualizada!');
    }

    public function destroy(Brand $brand)
    {
        $brand->delete();
        return redirect()->route('admin.brands.index')->with('success', 'Marca removida!');
    }
}
