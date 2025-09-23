# 📚 Documentação do Projeto SkyFashion

## 🎯 Visão Geral

Bem-vindo à documentação completa do **SkyFashion**, uma plataforma de e-commerce desenvolvida em Laravel 12 com foco em moda e produtos de vestuário.

## 📋 Índice da Documentação

### 📖 Documentação Técnica
- **[DOCUMENTACAO_TECNICA.md](./DOCUMENTACAO_TECNICA.md)** - Documentação técnica completa do sistema
- **[ARQUITETURA_TECNICA.md](./ARQUITETURA_TECNICA.md)** - Arquitetura e estrutura do sistema
- **[DECISOES_TECNICAS.md](./DECISOES_TECNICAS.md)** - Registro de decisões técnicas tomadas

### 🎯 Documentação Funcional
- **[CASOS_DE_USO.md](./CASOS_DE_USO.md)** - Casos de uso e cenários do sistema
- **[REGRAS_NEGOCIO.md](./REGRAS_NEGOCIO.md)** - Regras de negócio e validações

### 🚀 Planejamento e Metodologia
- **[ROADMAP_AGIL.md](./ROADMAP_AGIL.md)** - Roadmap seguindo metodologia ágil
- **[ROADMAP.md](./ROADMAP.md)** - Roadmap geral do projeto

## 🏗️ Estrutura do Projeto

```
SkyFashion/
├── app/                    # Código da aplicação Laravel
├── database/              # Migrações, seeders e factories
├── resources/            # Views, CSS, JS e assets
├── routes/               # Definição de rotas
├── public/              # Arquivos públicos
├── docs/                # Documentação do projeto
└── tests/               # Testes automatizados
```

## 🔧 Tecnologias Utilizadas

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Blade Templates + CSS Customizado
- **Autenticação**: Laravel Fortify + Jetstream
- **Autorização**: Spatie Laravel Permission
- **Banco de Dados**: MySQL/PostgreSQL
- **Assets**: Vite
- **APIs**: Laravel Sanctum

## 📊 Funcionalidades Principais

### 🛒 E-commerce
- Catálogo de produtos com filtros avançados
- Sistema de carrinho inteligente
- Processo de checkout completo
- Sistema de favoritos
- Gestão de pedidos

### 🎛️ Administração
- Dashboard administrativo
- Gestão de produtos, categorias, marcas
- Controle de pedidos e status
- Relatórios e métricas

### 🔐 Segurança
- Autenticação robusta com 2FA
- Sistema de roles e permissões
- Rate limiting e middleware de segurança

## 🚀 Como Começar

1. **Leia a [DOCUMENTACAO_TECNICA.md](./DOCUMENTACAO_TECNICA.md)** para entender a arquitetura
2. **Consulte os [CASOS_DE_USO.md](./CASOS_DE_USO.md)** para entender as funcionalidades
3. **Revise o [ROADMAP_AGIL.md](./ROADMAP_AGIL.md)** para o planejamento do projeto
4. **Verifique as [DECISOES_TECNICAS.md](./DECISOES_TECNICAS.md)** para contexto técnico

## 📝 Convenções

- **Documentação**: Markdown (.md)
- **Código**: PHP (Laravel), CSS, JavaScript
- **Metodologia**: Ágil/Scrum
- **Versionamento**: Git

## 🔄 Atualizações

Esta documentação é mantida atualizada conforme o desenvolvimento do projeto. Para sugestões ou correções, consulte o [ROADMAP_AGIL.md](./ROADMAP_AGIL.md).

---

**Última atualização**: {{ date('d/m/Y H:i') }}
**Versão**: 1.0
**Projeto**: SkyFashion E-commerce
