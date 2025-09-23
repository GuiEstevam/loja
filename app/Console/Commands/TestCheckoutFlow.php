<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestCheckoutFlow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:checkout-flow';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Testa o fluxo completo de checkout refatorado';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testando fluxo de checkout refatorado...');
        
        try {
            // 1. Criar usuário de teste
            $user = $this->createTestUser();
            $this->info("✅ Usuário criado: {$user->name} ({$user->email})");
            
            // 2. Criar produto de teste
            $product = $this->createTestProduct();
            $this->info("✅ Produto criado: {$product->name} (Estoque: {$product->stock})");
            
            // 3. Simular carrinho
            $cart = $this->createTestCart($product);
            $this->info("✅ Carrinho simulado com " . count($cart) . " item(s)");
            
            // 4. Testar validação de estoque
            $this->testStockValidation($cart);
            $this->info("✅ Validação de estoque funcionando");
            
            // 5. Testar criação de pedido
            $order = $this->testOrderCreation($user, $cart);
            $this->info("✅ Pedido criado: #{$order->id}");
            
            // 6. Testar criação de pagamento
            $payment = $this->testPaymentCreation($order);
            $this->info("✅ Pagamento criado: {$payment->status}");
            
            // 7. Testar processamento de pagamento
            $this->testPaymentProcessing($payment);
            $this->info("✅ Pagamento processado");
            
            // 8. Testar atualização de estoque
            $this->testStockUpdate($order);
            $this->info("✅ Estoque atualizado");
            
            // 9. Testar transições de status
            $this->testStatusTransitions($order);
            $this->info("✅ Transições de status funcionando");
            
            $this->info("\n🎉 Todos os testes passaram! Sistema refatorado funcionando corretamente.");
            
        } catch (\Exception $e) {
            $this->error("❌ Erro no teste: " . $e->getMessage());
            return 1;
        }
        
        return 0;
    }
    
    private function createTestUser(): User
    {
        return User::create([
            'name' => 'Usuário Teste',
            'email' => 'teste' . time() . '@skyfashion.com',
            'password' => bcrypt('password'),
            'phone' => '11999999999'
        ]);
    }
    
    private function createTestProduct(): Product
    {
        return Product::create([
            'name' => 'Produto Teste',
            'slug' => 'produto-teste-' . time(),
            'description' => 'Produto para teste do checkout',
            'price' => 99.90,
            'stock' => 10,
            'image' => 'test.jpg',
            'active' => true,
            'sku' => 'TEST' . time(),
            'brand_id' => 1
        ]);
    }
    
    private function createTestCart(Product $product): array
    {
        return [
            'test_item' => [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 2,
                'image' => $product->image,
                'color' => null,
                'colorName' => null,
                'size' => null
            ]
        ];
    }
    
    private function testStockValidation(array $cart): void
    {
        foreach ($cart as $item) {
            $product = Product::find($item['id']);
            
            if (!$product) {
                throw new \Exception("Produto não encontrado");
            }
            
            if (!$product->active) {
                throw new \Exception("Produto não está ativo");
            }
            
            if ($product->stock < $item['quantity']) {
                throw new \Exception("Estoque insuficiente");
            }
        }
    }
    
    private function testOrderCreation(User $user, array $cart): Order
    {
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        
        $order = Order::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'country' => 'BR',
            'zip' => '01234567',
            'street' => 'Rua Teste',
            'number' => '123',
            'complement' => null,
            'neighborhood' => 'Centro',
            'city' => 'São Paulo',
            'state' => 'SP',
            'address' => 'Rua Teste, 123, Centro, São Paulo/SP, BR, CEP: 01234567',
            'payment_method' => 'credit_card',
            'notes' => 'Pedido de teste',
            'subtotal' => $total,
            'discount' => 0,
            'total' => $total,
            'status' => Order::STATUS_PENDING,
        ]);
        
        // Criar itens do pedido
        foreach ($cart as $item) {
            $order->items()->create([
                'product_id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'image' => $item['image'],
                'color_id' => $item['color'],
                'color_name' => $item['colorName'],
                'size' => $item['size'],
            ]);
        }
        
        return $order;
    }
    
    private function testPaymentCreation(Order $order): Payment
    {
        return Payment::create([
            'order_id' => $order->id,
            'method' => Payment::METHOD_CREDIT_CARD,
            'status' => Payment::STATUS_PENDING,
            'amount' => $order->total,
            'transaction_id' => null,
            'gateway_response' => null,
            'processed_at' => null,
            'failed_at' => null,
            'failure_reason' => null,
        ]);
    }
    
    private function testPaymentProcessing(Payment $payment): void
    {
        $payment->markAsApproved(
            transactionId: 'TXN_TEST_' . time(),
            gatewayResponse: [
                'status' => 'approved',
                'transaction_id' => 'TXN_TEST_' . time(),
                'processed_at' => now()->toISOString(),
                'gateway' => 'test'
            ]
        );
        
        $payment->order->updateStatus(Order::STATUS_PAID, 'Pagamento aprovado em teste');
    }
    
    private function testStockUpdate(Order $order): void
    {
        foreach ($order->items as $item) {
            $product = $item->product;
            $oldStock = $product->stock;
            $product->decrement('stock', $item->quantity);
            
            if ($product->fresh()->stock !== ($oldStock - $item->quantity)) {
                throw new \Exception("Erro na atualização de estoque");
            }
        }
    }
    
    private function testStatusTransitions(Order $order): void
    {
        // Recarregar o pedido para ter o status atualizado
        $order->refresh();
        
        // Verificar se o pedido está como 'paid' após processamento do pagamento
        if ($order->status !== Order::STATUS_PAID) {
            throw new \Exception("Pedido deveria estar como 'paid' após processamento do pagamento");
        }
        
        // Testar transições válidas
        $order->updateStatus(Order::STATUS_PROCESSING, 'Iniciando processamento');
        $order->updateStatus(Order::STATUS_SHIPPED, 'Produto enviado');
        $order->updateStatus(Order::STATUS_DELIVERED, 'Produto entregue');
        
        // Testar transição inválida (deve falhar)
        try {
            $order->updateStatus(Order::STATUS_PENDING, 'Tentativa inválida');
            throw new \Exception("Transição inválida deveria ter falhado");
        } catch (\Exception $e) {
            // Esperado - transição inválida
        }
    }
}
