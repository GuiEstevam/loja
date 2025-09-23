<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoyaltyPoint;
use Illuminate\Http\Request;

class LoyaltyPointController extends Controller
{
    public function index()
    {
        $loyaltyPoints = LoyaltyPoint::with('user')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.loyalty_points.index', compact('loyaltyPoints'));
    }

    public function show(LoyaltyPoint $loyaltyPoint)
    {
        $loyaltyPoint->load('user');
        return view('admin.loyalty_points.show', compact('loyaltyPoint'));
    }

    public function edit(LoyaltyPoint $loyaltyPoint)
    {
        return view('admin.loyalty_points.edit', compact('loyaltyPoint'));
    }

    public function update(Request $request, LoyaltyPoint $loyaltyPoint)
    {
        $validated = $request->validate([
            'points' => 'required|integer|min:0'
        ]);

        $loyaltyPoint->update($validated);

        return redirect()->route('admin.loyalty_points.index')->with('success', 'Pontos atualizados!');
    }

    public function destroy(LoyaltyPoint $loyaltyPoint)
    {
        $loyaltyPoint->delete();
        return redirect()->route('admin.loyalty_points.index')->with('success', 'Registro removido!');
    }
}
