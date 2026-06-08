# ✅ Railway Deployment Checklist untuk Hexaglass Backend

## Pre-Deployment Checklist

### 1. Local Testing
- [ ] Run `composer install`
- [ ] Run `npm install`
- [ ] Run `npm run build`
- [ ] Test dengan `php artisan serve`
- [ ] Verify all API endpoints berjalan
- [ ] Check database migrations: `php artisan migrate:fresh --seed`

### 2. Code Preparation
- [ ] Update `.env.example` dengan konfigurasi terbaru ✅
- [ ] Create `Procfile` untuk Railway ✅
- [ ] Create `.railway.json` untuk Railway config ✅
- [ ] Verify `.gitignore` sudah benar ✅
- [ ] Check `composer.json` scripts sudah lengkap ✅
- [ ] Ensure no sensitive credentials di code

### 3. Git Repository
- [ ] Initialize git jika belum: `git init`
- [ ] Add all files: `git add .`
- [ ] Commit changes: `git commit -m "Prepare for Railway deployment"`
- [ ] Push ke GitHub: `git push -u origin main`

---

## Railway Setup Checklist

### 1. Railway Account & Project
- [ ] Buat akun di https://railway.app
- [ ] Create new project di Railway dashboard
- [ ] Connect GitHub repository
- [ ] Select `hexaglass-backend` as root directory

### 2. Database Setup
- [ ] Add MySQL plugin di Railway
- [ ] Copy database credentials yang di-generate Railway
- [ ] Catat DB_HOST, DB_PORT, DB_USERNAME, DB_PASSWORD

### 3. Environment Variables (Critical!)
Tambahkan di Railway dashboard → Environment Variables:

```
# Application
APP_NAME=Hexaglass
APP_ENV=production
APP_KEY=base64:bQ8PDS7l2rj2Eqy4YWRZnilopk4x+29l/0TEyrWCvl0=
APP_DEBUG=false
APP_URL=https://your-domain.up.railway.app

# Database (dari Railway MySQL)
DB_HOST=<railway-mysql-host>
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=<your-generated-password>

# Cache
CACHE_STORE=database

# Session
SESSION_DRIVER=database

# Queue
QUEUE_CONNECTION=database

# Filesystem
FILESYSTEM_DISK=local

# Optional: Redis (jika ingin pakai)
REDIS_HOST=<railway-redis-host>
REDIS_PASSWORD=<password>
REDIS_PORT=6379

# Optional: Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@hexaglass.com
```

### 4. Build Configuration
- [ ] Railway auto-detect Laravel ✅
- [ ] Procfile sudah ada ✅
- [ ] Build script di composer.json sudah benar ✅

---

## Deployment Process

### Step 1: Initial Deploy
1. Go to Railway dashboard
2. Click "Deploy"
3. Watch logs untuk ensure build berjalan:
   - `composer install`
   - `npm install`
   - `npm run build`
   - `php artisan migrate --force`

### Step 2: Monitor Deployment
- [ ] Check "Deployments" tab
- [ ] Lihat build logs real-time
- [ ] Tunggu hingga status "Success"
- [ ] Note the deployment URL

### Step 3: Post-Deployment Verification
- [ ] Check app logs di Railway dashboard
- [ ] Test API endpoints:
  ```bash
  curl https://your-domain.up.railway.app/api/auth/login -X POST
  ```
- [ ] Verify database migrations berhasil
- [ ] Check storage directories permission

---

## Troubleshooting Guide

### Issue: Deploy gagal dengan error "composer install failed"
**Solution:**
- Check PHP version di Procfile
- Verify composer.json syntax
- Check disk space di Railway
- Try manual redeploy

### Issue: Database migration error
**Solution:**
- SSH ke Railway: `railway exec bash`
- Check connection: `php artisan tinker`
- Manual migrate: `php artisan migrate --force`
- Check table exists: `php artisan db:table`

### Issue: "Permission denied" pada storage
**Solution:**
- SSH: `railway exec bash`
- Fix permissions: `chmod -R 755 storage/ bootstrap/cache/`
- Restart app

### Issue: Assets not loading (CSS/JS error)
**Solution:**
- Verify `npm run build` completed
- Check `public/build/` exists
- Verify `APP_URL` environment variable benar
- Clear view cache: `php artisan view:clear`

### Issue: CORS error dari frontend
**Solution:**
- Update `config/cors.php`
- Add Railway domain ke allowed origins
- Or set `ALLOWED_ORIGINS` environment variable

### Issue: Mail tidak terkirim
**Solution:**
- Verify MAIL_* environment variables
- Test dengan: `php artisan tinker` → `Mail::to('test@example.com')->send(new TestMail())`
- Check mail logs di Railway
- Verify SMTP credentials

---

## Post-Deployment Maintenance

### Daily Tasks
- [ ] Monitor application logs
- [ ] Check error rate
- [ ] Verify API response times

### Weekly Tasks
- [ ] Review database size
- [ ] Check storage usage
- [ ] Backup database jika perlu

### Monthly Tasks
- [ ] Review security updates
- [ ] Update Laravel & dependencies
- [ ] Check for deprecated features
- [ ] Review access logs

---

## Rollback Plan

Jika ada issue critical:

1. Go to Railway → Deployments
2. Pilih previous working deployment
3. Click "Redeploy"
4. Monitor logs
5. Verify API endpoints

---

## Important Notes

⚠️ **SECURITY**
- Never commit `.env` file
- Always use strong passwords
- Enable HTTPS (Railway otomatis)
- Regularly update dependencies
- Use Railway's secret management

⚠️ **DATABASE**
- Regular backups
- Test restore procedure
- Monitor disk space
- Optimize slow queries

⚠️ **MONITORING**
- Setup error tracking (Sentry, etc)
- Monitor API performance
- Setup alerts untuk critical errors
- Keep audit logs

---

## Useful Commands

```bash
# SSH ke Railway
railway exec bash

# Check environment variables
railway env list

# View logs
railway logs

# Manual database migration
php artisan migrate --force

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# Generate app key
php artisan key:generate

# Check storage permissions
ls -la storage/
ls -la bootstrap/cache/
```

---

## Support Resources

- Railway Docs: https://docs.railway.app
- Laravel Docs: https://laravel.com/docs
- Troubleshooting: Check Railway logs first!

---

**Last Updated:** June 2024
**Status:** Ready for Production Deployment ✅
