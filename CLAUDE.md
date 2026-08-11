# Carola Rental — Claude Code Instructions

## Project Context

This is a 48-hour Laravel internship assessment based on a supplied static car-rental template.

The main goal is to demonstrate practical Laravel backend knowledge, not frontend design skill.

The application uses:

- Laravel 13
- PHP 8.3 or newer
- SQLite
- Blade
- Conventional controllers
- Eloquent ORM
- Form Request validation
- Pest feature tests
- Laravel Boost
- Claude Code
- Carola Bootstrap template for public pages
- A simple Tailwind admin dashboard

Do not use single-file Livewire components for the required functionality.

The Laravel starter kit may provide authentication, but the car and booking features must use standard Laravel controllers, models, Form Requests and Blade views.

---

## Core Application Flow

Use these three supplied template pages:

- `index.html` → public homepage
- `car-listing.html` → public car listing
- `car-details.html` → public car details and booking form

These pages are different views of the same `Car` resource.

Do not create three separate CRUD systems.

Required routes:

```text
GET  /                         Homepage
GET  /cars                     Car listing
GET  /cars/{car:slug}          Car details
POST /cars/{car}/bookings      Create booking

GET  /admin/dashboard          Admin dashboard
RESOURCE /admin/cars           Car CRUD
ADMIN /admin/bookings          Booking management
```

---

## Scope and Simplicity Rules

Follow KISS and YAGNI.

Do not introduce any of the following unless a current requirement clearly needs it:

- Repository pattern
- Generic service layer
- DTO packages
- Action-class architecture
- React
- Vue
- Inertia
- REST API
- Docker
- Redis
- Queues
- WebSockets
- Payment integration
- Email sending
- Role-management packages
- State-machine packages
- CAPTCHA
- Multiple database connections

Do not create a class merely to move one or two lines out of a controller.

Use Laravel’s built-in features before installing packages.

---

## Public Template Rules

Do not convert the entire supplied HTML template.

Remove sections that do not contribute to the backend assessment, including:

- Blog
- Testimonials
- Team
- Video sections
- Brand carousels
- Newsletter
- Decorative counters
- Duplicate promotional sections
- Unused forms and modals

Keep only:

### Homepage

- Header
- Small hero section
- Search form
- Featured cars
- Optional simple call to action
- Footer

### Listing

- Header
- Filters
- Car cards
- Pagination
- Empty state
- Footer

### Details

- Header
- Car data
- Booking form
- Footer

Keep public Bootstrap assets separate from admin Tailwind assets.

Use:

```text
resources/views/layouts/public.blade.php
resources/views/layouts/admin.blade.php
```

Do not load both frontend frameworks globally.

---

## Database Rules

Use SQLite.

All schema changes must be created through migrations.

Never manually modify the database structure.

Use string database columns for closed values such as car type and booking status, then cast them to backed PHP enums.

Do not use database enum columns.

Expected models:

```text
Car
Booking
User
```

Expected relationships:

```text
Car hasMany Bookings
Booking belongsTo Car
```

Use database foreign-key constraints.

Do not cascade-delete a car’s booking history.

Store both:

```text
daily_rate_snapshot
total_price
```

A booking must retain the price that applied when it was created, even when the car’s current rate later changes.

---

## Required PHP Enums

Use backed PHP enums for:

```text
BookingStatus
CarType
```

Suggested booking statuses:

```text
pending
confirmed
rejected
cancelled
completed
```

Suggested car types:

```text
sedan
suv
sports
luxury
van
```

Cast model attributes directly to their enum classes.

Validate enum input using:

```php
Rule::enum(EnumClass::class)
```

Do not repeat raw string arrays throughout the application when an enum already represents those values.

---

## Strict Eloquent Rules

Enable strict model behavior in local and testing environments:

```php
Model::shouldBeStrict(
    app()->environment(['local', 'testing'])
);
```

This must be configured in `AppServiceProvider`.

Strict mode should help detect:

- Lazy loading
- Missing selected attributes
- Silently discarded mass-assignment attributes

Use eager loading when displaying relationships.

Example:

```php
Booking::query()
    ->with('car')
    ->latest()
    ->paginate(15);
```

Do not query relationships repeatedly from Blade.

Do not place database queries inside views.

---

## Routing Rules

Use named routes.

Use route model binding.

The `Car` model should bind through its slug:

```php
public function getRouteKeyName(): string
{
    return 'slug';
}
```

Use resource controllers for normal administrative CRUD.

Keep public browsing and admin CRUD in separate controllers.

Expected controller separation:

```text
HomeController
CarBrowseController
BookingController

Admin\DashboardController
Admin\CarController
Admin\BookingController
```

Do not put public browsing, booking creation and admin CRUD into one controller.

The public booking route should use:

```php
->middleware('throttle:5,1')
```

Do not use `throttle:bookings` unless a named `bookings` rate limiter has actually been registered.

---

## Validation Rules

Use Form Request classes for car creation, car updates, booking creation and booking status updates.

Expected requests:

```text
StoreCarRequest
UpdateCarRequest
StoreBookingRequest
UpdateBookingRequest
```

Use:

```php
$request->validated()
```

Never use:

```php
Model::create($request->all());
```

Never trust the following values from the browser:

- Car ID outside route model binding
- Daily rate
- Booking total
- Booking status
- Administrator approval
- Availability result

Checkbox values must be normalized correctly.

Uploaded images must be validated as images with an appropriate size limit.

---

## Controller Rules

Controllers should coordinate the request, not contain unrelated framework concerns.

A controller may:

