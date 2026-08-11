# Carola Car Rental

Laravel 13 car rental app. Public pages (home, car listing, car details) are
Blade views built on the supplied Carola HTML template; the admin panel (not
yet built) will use TailAdmin/Tailwind.

## Setup

```bash
composer install
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
```

## Admin account

`migrate` alone (no `--seed` needed) creates an admin account —
`admin@example.com`, password from the `ADMIN_PASSWORD` env var (defaults to
`password123`). This runs as a migration, not a seeder, specifically so it
exists on any host that runs migrations automatically but not seeders (e.g.
Laravel Cloud) — **set `ADMIN_PASSWORD` to a real value in your deployment's
environment variables before going live**, since the default is a published
dev password.

## Development seed data

`php artisan db:seed` (or `migrate --seed`) additionally creates optional
demo content — safe to skip entirely on a real deployment:

- 12 cars — 7 modeled on the supplied template (Toyota Land Cruiser, Nissan
  GTR Turbo, Mitsubishi Portan, Jeep Wagner, BMW 740L Series, BMW M5
  Competition, Tesla Model 3 Roadstar) plus 5 randomly generated.
- 8 sample bookings, covering every `BookingStatus` at least once.
- 6 users: 1 named test user, 5 generated customers.

> **Development credentials only — never use these in a deployed environment.**

| Role  | Email             | Password    |
|-------|-------------------|-------------|
| User  | test@example.com  | password    |

On a platform that only runs migrations automatically (not seeders), trigger
this once manually after your first deploy — e.g. via Laravel Cloud's
command runner: `php artisan db:seed --force`.

## Testing & code style

```bash
php artisan test
./vendor/bin/pint
```
