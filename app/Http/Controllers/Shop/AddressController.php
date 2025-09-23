<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    public function index()
    {
        $addresses = Auth::user()->addresses()->orderBy('created_at', 'desc')->get();
        return view('shop.addresses.index', compact('addresses'));
    }

    public function create()
    {
        return view('shop.addresses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address_line1'  => 'required|string|max:255',
            'address_line2'  => 'nullable|string|max:255',
            'city'           => 'required|string|max:100',
            'state'          => 'required|string|max:100',
            'country'        => 'required|string|max:100',
            'zipcode'        => 'required|string|max:20',
        ]);

        $user = Auth::user();
        $user->addresses()->create($validated);

        return redirect()->route('enderecos.index')->with('success', 'Endereço cadastrado com sucesso!');
    }

    public function show(Address $endereco)
    {
        if ($endereco->user_id !== Auth::id()) {
            abort(403);
        }
        return view('shop.addresses.show', ['address' => $endereco]);
    }

    public function edit(Address $endereco)
    {
        if ($endereco->user_id !== Auth::id()) {
            abort(403);
        }
        return view('shop.addresses.edit', ['address' => $endereco]);
    }

    public function update(Request $request, Address $endereco)
    {
        if ($endereco->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'phone'          => 'required|string|max:20',
            'address_line1'  => 'required|string|max:255',
            'address_line2'  => 'nullable|string|max:255',
            'city'           => 'required|string|max:100',
            'state'          => 'required|string|max:100',
            'country'        => 'required|string|max:100',
            'zipcode'        => 'required|string|max:20',
        ]);

        $endereco->update($validated);

        return redirect()->route('enderecos.index')->with('success', 'Endereço atualizado com sucesso!');
    }

    public function destroy(Address $endereco)
    {
        if ($endereco->user_id !== Auth::id()) {
            abort(403);
        }
        $endereco->delete();
        return redirect()->route('enderecos.index')->with('success', 'Endereço removido com sucesso!');
    }
}
