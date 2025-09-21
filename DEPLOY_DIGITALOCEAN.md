# 🚀 Deploy da Loja no DigitalOcean

Este guia completo irá te ajudar a fazer o deploy do projeto Loja no DigitalOcean.

## 📋 Pré-requisitos

- Conta no DigitalOcean
- Domínio configurado (opcional, mas recomendado)
- Acesso SSH ao servidor
- Projeto Loja local funcionando

## 🖥️ 1. Criar Droplet no DigitalOcean

### 1.1 Configurações Recomendadas

- **Imagem**: Ubuntu 22.04 LTS
- **Plano**: Basic ($12/mês - 2GB RAM, 1 CPU, 50GB SSD)
- **Região**: Escolha a mais próxima dos seus usuários
- **Autenticação**: SSH Key (recomendado) ou Password

### 1.2 Configurações Adicionais

- ✅ **Monitoring**: Ativado
- ✅ **Backups**: Ativado (recomendado)
- ✅ **IPv6**: Ativado
- ✅ **User Data**: Deixe em branco

## 🔧 2. Configuração Inicial do Servidor

### 2.1 Conectar ao Servidor

```bash
ssh root@SEU_IP_DO_SERVIDOR
```

### 2.2 Criar Usuário Não-Root

```bash
# Criar usuário
adduser loja

# Adicionar ao grupo sudo
usermod -aG sudo loja

# Configurar SSH para o novo usuário
cp -r ~/.ssh /home/loja/
chown -R loja:loja /home/loja/.ssh

# Sair e conectar com o novo usuário
exit
ssh loja@SEU_IP_DO_SERVIDOR
```

### 2.3 Atualizar Sistema

```bash
sudo apt update && sudo apt upgrade -y
```

## 📦 3. Upload dos Arquivos do Projeto

### 3.1 Método 1: Git Clone (Recomendado)

```bash
# Instalar Git
sudo apt install git -y

# Clonar repositório
cd /var/www
sudo git clone https://github.com/SEU_USUARIO/loja.git
sudo chown -R www-data:www-data loja
```

### 3.2 Método 2: Upload via SCP

```bash
# No seu computador local
scp -r ./loja loja@SEU_IP:/var/www/
```

### 3.3 Método 3: Upload via SFTP

Use um cliente como FileZilla para fazer upload dos arquivos.

## ⚙️ 4. Executar Script de Deploy

### 4.1 Tornar Script Executável

```bash
cd /var/www/skyfashion
chmod +x deploy.sh
```

### 4.2 Executar Deploy

```bash
./deploy.sh
```

**⚠️ IMPORTANTE**: Durante a execução, você será solicitado a:
- Configurar senha do MySQL
- Inserir senha forte para o usuário do banco
- Configurar domínio no Nginx

## 🔐 5. Configuração do Ambiente

### 5.1 Configurar Arquivo .env

```bash
cd /var/www/skyfashion
sudo cp env.example .env
sudo nano .env
```

**Configurações importantes no .env:**

```env
APP_NAME="SkyFashion"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://seudominio.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=skyfashion
DB_USERNAME=skyfashion_user
DB_PASSWORD=SUA_SENHA_FORTE

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@seudominio.com"
MAIL_FROM_NAME="SkyFashion"
```

### 5.2 Instalar Dependências

```bash
# Dependências PHP
composer install --no-dev --optimize-autoloader

# Dependências Node.js
npm install
npm run build
```

### 5.3 Configurar Laravel

```bash
# Gerar chave da aplicação
php artisan key:generate

# Executar migrações
php artisan migrate --force

# Criar cache de configuração
php artisan config:cache

# Criar cache de rotas
php artisan route:cache

# Criar cache de views
php artisan view:cache

# Configurar permissões
sudo chown -R www-data:www-data /var/www/skyfashion
sudo chmod -R 755 /var/www/skyfashion
sudo chmod -R 775 /var/www/skyfashion/storage
sudo chmod -R 775 /var/www/skyfashion/bootstrap/cache
```

## 🌐 6. Configuração do Domínio e SSL

### 6.1 Configurar DNS

No seu provedor de domínio, configure:

```
A     @           SEU_IP_DO_SERVIDOR
A     www         SEU_IP_DO_SERVIDOR
```

### 6.2 Instalar Certificado SSL

```bash
# Instalar Certbot
sudo apt install certbot python3-certbot-nginx -y

# Obter certificado SSL
sudo certbot --nginx -d seudominio.com -d www.seudominio.com

# Testar renovação automática
sudo certbot renew --dry-run
```

## 🔄 7. Configurar Supervisor

### 7.1 Aplicar Configuração

