# 🚀 Quick Reference - Railway Deployment

## Domain
**Production**: https://web-production-2598a.up.railway.app

---

## ⚡ 3-Step Environment Setup

### Step 1: Get Database Credentials
1. Railway Dashboard → MySQL service
2. Click "Connect" tab
3. Copy: Host, Port, Database, Username, Password

### Step 2: Add Environment Variables
Copy-paste into Railway Environment Variables:

```
APP_NAME=Hexaglass
APP_ENV=production
APP_KEY=base64:bQ8PDS7l2rj2Eqy4YWRZnilopk4x+29l/0TEyrWCvl0=
APP_DEBUG=false
APP_URL=https://web-production-2598a.up.railway.app
LOG_CHANNEL=stack
LOG_LEVEL=info
BCRYPT_ROUNDS=12
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.up.railway.app
BROADCAST_CONNECTION=log
QUEUE_CONNECTION=database
CACHE_STORE=database
FILESYSTEM_DISK=local
APP_LOCALE=id
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=id_ID

DB_HOST=<from MySQL service>
DB_PORT=<from MySQL service>
DB_DATABASE=<from MySQL service>
DB_USERNAME=<from MySQL service>
DB_PASSWORD=<from MySQL service>
```

### Step 3: Trigger Deploy
- Railway auto-redeploys when variables change
- Check logs to verify success

---

## ✅ Verify Deployment

```bash
# SSH
railway exec bash

# Check database
php artisan migrate:status

# Test API
curl https://web-production-2598a.up.railway.app/api/auth/login -X POST
```

---

## 📋 Files Updated
- ✅ `.env` - Local development settings (id locale, correct paths)
- ✅ `.env.production` - Production template with Railway domain
- ✅ `config/cors.php` - CORS configuration for Railway domain
- ✅ `RAILWAY_ENV_SETUP.md` - Detailed setup guide

---

**Status**: Ready for Environment Variable Configuration ✅

For detailed guide, see: **RAILWAY_ENV_SETUP.md**
