<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hak Cipta - Hilir Migas</title>
    <meta name="description" content="Informasi tentang HexaGlass dan hak cipta Hilir Migas">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body class="bg-[#0B1120] text-gray-300 antialiased overflow-x-hidden">
    <!-- ================= NAVBAR ================= -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#0B1120]/80 backdrop-blur-md border-b border-white/5 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="/" class="flex items-center gap-2">
                    <img src="{{ asset('img/logo.png') }}" alt="Hilir Migas Logo" class="h-10 w-auto">
                    <span class="font-bold text-xl tracking-tight text-white hidden sm:block">Hilir Migas</span>
                </a>

                <!-- Back Button -->
                <a href="/" class="text-gray-300 hover:text-white transition-colors font-semibold">← Kembali ke Beranda</a>
            </div>
        </div>
    </nav>

    <!-- ================= MAIN CONTENT ================= -->
    <section class="min-h-screen flex items-center justify-center pt-32 pb-16">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-16">
                <img src="{{ asset('img/logo.png') }}" alt="Hilir Migas Logo" class="h-16 w-auto mx-auto mb-6">
                <h1 class="text-4xl sm:text-5xl font-bold text-white mb-2">Hak Cipta</h1>
                <p class="text-gray-400">Platform Monitoring Operasional Hilir Migas</p>
            </div>

            <!-- Content Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Tentang HexaGlass -->
                    <div class="bg-[#1F2937]/50 border border-white/5 rounded-2xl p-8 backdrop-blur-sm hover:border-white/10 transition-all duration-300">
                        <h2 class="text-2xl font-bold text-white mb-4">Tentang HexaGlass</h2>
                        <div class="space-y-4 text-gray-300 leading-relaxed">
                            <p>
                                HexaGlass adalah perusahaan rintisan di bidang teknologi perangkat lunak yang berfokus pada pengembangan website dan aplikasi mobile. Nama HexaGlass berasal dari gabungan kata "Hexa" yang melambangkan struktur enam sisi yang kuat, stabil, dan terintegrasi, serta "Glass" yang merepresentasikan kejernihan, transparansi, dan sudut pandang yang tajam.
                            </p>
                            <p>
                                Sebagaimana kacamata membantu manusia melihat lebih jelas, HexaGlass hadir untuk membantu bisnis dan organisasi melihat solusi digital secara lebih terstruktur, efisien, dan tepat sasaran. Dengan latar belakang Teknologi Rekayasa Perangkat Lunak (TRPL), HexaGlass mengedepankan pendekatan rekayasa sistem yang terencana, scalable, dan berorientasi pada kebutuhan pengguna.
                            </p>
                        </div>
                    </div>

                    <!-- Tentang Hilir Migas -->
                    <div class="bg-[#1F2937]/50 border border-white/5 rounded-2xl p-8 backdrop-blur-sm hover:border-white/10 transition-all duration-300">
                        <h2 class="text-2xl font-bold text-white mb-4">Tentang Hilir Migas</h2>
                        <div class="space-y-4 text-gray-300 leading-relaxed">
                            <p>
                                Hilir Migas adalah platform digital terintegrasi untuk monitoring operasional, distribusi, infrastruktur, dan pengelolaan aset sektor hilir migas. Platform ini dirancang untuk memberikan solusi monitoring real-time yang komprehensif dan mudah digunakan.
                            </p>
                            <p>
                                Aplikasi ini dikembangkan menggunakan teknologi terkini dan best practices dalam software engineering untuk memastikan keandalan, keamanan, dan skalabilitas sistem.
                            </p>
                        </div>
                    </div>

                    <!-- Hak Cipta & Lisensi -->
                    <div class="bg-[#1F2937]/50 border border-white/5 rounded-2xl p-8 backdrop-blur-sm hover:border-white/10 transition-all duration-300">
                        <h2 class="text-2xl font-bold text-white mb-4">Hak Cipta & Lisensi</h2>
                        <div class="space-y-4 text-gray-300 leading-relaxed">
                            <p>
                                © {{ date('Y') }} Hilir Migas. All Rights Reserved.
                            </p>
                            <p>
                                Konten, desain, dan kode sumber dalam platform ini dilindungi oleh hak cipta. Dilarang mengkopi, memodifikasi, atau mendistribusikan tanpa izin dari pemilik hak cipta.
                            </p>
                            <p>
                                Untuk informasi lebih lanjut atau permintaan lisensi, silakan hubungi tim kami melalui info@hilirmigas.id
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="lg:col-span-1">
                    <!-- Info Card -->
                    <div class="bg-gradient-to-br from-green-500/10 to-orange-500/10 border border-green-500/30 rounded-2xl p-6 backdrop-blur-sm sticky top-28">
                        <h3 class="text-lg font-bold text-white mb-4">Informasi Kontak</h3>
                        <div class="space-y-3 text-sm text-gray-300">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-green-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                <div>
                                    <p class="font-semibold text-white">Email</p>
                                    <p>info@hilirmigas.id</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-orange-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                <div>
                                    <p class="font-semibold text-white">Support</p>
                                    <p>24/7 Tersedia</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Download Card -->
                    <div class="mt-6 bg-gradient-to-br from-green-600 to-orange-600 rounded-2xl p-6 shadow-lg shadow-orange-900/20">
                        <h3 class="text-lg font-bold text-white mb-2">Download Aplikasi</h3>
                        <p class="text-sm text-white/80 mb-4">Dapatkan aplikasi Hilir Migas untuk pengalaman terbaik</p>
                        <a href="{{ asset('Migas.apk') }}" download class="block w-full bg-white text-green-600 px-4 py-3 rounded-lg font-semibold text-center hover:bg-gray-100 transition-colors">
                            Download APK
                        </a>
                    </div>
                </div>
            </div>

            <!-- Footer Links -->
            <div class="mt-16 pt-8 border-t border-white/5 text-center">
                <p class="text-gray-500 mb-4">Tautan Penting</p>
                <div class="flex flex-wrap justify-center gap-6">
                    <a href="/" class="text-gray-400 hover:text-white transition-colors">Beranda</a>
                    <a href="/#tentang" class="text-gray-400 hover:text-white transition-colors">Tentang</a>
                    <a href="/#fitur" class="text-gray-400 hover:text-white transition-colors">Fitur</a>
                    <a href="/#galeri" class="text-gray-400 hover:text-white transition-colors">Galeri</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-[#0B1120] border-t border-white/5 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-gray-500 text-sm">
            <p>© {{ date('Y') }} Hilir Migas. All Rights Reserved.</p>
        </div>
    </footer>
</body>
</html>
