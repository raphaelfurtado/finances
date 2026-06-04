# Deploy Automático - Financas App

Este projeto usa GitHub Actions para deploy automático quando você faz push para a branch `main`.

## Configuração Inicial

### 1. Adicionar Secrets no GitHub

Vá em **Settings → Secrets and variables → Actions** no seu repositório e adicione:

- `SERVER_HOST`: IP ou domínio do servidor (ex: `websiteponto.com.br`)
- `SERVER_USER`: Usuário SSH do servidor (ex: `u123456789`)
- `SERVER_SSH_KEY`: Chave privada SSH (conteúdo completo do arquivo `id_rsa`)

### 2. Estrutura no Servidor

O deploy será feito em:
```
/home/{SERVER_USER}/finances.websiteponto.com.br/server-dir/finances/
```

### 3. Configurar servidor

Certifique-se de que o servidor tem:
- PHP 8.3+
- Composer
- MySQL
- Permissões corretas nas pastas

### 4. Variáveis de Ambiente no Servidor

Crie o arquivo `.env` no servidor em:
```
/home/{SERVER_USER}/finances.websiteponto.com.br/server-dir/finances/.env
```

Com as configurações de produção:
```env
APP_NAME="Finanças App"
APP_ENV=production
APP_KEY=base64:...  # Gere com: php artisan key:generate
APP_DEBUG=false
APP_URL=https://finances.websiteponto.com.br

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=u123456789_finances
DB_USERNAME=u123456789_finances
DB_PASSWORD=sua_senha_aqui

# ... outras configurações
```

## Como Fazer Deploy

1. Faça suas alterações localmente
2. **Compile os assets** (CSS do Tailwind + libs JS). O deploy via FTP apenas
   copia arquivos — ele NÃO roda build no servidor. Por isso o resultado em
   `public/css/` e `public/js/` é versionado no git e precisa estar atualizado:
   ```bash
   npm install   # apenas na primeira vez
   npm run build # gera public/css/app.css e copia lucide/chart para public/js
   ```
   > Sempre que você adicionar/alterar classes do Tailwind nos arquivos `.blade.php`,
   > rode `npm run build` de novo antes do commit, senão o estilo novo não vai para
   > produção. Durante o desenvolvimento, `npm run dev` recompila o CSS automaticamente.
3. Commit:
   ```bash
   git add .
   git commit -m "Descrição das mudanças"
   ```
4. Push para a branch main:
   ```bash
   git push origin main
   ```

O GitHub Actions vai:
- ✅ Instalar dependências do Composer
- ✅ Copiar arquivos para o servidor
- ✅ Executar migrations
- ✅ Limpar caches
- ✅ Ajustar permissões

## Verificar Status do Deploy

Vá em **Actions** no GitHub para ver o progresso do deploy.

## Comandos Úteis no Servidor (SSH)

```bash
# Acessar pasta do projeto
cd /home/u123456789/finances.websiteponto.com.br/server-dir/finances/

# Ver logs
tail -f storage/logs/laravel.log

# Limpar cache manualmente
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Executar migrations
php artisan migrate

# Popular banco de dados
php artisan db:seed --class=AdminSeeder
```

## Estrutura de Pastas no Servidor

```
/home/u123456789/
└── finances.websiteponto.com.br/
    └── server-dir/
        └── finances/          ← Deploy aqui
            ├── app/
            ├── bootstrap/
            ├── config/
            ├── database/
            ├── public/         ← Document root do domínio
            ├── resources/
            ├── routes/
            ├── storage/
            ├── .env
            └── composer.json
```

## Configuração do Apache/Nginx

O document root deve apontar para:
```
/home/u123456789/finances.websiteponto.com.br/server-dir/finances/public
```

## Credenciais de Acesso (Produção)

Após o primeiro deploy e seed:

- **Admin:** admin@financas-app.com / admin123
- **URL:** https://finances.websiteponto.com.br

**⚠️ IMPORTANTE:** Mude a senha do admin após o primeiro acesso!

## Problemas Comuns

### Erro de permissão
```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Erro de .env não encontrado
```bash
# Copie o .env.example e configure
cp .env.example .env
php artisan key:generate
```

### Erro de banco de dados
- Verifique se o banco existe
- Confirme usuário e senha no .env
- Execute: `php artisan migrate`
