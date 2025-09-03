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
        // Verificar se há dados do carrinho na sessão (fallback)
        $cart = session()->get('cart', []);

        // Se não há dados na sessão, retornar view vazia
        // Os dados serão carregados via JavaScript do localStorage
        $user = Auth::user();
        return view('shop.checkout', compact('cart', 'user'));
    }

    public function process(Request $request)
    {
        // Verificar se há dados do carrinho na sessão (fallback)
        $cart = session()->get('cart', []);

        // Se não há dados na sessão, verificar se há dados no request
        if (empty($cart)) {
            // Tentar obter dados do localStorage via request
            $cartData = $request->input('cart_data');
            if ($cartData) {
                $cart = json_decode($cartData, true);
            }
        }

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
            'payment_method' => 'required|string',
            'notes' => 'nullable|string',
            'save_address' => 'nullable|boolean',
        ]);

        $user = Auth::user();

        // Salvar dados do usuário para persistência
        $user->update([
            'phone' => $data['phone'] ?? $user->phone,
        ]);

        // Salvar endereço padrão do usuário
        $user->addresses()->updateOrCreate(
            ['is_default' => true],
            [
                'name' => $data['name'],
                'phone' => $data['phone'] ?? null,
                'address_line1' => $data['street'],
                'address_line2' => $data['complement'],
                'city' => $data['city'],
                'state' => $data['country'] === 'BR' ? ($data['state'] ?? '') : ($data['state_other'] ?? ''),
                'country' => $data['country'],
                'zipcode' => $data['zip'],
                'is_default' => true,
            ]
        );

        $country = $data['country'];
        $state = $country === 'BR'
            ? ($data['state'] ?? '')
            : ($data['state_other'] ?? '');

        $city = $data['city'];

        $fullAddress = $data['street'] . ', ' . $data['number'] .
            ($data['complement'] ? ' - ' . $data['complement'] : '') . ', ' .
            $data['neighborhood'] . ', ' . $city . '/' . $state .
            ', ' . $country . ', CEP: ' . $data['zip'];

        // Calcula total do pedido
        $total = 0;
        foreach ($cart as $productId => $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Cria o pedido
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
            'payment_method' => $data['payment_method'],
            'notes' => $data['notes'] ?? null,
            'subtotal' => $total,
            'discount' => 0,
            'total' => $total,
            'status' => 'pending',
        ]);

        // Salva os itens do pedido
        foreach ($cart as $variationKey => $item) {
            // Salvar imagem exatamente como vem do localStorage
            $imagePath = $item['image'] ?? null;

            $order->items()->create([
                'product_id' => $item['id'],
                'name'      => $item['name'],
                'price'     => $item['price'],
                'quantity'  => $item['quantity'],
                'image'     => $imagePath,
                'color_id'  => $item['color'] ?? null,
                'color_name' => $item['colorName'] ?? null,
                'size'      => $item['size'] ?? null,
            ]);
        }

        // Limpa o carrinho (session e localStorage via JavaScript)
        session()->forget('cart');

        return redirect()->route('shop.confirmation', $order)->with('success', 'Pedido realizado com sucesso!');
    }

    public function confirmation(Order $order)
    {
        // Garante que o pedido pertence ao usuário autenticado
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product');

        return view('shop.confirmation', compact('order'));
    }
}
