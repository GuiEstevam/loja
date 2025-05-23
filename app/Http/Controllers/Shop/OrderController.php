<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // Lista os pedidos do usuário autenticado
    public function index()
    {
        $user = Auth::user();

        $orders = $user->orders()->with('items.product')->orderBy('created_at', 'desc')->paginate(10);

        return view('shop.orders.index', compact('orders'));
    }

    // Mostra detalhes de um pedido do usuário
    public function show(Order $order)
    {
        // Garante que o pedido pertence ao usuário autenticado
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('shop.orders.show', compact('order'));
    }

    public function process(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->route('shop.cart.index')->with('error', 'Seu carrinho está vazio.');
        }

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'address' => 'required|string|max:255',
            'payment' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Crie o pedido (Order)
        $order = \App\Models\Order::create([
            'user_id' => auth()->id(),
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'],
            'payment_method' => $data['payment'],
            'notes' => $data['notes'] ?? null,
            'total' => $total,
            'subtotal' => $total, // ou calcule diferente se tiver descontos
            'status' => 'pending',
        ]);

        // Crie os itens do pedido
        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        // Limpa o carrinho
        session()->forget('cart');

        return redirect()->route('shop.orders.index')->with('success', 'Pedido realizado com sucesso!');
    }
}
