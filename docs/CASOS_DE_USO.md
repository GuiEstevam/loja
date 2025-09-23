# 🎯 Casos de Uso - SkyFashion

## 📋 Visão Geral

Este documento descreve os principais casos de uso do sistema **SkyFashion**, organizados por atores e funcionalidades. Os casos de uso foram identificados seguindo a metodologia ágil e representam as interações entre usuários e o sistema.

## 👥 Atores do Sistema

### **Atores Principais**
- **Cliente**: Usuário final que navega e compra produtos
- **Administrador**: Usuário que gerencia o sistema e produtos
- **Sistema**: Processos automáticos e integrações

### **Atores Secundários**
- **Visitante**: Usuário não registrado
- **Cliente Logado**: Usuário registrado e autenticado

## 🛒 Casos de Uso - Cliente

### **UC001 - Navegar no Catálogo de Produtos**
**Atores**: Cliente, Visitante
**Descrição**: O usuário navega pelo catálogo de produtos da loja

**Fluxo Principal**:
1. Usuário acessa a página de produtos
2. Sistema exibe lista de produtos com filtros
3. Usuário aplica filtros (categoria, marca, preço, cor, tamanho)
4. Sistema atualiza a lista de produtos
5. Usuário navega pelas páginas de resultados

**Fluxos Alternativos**:
- 3a. Usuário realiza busca por texto
- 3b. Usuário ordena produtos por critério
- 4a. Sistema não encontra produtos para os filtros aplicados

**Pré-condições**: Sistema funcionando, produtos cadastrados
**Pós-condições**: Usuário visualiza produtos filtrados

---

### **UC002 - Visualizar Detalhes do Produto**
**Atores**: Cliente, Visitante
**Descrição**: O usuário visualiza informações detalhadas de um produto

**Fluxo Principal**:
1. Usuário clica em um produto na lista
2. Sistema exibe página de detalhes do produto
3. Sistema mostra: imagens, descrição, preço, variações (cor/tamanho)
4. Usuário seleciona cor e tamanho (se aplicável)
5. Usuário define quantidade desejada

**Fluxos Alternativos**:
- 2a. Produto não encontrado
- 4a. Produto sem variações de cor/tamanho
- 5a. Quantidade excede estoque disponível

**Pré-condições**: Produto existente e ativo
**Pós-condições**: Usuário visualiza detalhes completos do produto

---

### **UC003 - Adicionar Produto ao Carrinho**
**Atores**: Cliente, Visitante
**Descrição**: O usuário adiciona um produto ao carrinho de compras

**Fluxo Principal**:
1. Usuário está na página de detalhes do produto
2. Usuário seleciona cor, tamanho e quantidade
3. Usuário clica em "Adicionar ao Carrinho"
4. Sistema valida disponibilidade do produto
5. Sistema adiciona produto ao carrinho
6. Sistema exibe mensagem de sucesso
7. Sistema atualiza contador do carrinho

**Fluxos Alternativos**:
- 4a. Produto sem estoque suficiente
- 4b. Produto inativo
- 5a. Produto já existe no carrinho (atualiza quantidade)

**Pré-condições**: Produto disponível e ativo
**Pós-condições**: Produto adicionado ao carrinho

---

### **UC004 - Gerenciar Carrinho de Compras**
**Atores**: Cliente, Visitante
**Descrição**: O usuário gerencia itens no carrinho de compras

**Fluxo Principal**:
1. Usuário acessa página do carrinho
2. Sistema exibe itens do carrinho
3. Usuário pode:
   - Alterar quantidade de itens
   - Remover itens
   - Visualizar total
4. Sistema atualiza cálculos automaticamente

**Fluxos Alternativos**:
- 2a. Carrinho vazio
- 3a. Item sem estoque suficiente
- 3b. Item removido do catálogo

**Pré-condições**: Usuário com itens no carrinho
**Pós-condições**: Carrinho atualizado

---

### **UC005 - Processar Checkout**
**Atores**: Cliente Logado
**Descrição**: O cliente finaliza a compra processando o checkout

**Fluxo Principal**:
1. Cliente acessa página de checkout
2. Sistema exibe resumo do pedido
3. Cliente preenche dados de entrega
4. Cliente seleciona método de pagamento
5. Cliente confirma pedido
6. Sistema cria pedido
7. Sistema limpa carrinho
8. Sistema redireciona para confirmação

**Fluxos Alternativos**:
- 2a. Carrinho vazio
- 3a. Cliente usa endereço salvo
- 4a. Método de pagamento indisponível
- 6a. Erro na criação do pedido

**Pré-condições**: Cliente logado, carrinho com itens
**Pós-condições**: Pedido criado, carrinho limpo

---

### **UC006 - Gerenciar Favoritos**
**Atores**: Cliente, Visitante
**Descrição**: O usuário gerencia lista de produtos favoritos

**Fluxo Principal**:
1. Usuário visualiza produto
2. Usuário clica em "Favoritar"
3. Sistema adiciona produto aos favoritos
4. Usuário pode acessar página de favoritos
5. Sistema exibe lista de produtos favoritos
6. Usuário pode remover favoritos

