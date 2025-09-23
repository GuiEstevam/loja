<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    public function index()
    {
        $orderItems = OrderItem::with(['order', 'product'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.order_items.index', compact('orderItems'));
    }

    public function show(OrderItem $orderItem)
    {
        $orderItem->load('order', 'product');
        return view('admin.order_items.show', compact('orderItem'));
    }

    public function edit(OrderItem $orderItem)
    {
        return view('admin.order_items.edit', compact('orderItem'));
    }

    public function update(Request $request, OrderItem $orderItem)
    {
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
        ]);

        $orderItem->update($validated);

        return redirect()->route('admin.order_items.index')->with('success', 'Item do pedido atualizado!');
    }

    public function destroy(OrderItem $orderItem)
    {
        $orderItem->delete();
        return redirect()->route('admin.order_items.index')->with('success', 'Item removido!');
    }
}
