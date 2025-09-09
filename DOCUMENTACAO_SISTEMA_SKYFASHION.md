# 📋 Documentação Técnica - Sistema SkyFashion

## 🎯 Visão Geral do Sistema

O **SkyFashion** é uma plataforma de e-commerce desenvolvida em **Laravel 12** com foco em moda e produtos de vestuário. O sistema oferece uma experiência completa de compra online com funcionalidades avançadas de carrinho, favoritos, sistema de pontos de fidelidade e painel administrativo robusto.

### 🏗️ Arquitetura Técnica

- **Framework**: Laravel 12 (PHP 8.2+)
- **Autenticação**: Laravel Fortify + Jetstream
- **Autorização**: Spatie Laravel Permission (roles/permissions)
- **Frontend**: Blade Templates + CSS Customizado
- **Banco de Dados**: MySQL/PostgreSQL
- **Assets**: Vite + CSS Modules
- **APIs**: Laravel Sanctum para autenticação de API

---

## 📊 Modelos de Dados e Relacionamentos

### 👤 **User (Usuário)**
```php
// Campos principais
- id, name, email, password, phone
- email_verified_at, created_at, updated_at

// Relacionamentos
- hasMany(Order::class) - Pedidos do usuário
- hasMany(Address::class) - Endereços salvos
- hasOne(LoyaltyPoint::class) - Pontos de fidelidade
- hasMany(CartItem::class) - Itens do carrinho
- hasMany(Favorite::class) - Produtos favoritos
- hasMany(Discount::class) - Cupons de desconto

// Traits especiais
- HasRoles (Spatie) - Sistema de roles (admin/cliente)
- TwoFactorAuthenticatable - Autenticação 2FA
- HasApiTokens - Tokens para API
```

### 🛍️ **Product (Produto)**
```php
// Campos principais
- id, name, slug, description, price, stock, image, sku
- brand_id, active, featured, weight, dimensions
- free_shipping, rating, rating_count
- installments, installment_value
- is_new, is_sale, sale_price, sale_ends_at

// Relacionamentos Many-to-Many
- belongsToMany(Category::class) - Categorias
- belongsToMany(Color::class) - Cores disponíveis
- belongsToMany(Size::class) - Tamanhos disponíveis
- belongsTo(Brand::class) - Marca do produto
- hasMany(OrderItem::class) - Itens em pedidos
```

### 🛒 **Order (Pedido)**
```php
// Campos principais
- id, user_id, subtotal, discount, total, status
- name, email, phone, address, country, zip
- street, number, complement, neighborhood, city, state
- payment_method, notes, created_at, updated_at

// Relacionamentos
- belongsTo(User::class) - Cliente
- hasMany(OrderItem::class) - Itens do pedido
- hasOne(Payment::class) - Informações de pagamento
```

### 🛒 **CartItem (Item do Carrinho)**
```php
// Campos principais
- id, user_id, session_id, product_id, quantity
- color, color_hex, size, price

// Funcionalidades especiais
- Suporte a usuários logados e sessões anônimas
- Chave única para variações (produto+cor+tamanho)
- Sincronização automática entre localStorage e banco
```

### ❤️ **Favorite (Favorito)**
```php
// Campos principais
- id, user_id, session_id, product_id

// Funcionalidades
- Suporte a usuários logados e sessões anônimas
- Sincronização automática com localStorage
```

### 🏷️ **Entidades de Classificação**
- **Category**: name, slug, active
- **Brand**: name, slug, active, logo
- **Color**: name, hex_code, active
- **Size**: name, active

---

## 🔐 Sistema de Autenticação e Autorização

### **Autenticação (Laravel Fortify)**
- Login/Registro com email e senha
- Autenticação de dois fatores (2FA)
- Recuperação de senha
- Verificação de email
- Rate limiting para tentativas de login

### **Autorização (Spatie Laravel Permission)**
- **Roles**: `admin`, `cliente`
- **Redirecionamento automático**:
  - Admin → `/admin/dashboard`
  - Cliente → `/shop/dashboard`

