# Carola Rental

A car rental web app built in Laravel 13 as a 24-hour exercise to test my Laravel skills. Customers search for cars and book them. Admins manage the fleet, bookings, locations and insurance pricing.

## Features

**Customers**
- Browse cars and filter them by type, transmission, fuel, seats and price
- Search by pickup and return dates. Cars already booked for those dates are hidden
- Book a car with pickup and return locations and an insurance package (Standard, Performance or Super)
- View and cancel your own bookings, or change the insurance on an existing booking

**Admins**
- Dashboard showing fleet size, pending bookings, confirmed revenue and the latest bookings
- Create, edit and delete cars and locations (a location used by a booking can't be deleted)
- Confirm, reject, complete or cancel bookings. Only valid status changes are allowed (e.g. a rejected booking can't be confirmed later)
- Edit the daily insurance rates

## Implementation notes

- **The server sets the price.** The total is always computed on the server from the car's daily rate plus insurance, and any price sent by the browser is ignored. The rates are saved with each booking, so a later price change doesn't affect existing bookings.
- **Billing in 24-hour blocks.** Any partial day is rounded up to a full day.
- **No double bookings.** A new booking is rejected if it overlaps a pending or confirmed booking for the same car. Cancelled and rejected bookings don't block dates. The check and the insert run in one transaction, and the car row is locked inside it so two simultaneous requests can't both pass the check. On SQLite, which allows one writer at a time, the lock does nothing and isn't needed.
- **Access control.** Admin routes are behind an authorization gate, and customers can only act on their own bookings. The booking endpoint is rate-limited to 5 requests per minute.
- **Validation.** All input goes through Form Request classes. Statuses, car types and insurance packages are PHP enums.
- **Admin account.** A migration creates `admin@example.com`, so it exists on hosts that run only migrations. In production the migration fails unless `ADMIN_PASSWORD` is set to something other than the dev default.

## Tech stack

Laravel 13 · PHP 8.3 · SQLite · Blade · Livewire/Flux (account settings) · Laravel Fortify (authentication) · Pest · GitHub Actions

The public pages use a commercial HTML template (Carola), and the admin panel uses Tailwind. The template can't be redistributed, so its CSS, JavaScript and images aren't in this repo, and the public pages render unstyled without them. The visual design is not my work. My work is the backend, the data model, the admin panel and the tests.

## Run locally

```bash
composer setup          # install, .env, app key, migrate, build assets
php artisan db:seed     # optional demo cars, bookings and users
php artisan serve
```

Open http://localhost:8000.

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@example.com | value of `ADMIN_PASSWORD` in `.env` |
| Customer | test@example.com | password |

These are development credentials only.

## Tests

```bash
php artisan test
```

Pest feature tests cover pricing, rounding up to full days, overlapping and cancelled bookings, invalid date ranges, admin access, car and location management, and car browsing. They run on every push to `main` through GitHub Actions.
