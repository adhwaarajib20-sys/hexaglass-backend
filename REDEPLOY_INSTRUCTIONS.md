# 🔄 Force Redeploy Instructions

Railway tidak auto-pick up perubahan tadi. Berikut 2 cara untuk force redeploy:

---

## Method 1️⃣: Manual Redeploy di Dashboard (FASTEST)

1. Go to: https://railway.app
2. Select: Your project
3. Go to: **Deployments** tab
4. Find: Latest deployment (top of list)
5. Click: **3 dots** (•••) menu
6. Click: **Redeploy**
7. Wait: Build process starts

---

## Method 2️⃣: Wait for Auto-Redeploy

We just pushed new code. Railway should auto-detect in next 1-2 minutes:
```bash
railway logs -f
# Watch for new "Starting Container"
```

---

## ✅ What to Expect

After redeploy starts:
```
✅ Starting Container
✅ Installing dependencies (Composer, npm)
✅ Building assets
✅ Running migrations
✅ php -S 0.0.0.0:8080  ← IMPORTANT! This should appear
❌ NO "heroku-php-apache2" errors
```

---

## 🧪 Test After Deploy

```bash
# Wait 3-5 minutes for build to complete, then:

curl https://web-production-2598a.up.railway.app/health

# Should return JSON, NOT 404
```

---

## 📊 Current Procfile (Fixed)

```
release: php artisan migrate --force && php artisan config:cache && php artisan route:cache
web: php -S 0.0.0.0:${PORT:-8080} -t public/
```

✅ Using PHP built-in server (works with nixpacks)
✅ NO Heroku Apache dependency

---

## 🎯 DO THIS NOW:

**Option A (Recommended):**
1. Go to Railway → Deployments
2. Click latest → Redeploy
3. Watch logs

**Option B:**
1. Wait 2 minutes
2. Check: `railway logs -f`
3. Should auto-redeploy

---

**Status**: ✅ Code fixed and pushed

**Next**: Click "Redeploy" in Railway dashboard or wait for auto-redeploy!
