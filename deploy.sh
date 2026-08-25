#!/bin/bash

# ==============================================================================
# Laravel Deployment Script
# ==============================================================================
set -e

echo "🚀 Starting Deployment Process..."

# 1. Maintenance Mode
if [ "$1" == "--maintenance" ]; then
    echo "🚧 Putting application into maintenance mode..."
    php artisan down --retry=60 || true
fi

# 2. Fetch Latest Changes
echo "📥 Pulling latest code from Git..."
git pull origin main

# 3. Install/Update PHP Dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 4. Database Migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 5. Build Frontend Assets (if Node/NPM is available)
if command -v npm &> /dev/null; then
    echo "⚡ Building frontend assets..."
    npm run build --silent || npm run build
    npm run assets:build --silent || npm run assets:build
fi

# 6. Ensure Storage Link Exists
echo "🔗 Verifying storage link..."
php artisan storage:link || true

# 7. Optimize & Cache Configuration / Routes / Views
echo "🧹 Optimizing and caching Laravel..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 8. Reload PHP-FPM to Clear OPcache (if running with systemctl/sudo)
if command -v systemctl &> /dev/null; then
    echo "🔄 Reloading PHP-FPM..."
    sudo systemctl reload php8.4-fpm 2>/dev/null || systemctl reload php8.4-fpm 2>/dev/null || sudo systemctl reload php8.3-fpm 2>/dev/null || true
fi

# 9. Bring Application Out of Maintenance Mode
if [ "$1" == "--maintenance" ]; then
    echo "✨ Bringing application back online..."
    php artisan up
fi

echo "✅ Deployment completed successfully!"
