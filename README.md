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
git clone https://github.com/seu-usuario/travel-order-manager.git
cd travel-order-manager
```

### 🔧 2. Criar o Arquivo .env com as Configurações do Banco de Dados
```bash
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:zm1lvmYOqz9h6JeE9vZyUfp/o/l5/leYR1tqws/ocTw=
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1 
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=1234
```

### 🔧 3. Entrar na Pasta Backend
```bash
cd backend
```

### 🔧 4. Subir os Containers 
```bash
docker compose up -d
```

### 🔧 5. Acessar o navegador na porta 5173 
