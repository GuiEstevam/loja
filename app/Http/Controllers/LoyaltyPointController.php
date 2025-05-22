<?php

namespace App\Http\Controllers;

use App\Models\LoyaltyPoint;
use Illuminate\Http\Request;

class LoyaltyPointController extends Controller
{
    public function index()
    {
        $loyaltyPoints = LoyaltyPoint::with('user')->get();
        return view('loyalty_points.index', compact('loyaltyPoints'));
    }

    public function create()
    {
        return view('loyalty_points.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'points' => 'required|integer|min:0'
        ]);
        LoyaltyPoint::create($validated);
        return redirect()->route('loyalty-points.index')->with('success', 'Pontos de fidelidade cadastrados!');
    }

    public function show(LoyaltyPoint $loyaltyPoint)
    {
        return view('loyalty_points.show', compact('loyaltyPoint'));
    }

    public function edit(LoyaltyPoint $loyaltyPoint)
    {
        return view('loyalty_points.edit', compact('loyaltyPoint'));
    }

    public function update(Request $request, LoyaltyPoint $loyaltyPoint)
    {
        $validated = $request->validate([
            'points' => 'required|integer|min:0'
        ]);
        $loyaltyPoint->update($validated);
        return redirect()->route('loyalty-points.index')->with('success', 'Pontos de fidelidade atualizados!');
    }

    public function destroy(LoyaltyPoint $loyaltyPoint)
    {
        $loyaltyPoint->delete();
        return redirect()->route('loyalty-points.index')->with('success', 'Registro removido!');
    }
}
