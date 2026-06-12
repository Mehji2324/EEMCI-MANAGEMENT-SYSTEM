# Deployment Guide

## Pre-deployment Checklist
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Generate secure `APP_KEY`
- [ ] Configure database credentials
- [ ] Configure `CORS_ALLOWED_ORIGINS`
- [ ] Set up SSL/TLS certificate

## Server Configuration (Nginx)
```nginx
server {
    listen 80;
    server_name api.eemci.edu;
    root /var/www/eemci-api/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Optimization
Run these commands on the production server:
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```
