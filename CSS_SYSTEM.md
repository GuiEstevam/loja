# Sistema CSS Unificado - Documentação

## 📋 **Visão Geral**

Este documento descreve o novo sistema CSS unificado da aplicação, que foi refatorado para ser mais modular, escalável e consistente.

## 🏗️ **Estrutura do Sistema**

```
resources/css/
├── variables.css          # Variáveis globais (cores, espaçamentos, etc.)
├── base.css              # Reset e estilos base
├── components.css        # Componentes reutilizáveis
├── admin.css            # Estilos específicos do admin
└── app.css              # Arquivo principal (importa todos os outros)
```

## 🎨 **Sistema de Variáveis**

### **Cores Principais**

```css
--primary: #3b82f6; /* Azul principal */
--secondary: #64748b; /* Cinza secundário */
--success: #10b981; /* Verde sucesso */
--warning: #f59e0b; /* Amarelo aviso */
--danger: #ef4444; /* Vermelho erro */
--info: #06b6d4; /* Ciano informação */
```

### **Cores de Fundo**

```css
--bg-primary: #ffffff; /* Fundo principal */
--bg-secondary: #f8fafc; /* Fundo secundário */
--bg-tertiary: #f1f5f9; /* Fundo terciário */
--bg-card: #ffffff; /* Fundo de cards */
```

### **Cores de Texto**

```css
--text-primary: #1e293b; /* Texto principal */
--text-secondary: #64748b; /* Texto secundário */
--text-muted: #94a3b8; /* Texto suave */
--text-light: #ffffff; /* Texto claro */
```

### **Espaçamentos**

```css
--space-xs: 0.25rem; /* 4px */
--space-sm: 0.5rem; /* 8px */
--space-md: 0.75rem; /* 12px */
--space-lg: 1rem; /* 16px */
--space-xl: 1.5rem; /* 24px */
--space-2xl: 2rem; /* 32px */
--space-3xl: 3rem; /* 48px */
--space-4xl: 4rem; /* 64px */
```

### **Tipografia**

```css
--font-family: "Inter", -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
--font-size-xs: 0.75rem; /* 12px */
--font-size-sm: 0.875rem; /* 14px */
--font-size-base: 1rem; /* 16px */
--font-size-lg: 1.125rem; /* 18px */
--font-size-xl: 1.25rem; /* 20px */
--font-size-2xl: 1.5rem; /* 24px */
--font-size-3xl: 1.875rem; /* 30px */
--font-size-4xl: 2.25rem; /* 36px */
```

## 🧩 **Componentes Disponíveis**

### **Botões**

```html
<!-- Botão primário -->
<button class="btn btn-primary">Botão Primário</button>

<!-- Botão secundário -->
<button class="btn btn-secondary">Botão Secundário</button>

<!-- Botão outline -->
<button class="btn btn-outline">Botão Outline</button>

<!-- Botão ghost -->
<button class="btn btn-ghost">Botão Ghost</button>

<!-- Tamanhos -->
<button class="btn btn-primary btn-sm">Pequeno</button>
<button class="btn btn-primary btn-lg">Grande</button>
```

### **Formulários**

```html
<div class="form-group">
    <label class="form-label">Nome</label>
    <input type="text" class="form-input" placeholder="Digite seu nome" />
</div>

<div class="form-group">
    <label class="form-label">Categoria</label>
    <select class="form-select">
        <option>Opção 1</option>
        <option>Opção 2</option>
    </select>
</div>

<div class="form-group">
    <label class="form-label">Descrição</label>
    <textarea class="form-textarea" placeholder="Digite a descrição"></textarea>
</div>
```

### **Cards**

```html
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Título do Card</h3>
        <p class="card-subtitle">Subtítulo do card</p>
    </div>
    <div class="card-body">
        <p>Conteúdo do card</p>
    </div>
    <div class="card-footer">
        <button class="btn btn-primary">Ação</button>
    </div>
</div>
```

### **Badges**

```html
<span class="badge">Badge Padrão</span>
<span class="badge badge-primary">Badge Primário</span>
<span class="badge badge-success">Badge Sucesso</span>
<span class="badge badge-warning">Badge Aviso</span>
<span class="badge badge-danger">Badge Erro</span>
```

### **Tabelas**

```html
<table class="table">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Email</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>João Silva</td>
            <td>joao@email.com</td>
            <td>
                <button class="btn btn-sm btn-primary">Editar</button>
            </td>
        </tr>
    </tbody>
</table>
```

### **Alertas**

```html
<div class="alert alert-info">Informação importante</div>
<div class="alert alert-success">Operação realizada com sucesso</div>
<div class="alert alert-warning">Atenção necessária</div>
<div class="alert alert-danger">Erro ocorreu</div>
```

