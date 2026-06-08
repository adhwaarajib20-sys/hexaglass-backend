# 🚨 502 Bad Gateway - Quick Fix Guide

## What We Fixed

1. **Updated start.sh** - Better error handling, environment validation, and database connection testing
2. **Updated build.sh** - Improved environment variable handling and error logging
3. **Updated Procfile** - Added release command for consistency
4. **Better Permission Management** - Ensures storage/ and bootstrap/cache/ are writable

## Immediate Steps to Redeploy

### Option 1: Quick Redeploy from GitHub
```bash
cd c:\laragon\www\coba\hexaglass-backend

# Make sure scripts are executable
chmod +x build.sh start.sh deploy.sh

# Commit changes
git add build.sh start.sh Procfile
git commit -m "Fix: 502 Bad Gateway - improved start and build scripts"
git push origin main

# Railway will auto-redeploy when changes are pushed
```

### Option 2: Manual Redeploy via Railway Dashboard
1. Go to https://railway.app
2. Navigate to your project
3. Click "Redeploy" button
4. Wait for build to complete (watch the logs)

## Verification Checklist

Once deployed, verify these points:

### 1. Check Deployment Status
```bash
railway logs -f --limit 50
```

Look for:
- ✅ "Build process completed"
- ✅ "Starting PHP server on port"
- ❌ No "ERROR:" messages

### 2. SSH to Check App Status
```bash
railway exec bash

# Test app
php artisan tinker -q
> DB::connection()->getPdo();
# Should show connection info without error

exit
```

### 3. Visit the Application
- Open: https://web-production-2598a.up.railway.app
- Should see your app, not 502 error

## If Still Getting 502

### Step 1: Check Logs in Detail
```bash
railway logs -f --limit 100 | grep -E "(ERROR|fatal|exception|Connection|DB_)"
```

### Step 2: Common Issues & Fixes

**Issue**: `ERROR: APP_KEY not set by Railway`
- **Fix**: Set APP_KEY in Railway env vars dashboard
- **Command**: `railway variable APP_KEY <your-key>`

**Issue**: `ERROR: DB_HOST not set by Railway`
- **Fix**: Make sure MySQL service is added to Railway project
- **Check**: `railway env list | grep DB_`

**Issue**: `SQLSTATE[HY000]` (Database connection error)
- **Fix**: Database not ready yet - wait 30 seconds, then restart
- **Command**: `railway restart`

**Issue**: `Process crashed` / `Exit with code 1`
- **Fix**: Check Laravel logs
- **Command**: `railway exec bash -c "tail -50 storage/logs/laravel.log"`

### Step 3: Nuclear Option (Last Resort)
```bash
# Delete current deployment
railway delete

# Redeploy from scratch
# Go to https://railway.app and create new deployment
```

## Environment Variables to Verify

Go to Railway dashboard and confirm these are set:

```
APP_NAME=Hexaglass
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:... (must start with base64:)
APP_URL=https://web-production-2598a.up.railway.app
DB_CONNECTION=mysql
DB_DATABASE=railway
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

If any are missing, add them via Railway dashboard.

## What These Scripts Do

### build.sh
- Validates environment variables (stops if APP_KEY or DB_HOST missing)
- Generates .env from Railway environment variables
- Runs `composer install`
- Runs `npm install && npm run build` (optional failures allowed)
- Caches configuration, routes
- Runs database migrations
- Sets proper file permissions

### start.sh
- Validates .env file exists
- Validates APP_KEY and DB_HOST are set
- Tests database connection
- Sets proper permissions
- Starts PHP development server on port from Railway

### deploy.sh (run on release)
- Clears all caches
- Sets correct directory permissions
- Caches configuration, routes, events
- Runs optimization commands

## Debug Info Collection

If you need help, collect this info:

```bash
echo "=== Railway Logs (last 50 lines) ==="
railway logs -n 50

echo ""
echo "=== Environment Variables ==="
railway env list

echo ""
echo "=== App Status Check ==="
railway exec bash -c "
  echo 'PHP Version:'; php --version
  echo 'Laravel Version:'; php artisan --version
  echo 'DB Connection:'; php artisan tinker -q -c 'DB::connection()->getPdo(); echo \"OK\\n\";' 2>&1
  echo 'Disk Check:'; df -h
"
```

---

**Last Updated**: 2026-06-08
**Status**: Ready to deploy
