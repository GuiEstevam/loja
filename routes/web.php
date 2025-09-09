<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Admin Controllers
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\BrandController as AdminBrandController;
use App\Http\Controllers\Admin\ColorController as AdminColorController;
use App\Http\Controllers\Admin\SizeController as AdminSizeController;
use App\Http\Controllers\Admin\ReviewController as AdminReviewController;

// Shop Controllers
use App\Http\Controllers\Shop\ProductController as ShopProductController;
use App\Http\Controllers\Shop\OrderController as ShopOrderController;
use App\Http\Controllers\Shop\OrderItemController as ShopOrderItemController;
use App\Http\Controllers\Shop\PaymentController as ShopPaymentController;
use App\Http\Controllers\Shop\DiscountController as ShopDiscountController;
use App\Http\Controllers\Shop\LoyaltyPointController as ShopLoyaltyPointController;
use App\Http\Controllers\Shop\AddressController as ShopAddressController;
use App\Http\Controllers\Shop\CartController as ShopCartController;
use App\Http\Controllers\Shop\FavoritesController as ShopFavoritesController;
use App\Http\Controllers\Shop\CategoryController as ShopCategoryController;
use App\Http\Controllers\Shop\BrandController as ShopBrandController;
use App\Http\Controllers\Shop\ReviewController as ShopReviewController;


// Página inicial
Route::get('/', [HomeController::class, 'welcome'])->name('home');

// -------------------
// Rotas Públicas
// -------------------


Route::get('/checkout', [ShopOrderController::class, 'checkout'])->name('shop.checkout');

// Listagem de categorias
Route::get('/categorias/{category:slug}', [ShopCategoryController::class, 'show'])
    ->name('shop.categories.show');

//Listagem de marcas

Route::get('/marcas', [ShopBrandController::class, 'index'])->name('shop.brands.index');
Route::get('/marcas/{brand:slug}', [ShopBrandController::class, 'show'])->name('shop.brands.show');

// Rota de fallback para /brands (redireciona para /marcas)
Route::get('/brands', function () {
    return redirect()->route('shop.brands.index');
});
Route::get('/brands/{brand:slug}', function ($brand) {
    return redirect()->route('shop.brands.show', $brand);
});

// Favoritos
Route::get('/favoritos', [ShopFavoritesController::class, 'index'])->name('shop.favorites.index');

// Listagem de produtos
Route::get('/produtos', [ShopProductController::class, 'index'])->name('shop.products.index');

// Busca AJAX de produtos
Route::post('/produtos/buscar', [ShopProductController::class, 'search'])->name('shop.products.search');

// Detalhe do produto
Route::get('/produtos/{product}', [ShopProductController::class, 'show'])->name('shop.products.show');

// Carrinho - ACESSO PÚBLICO
Route::get('/carrinho', [ShopCartController::class, 'index'])->name('shop.cart.index');
Route::post('/carrinho/adicionar/{product}', [ShopCartController::class, 'add'])->name('shop.cart.add');
Route::post('/carrinho/remover/{product}', [ShopCartController::class, 'remove'])->name('shop.cart.remove');
Route::post('/carrinho/atualizar/{product}', [ShopCartController::class, 'update'])->name('shop.cart.update');
Route::post('/comprar/{product}', [ShopCartController::class, 'buy'])->name('shop.cart.buy');

// Sincronização de dados (acesso público para usuários não logados)
Route::post('/api/sync/cart', [App\Http\Controllers\Api\SyncController::class, 'syncCart'])->name('api.sync.cart');
Route::post('/api/sync/favorites', [App\Http\Controllers\Api\SyncController::class, 'syncFavorites'])->name('api.sync.favorites');
Route::get('/api/sync/load', [App\Http\Controllers\Api\SyncController::class, 'loadData'])->name('api.sync.load');

// -------------------
// Rotas Privadas (usuário autenticado)
// -------------------
Route::middleware('auth')->group(function () {

    // Dashboard do usuário
    Route::get('/minha-conta', [App\Http\Controllers\Shop\DashboardController::class, 'index'])->name('shop.dashboard');

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

    // Reviews e Avaliações
    Route::get('/produtos/{product}/reviews', [ShopReviewController::class, 'index'])->name('shop.reviews.index');
    Route::post('/reviews', [ShopReviewController::class, 'store'])->name('shop.reviews.store');
    Route::put('/reviews/{review}', [ShopReviewController::class, 'update'])->name('shop.reviews.update');
    Route::delete('/reviews/{review}', [ShopReviewController::class, 'destroy'])->name('shop.reviews.destroy');
    Route::post('/reviews/{review}/helpful', [ShopReviewController::class, 'markHelpful'])->name('shop.reviews.helpful');
    Route::get('/produtos/{product}/can-review', [ShopReviewController::class, 'canReview'])->name('shop.reviews.can-review');

    // Endereços do usuário (CRUD)
    Route::resource('enderecos', ShopAddressController::class);

    // Checkout (exibe o formulário para finalizar compra)

    // Processa o pedido (checkout)
    Route::post('/checkout', [ShopOrderController::class, 'process'])->name('shop.checkout.process');

    // Confirmação do pedido
    Route::get('/confirmacao/{order}', [ShopOrderController::class, 'confirmation'])->name('shop.confirmation');
});

// -------------------
// Rotas de Admin (painel administrativo)
// -------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Produtos
    Route::resource('products', AdminProductController::class);
    Route::post('products/search', [AdminProductController::class, 'search'])->name('products.search');

    // Pedidos: apenas index, show e update (troca de status)
    Route::resource('orders', AdminOrderController::class)->only(['index', 'show', 'update']);

    // Categorias, marcas, cores, tamanhos
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('brands', AdminBrandController::class);
    Route::resource('colors', AdminColorController::class);
    Route::resource('sizes', AdminSizeController::class);

    // Reviews e Moderação
    Route::resource('reviews', AdminReviewController::class)->only(['index', 'show', 'destroy']);
    Route::post('reviews/{review}/approve', [AdminReviewController::class, 'approve'])->name('reviews.approve');
    Route::post('reviews/{review}/reject', [AdminReviewController::class, 'reject'])->name('reviews.reject');
    Route::post('reviews/approve-multiple', [AdminReviewController::class, 'approveMultiple'])->name('reviews.approve-multiple');
    Route::post('reviews/reject-multiple', [AdminReviewController::class, 'rejectMultiple'])->name('reviews.reject-multiple');
    Route::get('reviews-stats', [AdminReviewController::class, 'stats'])->name('reviews.stats');

    // Outras resources do admin (descomente conforme necessidade do MVP)
    // Route::resource('order-items', AdminOrderItemController::class);
    // Route::resource('payments', AdminPaymentController::class);
    // Route::resource('discounts', AdminDiscountController::class);
    // Route::resource('loyalty-points', AdminLoyaltyPointController::class);
});
