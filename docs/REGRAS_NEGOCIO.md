# 📋 Regras de Negócio - SkyFashion

## 🎯 Visão Geral

Este documento define as **Regras de Negócio** do sistema SkyFashion, estabelecendo as diretrizes, validações e comportamentos que o sistema deve seguir para garantir a integridade dos dados e a correta operação do e-commerce.

## 🛒 Regras de Negócio - Produtos

### **RB001 - Cadastro de Produtos**
- **Descrição**: Regras para cadastro e manutenção de produtos
- **Regras**:
  - Nome do produto é obrigatório e único
  - SKU é obrigatório e único no sistema
  - Preço deve ser maior que zero
  - Estoque inicial deve ser maior ou igual a zero
  - Produto deve ter pelo menos uma categoria
  - Produto deve ter pelo menos uma cor
  - Produto deve ter pelo menos um tamanho
  - Imagem é obrigatória para produtos ativos
  - Slug é gerado automaticamente baseado no nome

### **RB002 - Status de Produtos**
- **Descrição**: Regras para ativação/desativação de produtos
- **Regras**:
  - Produtos inativos não aparecem no catálogo público
  - Produtos inativos não podem ser adicionados ao carrinho
  - Produtos com estoque zero podem permanecer ativos
  - Administrador pode ativar/desativar produtos a qualquer momento

### **RB003 - Controle de Estoque**
- **Descrição**: Regras para controle de estoque
- **Regras**:
  - Estoque não pode ser negativo
  - Produtos com estoque zero não podem ser adicionados ao carrinho
  - Estoque é reduzido automaticamente ao finalizar pedido
  - Estoque pode ser aumentado manualmente pelo administrador
  - Alertas de baixo estoque quando quantidade < 5

### **RB004 - Variações de Produtos**
- **Descrição**: Regras para cores e tamanhos
- **Regras**:
  - Produto pode ter múltiplas cores
  - Produto pode ter múltiplos tamanhos
  - Cliente deve selecionar cor e tamanho antes de adicionar ao carrinho
  - Variações são obrigatórias para produtos com cores/tamanhos cadastrados
  - Preço é o mesmo para todas as variações do produto

## 🛒 Regras de Negócio - Carrinho de Compras

### **RB005 - Adição ao Carrinho**
- **Descrição**: Regras para adicionar produtos ao carrinho
- **Regras**:
  - Produto deve estar ativo
  - Produto deve ter estoque disponível
  - Quantidade máxima por item: 99 unidades
  - Quantidade mínima por item: 1 unidade
  - Produto com variações requer seleção de cor e tamanho
  - Produto já no carrinho: soma quantidades (se não exceder limite)

### **RB006 - Persistência do Carrinho**
- **Descrição**: Regras para persistência de dados do carrinho
- **Regras**:
  - Carrinho de usuário anônimo: persistido em session (30 dias)
  - Carrinho de usuário logado: persistido no banco de dados
  - Sincronização automática ao fazer login
  - Merge inteligente: maior quantidade entre local e banco
  - Carrinho limpo automaticamente após finalizar pedido

### **RB007 - Validação do Carrinho**
- **Descrição**: Regras para validação contínua do carrinho
- **Regras**:
  - Verificação de disponibilidade a cada acesso
  - Remoção automática de produtos inativos
  - Atualização de preços automaticamente
  - Validação de estoque antes do checkout

## 💳 Regras de Negócio - Pedidos

### **RB008 - Criação de Pedidos**
- **Descrição**: Regras para criação de novos pedidos
- **Regras**:
  - Cliente deve estar logado
  - Carrinho não pode estar vazio
  - Todos os itens devem ter estoque disponível
  - Dados de entrega são obrigatórios
  - Método de pagamento deve ser selecionado
  - Pedido criado com status "pending"

### **RB009 - Status de Pedidos**
- **Descrição**: Regras para mudança de status de pedidos
- **Regras**:
  - Status possíveis: pending → processing → shipped → delivered
  - Status "cancelled" pode ser aplicado em qualquer momento
  - Apenas administrador pode alterar status
  - Histórico de mudanças deve ser mantido
  - Notificação automática ao cliente em mudanças de status

### **RB010 - Cancelamento de Pedidos**
- **Descrição**: Regras para cancelamento de pedidos
- **Regras**:
  - Cliente pode cancelar apenas pedidos com status "pending"
  - Administrador pode cancelar pedidos em qualquer status
  - Cancelamento restaura estoque dos produtos
  - Reembolso automático para pedidos pagos
  - Notificação obrigatória ao cliente