```bash
# Copiar configuração
sudo cp supervisor-skyfashion.conf /etc/supervisor/conf.d/skyfashion-worker.conf

# Recarregar Supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start skyfashion-worker:*
sudo supervisorctl start skyfashion-schedule
```

### 7.2 Verificar Status

```bash
sudo supervisorctl status
```

## 📊 8. Monitoramento e Logs

### 8.1 Verificar Logs

```bash
# Logs do Nginx
sudo tail -f /var/log/nginx/skyfashion_error.log

# Logs do Laravel
tail -f /var/www/skyfashion/storage/logs/laravel.log

# Logs do Worker
tail -f /var/www/skyfashion/storage/logs/worker.log
```

### 8.2 Monitorar Recursos

```bash
# Uso de CPU e Memória
htop

# Uso de Disco
df -h

# Status dos Serviços
sudo systemctl status nginx
sudo systemctl status mysql
sudo systemctl status php8.4-fpm
sudo systemctl status redis-server
```

## 🔧 9. Comandos Úteis de Manutenção

### 9.1 Atualizar Aplicação

```bash
cd /var/www/skyfashion

# Fazer backup
sudo cp -r /var/www/skyfashion /var/backups/skyfashion-$(date +%Y%m%d)

# Atualizar código
git pull origin main

# Atualizar dependências
composer install --no-dev --optimize-autoloader
npm install && npm run build

# Limpar caches
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Recriar caches
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Reiniciar workers
sudo supervisorctl restart skyfashion-worker:*
```

### 9.2 Backup do Banco de Dados

```bash
# Backup manual
mysqldump -u skyfashion_user -p skyfashion > backup_$(date +%Y%m%d).sql

# Restaurar backup
mysql -u skyfashion_user -p skyfashion < backup_20240101.sql
```

## 🚨 10. Solução de Problemas

### 10.1 Erro 500

```bash
# Verificar logs
sudo tail -f /var/log/nginx/skyfashion_error.log
tail -f /var/www/skyfashion/storage/logs/laravel.log

# Verificar permissões
sudo chown -R www-data:www-data /var/www/skyfashion
sudo chmod -R 775 /var/www/skyfashion/storage
```

### 10.2 Problemas de Conexão com Banco

```bash
# Verificar status do MySQL
sudo systemctl status mysql

# Testar conexão
mysql -u skyfashion_user -p skyfashion
```

### 10.3 Workers Não Funcionando

```bash
# Verificar status
sudo supervisorctl status

# Reiniciar workers
sudo supervisorctl restart skyfashion-worker:*

# Ver logs
tail -f /var/www/skyfashion/storage/logs/worker.log
```

## 📈 11. Otimizações de Performance

### 11.1 Configurar OPcache

```bash
sudo nano /etc/php/8.2/fpm/conf.d/10-opcache.ini
```

Adicione:

```ini
opcache.enable=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=8
opcache.max_accelerated_files=4000
opcache.revalidate_freq=2
opcache.fast_shutdown=1
```

### 11.2 Configurar Redis para Cache

No arquivo `.env`:

```env
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

## 🔒 12. Segurança

### 12.1 Configurar Firewall

```bash
# Instalar UFW
sudo apt install ufw -y

# Configurar regras
sudo ufw default deny incoming
sudo ufw default allow outgoing
sudo ufw allow ssh
sudo ufw allow 'Nginx Full'

# Ativar firewall
sudo ufw enable
```

### 12.2 Configurar Fail2Ban

```bash
# Instalar Fail2Ban
sudo apt install fail2ban -y

# Configurar
sudo cp /etc/fail2ban/jail.conf /etc/fail2ban/jail.local
sudo systemctl enable fail2ban
sudo systemctl start fail2ban
```

## ✅ 13. Checklist Final

- [ ] Servidor criado e configurado
- [ ] Projeto enviado para o servidor
- [ ] Script de deploy executado
- [ ] Arquivo .env configurado
- [ ] Dependências instaladas
- [ ] Banco de dados configurado
- [ ] Migrações executadas
- [ ] Nginx configurado
- [ ] SSL instalado
- [ ] Supervisor configurado
- [ ] Workers funcionando
- [ ] Domínio apontando para o servidor
- [ ] Site acessível via HTTPS
- [ ] Logs sendo gerados corretamente
- [ ] Backup configurado

## 🆘 Suporte

Se encontrar problemas durante o deploy:

1. Verifique os logs de erro
2. Confirme se todos os serviços estão rodando
3. Verifique as permissões dos arquivos
4. Teste a conectividade com o banco de dados
5. Verifique se o domínio está apontando corretamente

## 📞 Contato

Para dúvidas sobre este processo de deploy, consulte a documentação do Laravel ou entre em contato com a equipe de desenvolvimento.
