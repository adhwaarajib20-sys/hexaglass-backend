# 🎯 FINAL SETUP GUIDE - Hexaglass Backend on Railway

**Production Domain**: https://web-production-2598a.up.railway.app

---

## 📋 Configuration Status

✅ Backend code deployed to GitHub  
✅ Procfile configured for Railway  
✅ View components fixed  
✅ Environment templates created  
✅ CORS configuration added  

**⏭️ NEXT STEP**: Set environment variables in Railway dashboard

---

## 🔧 Step-by-Step Final Setup

### Step 1: Get Database Credentials from Railway (2 minutes)

1. **Open Railway Dashboard**
   - Go to https://railway.app
   - Select your project

2. **Copy MySQL Credentials**
   - Click the MySQL service
   - Go to "Connect" tab
   - You'll see something like:
     ```
     MYSQL_HOST=railway.proxy.rlwy.net
     MYSQL_PORT=3306
     MYSQL_DATABASE=railway
     MYSQL_USERNAME=root
     MYSQL_PASSWORD=xxxxxxxxxxx
     ```

3. **Save these values** (you'll need them in next step)

---

### Step 2: Add Environment Variables (3 minutes)

1. **Go to Settings**
   - Click your project
   - Go to "Settings"
   - Select "Environment Variables"

2. **Add Variables One by One** (or paste all at once)

**Copy exactly as shown (replace the db values with yours):**

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
DB_HOST=railway.proxy.rlwy.net
DB_PORT=3306
DB_DATABASE=railway
DB_USERNAME=root
DB_PASSWORD=paste_your_password_here
```

3. **Click Save**
   - Railway akan otomatis redeploy

---

### Step 3: Monitor Deployment (2 minutes)

1. **Watch the Build**
   ```bash
   # In terminal:
   railway logs -f
   ```

2. **Look for Success**
   - Should see: "Build Successful"
   - Should see: "Migrations completed"
   - Should see: "Application started"

---

## ✅ Verify Everything Works

### SSH into Railway
```bash
railway exec bash
```

### Test Database Connection
```bash
php artisan tinker
>>> DB::connection()->getPdo()
>>> exit
```

### Check if Migrations Ran
```bash
php artisan migrate:status
```

### View Recent Logs
```bash
railway logs -20
```

---

## 🧪 Test API Endpoints

### From your local computer:

```bash
# Test health check
curl https://web-production-2598a.up.railway.app/health

# Test login (should return error with proper schema)
curl https://web-production-2598a.up.railway.app/api/auth/login \
  -X POST \
  -H "Content-Type: application/json" \
  -d '{"email":"test@example.com","password":"test"}'

# Test another API endpoint
curl https://web-production-2598a.up.railway.app/api/dashboard
```

---

## 📊 What's Running

- **Web Server**: Apache with PHP 8.3
- **Database**: MySQL 8.0 (Railway managed)
- **Cache**: Database-backed
- **Queue**: Database-backed
- **Logs**: Stack (file-based)

---

## 🔒 Security Checklist

- ✅ APP_DEBUG=false (NEVER enable in production)
- ✅ APP_KEY is secure
- ✅ Database password from Railway
- ✅ HTTPS enabled automatically
- ✅ CORS configured for domain

---

## 🆘 Troubleshooting

### "Build failed" Error

**Check logs first:**
```bash
railway logs | grep -i error
```

**Common issues:**
- DB credentials wrong → Check Railway MySQL service
- APP_KEY not set → Copy from here: `base64:bQ8PDS7l2rj2Eqy4YWRZnilopk4x+29l/0TEyrWCvl0=`
- Database doesn't exist → Railway creates it automatically

### "Cannot connect to database"

```bash
railway exec bash
php artisan tinker

# Test connection
>>> DB::connection()->getConfig()
>>> DB::raw('select 1')
```

### "API returns 500 error"

```bash
# Check application logs
railway logs

# Or SSH and view logs
railway exec bash
cat storage/logs/laravel.log | tail -100
```

---

## 📞 Support Resources

| Issue | Solution |
|-------|----------|
| Database not connecting | Check DB credentials match MySQL service |
| Build keeps failing | Check Rails logs for error details |
| API returning 404 | Verify APP_URL is set correctly |
| CORS errors | Domain must be in CORS config |
| Storage permission denied | Run: `chmod -R 755 storage/` |

---

## 🎯 Next Steps After Deployment

1. **Setup Frontend**
   - Update frontend API URL to: `https://web-production-2598a.up.railway.app/api`
   - Deploy frontend to Railway

2. **Monitor Application**
   - Setup error tracking (Sentry, etc)
   - Enable application monitoring
   - Setup alerting

3. **Database Backups**
   - Configure automated backups
   - Test restore procedure
   - Document backup schedule

4. **Production Optimization**
   - Monitor logs regularly
   - Check error rates
   - Review API performance

---

## 📝 Files Available for Reference

| File | Purpose |
|------|---------|
| **RAILWAY_QUICK_REF.md** | 3-step quick setup |
| **RAILWAY_ENV_SETUP.md** | Detailed environment guide |
| **DEPLOYMENT_CHECKLIST.md** | Complete verification |
| **config/cors.php** | CORS configuration |
| **.env.production** | Production template |
| **Procfile** | Railway deployment config |

---

## 🚀 Once Everything is Working

1. **Update Frontend**
   ```
   API_URL=https://web-production-2598a.up.railway.app/api
   ```

2. **Test Full Flow**
   - Login from frontend
   - Create data
   - Verify in database
   - Check logs

3. **Production Hardening**
   - Enable rate limiting
   - Setup firewall rules
   - Configure backups
   - Enable monitoring

---

**Status**: ✅ Ready for Environment Configuration

**Your Domain**: https://web-production-2598a.up.railway.app

**Next**: Go to Railway Dashboard → Settings → Add Environment Variables

---

*For any issues, check the logs first: `railway logs`*
