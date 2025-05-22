<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('items')->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string'
        ]);
        Order::create($validated);
        return redirect()->route('orders.index')->with('success', 'Pedido criado com sucesso!');
    }

    public function show(Order $order)
    {
        $order->load('items');
        return view('orders.show', compact('order'));
    }

    public function edit(Order $order)
    {
        return view('orders.edit', compact('order'));
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'subtotal' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'status' => 'required|string'
        ]);
        $order->update($validated);
        return redirect()->route('orders.index')->with('success', 'Pedido atualizado com sucesso!');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Pedido removido com sucesso!');
    }
}
