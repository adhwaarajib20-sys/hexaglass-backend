@echo off
REM Script untuk membuka firewall port 8000 di Windows

echo.
echo ========================================
echo  Membuka Firewall Port 8000
echo ========================================
echo.

REM Request admin privileges
if not "%1"=="am_admin" (
    echo Requesting admin privileges...
    powershell -Command "Start-Process cmd -ArgumentList '/c cd %cd% && %~s0 am_admin' -Verb RunAs"
    exit /b
)

REM Buka inbound port 8000
echo Menambah Inbound Rule untuk port 8000...
netsh advfirewall firewall add rule name="Laravel Port 8000" dir=in action=allow protocol=tcp localport=8000

echo.
echo ========================================
echo  SELESAI! Port 8000 sekarang terbuka
echo ========================================
echo.
pause
