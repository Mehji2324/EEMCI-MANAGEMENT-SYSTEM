# Configuration Reference

## Environment Variables

### Core
- `APP_ENV`: Environment (`local`, `staging`, `production`).
- `APP_DEBUG`: Must be `false` in production.
- `APP_URL`: The base URL of the API.

### Security
- `CORS_ALLOWED_ORIGINS`: Comma-separated list of allowed frontend URLs.
- `ADMIN_EMAIL` / `ADMIN_PASSWORD`: Default admin credentials for the initial database seed.

### Database & Cache
- `DB_CONNECTION`: `mysql` (production) or `sqlite` (testing).
- `CACHE_STORE`: Recommended to use `redis` for production.

### Logging
- `LOG_CHANNEL`: Uses `daily` in production to rotate logs every 14 days.

## File Locations
- **CORS**: `config/cors.php`
- **Rate Limiting**: `app/Providers/AppServiceProvider.php`
- **Logging**: `config/logging.php`
