# ⚡ Emergency Actions for 404 Error

## Immediate Actions (Do Now!)

### Action 1: Check Logs
```bash
railway logs | head -50
```

Copy the output and look for any of these errors:
- `Build failed`
- `Error:`
- `fatal:`
- `Exited with`

### Action 2: SSH and Diagnose
```bash
railway exec bash

# Quick checks
echo "=== PHP Version ==="
php --version

echo "=== Laravel Version ==="
php artisan --version

echo "=== Environment Variables ==="
php artisan tinker <<'EOF'
echo "APP_KEY=" . env('APP_KEY') . "\n";
echo "DB_HOST=" . env('DB_HOST') . "\n";
echo "DB_DATABASE=" . env('DB_DATABASE') . "\n";
EOF

echo "=== Recent Errors ==="
tail -20 storage/logs/laravel.log

exit
```

### Action 3: Restart
```bash
railway restart
```

---

## Most Common Causes & Fixes

| Symptom | Cause | Fix |
|---------|-------|-----|
| Still 404 after 10 mins | Build not done | Wait more or check `railway logs` |
| `Connection refused` error in logs | No database connection | Check DB_HOST, PORT, credentials in Railway env |
| `No application encryption key` | APP_KEY not set | Add to Railway env vars |
| `SQLSTATE[HY000]` | MySQL not accessible | Verify MySQL service running & credentials |
| `Process crashed` in logs | Application error | Check `storage/logs/laravel.log` |
| `/public directory` error | Procfile wrong | Verify Procfile has correct path |

---

## The Nuclear Option

If everything fails:

1. Go to Railway dashboard
2. Delete the deployment
3. Click "New Deploy"
4. Railway will redeploy from scratch

---

## Report These When Asking for Help

```bash
echo "=== LOGS ==="
railway logs -50

echo "=== ENV VARS ==="
railway env list

echo "=== SSH CHECK ==="
railway exec bash -c "
  echo 'PHP:' && php --version;
  echo 'Laravel:' && php artisan --version;
  echo 'DB Test:' && php artisan tinker -q <<'EOF'
    try {
      DB::connection()->getPdo();
      echo 'Database: OK\n';
    } catch (\Exception \$e) {
      echo 'Database: FAILED - ' . \$e->getMessage() . '\n';
    }
EOF
"
```

---

**Status**: Run diagnostic steps above and report what you find!
