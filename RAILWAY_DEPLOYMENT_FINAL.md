# 🚀 HEXAGLASS BACKEND - RAILWAY DEPLOYMENT FINAL CHECKLIST

## ✅ Setup Status

### File Deployment
- ✅ `Procfile` - Railway process configuration
- ✅ `.railway.json` - Railway config dengan MySQL driver
- ✅ `build.sh` - Build script dengan .env generation
- ✅ `start.sh` - Start script dengan runtime .env generation
- ✅ `.env.example` - Template untuk environment variables
- ✅ `.gitignore` - Mencegah .env ter-commit
- ✅ Database config - Default ke MySQL (bukan SQLite)

### Migrasi & Setup
- ✅ Database migrations - Ready untuk dijalankan
- ✅ Seeder (jika ada) - Siap untuk production

---

## 🎯 DEPLOYMENT STEPS

### Step 1: Pastikan Git Repository Siap

```bash
cd c:\laragon\www\coba\hexaglass-backend

# Cek status
git status

# Pastikan semua sudah committed
git add .
git commit -m "Ready for Railway deployment"
git push origin main
```

### Step 2: Login ke Railway dan Setup Project

1. **Buka** https://railway.app
2. **Login** dengan GitHub account
3. **Create New Project**
4. **Select GitHub Repository** → pilih `hexaglass-backend`
5. **Select Root Directory** → pilih folder backend (jika monorepo)

### Step 3: Tambah MySQL Database Service

1. Di Railway dashboard, klik **+ Create Service**
2. Pilih **MySQL** dari available services
3. Railway akan otomatis inject environment variables:
   - `DB_HOST`
   - `DB_PORT`
   - `DB_USERNAME`
   - `DB_PASSWORD`
   - `DB_DATABASE`

### Step 4: Set Environment Variables di Railway Dashboard

Di Railway web service settings, tambahkan variables:

```env
APP_NAME=Hexaglass
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:bQ8PDS7l2rj2Eqy4YWRZnilopk4x+29l/0TEyrWCvl0=
APP_URL=https://YOUR_RAILWAY_DOMAIN.up.railway.app

LOG_CHANNEL=stack
LOG_LEVEL=info

BCRYPT_ROUNDS=12

SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database
CACHE_STORE=database

MAIL_MAILER=log
MAIL_FROM_ADDRESS="noreply@hexaglass.com"
MAIL_FROM_NAME="Hexaglass"
```

**Note**: `DB_HOST`, `DB_PORT`, `DB_USERNAME`, `DB_PASSWORD`, `DB_DATABASE` akan otomatis di-inject dari MySQL service.

### Step 5: Deploy!

1. **Push ke GitHub** (jika belum):
   ```bash
   git push origin main
   ```

2. **Railway akan otomatis:**
   - ✅ Detect Procfile
   - ✅ Install dependencies via composer
   - ✅ Install PHP MySQL driver
   - ✅ Run build.sh
   - ✅ Generate .env
   - ✅ Run migrations
   - ✅ Cache config & routes
   - ✅ Start web server on port 8080

3. **Monitor logs** di Railway dashboard untuk memastikan berhasil

---

## 🔍 VERIFICATION

### Build Success Indicators
- ✅ Build time: 3-5 minutes
- ✅ No PHP driver errors
- ✅ No migration errors
- ✅ Caching completed successfully

### Startup Success Indicators
- ✅ `.env file created at runtime`
- ✅ `Starting PHP server on port 8080`
- ✅ `Ready for requests`

### Access Your App
```
https://YOUR_RAILWAY_DOMAIN.up.railway.app/
```

---

## 🛠️ TROUBLESHOOTING

### Issue: MySQL driver not found
**Fix**: `.railway.json` sudah include apt-get install PHP MySQL extensions
- Restart deployment jika tetap error

### Issue: .env not found
**Fix**: `start.sh` akan auto-generate .env dari Railway environment variables
- Pastikan `APP_KEY` dan `DB_HOST` di-set di Railway dashboard

### Issue: Database connection failed
**Fix**: 
1. Pastikan MySQL service sudah added di Railway
2. Check environment variables sudah ter-inject
3. Wait 1-2 minutes untuk MySQL ready

### Issue: Migrations failed
**Fix**:
1. Check database exists (otomatis created by Railway MySQL)
2. Check migration files valid
3. Cek logs di Railway untuk error detail

---

## 📋 PRODUCTION CHECKLIST

Sebelum go live:

- [ ] APP_KEY sudah di-set (jangan gunakan APP_KEY dari development)
- [ ] APP_URL sudah benar (domain Railway)
- [ ] APP_ENV=production
- [ ] APP_DEBUG=false
- [ ] MySQL service sudah active
- [ ] Migrations sudah berjalan (check migrations table di database)
- [ ] Test API endpoints dari browser
- [ ] Check logs untuk error atau warning

---

## 📞 SUPPORT

Jika terjadi error, kumpulkan informasi ini:

```bash
# Railway logs
railway logs -n 100

# Check environment
railway env list

# SSH ke container
railway exec bash
  php --version
  php -m | grep pdo_mysql
  exit
```

---

**Last Updated**: 2026-06-08  
**Status**: Ready for Railway Deployment ✅
