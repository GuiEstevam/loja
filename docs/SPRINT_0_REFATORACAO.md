# 🔧 Plano de Refatoração - Antes da Integração de Pagamento

## 🎯 Análise do Estado Atual

Após análise detalhada do código, identifiquei várias questões críticas que precisam ser refatoradas **ANTES** de implementar pagamento real:

### ❌ **Problemas Identificados**

#### **1. Sistema de Pagamento Incompleto**
- **Problema**: Modelo `Payment` existe mas não é usado no checkout
- **Impacto**: Pagamentos não são registrados no banco
- **Status**: Pedidos são criados sem registro de pagamento

#### **2. Checkout Process Inconsistente**
- **Problema**: `OrderController@process` não cria registro de `Payment`
- **Impacto**: Sem rastreamento de pagamentos
- **Status**: Apenas salva `payment_method` no Order

#### **3. Validação de Estoque Inadequada**
- **Problema**: Não há validação de estoque no checkout
- **Impacto**: Risco de overselling
- **Status**: Produtos podem ser vendidos sem estoque

#### **4. Sistema de Status de Pedido Limitado**
- **Problema**: Status não reflete estado real do pagamento
- **Impacto**: Confusão sobre status do pedido
- **Status**: Apenas 'pending' é usado

#### **5. Falta de Transações de Banco**
- **Problema**: Operações não são transacionais
- **Impacto**: Risco de inconsistência de dados
- **Status**: Sem rollback em caso de erro

---

## 📋 Sprint 0: Refatoração Crítica (1 semana)

### **Objetivo**: Preparar sistema para integração de pagamento real

---

## 🛠️ Tarefas de Refatoração

### **Fase 1: Correção do Sistema de Pagamento (Dias 1-2)**

#### **T001: Refatorar Modelo Payment**
```php
// Atualizar modelo Payment
class Payment extends Model
{
    protected $fillable = [
        'order_id',
        'method',           // credit_card, pix, boleto, etc.
        'status',           // pending, approved, declined, refunded
        'transaction_id',   // ID da transação no gateway
        'amount',           // Valor do pagamento
        'gateway_response',  // Resposta completa do gateway
        'processed_at',     // Data do processamento
        'failed_at',        // Data da falha (se houver)
        'failure_reason',   // Motivo da falha
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'processed_at' => 'datetime',
        'failed_at' => 'datetime',
    ];
}
```

#### **T002: Criar Migration de Atualização**
```sql
ALTER TABLE payments ADD COLUMN amount DECIMAL(10,2) NOT NULL;
ALTER TABLE payments ADD COLUMN gateway_response JSON NULL;
ALTER TABLE payments ADD COLUMN processed_at TIMESTAMP NULL;
ALTER TABLE payments ADD COLUMN failed_at TIMESTAMP NULL;
ALTER TABLE payments ADD COLUMN failure_reason TEXT NULL;
```

#### **T003: Refatorar OrderController@process**
```php
public function process(Request $request)
{
    DB::transaction(function () use ($request) {
        // 1. Validar carrinho e estoque
        $this->validateCartAndStock($cart);
        
        // 2. Criar pedido
        $order = $this->createOrder($data);
        
        // 3. Criar registro de pagamento
        $payment = $this->createPayment($order, $data);
        
        // 4. Processar pagamento (simulado por enquanto)
        $this->processPayment($payment);
        
        // 5. Atualizar estoque
        $this->updateStock($order);
        
        // 6. Limpar carrinho
        $this->clearCart();
        
        return $order;
    });
}
```

### **Fase 2: Sistema de Validação de Estoque (Dias 3-4)**

#### **T004: Implementar Validação de Estoque**
```php
private function validateCartAndStock($cart)
{
    foreach ($cart as $item) {
        $product = Product::find($item['id']);
        
        if (!$product || !$product->active) {
            throw new \Exception("Produto {$item['name']} não está disponível");
        }
        
        if ($product->stock < $item['quantity']) {
            throw new \Exception("Estoque insuficiente para {$item['name']}");
        }
    }
}
```

#### **T005: Implementar Atualização de Estoque**
```php
private function updateStock(Order $order)
{
    foreach ($order->items as $item) {
        $product = $item->product;
        $product->decrement('stock', $item->quantity);
        
        // Log de movimentação de estoque
        $this->logStockMovement($product, $item->quantity, 'sale');
    }
}
```

### **Fase 3: Sistema de Status Melhorado (Dias 5-6)**

#### **T006: Refatorar Status de Pedidos**
```php
// Adicionar novos status
const STATUS_PENDING = 'pending';           // Aguardando pagamento
const STATUS_PAID = 'paid';                 // Pago
const STATUS_PROCESSING = 'processing';     // Processando
const STATUS_SHIPPED = 'shipped';           // Enviado
const STATUS_DELIVERED = 'delivered';       // Entregue
const STATUS_CANCELLED = 'cancelled';       // Cancelado
const STATUS_REFUNDED = 'refunded';         // Reembolsado
```

