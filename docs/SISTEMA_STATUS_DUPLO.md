# 📊 Sistema de Status Duplo - Explicação Detalhada

## 🎯 **Conceito dos Dois Status**

O sistema SkyFashion usa **dois status separados** para gerenciar pedidos e pagamentos de forma independente:

### **1. STATUS DO PEDIDO** (`orders.status`)
**Representa**: O estado do pedido no processo de entrega
**Controla**: Fluxo logístico e operacional

### **2. STATUS DO PAGAMENTO** (`payments.status`)
**Representa**: O estado da transação financeira
**Controla**: Processo de cobrança e confirmação

---

## 🔄 **Fluxo Completo do Sistema**

### **Fase 1: Criação do Pedido**
```
1. Cliente finaliza compra
2. Pedido criado com status: "pending"
3. Pagamento criado com status: "pending"
4. Sistema aguarda confirmação do pagamento
```

### **Fase 2: Processamento do Pagamento**
```
1. Gateway processa pagamento
2. Pagamento atualizado para: "approved" ou "failed"
3. Se aprovado → Pedido atualizado para: "paid"
4. Se falhou → Pedido permanece: "pending"
```

### **Fase 3: Processamento do Pedido**
```
1. Admin confirma pagamento
2. Pedido atualizado para: "processing"
3. Produtos separados e embalados
4. Pedido atualizado para: "shipped"
5. Produto entregue
6. Pedido atualizado para: "delivered"
```

---

## 📋 **Status do Pedido (Order)**

| Status | Significado | Pode Alterar Para | Admin Pode Alterar |
|--------|-------------|-------------------|-------------------|
| `pending` | Aguardando pagamento | `paid`, `cancelled` | ✅ Sim |
| `paid` | Pago, aguardando processamento | `processing`, `cancelled` | ✅ Sim |
| `processing` | Processando (separando produtos) | `shipped`, `cancelled` | ✅ Sim |
| `shipped` | Enviado (em trânsito) | `delivered` | ✅ Sim |
| `delivered` | Entregue | `refunded` | ✅ Sim |
| `cancelled` | Cancelado | - | ❌ Não |
| `refunded` | Reembolsado | - | ❌ Não |

### **Regras de Transição**
- ✅ **Sequencial**: `pending` → `paid` → `processing` → `shipped` → `delivered`
- ✅ **Cancelamento**: Pode cancelar até `shipped`
- ✅ **Reembolso**: Só após `delivered`

---

## 💳 **Status do Pagamento (Payment)**

| Status | Significado | Gateway Controla | Admin Pode Alterar |
|--------|-------------|------------------|-------------------|
| `pending` | Aguardando processamento | ✅ Sim | ✅ Sim |
| `approved` | Pagamento aprovado | ✅ Sim | ✅ Sim |
| `declined` | Pagamento recusado | ✅ Sim | ✅ Sim |
| `failed` | Falha no processamento | ✅ Sim | ✅ Sim |
| `refunded` | Reembolsado | ✅ Sim | ✅ Sim |

### **Regras de Transição**
- ✅ **Gateway**: Controla `pending` → `approved`/`declined`/`failed`
- ✅ **Admin**: Pode alterar manualmente em casos especiais
- ✅ **Reembolso**: Só após `approved`

---

## 🎯 **Cenários Práticos**

### **Cenário 1: Compra Normal**
```
1. Cliente compra → Pedido: "pending", Pagamento: "pending"
2. PIX aprovado → Pedido: "paid", Pagamento: "approved"
3. Admin processa → Pedido: "processing", Pagamento: "approved"
4. Produto enviado → Pedido: "shipped", Pagamento: "approved"
5. Produto entregue → Pedido: "delivered", Pagamento: "approved"
```

### **Cenário 2: Pagamento Recusado**
```
1. Cliente compra → Pedido: "pending", Pagamento: "pending"
2. Cartão recusado → Pedido: "pending", Pagamento: "declined"
3. Cliente tenta novamente → Pagamento: "pending"
4. Pagamento aprovado → Pedido: "paid", Pagamento: "approved"
```