**Fluxos Alternativos**:
- 2a. Produto já está nos favoritos (remove)
- 4a. Lista de favoritos vazia

**Pré-condições**: Produto existente
**Pós-condições**: Lista de favoritos atualizada

---

### **UC007 - Acompanhar Pedidos**
**Atores**: Cliente Logado
**Descrição**: O cliente acompanha status dos seus pedidos

**Fluxo Principal**:
1. Cliente acessa "Meus Pedidos"
2. Sistema exibe lista de pedidos
3. Cliente clica em um pedido
4. Sistema exibe detalhes do pedido
5. Sistema mostra status atual e histórico

**Fluxos Alternativos**:
- 2a. Cliente sem pedidos
- 4a. Pedido não encontrado

**Pré-condições**: Cliente logado
**Pós-condições**: Cliente visualiza informações do pedido

---

### **UC008 - Gerenciar Endereços**
**Atores**: Cliente Logado
**Descrição**: O cliente gerencia seus endereços de entrega

**Fluxo Principal**:
1. Cliente acessa "Meus Endereços"
2. Sistema exibe lista de endereços
3. Cliente pode:
   - Adicionar novo endereço
   - Editar endereço existente
   - Definir endereço padrão
   - Remover endereço
4. Sistema valida dados do endereço
5. Sistema salva alterações

**Fluxos Alternativos**:
- 2a. Cliente sem endereços cadastrados
- 4a. Dados de endereço inválidos

**Pré-condições**: Cliente logado
**Pós-condições**: Endereços atualizados

---

## 🎛️ Casos de Uso - Administrador

### **UC009 - Gerenciar Produtos**
**Atores**: Administrador
**Descrição**: O administrador gerencia o catálogo de produtos

**Fluxo Principal**:
1. Administrador acessa painel de produtos
2. Sistema exibe lista de produtos
3. Administrador pode:
   - Adicionar novo produto
   - Editar produto existente
   - Ativar/desativar produto
   - Excluir produto
4. Sistema valida dados do produto
5. Sistema salva alterações

**Fluxos Alternativos**:
- 3a. Produto com pedidos associados
- 4a. Dados do produto inválidos
- 4b. Imagem não enviada

**Pré-condições**: Administrador logado
**Pós-condições**: Catálogo de produtos atualizado

---

### **UC010 - Gerenciar Pedidos**
**Atores**: Administrador
**Descrição**: O administrador gerencia pedidos dos clientes

**Fluxo Principal**:
1. Administrador acessa painel de pedidos
2. Sistema exibe lista de pedidos
3. Administrador clica em um pedido
4. Sistema exibe detalhes do pedido
5. Administrador atualiza status do pedido
6. Sistema salva alteração

**Fluxos Alternativos**:
- 2a. Nenhum pedido encontrado
- 4a. Pedido não encontrado
- 5a. Status inválido para o pedido

**Pré-condições**: Administrador logado
**Pós-condições**: Status do pedido atualizado

---

### **UC011 - Visualizar Dashboard**
**Atores**: Administrador
**Descrição**: O administrador visualiza métricas e estatísticas

**Fluxo Principal**:
1. Administrador acessa dashboard
2. Sistema exibe métricas principais:
   - Total de pedidos
   - Receita total
   - Novos clientes
   - Produtos com baixo estoque
3. Sistema exibe gráficos de vendas
4. Administrador pode filtrar por período

**Fluxos Alternativos**:
- 2a. Período sem dados
- 3a. Erro ao gerar gráficos

**Pré-condições**: Administrador logado
**Pós-condições**: Administrador visualiza métricas

---

### **UC012 - Gerenciar Categorias**
**Atores**: Administrador
**Descrição**: O administrador gerencia categorias de produtos

**Fluxo Principal**:
1. Administrador acessa painel de categorias
2. Sistema exibe lista de categorias
3. Administrador pode:
   - Adicionar nova categoria
   - Editar categoria existente
   - Ativar/desativar categoria
   - Excluir categoria
4. Sistema valida dados da categoria
5. Sistema salva alterações

**Fluxos Alternativos**:
- 3a. Categoria com produtos associados
- 4a. Dados da categoria inválidos

**Pré-condições**: Administrador logado
**Pós-condições**: Categorias atualizadas

---

### **UC013 - Gerenciar Marcas**
**Atores**: Administrador
**Descrição**: O administrador gerencia marcas de produtos

**Fluxo Principal**:
1. Administrador acessa painel de marcas
2. Sistema exibe lista de marcas
3. Administrador pode:
   - Adicionar nova marca
   - Editar marca existente
   - Upload de logo
   - Ativar/desativar marca
4. Sistema valida dados da marca
5. Sistema salva alterações

**Fluxos Alternativos**:
- 3a. Marca com produtos associados
- 4a. Logo inválido ou muito grande

**Pré-condições**: Administrador logado
**Pós-condições**: Marcas atualizadas

---

## 🔐 Casos de Uso - Autenticação

### **UC014 - Realizar Login**
**Atores**: Cliente, Administrador
**Descrição**: O usuário realiza login no sistema