### **Loading**

```html
<div class="loading">
    <div class="loading-spinner"></div>
    <span>Carregando...</span>
</div>
```

### **Breadcrumb**

```html
<nav class="breadcrumb">
    <a href="/">Home</a>
    <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
    <a href="/admin">Admin</a>
    <ion-icon name="chevron-forward-outline" class="separator"></ion-icon>
    <span>Produtos</span>
</nav>
```

### **Paginação**

```html
<div class="pagination">
    <a href="#" class="pagination-item">Anterior</a>
    <a href="#" class="pagination-item active">1</a>
    <a href="#" class="pagination-item">2</a>
    <a href="#" class="pagination-item">3</a>
    <a href="#" class="pagination-item">Próximo</a>
</div>
```

## 🎛️ **Classes Específicas do Admin**

### **Layout Admin**

```html
<div class="admin-page">
    <div class="admin-content">
        <div class="admin-container">
            <div class="admin-card">
                <!-- Conteúdo -->
            </div>
        </div>
    </div>
</div>
```

### **Dashboard Admin**

```html
<div class="admin-page">
    <div class="admin-content">
        <div class="admin-container">
            <!-- Header -->
            <div class="admin-card">
                <div class="admin-card-header">
                    <h1 class="admin-card-title">
                        <ion-icon name="settings-outline"></ion-icon>
                        Painel Administrativo
                    </h1>
                    <p class="admin-card-subtitle">
                        Gerencie produtos, pedidos e configurações
                    </p>
                </div>
            </div>

            <!-- Cards de Estatísticas -->
            <div class="admin-stats-grid">
                <a href="/admin/orders" class="admin-stat-card orders">
                    <div class="admin-stat-icon">
                        <ion-icon name="receipt-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-value">150</div>
                    <div class="admin-stat-label">Pedidos (30 dias)</div>
                </a>

                <div class="admin-stat-card revenue">
                    <div class="admin-stat-icon">
                        <ion-icon name="cash-outline"></ion-icon>
                    </div>
                    <div class="admin-stat-value">€15.750,00</div>
                    <div class="admin-stat-label">Receita (30 dias)</div>
                </div>
            </div>

            <!-- Seção de Gerenciamento -->
            <div class="admin-management-section">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2 class="admin-card-title">Gerenciamento da Loja</h2>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-category-cards">
                            <a
                                href="/admin/categories"
                                class="admin-category-card categories"
                            >
                                <div class="admin-category-icon">
                                    <ion-icon
                                        name="pricetags-outline"
                                    ></ion-icon>
                                </div>
                                <h3 class="admin-category-title">Categorias</h3>
                                <p class="admin-category-description">
                                    Gerencie as categorias dos produtos
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico -->
            <div class="admin-chart-section">
                <div class="admin-card">
                    <div class="admin-card-header">
                        <h2 class="admin-card-title">
                            Pedidos nos últimos 30 dias
                        </h2>
                        <div class="admin-chart-controls">
                            <label class="admin-chart-label">Período:</label>
                            <select class="admin-chart-select">
                                <option>30 dias</option>
                            </select>
                        </div>
                    </div>
                    <div class="admin-card-body">
                        <div class="admin-chart-content">
                            <!-- Canvas do gráfico -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

### **Formulários Admin**

```html
<div class="admin-form-group">
    <label class="admin-form-label">Campo</label>
    <input type="text" class="admin-form-input" />
</div>
```

### **Botões Admin**

```html
<button class="admin-btn admin-btn-primary">Botão Primário</button>
<button class="admin-btn admin-btn-secondary">Botão Secundário</button>
<button class="admin-icon-btn">
    <ion-icon name="edit-outline"></ion-icon>
</button>
```

### **Tabelas Admin**

```html
<div class="admin-table-container">
    <table class="admin-table">
        <!-- Conteúdo da tabela -->
    </table>
</div>
```

### **Produtos Admin**

```html
<img src="..." class="admin-product-image" />
<div class="admin-product-name">Nome do Produto</div>
<div class="admin-product-sku">SKU: ABC123</div>
<span class="admin-product-price">€99,99</span>
<span class="admin-product-stock good">15</span>
<span class="admin-product-status active">Ativo</span>
<div class="admin-product-actions">
    <button class="admin-icon-btn">
        <ion-icon name="eye-outline"></ion-icon>
    </button>
</div>
```

### **Filtros Admin**

```html
<form class="admin-filters">
    <div class="admin-filters-main">
        <div class="admin-form-group">
            <label class="admin-form-label">Buscar</label>
            <input type="text" class="admin-form-input" />
        </div>
    </div>
    <div class="admin-filters-actions">
        <button class="admin-btn admin-btn-primary">Pesquisar</button>
    </div>