### **Cenário 3: Cancelamento**
```
1. Pedido: "paid", Pagamento: "approved"
2. Cliente cancela → Pedido: "cancelled", Pagamento: "approved"
3. Reembolso processado → Pagamento: "refunded"
```

### **Cenário 4: Problema na Entrega**
```
1. Pedido: "shipped", Pagamento: "approved"
2. Produto extraviado → Pedido: "cancelled", Pagamento: "approved"
3. Reembolso processado → Pagamento: "refunded"
```

---

## 🔧 **O Que Pode Ser Alterado**

### **Status do Pedido (Admin)**
- ✅ **Manual**: Admin pode alterar status do pedido
- ✅ **Sequencial**: Deve seguir o fluxo correto
- ✅ **Validação**: Sistema impede transições inválidas
- ✅ **Log**: Todas as mudanças são registradas

### **Status do Pagamento**
- ✅ **Gateway**: Atualiza automaticamente via webhook
- ✅ **Manual**: Admin pode alterar em casos especiais
- ✅ **Sincronização**: Deve estar alinhado com o pedido

---

## 🚨 **Problemas Identificados na Sprint 0**

### **1. Inconsistência na Dashboard**
- ❌ **Problema**: Status de pagamento sempre "Pendente"
- ✅ **Corrigido**: Agora usa relacionamento correto

### **2. Falta de Sincronização**
- ❌ **Problema**: Status do pedido e pagamento não sincronizados
- 🔄 **Pendente**: Implementar sincronização automática

### **3. Validação de Transições**
- ✅ **Implementado**: Sistema de validação de transições
- ✅ **Implementado**: Log de mudanças de status

---

## 🔄 **Melhorias Necessárias**

### **Sprint 0 - Pendentes**
1. **Sincronização Automática**: Quando pagamento for aprovado, atualizar pedido para "paid"
2. **Webhooks**: Integrar com gateway para atualizações automáticas
3. **Validação**: Impedir alterações manuais inconsistentes

### **Sprint 1 - Pagamento Real**
1. **Gateway Integration**: Implementar webhooks
2. **Auto-sync**: Sincronização automática de status
3. **Notifications**: Alertas para mudanças de status

---

## 📊 **Exemplo Visual da Dashboard**

```
┌─────────────────────────────────────────────────────────────┐
│                    LISTA DE PEDIDOS                         │
├─────┬──────────┬──────────┬──────────┬──────────┬───────────┤
│ ID  │ CLIENTE  │ DATA    │ STATUS   │ PRODUTOS │ PAGAMENTO │
├─────┼──────────┼──────────┼──────────┼──────────┼───────────┤
│ #4  │ Cliente1 │ 09/09   │ PAGO     │ [img]    │ Pago      │
│ #3  │ Usuário  │ 09/09   │ ENTREGUE │ [img]    │ Pago      │
│ #2  │ Usuário  │ 09/09   │ AGUARDANDO│ [img]    │ Pendente  │
│ #1  │ Usuário  │ 09/09   │ AGUARDANDO│ [img]    │ Pendente  │
└─────┴──────────┴──────────┴──────────┴──────────┴───────────┘
```

**Explicação:**
- **#4**: Pedido pago e pagamento confirmado ✅
- **#3**: Pedido entregue e pagamento confirmado ✅
- **#2, #1**: Pedidos aguardando pagamento ✅

---

## 🎯 **Conclusão**

O sistema de status duplo é **correto e necessário** porque:

1. **Separação de Responsabilidades**: Pedido (logística) vs Pagamento (financeiro)
2. **Flexibilidade**: Permite diferentes fluxos de negócio
3. **Rastreabilidade**: Logs separados para cada processo
4. **Integração**: Facilita integração com gateways de pagamento

**A Sprint 0 está praticamente concluída**, faltando apenas a sincronização automática entre os status.
