<?php

use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\OrderItemController as AdminOrderItemController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\DiscountController as AdminDiscountController;
use App\Http\Controllers\Admin\LoyaltyPointController as AdminLoyaltyPointController;

use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\OrderController as ShopOrderController;
use App\Http\Controllers\Shop\OrderItemController as ShopOrderItemController;
use App\Http\Controllers\Shop\PaymentController as ShopPaymentController;
use App\Http\Controllers\Shop\DiscountController as ShopDiscountController;
use App\Http\Controllers\Shop\LoyaltyPointController as ShopLoyaltyPointController;
use App\Http\Controllers\Shop\AddressController as ShopAddressController;
use App\Http\Controllers\Shop\CartController as ShopCartController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
// Página inicial
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Listagem de produtos (público)
Route::get('/produtos', [ShopProductController::class, 'index'])->name('shop.products.index');

// Detalhe do produto (público)
Route::get('/produtos/{product}', [ShopProductController::class, 'show'])->name('shop.products.show');

// -------------------
// Rotas Privadas (usuário autenticado)
// -------------------
Route::middleware('auth')->group(function () {

    Route::get('/minha-conta', function () {
        return view('shop.dashboard');
    })->name('shop.dashboard');
    // Pedidos do usuário
    Route::get('/meus-pedidos', [ShopOrderController::class, 'index'])->name('shop.orders.index');
    Route::get('/meus-pedidos/{order}', [ShopOrderController::class, 'show'])->name('shop.orders.show');

    // Itens do pedido (opcional)
    Route::get('/meus-itens', [ShopOrderItemController::class, 'index'])->name('shop.order_items.index');
    Route::get('/meus-itens/{orderItem}', [ShopOrderItemController::class, 'show'])->name('shop.order_items.show');

    // Pagamentos
    Route::get('/meus-pagamentos', [ShopPaymentController::class, 'index'])->name('shop.payments.index');
    Route::get('/meus-pagamentos/{payment}', [ShopPaymentController::class, 'show'])->name('shop.payments.show');

    // Cupons e descontos
    Route::get('/meus-cupons', [ShopDiscountController::class, 'index'])->name('shop.discounts.index');
    Route::get('/meus-cupons/{discount}', [ShopDiscountController::class, 'show'])->name('shop.discounts.show');

    // Pontos de fidelidade
    Route::get('/meus-pontos', [ShopLoyaltyPointController::class, 'index'])->name('shop.loyalty_points.index');

    // Endereços
    Route::resource('enderecos', ShopAddressController::class);

    // Exibir carrinho
    Route::get('/carrinho', [ShopCartController::class, 'index'])->name('shop.cart.index');

    // Adicionar produto ao carrinho
    Route::post('/carrinho/adicionar/{product}', [ShopCartController::class, 'add'])->name('shop.cart.add');

    // Remover produto do carrinho
    Route::post('/carrinho/remover/{product}', [ShopCartController::class, 'remove'])->name('shop.cart.remove');

    // Atualizar quantidade (opcional)
    Route::post('/carrinho/atualizar/{product}', [ShopCartController::class, 'update'])->name('shop.cart.update');

    // Checkout (exibe o formulário para finalizar compra)
    Route::get('/checkout', function () {
        $cart = session()->get('cart', []);
        return view('shop.checkout', compact('cart'));
    })->name('shop.checkout');

    // Processa o pedido (checkout)
    Route::post('/checkout', [ShopOrderController::class, 'process'])->name('shop.checkout.process');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');
    Route::resource('products', AdminProductController::class);
    Route::resource('orders', AdminOrderController::class);
    Route::resource('order-items', AdminOrderItemController::class);
    Route::resource('payments', AdminPaymentController::class);
    Route::resource('discounts', AdminDiscountController::class);
    Route::resource('loyalty-points', AdminLoyaltyPointController::class);
    // AddressController admin só se/quando necessário
});
