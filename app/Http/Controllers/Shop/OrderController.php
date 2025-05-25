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
    public function checkout()
    {
        $cart = session()->get('cart', []);
        $user = auth()->user();
        return view('shop.checkout', compact('cart', 'user'));
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
            'country' => 'required|string|max:2',
            'zip' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'neighborhood' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'complement' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'nullable|string|max:255',
            'state_other' => 'nullable|string|max:255',
            'payment' => 'required|string',
            'notes' => 'nullable|string',
            'save_address' => 'nullable|boolean',
        ]);

        $user = auth()->user();

        $country = $data['country'];
        $state = $country === 'BR'
            ? ($data['state'] ?? '')
            : ($data['state_other'] ?? '');

        $city = $data['city'];

        $fullAddress = $data['street'] . ', ' . $data['number'] .
            ($data['complement'] ? ' - ' . $data['complement'] : '') . ', ' .
            $data['neighborhood'] . ', ' . $city . '/' . $state .
            ', ' . $country . ', CEP: ' . $data['zip'];

        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $order = \App\Models\Order::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'country' => $country,
            'zip' => $data['zip'],
            'street' => $data['street'],
            'number' => $data['number'],
            'complement' => $data['complement'],
            'neighborhood' => $data['neighborhood'],
            'city' => $city,
            'state' => $state,
            'address' => $fullAddress,
            'payment_method' => $data['payment'],
            'notes' => $data['notes'] ?? null,
            'subtotal' => $total,
            'discount' => 0,
            'total' => $total,
            'status' => 'pending',
        ]);

        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
            ]);
        }

        if ($request->filled('save_address')) {
            $user->addresses()->create([
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'address_line1' => $data['street'],
                'address_line2' => $data['complement'],
                'city' => $city,
                'state' => $state,
                'country' => $country,
                'zipcode' => $data['zip'],
            ]);
        }

        session()->forget('cart');

        return redirect()->route('shop.orders.index')->with('success', 'Pedido realizado com sucesso!');
    }
}
