ABC Bank MVP — Laravel 12 + Vue 3

Overview
- Secure JSON API backend (Laravel 12 + Sanctum)
- Vue 3 SPA frontend (Vite, Pinia, Vue Router, Axios)
- Core features: register/login, balance, deposit, transfer (atomic with row locks)
- DB: MySQL (Docker compose optional)

Tech Stack
- Backend: `PHP 8.2+`, `Laravel 12`, `Sanctum`, `MySQL`
- Frontend: `Node 20+`, `Vite`, `Vue 3`, `Pinia`, `Vue Router`, `Axios`, `Pico.css`
- Tests: `PHPUnit` (backend), `Vitest` with `happy-dom` (frontend)

Repository Layout
- `backend/` — Laravel app
- `frontend/` — Vue app (Vite)
- `docker/docker-compose.yml` — MySQL + phpMyAdmin (optional)

Prerequisites
- PHP 8.2+, Composer
- Node 20.x (npm)
- MySQL running locally or via Docker Desktop (optional)

Quick Start
1) Start MySQL (pick one)
- Docker Desktop: run from repo root:
  - `docker compose -f docker\docker-compose.yml up -d`
- Or use a local MySQL service and note host/port/credentials.

2) Backend setup
- Copy `backend/.env.example` to `backend/.env` and set:
  - `DB_CONNECTION=mysql`
  - `DB_HOST=127.0.0.1`
  - `DB_PORT=3306`
  - `DB_DATABASE=bank`
  - `DB_USERNAME=bank`
  - `DB_PASSWORD=bank`
- Install deps and migrate:
  - `cd backend`
  - `composer install`
  - `php artisan key:generate`
  - `php artisan migrate`
- Run API:
  - `php artisan serve --host=127.0.0.1 --port=8000`

3) Frontend setup
- Create `frontend/.env`:
  - `VITE_API_BASE_URL=http://127.0.0.1:8000/api`
- Install and run:
  - `cd frontend`
  - `npm install`
  - `npm run dev`
- Open `http://localhost:5173/`

API Endpoints (auth: Bearer token)
- Auth
  - `POST /api/auth/register` — `{ name, email, password, password_confirmation }` → `{ token }`
  - `POST /api/auth/login` — `{ email, password }` → `{ token }`
  - `POST /api/auth/logout`
  - `GET /api/auth/user`
- Account
  - `GET /api/account` → `{ account_number, balance }`
  - `GET /api/accounts/transactions` → recent transactions
- Money
  - `POST /api/accounts/deposit` — `{ amount }`
  - `POST /api/accounts/transfer` — `{ to_account_number, amount }`

Data Model
- `users` — standard auth fields
- `accounts` — `user_id`, `account_number` (unique), `balance (DECIMAL 18,2)`
- `transactions` — `from_account_id (nullable)`, `to_account_id`, `amount (DECIMAL 18,2)`, `type ('deposit'|'transfer')`

Concurrency & Money Integrity
- Transfers use `DB::transaction` + `lockForUpdate()` and deterministic lock ordering to avoid deadlocks.
- Balances use decimal and BCMath (`bcadd`, `bcsub`, `bccomp`) to avoid floating point errors.

Testing
- Backend:
  - `cd backend`
  - `php artisan test`
- Frontend:
  - `cd frontend`
  - `npm run test`

Troubleshooting
- Frontend cannot reach backend (ERR_CONNECTION_REFUSED)
  - Ensure `php artisan serve` is running at `127.0.0.1:8000`
  - Check `frontend/.env` has `VITE_API_BASE_URL=http://127.0.0.1:8000/api`
  - Restart `npm run dev` after changing `.env`
- Docker errors on Windows
  - Start Docker Desktop first and wait for it to be running
  - If port 3306 in use, change compose mapping to `3307:3306` and set `DB_PORT=3307`
- Vite alias or .vue resolution errors
  - Ensure `frontend/vite.config.ts` exists with Vue plugin and `@` alias
  - Restart dev server after config changes
- Node engine warnings
  - Vite may warn if Node < required minor; Node 20.x works for dev here

Security Notes (MVP)
- Sanctum Bearer tokens used for SPA.
- Passwords hashed by Laravel.
- Use HTTPS, secure cookies, stricter CORS, and secret rotation in production.

Next Steps (Optional)
- Add pagination to transactions
- Add rate limiting and audit logs
- CI (GitHub Actions) for test/build
- Deploy: API to `EC2/Forge/Vapor`; SPA to `S3 + CloudFront`

License
- For internal case study/demo purposes.


