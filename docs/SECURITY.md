# EEMCI Platform - Security Hardening

This document outlines the security measures implemented in the platform.

## 1. Authentication & Authorization
- **Laravel Sanctum**: Token-based authentication for stateful and stateless requests.
- **Role-Based Access Control (RBAC)**: Enforced via `RoleMiddleware`. Only authorized roles can access specific endpoints.
- **Password Policies**: Secure bcrypt hashing (cost 12). Forced password change implemented for default accounts.

## 2. API Security
- **Rate Limiting**: 
  - `throttle:api`: 100 requests per minute per IP/User.
  - `throttle:login`: 5 attempts per 15 minutes to prevent brute-force attacks.
- **CORS Restrictions**: Configured in `config/cors.php` to only allow specific origins (e.g., `https://app.eemci.edu`).
- **Input Sanitization**: `InputSanitizationMiddleware` strips HTML tags and trims whitespace from all string inputs to prevent XSS.

## 3. HTTP Security Headers
The `SecurityHeadersMiddleware` injects the following headers into every API response:
- `X-Frame-Options: DENY` (Prevents Clickjacking)
- `X-Content-Type-Options: nosniff` (Prevents MIME-sniffing)
- `X-XSS-Protection: 0` (Modern XSS protection configuration)
- `Referrer-Policy: strict-origin-when-cross-origin`
- `Content-Security-Policy: default-src 'none'`
- `Strict-Transport-Security: max-age=31536000; includeSubDomains` (Production only, enforces HTTPS)

## 4. Production Environment
- `APP_DEBUG=false` ensures stack traces are never exposed.
- Detailed error logging using Daily rotation.
- Secure Cookies enabled (`SESSION_SECURE_COOKIE=true`).