### **Middleware de Segurança**
- `auth` - Usuário autenticado
- `role:admin` - Acesso administrativo
- `SyncCartOnLogin` - Sincronização de dados após login

---

## 🛒 Funcionalidades do E-commerce

### **1. Catálogo de Produtos**
- **Listagem com filtros**: busca, categoria, marca, cor, tamanho, faixa de preço
- **Ordenação**: preço, nome, data de criação, popularidade
- **Paginação**: 12 produtos por página
- **Detalhes do produto**: imagens, variações, descrição, avaliações

### **2. Sistema de Carrinho Avançado**
- **Funcionalidades**:
  - Adicionar/remover produtos
  - Seleção de cor e tamanho
  - Quantidade personalizável
  - Persistência em sessão e banco de dados
  - Sincronização automática entre dispositivos

- **Sincronização Inteligente**:
  - Usuários anônimos: localStorage + session
  - Usuários logados: banco de dados + localStorage
  - Merge automático de carrinhos ao fazer login

### **3. Sistema de Favoritos**
- Adicionar/remover produtos dos favoritos
- Sincronização entre sessão e banco de dados
- Interface dedicada para gerenciar favoritos

### **4. Processo de Checkout**
- **Etapas**:
  1. Revisão do carrinho
  2. Informações de entrega
  3. Método de pagamento
  4. Confirmação do pedido

- **Campos de entrega**:
  - Dados pessoais (nome, email, telefone)
  - Endereço completo (país, CEP, rua, número, etc.)
  - Método de pagamento
  - Observações

### **5. Gestão de Pedidos**
- **Status do pedido**: pending, processing, shipped, delivered, cancelled
- **Acompanhamento**: histórico completo de pedidos
- **Detalhes**: itens, valores, endereço de entrega

### **6. Sistema de Endereços**
- CRUD completo de endereços
- Endereço padrão para checkout rápido
- Validação de dados de entrega

---

## 🎛️ Painel Administrativo

### **Dashboard Administrativo**
- **Métricas principais**:
  - Total de pedidos (período configurável)
  - Receita total
  - Ticket médio
  - Novos clientes
  - Produtos ativos
  - Produtos com baixo estoque

- **Gráficos**: Pedidos nos últimos 30 dias
- **Ações rápidas**: Links para gerenciamento

### **Gestão de Produtos**
- **CRUD completo**:
  - Criar/editar/excluir produtos
  - Upload de imagens
  - Associação com categorias, cores, tamanhos
  - Controle de estoque
  - Produtos em destaque

- **Funcionalidades avançadas**:
  - Busca por nome, SKU, categoria
  - Filtro de baixo estoque
  - Paginação configurável (5, 10, 15, 25, 50)
  - Validação de SKU único

### **Gestão de Pedidos**
- **Visualização**: Lista de todos os pedidos
- **Detalhes**: Informações completas do pedido
- **Atualização de status**: Mudança de status do pedido

### **Gestão de Entidades**
- **Categorias**: CRUD completo
- **Marcas**: CRUD completo + upload de logo
- **Cores**: CRUD completo + código hexadecimal
- **Tamanhos**: CRUD completo

---

## 🎨 Sistema de Design e Interface

### **Arquitetura CSS**
- **Modular**: Arquivos CSS separados por funcionalidade
- **Variáveis CSS**: Cores, espaçamentos, tipografia centralizados
- **Dark Mode**: Suporte nativo com classe `.dark`
- **Responsivo**: Mobile-first design

### **Arquivos CSS Principais**
```
resources/css/
├── variables.css      # Variáveis globais
├── base.css          # Reset e estilos base
├── components.css    # Componentes reutilizáveis
├── admin.css         # Estilos do painel admin
├── products.css      # Páginas de produtos
├── cart.css          # Carrinho de compras
├── checkout.css      # Processo de checkout
├── dashboard.css     # Dashboard do usuário
└── welcome.css       # Página inicial
```