**Fluxo Principal**:
1. Usuário acessa página de login
2. Usuário informa email e senha
3. Sistema valida credenciais
4. Sistema autentica usuário
5. Sistema sincroniza dados (carrinho/favoritos)
6. Sistema redireciona baseado no role:
   - Admin → Dashboard administrativo
   - Cliente → Dashboard do cliente

**Fluxos Alternativos**:
- 3a. Credenciais inválidas
- 3b. Conta não verificada
- 4a. Conta bloqueada
- 5a. Erro na sincronização

**Pré-condições**: Usuário registrado
**Pós-condições**: Usuário autenticado

---

### **UC015 - Realizar Registro**
**Atores**: Visitante
**Descrição**: O visitante cria uma nova conta

**Fluxo Principal**:
1. Visitante acessa página de registro
2. Visitante preenche dados pessoais
3. Sistema valida dados
4. Sistema cria conta
5. Sistema envia email de verificação
6. Sistema redireciona para login

**Fluxos Alternativos**:
- 3a. Email já cadastrado
- 3b. Dados inválidos
- 5a. Erro no envio do email

**Pré-condições**: Visitante não registrado
**Pós-condições**: Nova conta criada

---

### **UC016 - Recuperar Senha**
**Atores**: Cliente, Administrador
**Descrição**: O usuário recupera senha esquecida

**Fluxo Principal**:
1. Usuário acessa página de recuperação
2. Usuário informa email
3. Sistema valida email
4. Sistema envia link de recuperação
5. Usuário acessa link
6. Usuário define nova senha
7. Sistema atualiza senha

**Fluxos Alternativos**:
- 3a. Email não cadastrado
- 4a. Erro no envio do email
- 6a. Link expirado

**Pré-condições**: Usuário registrado
**Pós-condições**: Senha atualizada

---

## 🔄 Casos de Uso - Sistema

### **UC017 - Sincronizar Dados do Carrinho**
**Atores**: Sistema
**Descrição**: O sistema sincroniza dados do carrinho entre sessão e banco

**Fluxo Principal**:
1. Sistema detecta mudança no carrinho
2. Sistema identifica tipo de usuário:
   - Anônimo: Salva em session
   - Logado: Salva no banco
3. Sistema persiste dados
4. Sistema atualiza localStorage

**Fluxos Alternativos**:
- 2a. Erro na identificação do usuário
- 3a. Erro na persistência
- 4a. localStorage indisponível

**Pré-condições**: Sistema funcionando
**Pós-condições**: Dados sincronizados

---

### **UC018 - Processar Pagamento**
**Atores**: Sistema
**Descrição**: O sistema processa pagamento do pedido

**Fluxo Principal**:
1. Sistema recebe dados do pedido
2. Sistema valida informações de pagamento
3. Sistema processa pagamento
4. Sistema atualiza status do pedido
5. Sistema envia confirmação

**Fluxos Alternativos**:
- 2a. Dados de pagamento inválidos
- 3a. Pagamento recusado
- 4a. Erro na atualização do status

**Pré-condições**: Pedido válido
**Pós-condições**: Pagamento processado

---

## 📊 Diagramas de Casos de Uso

### **Diagrama Geral**
```
┌─────────────────────────────────────┐
│            SkyFashion               │
├─────────────────────────────────────┤
│  Cliente    │    Administrador      │
│             │                       │
│ UC001-UC008 │    UC009-UC013        │
│             │                       │
│ UC014-UC016 │    UC014-UC016        │
└─────────────────────────────────────┘
│              Sistema                │
│           UC017-UC018               │
└─────────────────────────────────────┘
```

### **Fluxo de Compra**
```
UC001 → UC002 → UC003 → UC004 → UC005
  ↓       ↓       ↓       ↓       ↓
Navegar → Ver → Adicionar → Gerenciar → Checkout
```

## 📋 Regras de Negócio Relacionadas

### **Carrinho de Compras**
- Máximo de 99 itens por produto
- Carrinho persiste por 30 dias
- Sincronização automática entre dispositivos

### **Pedidos**
- Status: pending → processing → shipped → delivered
- Cancelamento apenas em status "pending"
- Histórico completo de alterações

### **Produtos**
- Produtos inativos não aparecem no catálogo
- Estoque zero impede adição ao carrinho
- Variações obrigatórias para produtos com cores/tamanhos

### **Usuários**
- Email único por conta
- Verificação obrigatória de email
- Roles: admin, cliente
- Autenticação 2FA opcional

## 🚀 Casos de Uso Futuros

### **UC019 - Sistema de Avaliações**
- Cliente avalia produto após compra
- Sistema calcula média de avaliações
- Administrador modera avaliações

### **UC020 - Sistema de Cupons**
- Administrador cria cupons de desconto
- Cliente aplica cupons no checkout
- Sistema valida cupons automaticamente

### **UC021 - Programa de Fidelidade**
- Sistema acumula pontos por compras
- Cliente troca pontos por descontos
- Administrador configura regras de pontos

---

**Última atualização**: {{ date('d/m/Y H:i') }}
**Versão**: 1.0
**Projeto**: SkyFashion E-commerce