- Receive a validated request
- Run a readable Eloquent query
- Coordinate a transaction
- Store or remove an uploaded file
- Return a view
- Redirect with a flash message

A controller should not:

- Contain copied validation rules
- Trust client-calculated prices
- Query the database from Blade
- Use raw SQL without a clear need
- Build a generic architecture around one operation

Small private controller methods are acceptable for narrow logic such as unique slug generation.

Do not extract a service class automatically.

---

## Car Listing Rules

The public listing must:

- Display active cars only
- Search by car name or brand
- Filter by type
- Filter by transmission
- Filter by minimum seat capacity
- Filter by maximum daily price
- Paginate results
- Preserve filters in pagination links
- Show an empty state

Filtering and searching must happen in SQL before records are retrieved.

Correct:

```php
Car::query()
    ->where(...)
    ->paginate(6);
```

Incorrect:

```php
Car::all()->filter(...);
```

Use GET parameters for public search and filtering.

---

## Booking Availability Rules

Pending and confirmed bookings block availability.

Rejected, cancelled and completed bookings do not block availability unless the documented business rule says otherwise.

The overlap condition is:

```text
existing.start_date <= requested.end_date
AND
existing.end_date >= requested.start_date
```

Use ordinary column comparisons:

```php
->where('start_date', '<=', $endDate)
->where('end_date', '>=', $startDate)
```

Do not use `whereDate()` because the database columns are already typed as dates.

The availability check and booking creation should occur within one database transaction.

---

## Booking Price Rules

Normalize booking dates before calculating duration:

```php
$start = Carbon::parse(
    $validated['start_date']
)->startOfDay();

$end = Carbon::parse(
    $validated['end_date']
)->startOfDay();
```

Carbon 3 may return a floating-point value from `diffInDays()`.

Explicitly cast the result:

```php
$days = max(
    1,
    (int) $start->diffInDays($end)
);
```

The selected billing policy is:

```text
Pickup August 10
Return August 11
Charge 1 day
```

Calculate the booking total on the server:

```php
$totalPrice = $days * $car->daily_rate;
```

Store:

```text
daily_rate_snapshot = current car daily rate
total_price = calculated server total
status = pending
```

Never use a submitted total or submitted daily rate.

---

## Authentication and Authorization

Use the starter kit’s normal login functionality.

Do not enable these additional authentication features for this assessment:

- Public registration
- Email verification
- Two-factor authentication
- Passkeys
- Password confirmation

Use a seeded administrator account.

Add an `is_admin` boolean to users.

Protect admin routes using both authentication and authorization:

```php
['auth', 'can:access-admin']
```

Expected behavior:

```text
Guest → redirected to login
Normal user → 403
Administrator → allowed
```

Do not install a role or permission package for one administrator role.

---

## File Upload Rules

Store car images on Laravel’s `public` disk.

Use:

```php
$request->file('image')->store('cars', 'public');
```

Use:

```php
Storage::url($car->image_path)
```

When replacing an image:

1. Store the new image.
2. Update the car.
3. Remove the old stored image when safe.

When deleting a car without bookings, remove its uploaded image.

Provide a fallback template image when no uploaded image exists.

Do not store uploaded files directly in the repository.

---

## Testing Rules

Create only six to eight focused feature tests.

Each test must protect a meaningful risk.

Required coverage:

1. Admin authentication and authorization
2. Admin car creation and update
3. Public listing filters and inactive-car protection
4. Slug binding and inactive detail protection
5. Server-controlled booking price
6. Overlapping booking rejection
7. Cancelled booking does not block availability
8. Invalid booking date ranges

Do not create many shallow tests that only assert HTTP 200.

Tests should assert:

- Database state
- Validation errors
- Visible content
- Authorization outcome
- Correct business calculation

Use factories and `RefreshDatabase`.

Use fake storage for upload tests.

Run relevant focused tests after each backend change.

---

## Claude Code Workflow

Before editing:

1. Inspect the existing project structure.
2. Inspect relevant routes, migrations, models and tests.

During implementation:

- Work on one checkpoint at a time.
- Preserve existing working behavior.
- Follow the project’s Laravel version.
- Use Laravel Boost documentation and project inspection.
- Prefer Laravel conventions over custom abstractions.
- Do not silently introduce dependencies.
- Do not modify unrelated files.

After implementation:

1. Summarize changed files.
2. Explain important Laravel decisions.
3. Run the relevant tests.
4. Run formatting when PHP files changed.
5. Report failures honestly.
6. Do not claim success when commands were not run.

Do not generate large amounts of frontend markup.

Do not redesign the supplied theme.

---

## Commands to Run

After PHP changes:

```powershell
.\vendor\bin\pint
php artisan test
```

After frontend changes:

```powershell
npm run build
```

After route changes:

```powershell
php artisan route:list
```

After migration changes during development:

```powershell
php artisan migrate
php artisan migrate:status
```

Before final delivery:

```powershell
php artisan optimize:clear
php artisan migrate:fresh --seed
php artisan test
.\vendor\bin\pint
npm run build
php artisan route:list
git status
git diff
```

Do not run destructive commands without clearly stating what they will delete.

---

## Final Quality Standard

The best solution is not the solution with the most classes or packages.

The expected solution should:

- Use Laravel framework features correctly
- Keep the architecture understandable
- Protect administrative routes
- Prevent invalid and overlapping bookings
- Calculate prices on the server
- Avoid N+1 queries
- Use strict Eloquent development behavior
- Use backed PHP enums
- Pass focused tests
- Be installable from the README
- Be explainable and modifiable by the candidate

Prefer simple, correct and testable code over speculative abstraction.