</form>
```

### **Paginação Admin**

```html
<div class="admin-pagination">
    <div class="admin-pagination-info">Mostrando 1 até 10 de 50 resultados</div>
    <ul class="pagination">
        <!-- Links de paginação -->
    </ul>
</div>
```

## 🌙 **Dark Mode**

O sistema suporta dark mode através da classe `.dark` no elemento `<html>`:

```html
<html class="dark">
    <!-- Conteúdo da página -->
</html>
```

### **Ativação via JavaScript**

```javascript
// Ativar dark mode
document.documentElement.classList.add("dark");

// Desativar dark mode
document.documentElement.classList.remove("dark");

// Alternar dark mode
document.documentElement.classList.toggle("dark");
```

## 📱 **Responsividade**

O sistema é totalmente responsivo e inclui breakpoints para:

-   **Mobile**: `max-width: 768px`
-   **Tablet**: `max-width: 1024px`
-   **Desktop**: `min-width: 1025px`

### **Classes Responsivas**

```css
/* Exemplo de media query */
@media (max-width: 768px) {
    .admin-filters-main {
        grid-template-columns: 1fr;
    }

    .admin-filters-actions {
        flex-direction: column;
    }
}
```

## 🔧 **Como Usar**

### **1. Importar o Sistema**

O sistema já está importado no `app.css`:

```css
@import "variables.css";
@import "base.css";
@import "components.css";
@import "admin.css";
```

### **2. Usar as Classes**

```html
<!-- Exemplo de página admin -->
<div class="admin-page">
    <div class="admin-content">
        <div class="admin-container">
            <div class="admin-card">
                <div class="admin-card-header">
                    <nav class="admin-breadcrumb">
                        <a href="/">Home</a>
                        <ion-icon
                            name="chevron-forward-outline"
                            class="separator"
                        ></ion-icon>
                        <span>Produtos</span>
                    </nav>
                    <h1 class="admin-card-title">Gerenciar Produtos</h1>
                </div>
                <div class="admin-card-body">
                    <!-- Conteúdo -->
                </div>
            </div>
        </div>
    </div>
</div>
```

### **3. Personalizar Variáveis**

Para personalizar cores ou espaçamentos, edite o arquivo `variables.css`:

```css
:root {
    --primary: #your-color;
    --space-lg: 1.5rem;
}
```

## 🚀 **Benefícios do Sistema**

### **✅ Consistência**

-   Todas as páginas usam o mesmo sistema de design
-   Cores e espaçamentos padronizados
-   Componentes reutilizáveis

### **✅ Manutenibilidade**

-   Mudanças centralizadas nas variáveis
-   Código organizado e modular
-   Fácil de estender e modificar

### **✅ Performance**

-   CSS otimizado e minificado
-   Menos repetição de código
-   Carregamento mais rápido

### **✅ Escalabilidade**

-   Fácil adicionar novos componentes
-   Sistema preparado para crescimento
-   Padrões bem definidos

### **✅ Acessibilidade**

-   Contraste adequado
-   Estados de foco visíveis
-   Suporte a leitores de tela

## 📝 **Convenções de Nomenclatura**

### **Classes de Componentes**

-   `btn-*` - Botões
-   `form-*` - Elementos de formulário
-   `card-*` - Cards
-   `badge-*` - Badges
-   `alert-*` - Alertas
-   `admin-*` - Componentes específicos do admin

### **Variáveis CSS**

-   `--primary` - Cores principais
-   `--bg-*` - Cores de fundo
-   `--text-*` - Cores de texto
-   `--space-*` - Espaçamentos
-   `--font-*` - Tipografia

## 🔄 **Migração de Código Antigo**

### **Antes (Classes Antigas)**

```html
<div class="admin-products-page">
    <div class="admin-products-container">
        <div class="admin-products-card">
            <div class="admin-products-header">
                <h1 class="admin-products-title">Título</h1>
            </div>
        </div>
    </div>
</div>
```

### **Depois (Novas Classes)**

```html
<div class="admin-page">
    <div class="admin-content">
        <div class="admin-container">
            <div class="admin-card">
                <div class="admin-card-header">
                    <h1 class="admin-card-title">Título</h1>
                </div>
            </div>
        </div>
    </div>
</div>
```

## 🎯 **Próximos Passos**

1. **Migrar outras páginas admin** para o novo sistema
2. **Migrar páginas da loja** gradualmente
3. **Adicionar novos componentes** conforme necessário
4. **Otimizar performance** do CSS
5. **Criar documentação interativa** (Storybook)

---

**Sistema CSS Unificado v1.0** - Criado para ser escalável, consistente e profissional.
