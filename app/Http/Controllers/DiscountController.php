<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use Illuminate\Http\Request;

class DiscountController extends Controller
{
    public function index()
    {
        $discounts = Discount::all();
        return view('discounts.index', compact('discounts'));
    }

    public function create()
    {
        return view('discounts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code',
            'amount' => 'nullable|numeric|min:0',
            'percentage' => 'nullable|integer|min:0|max:100',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'active' => 'boolean'
        ]);
        Discount::create($validated);
        return redirect()->route('discounts.index')->with('success', 'Cupom cadastrado!');
    }

    public function show(Discount $discount)
    {
        return view('discounts.show', compact('discount'));
    }

    public function edit(Discount $discount)
    {
        return view('discounts.edit', compact('discount'));
    }

    public function update(Request $request, Discount $discount)
    {
        $validated = $request->validate([
            'code' => 'required|string|unique:discounts,code,' . $discount->id,
            'amount' => 'nullable|numeric|min:0',
            'percentage' => 'nullable|integer|min:0|max:100',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date|after_or_equal:valid_from',
            'active' => 'boolean'
        ]);
        $discount->update($validated);
        return redirect()->route('discounts.index')->with('success', 'Cupom atualizado!');
    }

    public function destroy(Discount $discount)
    {
        $discount->delete();
        return redirect()->route('discounts.index')->with('success', 'Cupom removido!');
    }
}
