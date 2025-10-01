## Mihwar Alabtikar (Multi-tenant Project/Task Management API)

A Laravel 12, PostgreSQL, Sanctum-powered multi-tenant API using `stancl/tenancy` with role-based access for Projects and Tasks.

### Features
- Multi-tenancy with domain-based identification (stancl/tenancy v3)
- Tenant-aware databases, cache, filesystem
- Authentication via Laravel Sanctum (Bearer tokens)
- Roles & permissions (spatie/laravel-permission)
  - Viewer: read-only
  - Admin: create/edit/delete
- Projects: CRUD, filtering, pagination, relations (owner, users, tasks)
- Tasks: CRUD, filtering, pagination, relations (project, creator)
- Invitations (scaffolded)
- Clean architecture: Controllers delegate to Services

### Tech Stack
- PHP 8.2+, Laravel 12
- PostgreSQL 15 (Docker)
- Sanctum, Spatie Permission, Stancl Tenancy
- Pest for testing

## Getting Started

### 1) Clone & Install
```bash
git clone <repo-url>
cd MihwarAlabtikar
composer install
cp .env.example .env
php artisan key:generate
```

### 2) Configure .env
Set the app domain used for tenant domains:
```
APP_NAME=MihwarAlabtikar
APP_URL=http://localhost
APP_DOMAIN=localhost
```

Database (Docker defaults below):
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=mihwaralabtikar
DB_USERNAME=admin
DB_PASSWORD=admin123
```

Sanctum:
```
SANCTUM_TOKEN=api-token
```

### 3) Start PostgreSQL with Docker
`docker-compose.yml` provides Postgres and pgAdmin.
```bash
docker compose up -d
```
Services:
- Postgres: 127.0.0.1:5432 (admin / admin123)
- pgAdmin: http://localhost:5050 (admin@admin.com / admin123)

If the database `mihwaralabtikar` doesn’t exist, Docker will create it on first start from `POSTGRES_DB`.

### 4) Run Migrations & Seeders
Central (app) database:
```bash
php artisan migrate
php artisan db:seed
```

Tenants:
```bash
php artisan tenants:migrate
php artisan tenants:seed
```

Note: Tenants are `Organization` models. When registering a user, an organization is created and a domain is added as `<domain_name>.${APP_DOMAIN}`.

## API Quick Start

Auth (tenant routes are domain-scoped):
- POST `http://{tenant-domain}/api/v1/auth/signup`
- POST `http://{tenant-domain}/api/v1/auth/signin`
- GET `http://{tenant-domain}/api/v1/auth/signout` (Bearer token)

Projects (tenant, Sanctum, roles enforced):
- GET/SHOW allowed for Viewer/Admin
- POST/PUT/PATCH/DELETE allowed for Admin only

Tasks (tenant, Sanctum, roles enforced):
- GET/SHOW allowed for Viewer/Admin
- POST/PUT/PATCH/DELETE allowed for Admin only

## API Collection

A ready-to-use **Postman Collection** is provided to quickly test all APIs.

👉 [Download MihwarAlabtikar.postman_collection.json](https://raw.githubusercontent.com/KareemHussen/MihwarAlabtikar/main/MihwarAlabtikar.postman_collection.json)

## Testing

Pest is configured. Ensure a separate testing database exists or set test env via `phpunit.xml` env vars.

Run tests:
```bash
vendor/bin/pest
```

Optional `.env.testing` (if you prefer file-based test config):
```
APP_ENV=testing
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=mihwaralabtikar_testing
DB_USERNAME=admin
DB_PASSWORD=admin123
```
Create the testing DB via psql/pgAdmin.

## Development Notes
- Tenancy middleware is registered in `App\Providers\TenancyServiceProvider` and routes in `routes/tenant.php`.
- Services: `App\Services\AuthService`, `ProjectService`, `TaskService`.
- Policies are present for fine-grained authorization; routes also protect by roles.

## Troubleshooting
- 401 on tenant routes: ensure Authorization `Bearer <token>` and you’re calling the tenant domain (not central domain).
- 403 on write ops: verify the user has `Admin` role for the tenant.
- Domain not found: ensure tenant `domains` record exists and matches the host.

## License
MIT
