# Railway Environment Variables Setup Guide

**Domain**: https://web-production-2598a.up.railway.app

## 🔧 Required Environment Variables for Railway

Salin dan paste environment variables berikut ke Railway Dashboard (Project → Settings → Environment Variables):

### Application Configuration
```
APP_NAME=Hexaglass
APP_ENV=production
APP_KEY=base64:bQ8PDS7l2rj2Eqy4YWRZnilopk4x+29l/0TEyrWCvl0=
APP_DEBUG=false
APP_URL=https://web-production-2598a.up.railway.app
```

### Database Configuration
**Dapatkan nilai-nilai ini dari Railway MySQL Plugin yang sudah dibuat:**

```
DB_HOST=<copy dari Railway MySQL Host>
DB_PORT=<copy dari Railway MySQL Port, biasanya 3306>
DB_DATABASE=<copy dari Railway MySQL Database, biasanya railway>
DB_USERNAME=<copy dari Railway MySQL Username, biasanya root>
DB_PASSWORD=<copy dari Railway MySQL Password>
```

**Cara mendapatkan Database Credentials:**
1. Go to Railway Dashboard
2. Click service MySQL
3. Lihat tab "Connect" 
4. Copy setiap value: Hostname, Port, Database, Username, Password

### Application Settings
```
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
```

### Mail Configuration (Optional)
Jika ingin menggunakan email notifikasi:

```
MAIL_MAILER=smtp
MAIL_SCHEME=tls
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-specific-password
MAIL_FROM_ADDRESS=noreply@hexaglass.com
MAIL_FROM_NAME=Hexaglass
```

**Note**: Untuk Gmail, gunakan App Password bukan password akun biasa. Cara:
1. Enable 2FA di Gmail
2. Go to https://myaccount.google.com/apppasswords
3. Generate App Password untuk "Mail"
4. Copy password ke MAIL_PASSWORD

---

## 📋 Step-by-Step Setup di Railway

### 1. Verify MySQL Service is Running
```bash
railway exec bash
mysql -h $DB_HOST -u $DB_USERNAME -p$DB_PASSWORD
show databases;
exit
```

### 2. Add Environment Variables
1. Go to Railway Dashboard
2. Click your project
3. Click Settings
4. Go to "Environment Variables"
5. Add variables one by one (atau copy-paste sekaligus)

### 3. Trigger Redeploy
- Setelah add environment variables, Railway akan otomatis redeploy
- Watch logs untuk memastikan deployment berjalan lancar

### 4. Verify Deployment
```bash
# SSH ke Railway
railway exec bash

# Test database connection
php artisan tinker
>>> DB::connection()->getPdo()

# Check migrations
php artisan migrate:status

# Check app key
php artisan tinker
>>> env('APP_KEY')

# View logs
exit
railway logs
```

---

## ✅ Verification Checklist

- [ ] APP_KEY sudah diset
- [ ] APP_DEBUG=false (PENTING!)
- [ ] APP_URL=https://web-production-2598a.up.railway.app
- [ ] Database credentials dari Railway sudah diset
- [ ] Build berhasil tanpa error
- [ ] Migrations berhasil berjalan
- [ ] API endpoints dapat diakses

### Test API Endpoints:
```bash
curl https://web-production-2598a.up.railway.app/api/auth/login \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password"
  }'

# Or test health check
curl https://web-production-2598a.up.railway.app/health
```

---

## 🔒 Security Notes

⚠️ **CRITICAL** - Pastikan:
- APP_DEBUG SELALU false di production
- Jangan share APP_KEY
- Database password aman (generated oleh Railway)
- MAIL_PASSWORD tidak di-commit (hanya di Railway)
- HTTPS aktif (Railway otomatis)

---

## 🆘 Troubleshooting

### Error: "SQLSTATE[HY000] [2002] No such file or directory"
**Penyebab**: Database credentials salah  
**Solusi**: Double-check DB_HOST, DB_USERNAME, DB_PASSWORD

### Error: "Specified key was too long"
**Penyebab**: Laravel 13 memerlukan konfigurasi khusus untuk MySQL  
**Solusi**: 
```bash
railway exec bash
php artisan migrate --force
```

### Error: "Database does not exist"
**Penyebab**: Database belum dibuat  
**Solusi**: Migration will create tables automatically

### Deployment Stuck
**Solusi**:
1. Check logs: `railway logs`
2. SSH: `railway exec bash`
3. Manual migrate: `php artisan migrate --force`
4. Clear caches: `php artisan cache:clear`

---

## 📞 Support Links

- Railway Docs: https://docs.railway.app
- Laravel Docs: https://laravel.com/docs
- Check Railway logs first untuk error details!

---

**Last Updated**: June 8, 2024  
**Status**: Ready for Configuration ✅
