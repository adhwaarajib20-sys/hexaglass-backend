# 📋 Quick Setup Guide - Railway Deployment

## File-file yang sudah disiapkan:

✅ **Procfile** - Konfigurasi untuk Railway  
✅ **.railway.json** - Build configuration  
✅ **.env.production** - Production environment template  
✅ **.env.example** - Updated dengan konfigurasi yang benar  
✅ **RAILWAY_DEPLOYMENT.md** - Detailed deployment guide  
✅ **DEPLOYMENT_CHECKLIST.md** - Step-by-step checklist  
✅ **deploy.sh** - Post-deployment optimization script  

---

## Langkah Cepat (5 Menit)

### 1. Commit & Push ke GitHub
```bash
cd c:\laragon\www\coba\hexaglass-backend
git add .
git commit -m "🚀 Prepare for Railway deployment"
git push origin main
```

### 2. Setup di Railway Dashboard
1. Go to https://railway.app
2. Click "New Project"
3. Select "Deploy from GitHub"
4. Choose your GitHub repository
5. Select the `hexaglass-backend` folder

### 3. Add Database
1. Click "Add Service"
2. Select "MySQL"
3. Railway akan auto-generate credentials

### 4. Configure Environment Variables
Copy-paste ke Railway Environment Variables:
```
APP_NAME=Hexaglass
APP_ENV=production
APP_KEY=base64:bQ8PDS7l2rj2Eqy4YWRZnilopk4x+29l/0TEyrWCvl0=
APP_DEBUG=false
APP_URL=https://your-project.up.railway.app
CACHE_STORE=database
SESSION_DRIVER=database
QUEUE_CONNECTION=database
FILESYSTEM_DISK=local
```

Database variables akan otomatis di-inject oleh Railway.

### 5. Deploy
Click "Deploy" button dan tunggu ✅

---

## Environment Variables yang Diperlukan

Dari Railway MySQL Plugin, dapatkan:
- `DB_HOST`
- `DB_PORT`
- `DB_USERNAME`
- `DB_PASSWORD`

Set juga:
- `APP_URL` = URL dari Railway (contoh: https://hexaglass-backend.up.railway.app)

---

## Testing Setelah Deploy

```bash
# Test API
curl https://your-url.up.railway.app/api/auth/login -X POST

# SSH ke Railway
railway exec bash

# Check logs
php artisan logs

# Verify database
php artisan db:show

# List tables
php artisan db:table
```

---

## Important Notes

⚠️ Jangan lupa:
- Update `APP_URL` di Railway dengan domain yang benar
- Pastikan `APP_DEBUG=false` untuk production
- Database credentials dari Railway sudah auto-set
- Check logs jika ada error saat deploy

---

## Next Steps

Setelah deploy berhasil:
1. Update frontend API_CONFIG dengan URL Railway
2. Setup email/SMTP jika diperlukan
3. Configure Redis untuk cache (opsional)
4. Setup monitoring dan logging

---

**Status**: ✅ Ready to Deploy!

Untuk bantuan lebih lanjut, lihat:
- `RAILWAY_DEPLOYMENT.md` - Detailed guide
- `DEPLOYMENT_CHECKLIST.md` - Complete checklist
- Railway Docs: https://docs.railway.app
