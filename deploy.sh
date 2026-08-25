#!/bin/bash

# ==============================================================================
# Laravel Deployment Script
# ==============================================================================
set -e

echo "🚀 Starting Deployment Process..."

export COMPOSER_ALLOW_SUPERUSER=1

# 1. Maintenance Mode
if [ "$1" == "--maintenance" ]; then
    echo "🚧 Putting application into maintenance mode..."
    php artisan down --retry=60 || true
fi

# 2. Fetch Latest Changes
echo "📥 Pulling latest code from Git..."
git config --global --add safe.directory "$(pwd)" 2>/dev/null || true
git pull origin main

# 3. Install/Update PHP Dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev

# 4. Database Migrations
echo "🗄️ Running database migrations..."
php artisan migrate --force

# 5. Build Frontend Assets (regenerated here, not committed to Git)
echo "⚡ Building frontend assets..."
if [ -f "package.json" ]; then
    if ! command -v node >/dev/null 2>&1 || ! command -v npm >/dev/null 2>&1; then
        echo "❌ Node.js/npm is required to build frontend assets."
        exit 1
    fi
    if [ ! -d "node_modules" ]; then
        echo "📦 Installing npm dependencies..."
        if [ -f "package-lock.json" ]; then
            npm ci --no-interaction
        else
            npm install --no-interaction --prefer-offline
        fi
    fi
    npm run build
    npm run assets:build
    php artisan filament:assets
fi

# 6. Ensure Storage Link Exists
echo "🔗 Verifying storage link..."
php artisan storage:link 2>/dev/null || true

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

# 9. Clean PM2 Logs (to prevent disk space bloat from other server apps)
if command -v pm2 &> /dev/null; then
    echo "🧹 Flushing PM2 logs..."
    pm2 flush || true
fi

# 10. Bring Application Out of Maintenance Mode
if [ "$1" == "--maintenance" ]; then
    echo "✨ Bringing application back online..."
    php artisan up
fi

echo "✅ Deployment completed successfully!"
