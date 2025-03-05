# 🌍 Travel Order Manager ✈️  
**Sistema Full Stack para gestão de pedidos de viagens corporativas!**  

Desenvolvido com **Laravel (API REST) & Vue.js (Frontend SPA)**, este sistema permite que usuários realizem solicitações de viagens, filtrem pedidos e recebam notificações sobre aprovações e cancelamentos. 🚀  

🔹 **Gerencie pedidos de viagem com eficiência**  
🔹 **Acompanhe aprovações e cancelamentos em tempo real**  
🔹 **Interface intuitiva e responsiva**  

---

## 📦 Tecnologias Utilizadas  

🛠 **Backend:** Laravel 10 (API REST)  
🎨 **Frontend:** Vue.js 3 (SPA - Aplicação de Página Única)  
🗄 **Banco de Dados:** MySQL  
🐳 **Containers:** Docker & Docker Compose  
🔐 **Autenticação:** JWT (JSON Web Token)  
💎 **UI:** Bootstrap  
✅ **Testes:** PHPUnit  

---

## 🚀 Como Rodar o Projeto  

### 1️⃣ Clonar o Repositório  
```bash
git clone https://github.com/Lucas-0802/travel.git
cd travel
```

### 2️⃣ Configurar o Backend  
```bash
cd backend
```

Criar o arquivo **.env** e configurar o banco de dados:  

```ini
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

### 3️⃣ Subir os Containers 🚢  
```bash
docker compose up -d
```

### 4️⃣ Acessar o Navegador  
Abra **http://localhost:5173** para visualizar a aplicação.  

### 5️⃣ Usuário Admin Padrão  
🔑 **Login Automático Criado**:  
```bash
Username: admin@admin.com
Password: admin123
```

### 6️⃣ Rodando os Testes ⚙️  
```bash
docker compose exec laravel-1 php artisan test --filter=TravelOrdersServiceTest
```

---

🎯 **Pronto! Agora você pode gerenciar viagens de forma prática e eficiente!**  
🚀 **Contribua, sugira melhorias e ajude a evoluir o Travel Order Manager!**  
