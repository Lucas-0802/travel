# 🌍 Travel Order Manager ✈️

Bem-vindo ao **Travel Order Manager**, um sistema Full Stack para **gestão de pedidos de viagens corporativas**.  
Desenvolvido com **Laravel (API REST) & Vue.js (Frontend Interativo)**, o sistema permite que usuários realizem solicitações de viagens, filtrem pedidos e recebam notificações sobre aprovações e cancelamentos. 🎯

---

## 📦 Tecnologias Utilizadas
- 🔹 **Laravel 10** - Backend API  
- 🔹 **Vue.js 3** - Frontend SPA  
- 🔹 **MySQL** - Banco de Dados  
- 🔹 **Docker & Docker Compose** - Ambientes isolados  
- 🔹 **JWT** - Autenticação segura  
- 🔹 **Bootstrap & TailwindCSS** - UI responsiva  
- 🔹 **PHPUnit** - Testes automatizados  

---

## 🚀 Como Rodar o Projeto

### 🔧 1. Clonar o Repositório
```bash
git clone https://github.com/Lucas-0802/travel.git
cd travel
```

### 🔧 2. Entrar na Pasta Backend
```bash
cd backend
```

### 🔧 3. Criar o Arquivo .env com as Configurações do Banco de Dados
```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:zm1lvmYOqz9h6JeE9vZyUfp/o/l5/leYR1tqws/ocTw=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1 
DB_PORT=3306
DB_DATABASE=corporate_travel
DB_USERNAME=admin
DB_PASSWORD=1234
```

### 🔧 4. Subir os Containers 
```bash
docker compose up -d
```

### 🔧 5. Acessar o Navegador na Porta 5173 


### 🔧 6. A aplicação cria o usuário Admin automaticamente

```bash
username: admin@admin.com
password: admin123
```

### 🔧 7. Rodando os Testes

```bash
docker compose exec laravel-1 php artisan test --filter=TravelOrdersServiceTest
```
