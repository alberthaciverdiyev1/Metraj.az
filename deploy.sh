#!/bin/bash

# ==============================================================================
# Laravel Deployment Script - KibrisKare.com
# ==============================================================================
set -e

echo "🚀 Starting Deployment Process..."

umask 0000
export COMPOSER_ALLOW_SUPERUSER=1

# 0. Pre-Deployment Database Backup
echo "💾 Creating pre-deployment database backup..."
php artisan db:backup || true

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
    php artisan livewire:publish --assets || true
    mkdir -p public/livewire
    cp -rn vendor/livewire/livewire/dist/* public/livewire/ 2>/dev/null || true
fi

# 6. Ensure Storage Link Exists
echo "🔗 Verifying storage link..."
php artisan storage:link 2>/dev/null || true

# 7. Complete Cache Purge (Laravel App Cache, Views, Config, Routes, Events)
echo "🧹 Purging all application caches..."
php artisan cache:clear || true
php artisan optimize:clear || true
php artisan view:clear || true
php artisan route:clear || true
php artisan config:clear || true
php artisan event:clear || true

# 8. Re-warming Caches (Production Optimization)
echo "⚡ Re-caching configuration, routes, and views..."
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache 2>/dev/null || true

# 8.5 Warm up currency rates cache
echo "🪙 Warming up currency rates..."
php artisan currency:update-rates || true

# 8.6 Ensure proper permissions and ownership for www-data
echo "🔒 Fixing storage and cache permissions (www-data)..."
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || sudo chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 777 storage bootstrap/cache 2>/dev/null || sudo chmod -R 777 storage bootstrap/cache 2>/dev/null || true
find storage bootstrap/cache -type d -exec chmod 2777 {} + 2>/dev/null || true

# 9. Purge Nginx Caches & Reload Nginx
echo "🌐 Flushing Nginx cache and reloading Nginx..."
if [ -d "/var/cache/nginx" ]; then
    rm -rf /var/cache/nginx/* 2>/dev/null || sudo rm -rf /var/cache/nginx/* 2>/dev/null || true
fi
if command -v systemctl &> /dev/null; then
    sudo systemctl reload nginx 2>/dev/null || systemctl reload nginx 2>/dev/null || true
fi

# 10. Restart PHP-FPM to Flush OPcache
if command -v systemctl &> /dev/null; then
    echo "🔄 Restarting PHP-FPM to clear OPcache..."
    sudo systemctl restart php8.4-fpm 2>/dev/null || systemctl restart php8.4-fpm 2>/dev/null || sudo systemctl reload php8.4-fpm 2>/dev/null || true
fi

# 11. Clean PM2 Logs (to prevent disk space bloat from other server apps)
if command -v pm2 &> /dev/null; then
    echo "🧹 Flushing PM2 logs..."
    pm2 flush || true
fi

# 12. Bring Application Out of Maintenance Mode
if [ "$1" == "--maintenance" ]; then
    echo "✨ Bringing application back online..."
    php artisan up
fi

# 13. Warm up OPcache and Guest Cache with a curl request
echo "☕ Warming up OPcache & homepage guest cache..."
curl -sL https://kibriskare.com > /dev/null || true

# 14. Final Permission Lock (ensuring cache created by curl / warm-up is writable)
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || sudo chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 777 storage bootstrap/cache 2>/dev/null || sudo chmod -R 777 storage bootstrap/cache 2>/dev/null || true
find storage bootstrap/cache -type d -exec chmod 2777 {} + 2>/dev/null || true

echo "✅ Deployment completed successfully! All caches cleared & re-warmed."
