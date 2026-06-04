# 🔍 DEBUG CHECKLIST - Expo Go Tidak Bisa Connect

## ⚡ LANGKAH PERBAIKAN CEPAT

### **1️⃣ BUKA FIREWALL PORT 8000** (PALING PENTING!)
```bash
# Jalankan sebagai Administrator:
cd c:\laragon\www\hexaglass-backend
buka_firewall.bat
```
✅ Ini adalah masalah paling umum!

### **2️⃣ STOP Backend Sekarang**
- Tekan Ctrl+C di terminal PHP

### **3️⃣ START Backend dengan Host 0.0.0.0**
```bash
cd c:\laragon\www\hexaglass-backend
php artisan serve --host=0.0.0.0 --port=8000
```
✅ Harus melihat: `INFO  Server running on [http://0.0.0.0:8000]`

### **4️⃣ TEST DARI LAPTOP SENDIRI**
Buka PowerShell baru:
```powershell
# Test bisa ping ke port 8000
Test-NetConnection -ComputerName 10.62.33.192 -Port 8000

# Hasil harus: TcpTestSucceeded : True
```

### **5️⃣ DI HP - SCAN ULANG QR CODE**
- Buka Expo Go
- Scan QR dari terminal npm start
- Tunggu bundling
- ✅ Semoga berhasil!

---

## 🎯 JIKA MASIH TIDAK BISA

### **A. Cek Status Backend**
```bash
# Di terminal backend, lihat ada error?
# Harus ada message: "Server running on [http://0.0.0.0:8000]"
```

### **B. Cek Network**
```bash
# Di HP, coba ping laptop:
ping 10.62.33.192

# Atau test di PC:
Test-NetConnection -ComputerName 10.62.33.192 -Port 8000
```

### **C. Database Issue?**
Cek di backend console ada error "database connection"?
```bash
# Di folder backend:
php artisan migrate --seed
```

### **D. Hard Reset Expo Go**
1. Close Expo Go app
2. Buka ulang
3. Shake phone
4. Tap "Reload"

---

## 📱 AKUN TEST (Pastikan di-seed)
- Admin: `admin@hexaglass.com` / `password`
- Operator: `operator@hexaglass.com` / `password`

---

## ✅ TANDA SUKSES

- ✅ Backend: `Server running on [http://0.0.0.0:8000]`
- ✅ Laptop: `Test-NetConnection` return `TcpTestSucceeded : True`
- ✅ HP: Expo Go app load tanpa blank
- ✅ Login: Username/password accept
- ✅ Dashboard: Data terlihat

---

**PRIORITAS PERTAMA: BUKA FIREWALL PORT 8000!**
