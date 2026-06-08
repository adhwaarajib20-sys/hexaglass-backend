# Railway Deployment Guide - Hexaglass Backend

## 📋 Prerequisites

- Railway.app account
- Git repository (GitHub/GitLab/Gitea)
- MySQL database (dapat ditambahkan via Railway)
- Node.js 18+ (ditangani oleh Railway)
- PHP 8.3+ (ditangani oleh Railway)

---

## 🚀 Quick Start Deployment

### 1. Hubungkan Repository ke Railway

1. Buka [railway.app](https://railway.app)
2. Klik "New Project" → "Deploy from GitHub"
3. Pilih repository Anda
4. Railway akan otomatis mendeteksi ini adalah proyek Laravel

### 2. Konfigurasi Variabel Environment

Atur variabel berikut di section Railway:

```env
APP_NAME=MigasQueue
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-app.railway.app

APP_LOCALE=id
APP_FALLBACK_LOCALE=id

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_LEVEL=warning

# MySQL Configuration (akan otomatis diisi oleh Railway)
DB_CONNECTION=mysql
DB_HOST=${{MYSQL_HOST}}
DB_PORT=${{MYSQL_PORT}}
DB_DATABASE=${{MYSQL_NAME}}
DB_USERNAME=${{MYSQL_USER}}
DB_PASSWORD=${{MYSQL_PASSWORD}}

SESSION_DRIVER=database
SESSION_LIFETIME=120
CACHE_STORE=database

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

MAIL_MAILER=log

SANCTUM_STATEFUL_DOMAINS=your-app.railway.app
SESSION_DOMAIN=your-app.railway.app
```

### 3. Tambahkan MySQL Database Plugin

1. Di project Railway, klik "+ Add Plugin"
2. Pilih "MySQL"
3. Ini akan otomatis mengisi variabel DB_*

### 4. Deploy

Railway akan otomatis:
1. Mendeteksi Procfile
2. Menginstall dependensi Composer
3. Menjalankan script build.sh
4. Menjalankan migrasi
5. Build front-end assets
6. Memulai aplikasi

---

## 📁 Struktur Proyek

```
hexaglass-backend/
├── app/               # Kode aplikasi Laravel
├── bootstrap/         # File bootstrap
├── config/           # File konfigurasi
├── database/         # Migrations & seeders
├── public/           # Assets publik & index.php
├── resources/        # Views, CSS, JS
├── routes/           # Definisi route
├── storage/          # Upload, logs (dibuat saat runtime)
├── tests/            # Unit & feature tests
├── Procfile          # Konfigurasi web server Railway
├── railway.json      # Pengaturan deployment Railway
├── .env.example      # Template variabel environment
├── .env.production   # File environment production
├── build.sh          # Script build (berjalan saat deployment)
├── deploy.sh         # Script deployment manual
├── composer.json     # Dependensi PHP
└── package.json      # Dependensi Node.js
```

---

## 🔧 Build & Deployment Scripts

### Procfile
Mendefinisikan bagaimana Railway menjalankan aplikasi:
```
web: vendor/bin/heroku-php-apache2 public/
```

### build.sh
Otomatis berjalan saat deployment:
- Install dependensi PHP via Composer
- Install dependensi Node
- Build front-end assets dengan Vite
- Buat storage symlink

### deploy.sh
Script deployment manual (jalankan lokal jika diperlukan):
```bash
chmod +x deploy.sh
./deploy.sh
```

---

## 🗄️ Setup Database

### Automatic Migration
Railway akan otomatis menjalankan migrasi via `build.sh`.

### Manual Migration
Jika perlu menjalankan migrasi secara manual:
```bash
railway run php artisan migrate --force
```

### Seed Database
Untuk seed database dengan data awal:
```bash
railway run php artisan db:seed --force
```

---

## 🔑 Generate Application Key

Railway akan otomatis generate `APP_KEY` jika tidak diatur.

Untuk generate secara manual:
```bash
railway run php artisan key:generate --force
```

---

## 📝 Logging

Log disimpan di `storage/logs/` dan output ke Railway logs:
```bash
# Lihat logs
railway logs
```

Konfigurasi log level di variabel `LOG_LEVEL`:
- `debug` - Development
- `info` - Operasi normal
- `warning` - Hanya warning dan error (recommended untuk production)
- `error` - Hanya error

---

## 🛣️ Routes Caching

Untuk performa lebih baik, routes di-cache saat deployment:
```bash
php artisan route:cache
```

Untuk clear cache:
```bash
railway run php artisan route:cache
```

---

## 📦 Asset Compilation

Front-end assets di-build saat deployment:
```bash
npm install
npm run build
```

Assets yang di-build disimpan di `public/build/`.

---

## 🔗 Storage Symlink

Symlink storage dibuat saat deployment:
```bash
public/storage → storage/app/public
```

File yang di-upload ke `storage/app/public` dapat diakses via `/storage/` URLs.

---

## 🔒 Permissions & Roles

Jika menggunakan Spatie Laravel Permission:
```bash
railway run php artisan permission:cache-reset
```

---

## ⚠️ Troubleshooting

### 1. **500 Internal Server Error**
Lihat logs:
```bash
railway logs
```

Penyebab umum:
- Missing `APP_KEY` → Jalankan: `railway run php artisan key:generate --force`
- Database connection failed → Cek variabel `DB_*`
- Permissions issue → Jalankan: `railway run chmod -R 775 storage bootstrap/cache`

### 2. **Database Connection Refused**
- Pastikan MySQL plugin ditambahkan ke Railway project
- Cek variabel `DB_*` sesuai dengan pengaturan Railway MySQL
- Jalankan: `railway run php artisan migrate --force`

### 3. **Assets Return 404 (CSS/JS)**
- Jalankan: `railway run npm run build`
- Cek folder `public/build/` ada
- Verifikasi `ASSET_URL` di config/app.php

### 4. **File Upload Tidak Bekerja**
Jalankan:
```bash
railway run php artisan storage:link
```

### 5. **Cache Issues**
Clear semua cache:
```bash
railway run php artisan cache:clear
railway run php artisan config:clear
railway run php artisan view:clear
```

---

## 🚨 Deployment Checklist

- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_URL` ke Railway domain Anda
- [ ] Generate dan set `APP_KEY`
- [ ] Konfigurasi database (`DB_*` variables)
- [ ] Tambahkan MySQL plugin ke Railway
- [ ] Set `LOG_LEVEL=warning` untuk production
- [ ] Konfigurasi mail settings jika diperlukan
- [ ] Test file uploads ke storage
- [ ] Verifikasi routes di-cache
- [ ] Cek storage symlink dibuat
- [ ] Review application logs
- [ ] Test fitur kritis (login, display data, exports)

---

## 📞 Support & Documentation

- **Laravel Documentation**: https://laravel.com/docs
- **Railway Documentation**: https://docs.railway.app
- **Spatie Permission**: https://spatie.be/docs/laravel-permission
- **Laravel Sanctum**: https://laravel.com/docs/sanctum
- **Maatwebsite Excel**: https://docs.laravel-excel.com

---

## 🔄 Redeployment

Untuk trigger deployment baru:
1. Push perubahan ke branch main
2. Railway akan otomatis rebuild dan deploy

Atau manually redeploy di Railway dashboard:
1. Buka project Anda
2. Klik web service
3. Klik "Redeploy"

---

## 📊 Performance Tips

1. **Enable Query Caching**: Set `CACHE_STORE=redis` jika Redis tersedia
2. **Optimize Database**: Tambahkan indexes untuk frequently queried columns
3. **Use Horizon** untuk queue management (optional enhancement)
4. **Enable OPcache**: Ditangani otomatis oleh Railway
5. **Minify Assets**: Dilakukan otomatis oleh Vite di production

---

## 🔐 Security Best Practices

1. ✅ `APP_DEBUG=false` di production
2. ✅ Gunakan strong `APP_KEY` (auto-generated)
3. ✅ Enable HTTPS (Railway handle SSL otomatis)
4. ✅ Gunakan environment variables untuk data sensitif
5. ✅ Regular backups database dan uploads
6. ✅ Keep dependencies updated: `composer update`
7. ✅ Monitor logs secara regular

---

**Last Updated**: June 4, 2026  
**Compatible With**: Laravel 13, PHP 8.3+
