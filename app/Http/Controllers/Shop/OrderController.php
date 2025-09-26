<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // Lista os pedidos do usuário autenticado
    public function index()
    {
        $user = Auth::user();

        $orders = $user->orders()->with(['items.product', 'payment'])->orderBy('created_at', 'desc')->paginate(10);

        // Preparar dados formatados para JavaScript
        $ordersData = $orders->map(function($order) {
            return [
                'id' => $order->id,
                'status' => $order->status,
                'created_at' => $order->created_at->format('d/m/Y H:i'),
                'items_count' => $order->items->count(),
                'total' => $order->total, // Número sem formatação para cálculos no JS
                'total_formatted' => number_format($order->total, 2, ',', '.'), // Formatação para exibição
                'show_url' => route('shop.orders.show', $order)
            ];
        })->values();

        return view('shop.orders.index', compact('orders', 'ordersData'));
    }

    // Mostra detalhes de um pedido do usuário
    public function show(Order $order)
    {
        // Garante que o pedido pertence ao usuário autenticado
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load(['items.product', 'payment']);

        return view('shop.orders.show', compact('order'));
    }

    public function checkout()
    {
        // Verificar se o usuário está logado
        if (!Auth::check()) {
            // Salvar URL de destino para redirecionar após login
            session(['intended_url' => route('shop.checkout')]);
            
            return redirect()->route('login')->with('message', 'Você precisa estar logado para finalizar a compra.');
        }

        // Verificar se há dados do carrinho na sessão (fallback)
        $cart = session()->get('cart', []);

        // Se não há dados na sessão, retornar view vazia
        // Os dados serão carregados via JavaScript do localStorage
        $user = Auth::user();
        
        // Preparar endereço padrão para JavaScript
        $defaultAddress = $user->addresses->where('is_default', true)->first();
        
        return view('shop.checkout', compact('cart', 'user', 'defaultAddress'));
    }

    public function process(Request $request)
    {
        return DB::transaction(function () use ($request) {
            try {
                // 1. Validar e obter dados do carrinho
                $cart = $this->validateAndGetCart($request);
                
                // 2. Validar dados do formulário
                $data = $this->validateCheckoutData($request);
                
                // 3. Validar estoque de todos os produtos
                $this->validateStock($cart);
                
                // 4. Criar pedido
                $order = $this->createOrder($data, $cart);
                
                // 5. Criar registro de pagamento
                $payment = $this->createPayment($order, $data);
                
                // 6. Processar pagamento (simulado por enquanto)
                $this->processPayment($payment);
                
                // 7. Atualizar estoque
                $this->updateStock($order);
                
                // 8. Limpar carrinho
                $this->clearCart();
                
                return redirect()->route('shop.confirmation', $order)->with('success', 'Pedido realizado com sucesso!');
                
            } catch (\Exception $e) {
                Log::error('Erro no checkout: ' . $e->getMessage(), [
                    'user_id' => Auth::id(),
                    'cart' => $cart ?? null,
                    'request_data' => $request->all()
                ]);
                
                return redirect()->back()->with('error', 'Erro ao processar pedido: ' . $e->getMessage());
            }
        });
    }

    /**
     * Valida e obtém dados do carrinho
     */
    private function validateAndGetCart(Request $request): array
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
            throw new \Exception('Seu carrinho está vazio.');
        }

        return $cart;
    }

    /**
     * Valida dados do formulário de checkout
     */
    private function validateCheckoutData(Request $request): array
    {
        return $request->validate([
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
    }

    /**
     * Valida estoque de todos os produtos no carrinho
     */
    private function validateStock(array $cart): void
    {
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            
            if (!$product) {
                throw new \Exception("Produto '{$item['name']}' não encontrado.");
            }
            
            if (!$product->active) {
                throw new \Exception("Produto '{$item['name']}' não está disponível.");
            }
            
            if ($product->stock < $item['quantity']) {
                throw new \Exception("Estoque insuficiente para '{$item['name']}'. Disponível: {$product->stock}, Solicitado: {$item['quantity']}");
            }
        }
    }

    /**
     * Cria o pedido
     */
    private function createOrder(array $data, array $cart): Order
    {
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
        $order = Order::create([
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

        return $order;
    }

    /**
     * Cria registro de pagamento
     */
    private function createPayment(Order $order, array $data): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'method' => $data['payment_method'],
            'status' => Payment::STATUS_PENDING,
            'amount' => $order->total,
            'transaction_id' => null,
            'gateway_response' => null,
            'processed_at' => null,
            'failed_at' => null,
            'failure_reason' => null,
        ]);
    }

    /**
     * Processa pagamento (simulado por enquanto)
     */
    private function processPayment(Payment $payment): void
    {
        // Por enquanto, simular aprovação do pagamento
        // Em produção, aqui seria feita a integração com gateway de pagamento
        
        $payment->markAsApproved(
            transactionId: 'TXN_' . time() . '_' . $payment->id,
            gatewayResponse: [
                'status' => 'approved',
                'transaction_id' => 'TXN_' . time() . '_' . $payment->id,
                'processed_at' => now()->toISOString(),
                'gateway' => 'simulated'
            ]
        );

        // Atualizar status do pedido para 'paid'
        $payment->order->update(['status' => 'paid']);
    }

    /**
     * Atualiza estoque após pedido aprovado
     */
    private function updateStock(Order $order): void
    {
        foreach ($order->items as $item) {
            $product = $item->product;
            $product->decrement('stock', $item->quantity);
            
            // Log de movimentação de estoque
            Log::info('Estoque atualizado', [
                'product_id' => $product->id,
                'product_name' => $product->name,
                'quantity_sold' => $item->quantity,
                'remaining_stock' => $product->fresh()->stock,
                'order_id' => $order->id
            ]);
        }
    }

    /**
     * Limpa carrinho após pedido
     */
    private function clearCart(): void
    {
        session()->forget('cart');
    }

    public function confirmation(Order $order)
    {
        // Garante que o pedido pertence ao usuário autenticado
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        $order->load('items.product', 'payment');

        return view('shop.confirmation', compact('order'));
    }
}
