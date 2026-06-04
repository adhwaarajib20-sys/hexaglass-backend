<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — MigasQueue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'] },
                    colors: {
                        brand: { 
                            green: '#1a7a2e', 
                            light: '#ffffff',
                            dark: '#145c22',
                            orange: '#e8650a',
                            greenAccent: '#17a34a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Transisi input form agar lebih smooth */
        input:focus { box-shadow: 0 0 0 3px rgba(26, 122, 46, 0.2); border-color: #1a7a2e; }
    </style>
</head>
<body class="min-h-screen bg-[#08170f] flex items-center justify-center p-4 md:p-6 relative">

    <!-- Background Decoration (Blurs untuk estetika) -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-24 -left-24 w-96 h-96 bg-brand-green/30 rounded-full blur-3xl opacity-60"></div>
        <div class="absolute top-1/2 right-0 w-[500px] h-[500px] bg-brand-orange/15 rounded-full blur-3xl opacity-60 transform translate-x-1/2 -translate-y-1/2"></div>
    </div>

    <!-- Container Utama (Logo color theme) -->
    <div class="w-full max-w-5xl bg-[#122c1d]/95 backdrop-blur-xl rounded-3xl border border-brand-green/20 shadow-2xl overflow-hidden grid lg:grid-cols-[1.1fr_1fr] relative z-10 text-white">
        
        <!-- KIRI: Branding & Logo -->
        <div class="hidden lg:flex flex-col justify-between p-10 bg-gradient-to-br from-brand-green to-brand-dark">
            <div>
                <div class="w-20 h-20 mb-6 rounded-2xl bg-white/10 ring-1 ring-white/10 flex items-center justify-center overflow-hidden">
                    <img src="{{ asset('img/logo.png') }}" alt="Logo MigasQueue" class="w-20 h-20 md:w-24 md:h-24 object-contain">
                </div>

                <h1 class="text-3xl font-bold text-white tracking-tight">MigasQueue</h1>
                <p class="mt-3 text-slate-300 leading-relaxed text-sm max-w-sm">
                    Sistem Manajemen Antrean & Pelaporan Digital untuk Hilirisasi Migas yang efisien dan terintegrasi.
                </p>
            </div>

            <div class="space-y-4 mt-8">
                <div class="flex items-center gap-3 p-4 rounded-2xl bg-black/10 border border-brand-green/30">
                    <div class="w-2 h-2 rounded-full bg-brand-greenAccent animate-pulse"></div>
                    <div>
                        <p class="text-sm font-semibold text-white">Sistem Real-Time</p>
                        <p class="text-xs text-brand-light/80">Pantau antrean secara langsung tanpa hambatan.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 p-4 rounded-2xl bg-black/10 border border-brand-orange/30">
                    <div class="w-2 h-2 rounded-full bg-brand-orange"></div>
                    <div>
                        <p class="text-sm font-semibold text-white">Terintegrasi Mobile</p>
                        <p class="text-xs text-brand-light/80">Sinkronisasi data dengan aplikasi lapangan.</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- KANAN: Form Login (logo theme) -->
        <div class="bg-[#0e2518] p-8 md:p-12 flex flex-col justify-center">
                <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10">
                    <svg viewBox="0 0 120 120" xmlns="http://www.w3.org/2000/svg" class="w-full h-full">
                        <path d="M60 5C35 35 20 65 20 85C20 110 40 120 60 120C80 120 100 110 100 85C100 65 85 35 60 5Z" fill="#1a7a2e"/>
                        <path d="M60 18C45 40 35 60 35 80C35 95 45 105 60 105C75 105 85 95 85 80C85 60 75 40 60 18Z" fill="#17a34a"/>
                        <path d="M68 30C78 45 82 62 82 72C82 84 72 94 62 94C52 94 42 84 48 72C54 60 64 48 68 30Z" fill="#e8650a"/>
                    </svg>
                </div>
                <span class="font-bold text-white text-xl">MigasQueue</span>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-white">Masuk ke Akun</h2>
                <p class="text-brand-light/80 text-sm mt-1">Masukkan kredensial untuk mengakses dashboard manajemen.</p>
            </div>

            @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl bg-red-900/60 border border-red-700 flex items-start gap-3">
                <span class="text-red-200 text-sm font-bold">⚠️</span>
                <span class="text-sm text-red-200">{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label for="email" class="block text-sm font-medium text-brand-light mb-1.5">Email Perusahaan</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        value="{{ old('email') }}" 
                        required 
                        autofocus
                        class="w-full rounded-xl border border-brand-green/40 bg-[#16321e] px-4 py-3.5 text-sm text-brand-light outline-none transition-all focus:border-brand-light focus:bg-[#173a24] placeholder:text-brand-light/50"
                        placeholder="nama@perusahaan.com"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-brand-light mb-1.5">Password</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        required
                        class="w-full rounded-xl border border-brand-green/40 bg-[#16321e] px-4 py-3.5 text-sm text-brand-light outline-none transition-all focus:border-brand-light focus:bg-[#173a24] placeholder:text-brand-light/50"
                        placeholder="••••••••"
                    >
                </div>

                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center gap-2 text-sm text-brand-light/80">
                        <input type="checkbox" class="rounded border-brand-light/40 text-brand-green focus:ring-brand-green focus:ring-2">
                        Ingat saya
                    </label>
                    <a href="#" class="text-sm font-medium text-brand-orange hover:text-brand-light hover:underline">Lupa password?</a>
                </div>

                <button type="submit" class="w-full mt-2 rounded-xl bg-brand-green px-4 py-3.5 text-sm font-semibold text-white shadow-lg shadow-brand-green/30 transition-all hover:bg-brand-dark hover:shadow-brand-green/40 active:scale-[0.98] flex justify-center items-center gap-2">
                    Masuk
                </button>
            </form>

                <div class="mt-8 border-t border-slate-700 pt-6 flex flex-col items-center gap-1 text-xs text-slate-400">
                <p>© 2026 PT Migas Hilir Jabar</p>
                <p class="text-[10px]">v3.2.0 — Sistem Antrean & Pelaporan Digital</p>
            </div>
        </div>
    </div>
</body>
</html>