### **RB011 - Cálculo de Valores**
- **Descrição**: Regras para cálculo de valores do pedido
- **Regras**:
  - Subtotal: soma dos preços × quantidades
  - Desconto: aplicado sobre o subtotal
  - Frete: calculado baseado no valor e localização
  - Total: subtotal - desconto + frete
  - Valores são calculados no momento da criação do pedido

## 👤 Regras de Negócio - Usuários

### **RB012 - Cadastro de Usuários**
- **Descrição**: Regras para cadastro de novos usuários
- **Regras**:
  - Email é obrigatório e único no sistema
  - Nome é obrigatório (mínimo 2 caracteres)
  - Senha deve ter mínimo 8 caracteres
  - Telefone é opcional
  - Verificação de email é obrigatória
  - Role padrão: "cliente"

### **RB013 - Autenticação**
- **Descrição**: Regras para autenticação de usuários
- **Regras**:
  - Máximo 5 tentativas de login por minuto por IP
  - Conta bloqueada após 10 tentativas falhadas
  - Autenticação 2FA opcional para administradores
  - Sessão expira em 2 horas de inatividade
  - Redirecionamento baseado no role após login

### **RB014 - Roles e Permissões**
- **Descrição**: Regras para controle de acesso
- **Regras**:
  - Roles disponíveis: "admin", "cliente"
  - Apenas administradores acessam painel admin
  - Clientes acessam apenas área da loja
  - Permissões são herdadas pelos roles
  - Apenas administradores podem criar outros administradores

## 🏠 Regras de Negócio - Endereços

### **RB015 - Cadastro de Endereços**
- **Descrição**: Regras para cadastro de endereços
- **Regras**:
  - Cliente deve estar logado
  - País é obrigatório
  - CEP é obrigatório e deve ser válido
  - Rua e número são obrigatórios
  - Cidade e estado são obrigatórios
  - Cliente pode ter múltiplos endereços
  - Apenas um endereço pode ser padrão

### **RB016 - Validação de Endereços**
- **Descrição**: Regras para validação de endereços
- **Regras**:
  - CEP deve seguir formato válido do país
  - Endereço deve ser único por cliente
  - Validação de CEP via API externa (opcional)
  - Endereço padrão é usado automaticamente no checkout

## ❤️ Regras de Negócio - Favoritos

### **RB017 - Sistema de Favoritos**
- **Descrição**: Regras para sistema de favoritos
- **Regras**:
  - Produto deve estar ativo para ser favoritado
  - Cliente pode ter até 100 produtos favoritos
  - Favoritos são sincronizados entre dispositivos
  - Produto removido do catálogo é removido dos favoritos
  - Favoritos persistem por 1 ano

## 🎛️ Regras de Negócio - Administração

### **RB018 - Gestão de Produtos**
- **Descrição**: Regras para administração de produtos
- **Regras**:
  - Apenas administradores podem criar/editar produtos
  - Upload de imagem limitado a 2MB
  - Formatos aceitos: JPG, PNG, WEBP
  - Produto excluído remove associações com pedidos
  - Backup automático antes de exclusões

### **RB019 - Gestão de Pedidos**
- **Descrição**: Regras para administração de pedidos
- **Regras**:
  - Administrador pode visualizar todos os pedidos
  - Mudança de status gera notificação ao cliente
  - Pedidos não podem ser editados após criação
  - Relatórios de pedidos disponíveis por período
  - Exportação de dados em CSV/Excel

### **RB020 - Dashboard Administrativo**
- **Descrição**: Regras para métricas e relatórios
- **Regras**:
  - Métricas calculadas em tempo real
  - Período padrão: últimos 30 dias
  - Dados são atualizados a cada 5 minutos
  - Gráficos são gerados dinamicamente
  - Apenas administradores acessam dashboard

## 🔐 Regras de Negócio - Segurança

### **RB021 - Proteção de Dados**
- **Descrição**: Regras para proteção de dados pessoais
- **Regras**:
  - Senhas são criptografadas com hash bcrypt
  - Dados pessoais são criptografados em trânsito
  - Logs de acesso são mantidos por 1 ano
  - Backup de dados diário automático
  - Conformidade com LGPD

### **RB022 - Rate Limiting**
- **Descrição**: Regras para limitação de requisições
- **Regras**:
  - Login: máximo 5 tentativas por minuto
  - API: máximo 100 requisições por hora
  - Upload: máximo 10 arquivos por minuto
  - Busca: máximo 50 consultas por minuto
  - IP bloqueado após exceder limites