### **Componentes Disponíveis**
- **Botões**: Primário, secundário, outline, ghost
- **Formulários**: Inputs, selects, textareas com validação
- **Cards**: Headers, bodies, footers modulares
- **Badges**: Status e categorização
- **Tabelas**: Responsivas com ações
- **Alertas**: Info, success, warning, danger
- **Loading**: Spinners e estados de carregamento
- **Paginação**: Navegação entre páginas

### **Ícones**
- **Ion Icons**: Biblioteca de ícones consistente
- **Uso**: Navegação, ações, status, categorização

---

## 🛣️ Estrutura de Rotas

### **Rotas Públicas**
```php
// Página inicial
GET / → HomeController@welcome

// Catálogo
GET /produtos → ShopProductController@index
GET /produtos/{product} → ShopProductController@show
POST /produtos/buscar → ShopProductController@search

// Categorias e Marcas
GET /categorias/{category:slug} → ShopCategoryController@show
GET /marcas → ShopBrandController@index
GET /marcas/{brand:slug} → ShopBrandController@show

// Carrinho (acesso público)
GET /carrinho → ShopCartController@index
POST /carrinho/adicionar/{product} → ShopCartController@add
POST /carrinho/remover/{product} → ShopCartController@remove
POST /carrinho/atualizar/{product} → ShopCartController@update
POST /comprar/{product} → ShopCartController@buy

// Checkout
GET /checkout → ShopOrderController@checkout

// Favoritos
GET /favoritos → ShopFavoritesController@index

// APIs de Sincronização
POST /api/sync/cart → ApiSyncController@syncCart
POST /api/sync/favorites → ApiSyncController@syncFavorites
GET /api/sync/load → ApiSyncController@loadData
```

### **Rotas Autenticadas**
```php
// Dashboard do usuário
GET /minha-conta → ShopDashboardController@index

// Pedidos
GET /meus-pedidos → ShopOrderController@index
GET /meus-pedidos/{order} → ShopOrderController@show
POST /checkout → ShopOrderController@process
GET /confirmacao/{order} → ShopOrderController@confirmation

// Endereços (CRUD completo)
Route::resource('enderecos', ShopAddressController::class);

// Outras funcionalidades
GET /meus-pagamentos → ShopPaymentController@index
GET /meus-cupons → ShopDiscountController@index
GET /meus-pontos → ShopLoyaltyPointController@index
```

### **Rotas Administrativas**
```php
// Dashboard admin
GET /admin/dashboard → AdminDashboardController@index

// Gestão de produtos
Route::resource('admin/products', AdminProductController::class);
POST /admin/products/search → AdminProductController@search

// Gestão de pedidos
Route::resource('admin/orders', AdminOrderController::class)
    ->only(['index', 'show', 'update']);

// Gestão de entidades
Route::resource('admin/categories', AdminCategoryController::class);
Route::resource('admin/brands', AdminBrandController::class);
Route::resource('admin/colors', AdminColorController::class);
Route::resource('admin/sizes', AdminSizeController::class);
```

---

## 🔄 Serviços e Funcionalidades Avançadas

### **CartSyncService**
Serviço responsável pela sincronização inteligente de dados entre localStorage e banco de dados:

```php
// Funcionalidades principais
- syncCartToDatabase() - Sincronizar carrinho local → banco
- loadCartFromDatabase() - Carregar carrinho do banco
- mergeCarts() - Mesclar carrinhos local e banco
- syncFavoritesToDatabase() - Sincronizar favoritos
- loadFavoritesFromDatabase() - Carregar favoritos do banco
- mergeFavorites() - Mesclar favoritos local e banco
```

### **Sistema de Sincronização**
- **Usuários anônimos**: Dados salvos em localStorage + session
- **Usuários logados**: Dados persistidos no banco de dados
- **Login**: Merge automático de dados locais com dados do banco
- **APIs REST**: Endpoints para sincronização via JavaScript

### **Middleware de Sincronização**
- `SyncCartOnLogin`: Executa após login bem-sucedido
- Sincroniza carrinho e favoritos automaticamente
- Transparente para o usuário

---

## 📱 Experiência do Usuário

### **Página Inicial (Welcome)**
- **Banner Hero**: Slider com produtos em destaque
- **Seção de Marcas**: Carrossel de logos das marcas
- **Produtos em Destaque**: Grid responsivo de produtos
- **Navegação**: Swiper.js para carrosséis interativos

