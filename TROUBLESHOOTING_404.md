# 🔧 Troubleshooting: Railway 404 "Train has not arrived"

**Error**: `404 Not Found - The train has not arrived at the station`

Ini berarti aplikasi belum running atau ada error saat build/deploy.

---

## ⚡ Quick Fixes (Do These First)

### 1. Check Deployment Status
```bash
railway logs
# atau
railway logs -f --limit 100
```

**Look for**:
- ❌ `Build failed` → Check error message
- ❌ `Process crashed` → Check logs
- ✅ `Build successful` → Proceed to next checks

### 2. Restart Application
```bash
railway restart
```

### 3. Check Environment Variables Are Set
```bash
railway env list
```

**Must have**:
- APP_NAME ✓
- APP_ENV ✓
- APP_URL ✓
- APP_KEY ✓
- DB_HOST ✓
- DB_PORT ✓
- DB_DATABASE ✓
- DB_USERNAME ✓
- DB_PASSWORD ✓

---

## 🔍 Detailed Troubleshooting

### Step 1: SSH into Railway
```bash
railway exec bash
```

### Step 2: Check if Web Service Running
```bash
ps aux | grep apache
# Should show apache2 processes running

# Or check if server is listening
netstat -tlnp | grep 8080
```

### Step 3: Check Application Key
```bash
php artisan key:show
# Should output: base64:bQ8PDS7l2rj2Eqy4YWRZnilopk4x+29l/0TEyrWCvl0=
```

### Step 4: Check Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo()
# Should NOT throw error
>>> exit
```

### Step 5: Check Configuration
```bash
php artisan config:show
# or
php artisan config:show app
```

### Step 6: Check Routes Cached
```bash
php artisan route:list | head -20
```

### Step 7: View Recent Errors
```bash
cat storage/logs/laravel.log | tail -50
```

---

## 🚨 Common Errors & Solutions

### Error: "Cannot connect to database"
```
SQLSTATE[HY000]: General error: 2003 Can't connect to MySQL server
```

**Fix:**
```bash
# SSH to Railway
railway exec bash

# Verify DB connection
php artisan tinker
>>> echo env('DB_HOST')
>>> echo env('DB_USERNAME')
>>> echo env('DB_PASSWORD')
exit
```

**Check env variables are correct:**
- DB_HOST: Should be `railway.proxy.rlwy.net` (NOT localhost)
- DB_PORT: Usually `3306`
- DB_USERNAME & PASSWORD: From Railway MySQL service

### Error: "APP_KEY not set"
```
RuntimeException: No application encryption key has been specified
```

**Fix:**
```bash
# Ensure APP_KEY is in environment variables
railway env set APP_KEY base64:bQ8PDS7l2rj2Eqy4YWRZnilopk4x+29l/0TEyrWCvl0=

# Or redeploy
railway redeploy
```

### Error: "php artisan migrate failed"
```
Process exited with status code 1
```

**Fix:**
```bash
# SSH and check what error
railway exec bash
php artisan migrate --force

# If database doesn't exist
php artisan migrate:refresh --force --seed
```

### Error: Procfile execution failed

**Check Procfile is correct:**
```bash
cat Procfile
# Should show:
# release: php artisan migrate --force && php artisan config:cache && php artisan route:cache
# web: vendor/bin/heroku-php-apache2 public/
```

---

## 📊 Step-by-Step Diagnostic

Run these commands in order:

```bash
railway exec bash

# 1. Check if we're in the right directory
pwd
ls -la

# 2. Check if vendor directory exists
ls -la vendor/bin/

# 3. Check PHP version
php --version

# 4. Check Apache modules
apache2ctl -M | grep php

# 5. Check Laravel installation
php artisan --version

# 6. Check config files exist
ls -la config/

# 7. Check storage directory writable
ls -la storage/
touch storage/test.txt && rm storage/test.txt && echo "Storage writable ✓"

# 8. Check bootstrap cache
ls -la bootstrap/cache/

# 9. View full application log
tail -200 storage/logs/laravel.log

exit
```

---

## 🔧 Force Redeploy

Sometimes Railway cache needs clearing:

```bash
# 1. SSH
railway exec bash

# 2. Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# 3. Recreate caches for production
php artisan config:cache
php artisan route:cache

# 4. Exit and restart
exit
railway restart
```

---

## 📝 Environment Variables Verification

Make sure ALL these are set in Railway:

```
✓ APP_NAME=Hexaglass
✓ APP_ENV=production
✓ APP_KEY=base64:bQ8PDS7l2rj2Eqy4YWRZnilopk4x+29l/0TEyrWCvl0=
✓ APP_DEBUG=false
✓ APP_URL=https://web-production-2598a.up.railway.app
✓ LOG_CHANNEL=stack
✓ LOG_LEVEL=info
✓ BCRYPT_ROUNDS=12
✓ SESSION_DRIVER=database
✓ QUEUE_CONNECTION=database
✓ CACHE_STORE=database
✓ FILESYSTEM_DISK=local
✓ APP_LOCALE=id
✓ DB_HOST=<from MySQL>
✓ DB_PORT=<from MySQL>
✓ DB_DATABASE=<from MySQL>
✓ DB_USERNAME=<from MySQL>
✓ DB_PASSWORD=<from MySQL>
```

**If missing any, add them and Railway will redeploy.**

---

## 🌐 Check if Domain Provisioned

```bash
# From your local computer, check DNS
nslookup web-production-2598a.up.railway.app

# Or test with curl
curl -I https://web-production-2598a.up.railway.app
# Should NOT return 404
```

---

## 🛑 Nuclear Option - Full Redeploy

If nothing works:

1. **Go to Railway Dashboard**
2. **Click your project**
3. **Go to Deployments tab**
4. **Click the recent failed deployment**
5. **Click "Redeploy"**
6. **Watch logs:** `railway logs -f`

---

## 📞 Getting Help

**Provide these when asking for help:**

```bash
railway logs -100  # Last 100 lines of logs
railway env list   # Environment variables
railway exec bash -c "php artisan config:show app"  # App config
```

---

## ✅ When it Works

You should see:
- ✅ `Build successful`
- ✅ `Running release process`
- ✅ `Migrations completed`
- ✅ Process listening on 0.0.0.0:8080
- ✅ Domain responds with API (not 404)

Test with:
```bash
curl https://web-production-2598a.up.railway.app/health
# Should return JSON response, not 404
```

---

**Next Steps:**
1. Check logs: `railway logs`
2. SSH and diagnose: `railway exec bash`
3. Run diagnostic steps above
4. Report specific error found

If you find a specific error message, I can help fix it!