### **RB023 - Validação de Entrada**
- **Descrição**: Regras para validação de dados de entrada
- **Regras**:
  - Todos os inputs são sanitizados
  - Validação server-side obrigatória
  - Validação client-side para UX
  - Rejeição de caracteres especiais maliciosos
  - Limite de tamanho para campos de texto

## 💰 Regras de Negócio - Financeiro

### **RB024 - Cálculo de Preços**
- **Descrição**: Regras para cálculo de preços
- **Regras**:
  - Preços são armazenados com 2 casas decimais
  - Preços são exibidos com símbolo da moeda (€)
  - Descontos são aplicados sobre preço original
  - Preços promocionais têm data de validade
  - Histórico de alterações de preço é mantido

### **RB025 - Frete e Entrega**
- **Descrição**: Regras para cálculo de frete
- **Regras**:
  - Frete grátis para pedidos acima de €50
  - Cálculo baseado em peso e distância
  - Frete fixo para regiões específicas
  - Entrega estimada: 3-7 dias úteis
  - Rastreamento obrigatório para envios

## 📊 Regras de Negócio - Relatórios

### **RB026 - Geração de Relatórios**
- **Descrição**: Regras para geração de relatórios
- **Regras**:
  - Relatórios são gerados sob demanda
  - Dados são atualizados em tempo real
  - Exportação em múltiplos formatos
  - Relatórios são mantidos por 2 anos
  - Apenas administradores acessam relatórios

### **RB027 - Métricas de Performance**
- **Descrição**: Regras para métricas de sistema
- **Regras**:
  - Tempo de resposta máximo: 2 segundos
  - Uptime mínimo: 99.5%
  - Monitoramento 24/7
  - Alertas automáticos para problemas
  - Logs detalhados de performance

## 🔄 Regras de Negócio - Integração

### **RB028 - APIs Externas**
- **Descrição**: Regras para integração com APIs externas
- **Regras**:
  - Timeout máximo: 30 segundos
  - Retry automático em caso de falha
  - Cache de respostas por 1 hora
  - Fallback para serviços indisponíveis
  - Logs de todas as chamadas externas

### **RB029 - Sincronização de Dados**
- **Descrição**: Regras para sincronização entre sistemas
- **Regras**:
  - Sincronização em tempo real quando possível
  - Fallback para sincronização em lote
  - Resolução de conflitos: último update vence
  - Validação de integridade após sincronização
  - Rollback automático em caso de erro

## 📋 Validações Específicas

### **Validação de Email**
```php
// Formato válido de email
- Deve conter @ e domínio válido
- Máximo 255 caracteres
- Caracteres especiais permitidos: . - _
- Não pode começar ou terminar com ponto
```

### **Validação de CEP**
```php
// Formato brasileiro
- 8 dígitos numéricos
- Formato: 00000-000
- Validação via API dos Correios (opcional)
```

### **Validação de Telefone**
```php
// Formato brasileiro
- Mínimo 10 dígitos
- Máximo 11 dígitos
- Formato: (00) 00000-0000
- Aceita apenas números
```

### **Validação de Preços**
```php
// Valores monetários
- Mínimo: €0.01
- Máximo: €999,999.99
- 2 casas decimais obrigatórias
- Formato: 0.00
```

## 🚨 Exceções e Tratamento de Erros

### **RB030 - Tratamento de Exceções**
- **Descrição**: Regras para tratamento de erros
- **Regras**:
  - Erros são logados com contexto completo
  - Usuário recebe mensagem amigável
  - Dados não são perdidos em caso de erro
  - Rollback automático de transações
  - Notificação automática para administradores

### **RB031 - Recuperação de Dados**
- **Descrição**: Regras para recuperação após falhas
- **Regras**:
  - Backup automático a cada 6 horas
  - Retenção de backups por 30 dias
  - Teste de restauração mensal
  - Plano de recuperação documentado
  - Tempo máximo de recuperação: 4 horas

## 📋 Conclusão

Estas regras de negócio garantem:

✅ **Integridade dos Dados**: Validações rigorosas em todas as operações
✅ **Segurança**: Proteção adequada de informações sensíveis
✅ **Consistência**: Comportamento uniforme em todo o sistema
✅ **Auditoria**: Rastreabilidade de todas as operações
✅ **Conformidade**: Adequação às leis e regulamentações
✅ **Escalabilidade**: Regras preparadas para crescimento
✅ **Manutenibilidade**: Facilidade de atualização e modificação

As regras são implementadas tanto no backend (validações server-side) quanto no frontend (validações client-side) para garantir uma experiência de usuário fluida e segura.

---

**Última atualização**: {{ date('d/m/Y H:i') }}
**Versão**: 1.0
**Projeto**: SkyFashion E-commerce
