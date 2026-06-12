# EEMCI Platform - Educational Management System REST API

A production-ready Laravel 11.9 REST API serving the EEMCI Educational Management System. Built with the Service-Repository pattern.

## Features
- **Role-Based Access Control**: Admin, Teacher, and Student roles using Laravel Sanctum.
- **Student Validation Workflow**: Multi-step registration and academic email generation.
- **Academic Management**: Track Filieres, Classes, Modules, Notes, and Absences.
- **Payment Tracking**: Full financial tracking for student tuition.
- **Hardened Security**: Strict CORS, Rate Limiting, Input Sanitization, Security Headers.
- **Test Coverage**: Comprehensive Unit and Feature testing suite.

## Documentation
- [API Reference](docs/API.md) - Detailed endpoint documentation
- [Setup Guide](docs/SETUP.md) - Local development setup
- [Architecture](docs/ARCHITECTURE.md) - System design and patterns
- [Configuration](docs/CONFIGURATION.md) - Environment variables and logging
- [Security](docs/SECURITY.md) - Security hardening measures
- [Deployment](docs/DEPLOYMENT.md) - Production deployment checklist

## Quick Start
```bash
git clone <repo-url>
cd eemci-api
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

## Testing
```bash
php artisan test
```