### **Catálogo de Produtos**
- **Filtros Laterais**: Busca, preço, categoria, marca, cor, tamanho
- **Grid Responsivo**: Adaptação automática para diferentes telas
- **Paginação**: Navegação suave entre páginas
- **Ordenação**: Múltiplas opções de ordenação

### **Detalhes do Produto**
- **Galeria de Imagens**: Visualização ampliada
- **Seleção de Variações**: Cor e tamanho
- **Quantidade**: Controles intuitivos
- **Ações**: Adicionar ao carrinho, comprar agora, favoritar
- **Informações**: Garantia, frete, devolução

### **Carrinho de Compras**
- **Interface Dinâmica**: Atualização em tempo real
- **Progresso Visual**: Indicador de etapas
- **Cálculos Automáticos**: Subtotal, frete, total
- **Persistência**: Dados mantidos entre sessões

### **Checkout**
- **Formulário Intuitivo**: Campos organizados logicamente
- **Validação em Tempo Real**: Feedback imediato
- **Endereços Salvos**: Reutilização de endereços
- **Confirmação**: Resumo completo do pedido

---

## 🔧 Configuração e Deploy

### **Dependências Principais**
```json
{
  "laravel/framework": "^12.0",
  "laravel/jetstream": "^5.3",
  "laravel/sanctum": "^4.0",
  "livewire/livewire": "^3.0",
  "spatie/laravel-permission": "^6.18"
}
```

### **Scripts de Desenvolvimento**
```json
{
  "dev": "php artisan serve + queue:listen + pail + npm run dev",
  "test": "php artisan config:clear + php artisan test"
}
```

### **Configurações Importantes**
- **Autenticação**: Fortify + Jetstream configurados
- **Permissões**: Spatie Permission com roles admin/cliente
- **APIs**: Sanctum para autenticação de API
- **Assets**: Vite para build de CSS/JS
- **Localização**: Português brasileiro (pt_BR)

---

## 📈 Métricas e Analytics

### **Dashboard Administrativo**
- **Pedidos**: Total, receita, ticket médio
- **Clientes**: Novos clientes, crescimento
- **Produtos**: Ativos, baixo estoque, performance
- **Período**: Configurável (7, 30, 90 dias)

### **Dashboard do Cliente**
- **Pedidos**: Total de pedidos, pendentes
- **Gastos**: Total gasto na plataforma
- **Endereços**: Quantidade de endereços salvos
- **Acesso Rápido**: Links para funcionalidades principais

---

## 🚀 Funcionalidades Futuras

### **Sistema de Pontos de Fidelidade**
- Acúmulo de pontos por compras
- Troca de pontos por descontos
- Programa de referência

### **Sistema de Cupons**
- Cupons de desconto
- Cupons de frete grátis
- Validação automática

### **Sistema de Pagamento**
- Integração com gateways de pagamento
- Parcelamento automático
- Confirmação de pagamento

### **Sistema de Avaliações**
- Avaliações de produtos
- Sistema de estrelas
- Comentários de clientes

---

## 📋 Conclusão

O sistema **SkyFashion** representa uma solução completa de e-commerce com:

✅ **Arquitetura Robusta**: Laravel 12 com padrões modernos
✅ **Experiência do Usuário**: Interface intuitiva e responsiva
✅ **Funcionalidades Avançadas**: Carrinho inteligente, favoritos, sincronização
✅ **Painel Administrativo**: Gestão completa de produtos e pedidos
✅ **Segurança**: Autenticação robusta com 2FA e controle de acesso
✅ **Escalabilidade**: Estrutura preparada para crescimento
✅ **Manutenibilidade**: Código organizado e documentado

O sistema está pronto para produção e pode ser facilmente estendido com novas funcionalidades conforme necessário.

---

**Documentação criada em**: {{ date('d/m/Y H:i') }}
**Versão do Sistema**: 1.0
**Framework**: Laravel 12
**Desenvolvedor**: Sistema SkyFashion
