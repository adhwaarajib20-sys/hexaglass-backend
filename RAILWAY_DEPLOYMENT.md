# Panduan Deployment ke Railway

## Persiapan Sebelum Deploy

### 1. Setup Environment Variables di Railway

Tambahkan environment variables berikut di Railway dashboard:

```env
APP_NAME=Hexaglass
APP_ENV=production
APP_KEY=base64:bQ8PDS7l2rj2Eqy4YWRZnilopk4x+29l/0TEyrWCvl0=
APP_DEBUG=false
APP_URL=https://your-railway-domain.up.railway.app

# Database
DB_HOST=<railway-mysql-host>
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=<generated-password>

# Redis (opsional, untuk cache/session)
REDIS_HOST=<railway-redis-host>
REDIS_PORT=6379
REDIS_PASSWORD=<generated-password>

# Mail Configuration
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_ADDRESS=noreply@hexaglass.com
```

### 2. Database Setup

Railway akan otomatis membuat MySQL database. Pastikan:
- Koneksi database sudah ter-konfigurasi di environment variables
- Migration akan berjalan otomatis via post-deploy script

### 3. Storage & Permissions

Untuk production, pastikan:
- Directory `storage/` writable oleh web server
- Directory `bootstrap/cache/` writable oleh web server

### 4. Post-Deployment Script

Railway akan menjalankan build script dari `composer.json`. Pastikan composer script `setup` sudah benar:

```json
"setup": [
    "composer install",
    "@php -r \"file_exists('.env') || copy('.env.example', '.env');\"",
    "@php artisan key:generate",
    "@php artisan migrate --force",
    "npm install --ignore-scripts",
    "npm run build"
]
```

## Langkah-Langkah Deploy

1. **Push ke GitHub**
   ```bash
   git add .
   git commit -m "Prepare for Railway deployment"
   git push origin main
   ```

2. **Connect ke Railway**
   - Login ke railway.app
   - Create new project
   - Connect GitHub repository
   - Select this backend folder as the root

3. **Configure Services**
   - Tambah MySQL plugin di Railway
   - Tambah Redis plugin (opsional) di Railway
   - Set environment variables sesuai panduan di atas

4. **Deploy**
   - Railway akan otomatis detect Laravel
   - Akan menjalankan composer install
   - Akan menjalankan npm install & build
   - Akan menjalankan migrations

5. **Verify**
   - Check logs di Railway dashboard
   - Test API endpoints
   - Verify database migrations berjalan

## Troubleshooting

### Deploy gagal dengan error "permission denied"
- Pastikan directory `storage/` dan `bootstrap/cache/` writable
- SSH ke Railway dan jalankan:
  ```bash
  chmod -R 775 storage/
  chmod -R 775 bootstrap/cache/
  ```

### Database migration error
- Pastikan `DB_HOST`, `DB_USERNAME`, `DB_PASSWORD` benar
- Check di Railway MySQL plugin untuk credentials
- Jalankan manual jika diperlukan:
  ```bash
  php artisan migrate --force
  ```

### Asset tidak ter-load
- Pastikan `npm run build` berjalan di build script
- Check `APP_URL` environment variable benar

### CORS Error
- Update `config/cors.php` untuk allow Railway domain
- Atau set `FRONTEND_URL` environment variable

## Tips Keamanan

1. **APP_DEBUG=false** - Sudah diset di .env.production
2. **APP_KEY** - Generate baru untuk production jika perlu
3. **Database Password** - Gunakan password yang kuat
4. **Mail Credentials** - Gunakan app-specific password, bukan password main account
5. **HTTPS** - Railway otomatis SSL certificate

## Rollback

Jika ada issue setelah deploy:
1. Go to Railway deployments
2. Select previous working deployment
3. Click "Redeploy"

## Monitoring

- Check application logs di Railway dashboard
- Monitor resource usage (CPU, Memory)
- Setup alerts jika diperlukan

---

**Last Updated**: 2024
**Backend Version**: Laravel 13
