<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::with(['order', 'product'])->get();
        return view('order_items.index', compact('orderItems'));
    }

    public function create()
    {
        return view('order_items.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ]);
        OrderItem::create($validated);
        return redirect()->route('order_items.index')->with('success', 'Item de pedido criado com sucesso!');
    }

    public function show(OrderItem $orderItem)
    {
        return view('order_items.show', compact('orderItem'));
    }

    public function edit(OrderItem $orderItem)
    {
        return view('order_items.edit', compact('orderItem'));
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0'
        ]);
        $orderItem->update($validated);
        return redirect()->route('order_items.index')->with('success', 'Item de pedido atualizado com sucesso!');
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();
        return redirect()->route('order_items.index')->with('success', 'Item de pedido removido com sucesso!');
    }
}
