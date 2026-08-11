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

## Development seed data

`php artisan db:seed` (or `migrate --seed`) creates:

- 12 cars — 7 modeled on the supplied template (Toyota Land Cruiser, Nissan
  GTR Turbo, Mitsubishi Portan, Jeep Wagner, BMW 740L Series, BMW M5
  Competition, Tesla Model 3 Roadstar) plus 5 randomly generated.
- 8 sample bookings, covering every `BookingStatus` at least once.
- 7 users: 1 admin, 1 named test user, 5 generated customers.

> **Development credentials only — never use these in a deployed environment.**

| Role  | Email             | Password    |
|-------|-------------------|-------------|
| Admin | admin@example.com | password123 |
| User  | test@example.com  | password    |

## Testing & code style

```bash
php artisan test
./vendor/bin/pint
```
