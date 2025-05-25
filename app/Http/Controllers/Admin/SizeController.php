<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    public function index()
    {
        $sizes = Size::orderBy('name')->paginate(15);
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
        Size::create($data);
        return redirect()->route('admin.sizes.index')->with('success', 'Tamanho criado!');
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
        $size->update($data);
        return redirect()->route('admin.sizes.index')->with('success', 'Tamanho atualizado!');
    }

    public function destroy(Size $size)
    {
        $size->delete();
        return redirect()->route('admin.sizes.index')->with('success', 'Tamanho removido!');
    }
}
