# Deployment, Security, and API Route Notes

## Deployment setup

### Current deployment files
- `render.yaml`
  - Defines a single `web` service deployed with Docker.
  - Uses `dockerfilePath: Dockerfile`.
  - Runs `./scripts/railway-start.sh` as the start command.
- `railway.toml`
  - Uses Dockerfile builder.
  - Starts the same `./scripts/railway-start.sh`.
- `Dockerfile`
  - Builds a PHP 8.3 Alpine image.
  - Installs PHP extensions, Node, npm.
  - Copies the repo, installs Composer deps, builds frontend.
  - Exposes port `10000`.
  - Starts the container with `php artisan serve --host=0.0.0.0 --port=${PORT:-10000}`.
- `scripts/railway-start.sh`
  - Writes a runtime `.env` from environment variables.
  - Runs migrations and `storage:link` during container startup.
  - Clears and caches config.
  - Starts Laravel using `php artisan serve`.

### Deployment security notes

- No application-level load balancer is configured in the repo.
  - The service is served directly by PHP's built-in server inside the container.
  - This is not recommended for production traffic.
- Platform-side routing may still exist, but there is no app code for a reverse proxy or load balancer.
- `php artisan serve` is intended for development and not optimized for security or performance.
- `.env` file is generated at runtime from environment variables in `scripts/railway-start.sh`.
  - Good: runtime values are used instead of baked-in local `.env`.
  - Risk: if environment variables are missing or misconfigured, default values may be written.
- Exposed port is `10000`.
  - The service should still be fronted by a secure HTTPS endpoint from the platform.
- Key production config values are controlled by environment variables:
  - `APP_ENV`
  - `APP_DEBUG`
  - `APP_URL`
  - `DB_*`
  - `MAIL_*`
  - `QUEUE_CONNECTION`
  - `CACHE_STORE`
  - `SESSION_DRIVER`
- Current defaults in `railway-start.sh`:
  - `SESSION_DRIVER=database`
  - `CACHE_STORE=database`
  - `QUEUE_CONNECTION=database`
  - `FILESYSTEM_DISK=local`
  - `MAIL_MAILER=smtp`

### Production readiness checklist

The repo currently has a single runtime layer plus platform routing. For production, a safer architecture should include at least these layers:

1. Load balancer / ingress layer
   - handles external traffic, health checking, SSL termination, and scaling.
   - this may be provided by the hosting platform, but the app should still be ready for it.
2. Reverse proxy / web server layer
   - `nginx`, `Caddy`, or equivalent.
   - terminates TLS and forwards requests to PHP-FPM.
3. PHP application runtime layer
   - `php-fpm` or an equivalent process manager.
   - not `php artisan serve`.

### Recommended production hardening

- Replace `php artisan serve` with `nginx + php-fpm`, `Caddy`, or another production web server.
- Use an explicit reverse proxy/load balancer if the hosting platform does not already provide one.
- Terminate HTTPS/TLS at the frontend.
- Use secure environment/secret management on the platform.
- Avoid writing default fallback values into `.env` when required vars are missing.
- Use production-ready drivers:
  - `SESSION_DRIVER=database` or `redis`
  - `CACHE_STORE=redis` or `memcached`
  - `QUEUE_CONNECTION=redis` or `database` with a dedicated worker
- Run database migrations in a controlled deployment step, not automatically on every container start if possible.
- Cache config and routes during build/deploy:
  - `php artisan config:cache`
  - `php artisan route:cache`
- Use a queue worker for expensive asynchronous work:
  - email sending
  - PDF generation
  - any background task
- Disable debug and development tooling in production:
  - `APP_DEBUG=false`
  - remove dev dependencies from production builds
- Add security headers in the web server configuration:
  - `Strict-Transport-Security`
  - `X-Content-Type-Options`
  - `X-Frame-Options`
  - `Referrer-Policy`
  - `Content-Security-Policy` as appropriate
- Add API rate limiting / throttling for public endpoints.
- Review CORS and request validation policies.
- Monitor logs, errors, and performance metrics.
- Back up databases and storage regularly.

### Deployment security summary

- The current repo is missing a production web server layer.
- The current repo is missing an explicit load balancer/reverse proxy layer.
- The current runtime is a direct PHP built-in server, which is only suitable for development.
- A production-ready deployment should still include multiple layers plus secure config and queue processing.

## API routes and auth

### Protected API routes
- `Route::middleware('auth:api')->group(...)` protects:
  - `GET /api/gracia-points`
- `auth:api` uses Laravel token guard configured in `config/auth.php`.
  - Guard name: `api`
  - Driver: `token`
  - Provider: `users`
  - Storage key: `api_token`
- `POST /api/login` and `POST /api/register` create or generate API tokens on the user model.
- Some controllers manually check `auth('api')->check()` and `auth('api')->user()`.

### Public API routes
Most API endpoints are currently public and do not require authentication:
- `POST /api/login`
- `POST /api/register`
- `POST /api/register/request-otp`
- `POST /api/register/verify-otp`
- `POST /api/email-verification/request`
- `POST /api/email-verification/verify`
- `GET /api/origins`
- `GET /api/destinations`
- `GET /api/available-dates`
- `GET /api/operators`
- `POST /api/schedules`
- `GET /api/all-schedules`
- `POST /api/bookings`
- `GET /api/bookings`
- `POST /api/bookings/{id}/proof`
- `POST /api/bookings/{id}/cancel`
- `POST /api/bookings/{id}/rebook`
- `GET /api/payment-settings`
- `POST /api/support`
- `GET /api/promotions`
- `GET /api/discounts`
- `GET /api/vouchers`
- `POST /api/vouchers/validate`
- `GET /api/accommodations`
- `GET /api/tours`
- `GET /api/services`
- `GET /api/vehicle-rates`
- `GET /api/app-version`

### Route security concerns
- `POST /api/bookings` is a critical booking creation endpoint that is currently public.
  - This may be intentional for guest booking, but it should be protected if it should only be used by authenticated users.
- `GET /api/bookings` allows lookup by email and optional lookup token.
  - It performs email verification or token validation, but this flow should be audited for security.
- No API rate limiting or throttling appears in `routes/api.php`.
  - Adding throttling middleware would help prevent abuse.
- The token auth guard is basic and relies on `api_token` on the `users` table.
  - Consider using Laravel Sanctum or Passport for stronger, more flexible API auth.

## Deployment TODOs

### Recommended next steps
- Add an `nginx` or `caddy` web server layer instead of `php artisan serve`.
- Configure a load balancer or reverse proxy in the deployment pipeline.
- Add route throttling or API rate limiting for public endpoints.
- Harden API auth:
  - consider Laravel Sanctum, Passport, or OAuth.
  - verify `api_token` rotation and revocation.
- Move email/PDF generation from booking creation into queued jobs.
- Review public API surface and lock down any endpoints that should require auth.
- Ensure the runtime `.env` is generated only from trusted env vars.
- Use production-ready session/cache drivers for the hosting environment.

### Useful files
- `routes/api.php`
- `config/auth.php`
- `Dockerfile`
- `render.yaml`
- `railway.toml`
- `scripts/railway-start.sh`
- `app/Http/Controllers/AuthController.php`
- `app/Http/Controllers/Api/BookingController.php`
- `app/Http/Controllers/Api/VoucherController.php`
- `app/Http/Controllers/Api/ScheduleController.php`

## How to use this note
- Next time, open `DEPLOYMENT_SECURITY_AND_ROUTES.md` to review deployment requirements and security findings.
- Use this note to decide whether to add a load balancer, harden auth, or lock down routes.
