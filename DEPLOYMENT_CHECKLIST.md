# Hexaglass Backend - Railway Deployment Checklist

## ✅ Pre-Deployment Checklist

### 1. **Code Preparation**
- [ ] Semua code di-commit ke Git
- [ ] Tidak ada local environment variables di code
- [ ] Tidak ada hardcoded database credentials
- [ ] Tidak ada hardcoded API keys atau secrets
- [ ] Semua `.env` files di `.gitignore`
- [ ] Clean commit history (tanpa sensitive data)

### 2. **Configuration Files**
- [ ] ✅ `.env.example` updated dengan default yang benar
- [ ] ✅ `.env.production` dibuat dengan Railway variables
- [ ] ✅ `Procfile` dibuat untuk web process
- [ ] ✅ `railway.json` dibuat untuk build config
- [ ] ✅ `build.sh` script dibuat
- [ ] ✅ Directory `.gitkeep` files ditambahkan untuk storage/framework/logs

### 3. **Dependencies**
- [ ] Run `composer install` lokal untuk verify tidak ada errors
- [ ] Run `npm install` lokal untuk verify tidak ada errors
- [ ] Run `npm run build` lokal untuk verify front-end builds
- [ ] Semua dependencies memiliki locked versions
- [ ] Tidak ada conflicting package versions

### 4. **Database & Migrations**
- [ ] Semua migrations dibuat
- [ ] Migrations bisa dijalankan dengan `php artisan migrate --force`
- [ ] Database seeders siap (jika diperlukan)
- [ ] Foreign key constraints properly configured
- [ ] Database character set adalah UTF-8

### 5. **Laravel Configuration**
- [ ] ✅ `APP_NAME` set ke "MigasQueue"
- [ ] ✅ `APP_LOCALE` set ke "id" (Indonesian)
- [ ] ✅ Timezone correctly configured
- [ ] ✅ Cache driver set ke `database` untuk production
- [ ] ✅ Session driver set ke `database`
- [ ] ✅ Queue driver set ke `database`

### 6. **Application Functionality**
- [ ] Login/authentication bekerja
- [ ] Admin dashboard menampilkan dengan benar
- [ ] Data exports bekerja (Excel)
- [ ] File uploads bekerja dengan benar
- [ ] Permissions dan roles properly configured
- [ ] Semua API endpoints respond dengan benar
- [ ] Tidak ada 404 atau 500 errors di halaman kritis

### 7. **Security**
- [ ] `APP_DEBUG=false` di production
- [ ] `APP_ENV=production` di production
- [ ] Semua sensitive data di environment variables
- [ ] CORS properly configured jika diperlukan
- [ ] CSRF protection enabled
- [ ] SQL injection prevention implemented
- [ ] XSS protection enabled

### 8. **Assets & Front-End**
- [ ] CSS compiles tanpa errors
- [ ] JavaScript bundles tanpa errors
- [ ] Semua images dioptimasi
- [ ] Tidak ada broken image/font links
- [ ] Responsive design bekerja di mobile
- [ ] Semua icons load dengan benar
- [ ] DataTables menampilkan dengan benar

### 9. **Logging & Monitoring**
- [ ] Log channel dikonfigurasi
- [ ] Log level appropriate untuk production (warning)
- [ ] Logs directory writable
- [ ] Tidak ada sensitive data di logs
- [ ] Error tracking siap (jika applicable)

### 10. **Documentation**
- [ ] ✅ `README.md` up-to-date
- [ ] ✅ `RAILWAY_DEPLOYMENT.md` dibuat
- [ ] Database schema documented
- [ ] API endpoints documented
- [ ] Setup instructions clear

---

## 🚀 Deployment Steps

### Step 1: Push Code ke Repository
```bash
git add .
git commit -m "Prepare untuk Railway deployment"
git push origin main
```

### Step 2: Create Railway Project
1. Buka https://railway.app
2. Klik "New Project" → "Deploy from GitHub"
3. Pilih repository ini
4. Tunggu Railway untuk mendeteksi project

### Step 3: Add MySQL Database
1. Di Railway, klik "+ Add Plugin"
2. Pilih "MySQL"
3. Railway akan otomatis configure DB variables

### Step 4: Set Environment Variables
Di Railway dashboard, set variables ini:
```
APP_NAME=MigasQueue
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.railway.app

APP_LOCALE=id
APP_FALLBACK_LOCALE=id

LOG_LEVEL=warning

SANCTUM_STATEFUL_DOMAINS=your-domain.railway.app
SESSION_DOMAIN=your-domain.railway.app
```

MySQL variables akan otomatis terisi:
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### Step 5: Deploy
1. Railway otomatis memulai deployment saat Anda push code
2. Monitor deployment di Railway dashboard
3. Cek logs untuk errors

### Step 6: Verify Deployment
```bash
# Lihat logs
railway logs

# Cek application health
curl https://your-domain.railway.app

# Test API
curl https://your-domain.railway.app/api/auth/login
```

---

## ⚠️ Common Issues & Solutions

### Issue: 500 Error on First Load
**Solution:**
```bash
railway run php artisan key:generate --force
railway run php artisan migrate --force
railway run php artisan storage:link
```

### Issue: Database Connection Failed
**Solution:**
- Verify MySQL plugin ditambahkan
- Cek DB_* variables di Railway dashboard
- Jalankan: `railway run php artisan migrate --force`

### Issue: Assets Return 404
**Solution:**
```bash
railway run npm run build
railway run php artisan storage:link
```

### Issue: File Uploads Tidak Bekerja
**Solution:**
```bash
railway run php artisan storage:link
railway run chmod -R 775 storage bootstrap/cache
```

### Issue: Permission Denied on Storage
**Solution:**
```bash
railway run chmod -R 775 storage bootstrap/cache
railway run chown -R nobody:nogroup storage bootstrap/cache
```

---

## 📊 Post-Deployment Verification

- [ ] Homepage loads tanpa errors
- [ ] Login page accessible
- [ ] Bisa login dengan test credentials
- [ ] Admin dashboard menampilkan data
- [ ] Semua menu items bekerja
- [ ] Data tables menampilkan dengan benar
- [ ] Export Excel functionality bekerja
- [ ] File uploads succeed
- [ ] Tidak ada console errors
- [ ] Tidak ada network 404 errors
- [ ] Database dipopulasi dengan data
- [ ] Logs tidak menunjukkan errors
- [ ] Response times acceptable
- [ ] API endpoints respond dengan benar

---

## 🔄 Continuous Deployment

Setelah setup awal, Railway akan otomatis:
1. Detect pushes ke main branch Anda
2. Rebuild aplikasi
3. Jalankan migrations
4. Deploy versi baru

Untuk manually trigger deployment:
1. Buka Railway dashboard
2. Pilih project Anda
3. Klik "Redeploy"

---

## 📝 Maintenance Tasks

### Daily
- Monitor logs untuk errors
- Cek application health
- Verify database connectivity

### Weekly
- Review error logs
- Cek performance metrics
- Update dependencies (jika diperlukan)

### Monthly
- Backup database
- Review security logs
- Update Laravel packages
- Test disaster recovery plan

---

## 🆘 Support & Resources

- **Project Lead**: [Your Name]
- **Slack Channel**: #hexaglass-deployment
- **Documentation**: `/docs` folder
- **Emergency Hotline**: [Contact Info]
- **Railway Support**: https://railway.app/support

---

**Status**: ✅ Ready untuk Deployment  
**Last Updated**: June 4, 2026  
**Version**: 1.0.0
