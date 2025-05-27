<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Brand;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::where('active', 1)->orderBy('name')->get();
        return view('shop.brands.index', compact('brands'));
    }

    public function show(Brand $brand)
    {
        $products = $brand->products()->where('active', 1)->with('categories', 'colors', 'sizes')->paginate(12);
        return view('shop.brands.show', compact('brand', 'products'));
    }
}
