#!/bin/bash

# Portfolio Management System Deployment Script
# Usage: ./deploy.sh [production|staging]

set -e

ENVIRONMENT=${1:-production}
APP_NAME="portfolio-management"
DEPLOY_PATH="/var/www/$APP_NAME"
BACKUP_PATH="/var/backups/$APP_NAME"

echo "🚀 Starting deployment to $ENVIRONMENT..."

# Create backup
echo "📦 Creating backup..."
mkdir -p $BACKUP_PATH
if [ -d "$DEPLOY_PATH" ]; then
    tar -czf "$BACKUP_PATH/backup-$(date +%Y%m%d-%H%M%S).tar.gz" -C $DEPLOY_PATH .
fi

# Pull latest code
echo "📥 Pulling latest code..."
cd $DEPLOY_PATH
git pull origin main

# Install dependencies
echo "📦 Installing dependencies..."
composer install --no-dev --optimize-autoloader

# Clear caches
echo "🧹 Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
echo "🗄️ Running migrations..."
php artisan migrate --force

# Optimize for production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set permissions
echo "🔐 Setting permissions..."
chown -R www-data:www-data $DEPLOY_PATH
chmod -R 755 $DEPLOY_PATH
chmod -R 775 $DEPLOY_PATH/storage
chmod -R 775 $DEPLOY_PATH/bootstrap/cache

# Restart services
echo "🔄 Restarting services..."
systemctl reload nginx
systemctl reload php8.2-fpm

echo "✅ Deployment completed successfully!"
echo "🌐 Application is now live at: https://your-domain.com"
