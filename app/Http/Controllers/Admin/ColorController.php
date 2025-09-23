<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    public function index()
    {
        $colors = Color::orderBy('name')->paginate(15);
        return view('admin.colors.index', compact('colors'));
    }

    public function create()
    {
        return view('admin.colors.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'hex_code' => 'nullable|string|max:7',
            'active' => 'boolean'
        ]);
        Color::create($data);
        return redirect()->route('admin.colors.index')->with('success', 'Cor criada!');
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
        $color->update($data);
        return redirect()->route('admin.colors.index')->with('success', 'Cor atualizada!');
    }

    public function destroy(Color $color)
    {
        $color->delete();
        return redirect()->route('admin.colors.index')->with('success', 'Cor removida!');
    }
}
