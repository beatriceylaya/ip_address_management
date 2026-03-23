# IP Address Management Project

This project is a full-stack application designed to manage IP addresses, featuring a Laravel backend, a Vue.js frontend, and a MySQL database, all containerized using Docker. This also includes audit logging system for tracking IP lifecycle and user activity (login/logout).

---
### Project Architecture
- Backend: Laravel 12
- Frontend: Vue 3
- Database: MySQL 8.0

### Getting Started
1. **Prerequisites**
- Docker Desktop
- Docker Compose

2. **Installation & Setup**

Follow these steps to get the environment up and running:

- Step 1: Clone the repository
```bash
git clone https://github.com/beatriceylaya/ip_address_management.git
cd ip_address_management
```

- Step 2: Environment Config

Copy .env file for both backend and frontend
```bash
cp backend/.env.example backend/.env
cp frontend/.env.example frontend/.env
```

- Step 3: Build and Start Containers
```bash
docker compose up -d --build
```

- Step 4: Backend Initialization
```bash
# Install PHP dependencies
docker exec -it backend composer install

# Generate application key
docker exec -it backend php artisan key:generate

# Run migrations and seed roles
docker exec -it backend php artisan migrate --seed

# Run UserSeeder to populate user and ip address data
docker exec -it backend php artisan db:seed --class=UserSeeder
```

### Accessing the Application

| Service | URL | Port |
| ----------- | ----------- | ----------- |
| Frontend (Vue) | ```http://localhost:5173``` | 5173 |
| Backend (API) | ```http://localhost:8000``` | 8000 |
| MySQL | ```localhost``` | 3306 |