#### **T007: Implementar Transições de Status**
```php
class Order extends Model
{
    public function canTransitionTo($newStatus)
    {
        $allowedTransitions = [
            'pending' => ['paid', 'cancelled'],
            'paid' => ['processing', 'cancelled'],
            'processing' => ['shipped', 'cancelled'],
            'shipped' => ['delivered'],
            'delivered' => ['refunded'],
            'cancelled' => [],
            'refunded' => [],
        ];
        
        return in_array($newStatus, $allowedTransitions[$this->status] ?? []);
    }
    
    public function updateStatus($newStatus, $reason = null)
    {
        if (!$this->canTransitionTo($newStatus)) {
            throw new \Exception("Transição de status inválida");
        }
        
        $this->update(['status' => $newStatus]);
        
        // Log da mudança de status
        $this->logStatusChange($newStatus, $reason);
    }
}
```

### **Fase 4: Sistema de Transações (Dias 7)**

#### **T008: Implementar Transações de Banco**
```php
public function process(Request $request)
{
    return DB::transaction(function () use ($request) {
        try {
            // Todas as operações dentro da transação
            $order = $this->createOrder($data);
            $payment = $this->createPayment($order, $data);
            $this->updateStock($order);
            
            return $order;
            
        } catch (\Exception $e) {
            // Rollback automático
            Log::error('Erro no checkout: ' . $e->getMessage());
            throw $e;
        }
    });
}
```

---

## 📊 Checklist de Refatoração

### **Preparação**
- [ ] Backup do banco de dados atual
- [ ] Ambiente de teste configurado
- [ ] Plano de rollback definido
- [ ] Testes de regressão preparados

### **Desenvolvimento**
- [ ] Modelo Payment atualizado
- [ ] Migration executada
- [ ] OrderController refatorado
- [ ] Validação de estoque implementada
- [ ] Sistema de status melhorado
- [ ] Transações implementadas

### **Testes**
- [ ] Testes unitários passando
- [ ] Testes de integração passando
- [ ] Testes de regressão passando
- [ ] Testes de transação funcionando
- [ ] Validação de estoque testada

### **Deploy**
- [ ] Código versionado
- [ ] Deploy em ambiente de teste
- [ ] Testes de aceitação realizados
- [ ] Deploy em produção
- [ ] Monitoramento ativo

---

## 🚨 Riscos da Refatoração

### **Riscos Identificados**
1. **Quebra de Funcionalidade Existente**
   - **Probabilidade**: Média
   - **Impacto**: Alto
   - **Mitigação**: Testes extensivos e deploy gradual

2. **Perda de Dados**
   - **Probabilidade**: Baixa
   - **Impacto**: Alto
   - **Mitigação**: Backup completo e transações

3. **Inconsistência de Dados**
   - **Probabilidade**: Média
   - **Impacto**: Médio
   - **Mitigação**: Scripts de migração e validação

### **Plano de Contingência**
- **Rollback**: Plano de reversão completo
- **Backup**: Backup antes de cada mudança
- **Testes**: Ambiente de teste idêntico à produção
- **Monitoramento**: Acompanhamento em tempo real

---

## 📈 Benefícios da Refatoração

### **Benefícios Técnicos**
- ✅ **Consistência**: Dados sempre consistentes
- ✅ **Confiabilidade**: Transações garantem integridade
- ✅ **Rastreabilidade**: Pagamentos totalmente rastreados
- ✅ **Escalabilidade**: Base sólida para crescimento

### **Benefícios de Negócio**
- ✅ **Confiabilidade**: Sistema mais confiável
- ✅ **Transparência**: Status claros para clientes
- ✅ **Controle**: Melhor controle de estoque
- ✅ **Preparação**: Base para pagamento real

---

## 🎯 Cronograma de Refatoração

### **Semana 1: Refatoração Crítica**
- **Dia 1-2**: Sistema de pagamento
- **Dia 3-4**: Validação de estoque
- **Dia 5-6**: Sistema de status
- **Dia 7**: Transações e testes

### **Semana 2: Testes e Deploy**
- **Dia 1-2**: Testes extensivos
- **Dia 3-4**: Deploy em ambiente de teste
- **Dia 5**: Deploy em produção
- **Dia 6-7**: Monitoramento e ajustes

---

## 📋 Conclusão

**Você está correto!** Não devemos partir para integração de pagamento real sem antes refatorar o sistema atual. Os problemas identificados são críticos e podem causar:

- ❌ **Perda de dados de pagamento**
- ❌ **Inconsistência de estoque**
- ❌ **Problemas de transação**
- ❌ **Status incorretos de pedidos**

### **Recomendação**
1. **Executar Sprint 0**: Refatoração crítica (1 semana)
2. **Testes Extensivos**: Validar todas as funcionalidades
3. **Deploy Gradual**: Ambiente de teste → produção
4. **Depois**: Partir para integração de pagamento real

Esta refatoração é **essencial** para garantir que o sistema esteja robusto e confiável antes de integrar pagamentos reais.

---

**Última atualização**: {{ date('d/m/Y H:i') }}
**Versão**: 1.0
**Sprint**: 0 - Refatoração Crítica
