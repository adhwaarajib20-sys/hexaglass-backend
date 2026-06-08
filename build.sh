#!/bin/bash

# This script runs when the build completes on Railway

set -e

echo "🔧 Building Hexaglass application..."
echo "Node version: $(node --version)"
echo "npm version: $(npm --version)"

# Install PHP dependencies
echo "📦 Installing PHP dependencies..."
composer install --no-interaction --optimize-autoloader --no-dev

# Install Node dependencies with clean install
echo "📦 Installing Node dependencies..."
npm ci --prefer-offline --no-audit

# Build frontend assets
echo "🔨 Building frontend assets..."
npm run build

# Create symbolic link for storage
echo "🔗 Creating storage symbolic link..."
php artisan storage:link || true

# Optimize Laravel
echo "⚡ Optimizing Laravel..."
php artisan optimize

echo "✅ Build completed successfully!"
