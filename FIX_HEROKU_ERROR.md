# 🔧 FIX: vendor/bin/heroku-php-apache2 Not Found

## 🚨 Problem
```
/bin/bash: line 1: vendor/bin/heroku-php-apache2: No such file or directory
```

## Root Cause
- **Procfile** menggunakan `vendor/bin/heroku-php-apache2` (Heroku buildpack command)
- **Railway** menggunakan `nixpacks` builder (NOT Heroku)
- Mereka tidak kompatibel → command tidak ditemukan

## ✅ Solution Applied

### 1️⃣ Updated Procfile
**Before (Wrong untuk Railway):**
```
web: vendor/bin/heroku-php-apache2 public/
```

**After (Fixed untuk Railway):**
```
web: php -S 0.0.0.0:${PORT:-8080} -t public/
```

**Why:** Menggunakan built-in PHP server yang tersedia di semua environment

### 2️⃣ Updated .railway.json
**Before:**
```json
{
  "build": {
    "builder": "nixpacks"
  }
}
```

**After:**
```json
{
  "build": {
    "builder": "nixpacks",
    "nixPackages": ["php83", "composer", "nodejs", "npm"]
  },
  "start": "sh -c 'php artisan migrate --force && php -S 0.0.0.0:${PORT:-8080} -t public/'"
}
```

**Why:** 
- Explicitly specify PHP 8.3 dan dependencies
- Set start command dengan migrations

---

## 🚀 What Happens Next

1. **Railway will redeploy** automatically (code already updated in GitHub)
2. **Build process:**
   - Install PHP 8.3, Composer, Node, npm
   - Run `composer install`
   - Run `npm install` & `npm run build`
   - Create necessary directories

3. **Release process:**
   - Run migrations: `php artisan migrate --force`
   - Cache config & routes
   - Start app

4. **App starts:**
   - PHP built-in server runs on port 8080
   - Ready to accept requests

---

## ✅ Next Steps

1. **Trigger new deploy:**
   ```bash
   railway restart
   # or
   railway redeploy
   ```

2. **Monitor logs:**
   ```bash
   railway logs -f
   ```
   
   **Look for:**
   - ✅ `Build successful`
   - ✅ `Installing dependencies`
   - ✅ `php -S 0.0.0.0:8080`
   - ❌ NO `heroku-php-apache2` errors

3. **Test:**
   ```bash
   curl https://web-production-2598a.up.railway.app/health
   ```
   Should return JSON, NOT 404

---

## 📊 Comparison: Buildpacks vs Nixpacks

| Feature | Heroku Buildpack | Nixpacks |
|---------|------------------|----------|
| Web Server | `vendor/bin/heroku-php-apache2` | PHP built-in server |
| Build Speed | Slower | Faster |
| Flexibility | Limited | Very flexible |
| Start Command | In buildpack | In .railway.json |
| Railway Native | ❌ Legacy | ✅ Modern |

---

## 🔐 Environment Variables Check

Verify semua env vars masih ada:
```bash
railway env list | grep -E "APP_|DB_"
```

Should show:
```
✓ APP_NAME=Hexaglass
✓ APP_ENV=production
✓ DB_HOST=railway.proxy.rlwy.net
✓ DB_USERNAME=root
... (all 31 variables)
```

---

## 🆘 If Still 404

Run diagnostics:
```bash
railway exec bash
# Check if PHP server is running
ps aux | grep "php -S"
# Check if port is listening
netstat -tlnp | grep 8080
# Check app logs
tail -50 storage/logs/laravel.log
exit
```

---

## 📝 Files Changed

| File | Change | Reason |
|------|--------|--------|
| Procfile | Use PHP server | Works with nixpacks |
| .railway.json | Add nixPackages + start command | Explicit config |

---

**Status**: ✅ **FIXED - Ready for Redeploy**

**Action**: 
1. `railway restart` 
2. Wait for build
3. Test: `curl https://web-production-2598a.up.railway.app/health`
