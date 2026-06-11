<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Download Hilir Migas - Aplikasi Monitoring Hilir Migas</title>
    <meta name="description" content="Download aplikasi Hilir Migas untuk Android dan iOS">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        body {
            background: linear-gradient(135deg, #16a34a 0%, #f97316 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 600px;
            width: 90%;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            text-align: center;
        }

        .logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 30px;
            background: linear-gradient(135deg, #16a34a 0%, #f97316 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 40px;
            font-weight: bold;
        }

        h1 {
            color: #1f2937;
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: bold;
        }

        .subtitle {
            color: #6b7280;
            font-size: 16px;
            margin-bottom: 30px;
        }

        .download-options {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        @media (max-width: 600px) {
            .download-options {
                grid-template-columns: 1fr;
            }
        }

        .download-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            text-decoration: none;
            transition: all 0.3s ease;
            color: #1f2937;
            background: white;
        }

        .download-btn:hover {
            border-color: #16a34a;
            box-shadow: 0 10px 30px rgba(22, 163, 74, 0.2);
            transform: translateY(-5px);
        }

        .download-btn.android {
            border-color: #16a34a;
            background: linear-gradient(135deg, rgba(22, 163, 74, 0.05) 0%, rgba(22, 163, 74, 0.02) 100%);
        }

        .download-btn.ios {
            border-color: #f97316;
            background: linear-gradient(135deg, rgba(249, 115, 22, 0.05) 0%, rgba(249, 115, 22, 0.02) 100%);
        }

        .icon {
            font-size: 40px;
        }

        .label {
            font-weight: 600;
            font-size: 16px;
        }

        .version {
            font-size: 12px;
            color: #9ca3af;
        }

        .qr-section {
            background: #f9fafb;
            border-radius: 12px;
            padding: 20px;
            margin: 20px 0;
        }

        .qr-section h3 {
            color: #1f2937;
            font-size: 16px;
            margin-bottom: 15px;
            font-weight: 600;
        }

        .qr-code {
            background: white;
            padding: 15px;
            border-radius: 8px;
            display: inline-block;
            border: 2px solid #e5e7eb;
        }

        .qr-code img {
            width: 150px;
            height: 150px;
            display: block;
        }

        .qr-text {
            color: #6b7280;
            font-size: 14px;
            margin-top: 10px;
        }

        .back-link {
            color: #16a34a;
            text-decoration: none;
            font-weight: 500;
            display: inline-block;
            margin-top: 20px;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #15803d;
        }

        .info-box {
            background: #f0fdf4;
            border-left: 4px solid #16a34a;
            padding: 15px;
            border-radius: 8px;
            margin-top: 20px;
            text-align: left;
            font-size: 14px;
            color: #166534;
        }

        .info-box strong {
            display: block;
            margin-bottom: 8px;
        }

        .spinner {
            display: none;
            animation: spin 1s linear infinite;
            margin-right: 8px;
            vertical-align: middle;
        }

        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">📱</div>
        
        <h1>Download Hilir Migas</h1>
        <p class="subtitle">Sistem Monitoring Operasional Hilir Migas</p>

        <div class="download-options">
            <!-- Android Download -->
            <a href="javascript:downloadAndroid()" class="download-btn android">
                <div class="icon">🤖</div>
                <div class="label">Android</div>
                <div class="version">APK & Play Store</div>
            </a>

            <!-- iOS Download -->
            <a href="javascript:downloadIOS()" class="download-btn ios">
                <div class="icon">🍎</div>
                <div class="label">iOS</div>
                <div class="version">App Store</div>
            </a>
        </div>

        <!-- QR Code Section -->
        <div class="qr-section">
            <h3>Scan untuk Download</h3>
            <div class="qr-code">
                <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://expo.dev/accounts/adhwaa20/projects/hexaglass/builds/a844a4fc-11a9-4108-b7e3-7460ee85f2c3" alt="QR Code untuk Download">
            </div>
            <p class="qr-text">Scan dengan kamera smartphone Anda</p>
        </div>

        <!-- Info Box -->
        <div class="info-box">
            <strong>⚙️ Syarat Sistem Minimum:</strong>
            • Android 8.0+ atau iOS 12.0+<br>
            • Koneksi internet stabil<br>
            • Minimal 100MB storage
        </div>

        <a href="/" class="back-link">← Kembali ke Landing Page</a>
    </div>

    <script>
        function downloadAndroid() {
            // Redirect ke Expo build atau Google Play Store
            // Expo build URL akan mendeteksi platform dan mengarahkan ke APK
            window.location.href = 'https://expo.dev/accounts/adhwaa20/projects/hexaglass/builds/a844a4fc-11a9-4108-b7e3-7460ee85f2c3';
            
            // Fallback untuk APK download langsung (jika tersedia)
            setTimeout(() => {
                // Attempt direct APK download if available
                fetch('https://expo.dev/accounts/adhwaa20/projects/hexaglass/builds/a844a4fc-11a9-4108-b7e3-7460ee85f2c3')
                    .catch(err => console.log('APK download initiated'));
            }, 100);
        }

        function downloadIOS() {
            // Redirect ke halaman iOS download atau App Store
            // Akan membuka App Store jika user di iOS, atau halaman info di browser lain
            const isIOS = /iPad|iPhone|iPod/.test(navigator.userAgent);
            
            if (isIOS) {
                // Jika user di iOS, buka App Store (sesuaikan dengan ID app Anda)
                window.location.href = 'https://apps.apple.com/id/app/id-number-here'; // Ganti dengan ID app yang benar
            } else {
                // Jika user tidak di iOS, buka halaman info download
                window.location.href = 'https://expo.dev/accounts/adhwaa20/projects/hexaglass/builds/a844a4fc-11a9-4108-b7e3-7460ee85f2c3';
            }
        }

        // Auto-redirect setelah 3 detik jika user membuka langsung dari QR code
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('auto') === '1') {
            const platform = urlParams.get('platform') || 'android';
            setTimeout(() => {
                if (platform === 'ios') {
                    downloadIOS();
                } else {
                    downloadAndroid();
                }
            }, 1000);
        }
    </script>
</body>
</html>
