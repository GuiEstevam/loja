<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // Exibe o carrinho
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('shop.cart.index', compact('cart'));
    }

    // Adiciona produto ao carrinho
    public function add(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity']++;
        } else {
            $cart[$product->id] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => 1,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Produto adicionado ao carrinho!');
    }

    // Remove produto do carrinho
    public function remove(Product $product)
    {
        $cart = session()->get('cart', []);
        unset($cart[$product->id]);
        session()->put('cart', $cart);

        return redirect()->route('shop.cart.index')->with('success', 'Produto removido do carrinho.');
    }

    public function buy(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $cart[$product->id] = [
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => 1,
            'image' => $product->image,
        ];
        session()->put('cart', $cart);
        return redirect()->route('shop.checkout');
    }

    // Atualiza quantidade (opcional)
    public function update(Request $request, Product $product)
    {
        $cart = session()->get('cart', []);
        $quantity = max(1, (int)($request->input('quantity') ?? $request->quantity));
        if (isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] = $quantity;
            session()->put('cart', $cart);
        }
        // Retorne JSON para AJAX
        return response()->json(['success' => true]);
    }
}
