# EEMCI Platform - Local Setup Guide

## Prerequisites
- PHP 8.2 or higher
- Composer
- MySQL 8.0 or higher
- Redis (optional, for caching)

## Step-by-Step Installation

1. **Clone the repository**
   ```bash
   git clone <repo-url>
   cd eemci-api
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   Create a MySQL database named `eemci`. Update your `.env` file with the database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=eemci
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **Run Migrations and Seeders**
   ```bash
   php artisan migrate --seed
   ```
   *Note: The admin credentials will be printed to your console. Keep the generated password safe.*

6. **Start the Development Server**
   ```bash
   php artisan serve
   ```

## Running Tests
To run the automated test suite (SQLite in-memory):
```bash
php artisan test
```
