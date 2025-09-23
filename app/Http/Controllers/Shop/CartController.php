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

        // Pegue as escolhas do usuário (se houver)
        $selectedColor = $request->input('color');
        $selectedSize = $request->input('size');

        // Crie uma chave única para variações (produto+cor+tamanho)
        $cartKey = $product->id;
        if ($selectedColor) $cartKey .= "-c{$selectedColor}";
        if ($selectedSize) $cartKey .= "-s{$selectedSize}";

        if (isset($cart[$cartKey])) {
            $cart[$cartKey]['quantity']++;
        } else {
            $cart[$cartKey] = [
                'id' => $product->id,
                'name' => $product->name,
                'sku' => $product->sku,
                'price' => $product->price,
                'image' => $product->image,
                'brand' => $product->brand ? $product->brand->name : null,
                'categories' => $product->categories ? $product->categories->pluck('name')->toArray() : [],
                'color' => $selectedColor ? optional($product->colors->where('id', $selectedColor)->first())->name : null,
                'color_hex' => $selectedColor ? optional($product->colors->where('id', $selectedColor)->first())->hex_code : null,
                'size' => $selectedSize ? optional($product->sizes->where('id', $selectedSize)->first())->name : null,
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
        $selectedColor = $request->input('color');
        $selectedSize = $request->input('size');
        $cartKey = $product->id;
        if ($selectedColor) $cartKey .= "-c{$selectedColor}";
        if ($selectedSize) $cartKey .= "-s{$selectedSize}";

        $cart[$cartKey] = [
            'id' => $product->id,
            'name' => $product->name,
            'sku' => $product->sku,
            'price' => $product->price,
            'image' => $product->image,
            'brand' => $product->brand ? $product->brand->name : null,
            'categories' => $product->categories ? $product->categories->pluck('name')->toArray() : [],
            'color' => $selectedColor ? optional($product->colors->where('id', $selectedColor)->first())->name : null,
            'color_hex' => $selectedColor ? optional($product->colors->where('id', $selectedColor)->first())->hex_code : null,
            'size' => $selectedSize ? optional($product->sizes->where('id', $selectedSize)->first())->name : null,
            'quantity' => 1,
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
