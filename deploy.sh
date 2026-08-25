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

# 5. Build Frontend Assets (if node >= 18 is present)
echo "⚡ Checking frontend assets..."
if [ -f "package.json" ] && command -v node >/dev/null 2>&1; then
    NODE_MAJOR=$(node -v | cut -d'.' -f1 | tr -d 'v')
    if [ "$NODE_MAJOR" -ge 18 ] 2>/dev/null; then
        echo "   Node.js version $(node -v) detected. Building frontend assets..."
        if [ -f "package-lock.json" ]; then
            npm ci || npm install --prefer-offline || true
        else
            npm install --prefer-offline || true
        fi
        npm run build || true
        npm run assets:build || true
    else
        echo "   Node.js $(node -v) is older than v18; using repository pre-built assets."
    fi
    php artisan filament:assets || true
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
