<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hilir Migas - Platform Monitoring Operasional Digital</title>
    <meta name="description" content="Platform digital untuk monitoring operasional, distribusi, infrastruktur, dan pengelolaan aset Hilir Migas secara real-time.">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Fonts: Poppins & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    
    <!-- AOS Library -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0B1120; }
        ::-webkit-scrollbar-thumb { background: #15803D; border-radius: 8px; }
        ::-webkit-scrollbar-thumb:hover { background: #14532D; }

        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6 { font-family: 'Poppins', sans-serif; }

        /* Glassmorphism Utility */
        .glass-dark {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        .glass-light {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        /* Brand Gradient Text */
        .text-gradient {
            background: linear-gradient(135deg, #15803D 0%, #EA580C 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Card Gradient Border */
        .card-gradient-border {
            position: relative;
            background-clip: padding-box;
            border: 1px solid transparent;
        }
        .card-gradient-border::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 1rem;
            padding: 1px;
            background: linear-gradient(135deg, rgba(21, 128, 61, 0.5), rgba(234, 88, 12, 0.3));
            -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask-composite: exclude;
            pointer-events: none;
        }

        /* Hero Overlay Dark Premium */
        .hero-overlay {
            background: linear-gradient(180deg, rgba(11,17,32,0.9) 0%, rgba(11,17,32,0.6) 50%, rgba(11,17,32,0.85) 100%);
        }
    </style>
</head>
<body class="bg-[#0B1120] text-gray-300 antialiased overflow-x-hidden" x-data="{ mobileMenuOpen: false, lightboxOpen: false, lightboxImage: '' }">

    <!-- ================= NAVBAR ================= -->
    <nav class="fixed top-0 left-0 right-0 z-50 bg-[#0B1120]/80 backdrop-blur-md border-b border-white/5 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <a href="#" class="flex items-center gap-2">
                    <img src="{{ asset('img/logo.png') }}" alt="Hilir Migas Logo" class="h-10 w-auto">
                    <span class="font-bold text-xl tracking-tight text-white hidden sm:block">Hilir Migas</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8 text-sm font-medium">
                    <a href="#beranda" class="text-gray-300 hover:text-white transition-colors">Beranda</a>
                    <a href="#tentang" class="text-gray-300 hover:text-white transition-colors">Tentang</a>
                    <a href="#fitur" class="text-gray-300 hover:text-white transition-colors">Fitur</a>
                    <a href="#operasional" class="text-gray-300 hover:text-white transition-colors">Operasional</a>
                    <a href="#galeri" class="text-gray-300 hover:text-white transition-colors">Galeri</a>
                </div>

                <!-- CTA Desktop -->
                <div class="hidden md:flex items-center">
                    <a href="{{ asset('Migas.apk') }}" download class="bg-gradient-to-r from-[#15803D] to-[#EA580C] hover:from-[#166534] hover:to-[#C2410C] text-white px-6 py-2.5 rounded-full font-semibold text-sm shadow-lg shadow-orange-900/20 hover:shadow-orange-500/30 transition-all duration-300 hover:-translate-y-0.5">
                        Download APK
                    </a>
                </div>

                <!-- Mobile Hamburger -->
                <button @click="mobileMenuOpen = !mobileMenuOpen" class="md:hidden text-gray-300 hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="mobileMenuOpen" x-transition class="md:hidden bg-[#111827] border-t border-white/5">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="#beranda" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-white/5">Beranda</a>
                <a href="#tentang" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-white/5">Tentang</a>
                <a href="#fitur" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-white/5">Fitur</a>
                <a href="#operasional" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-white/5">Operasional</a>
                <a href="#galeri" class="block px-3 py-2 rounded-md text-base font-medium text-gray-300 hover:text-white hover:bg-white/5">Galeri</a>
                <div class="pt-4">
                    <a href="{{ asset('Migas.apk') }}" download class="block w-full text-center bg-gradient-to-r from-[#15803D] to-[#EA580C] text-white px-4 py-3 rounded-lg font-semibold">
                        Download APK
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- ================= HERO SECTION ================= -->
    <section id="beranda" class="relative min-h-screen flex items-center overflow-hidden pt-16">
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('img/hilirmigas.jpg') }}" alt="Hilir Migas Operasional" class="w-full h-full object-cover">
            <div class="hero-overlay absolute inset-0"></div>
        </div>
        
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div data-aos="fade-up" data-aos-duration="1000">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm border border-white/10 rounded-full px-4 py-1.5 mb-6">
                    <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                    <span class="text-xs font-medium text-white/80 tracking-wide">Sistem Terintegrasi</span>
                </div>
                <h1 class="text-4xl sm:text-6xl lg:text-7xl font-bold text-white leading-tight mb-6">
                    Hilir Migas <br>
                    <span class="text-gradient">Monitoring System</span>
                </h1>
                <p class="text-lg sm:text-xl text-gray-300 max-w-2xl mb-10 leading-relaxed">
                    Platform digital untuk monitoring operasional, distribusi, infrastruktur, aset, dan fasilitas CNG secara real-time, terintegrasi, dan modern.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="{{ asset('Migas.apk') }}" download class="inline-flex items-center justify-center gap-2 bg-gradient-to-r from-[#15803D] to-[#EA580C] hover:shadow-lg hover:shadow-orange-500/25 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                        Download Migas.apk
                    </a>
                    <button onclick="document.getElementById('tentang').scrollIntoView({behavior: 'smooth'})" class="inline-flex items-center justify-center gap-2 glass-light text-white px-8 py-4 rounded-xl font-semibold text-lg hover:bg-white/20 transition-all duration-300">
                        Pelajari Lebih Lanjut
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 z-10 hidden sm:block">
            <div class="animate-bounce">
                <div class="w-6 h-10 border-2 border-white/30 rounded-full flex justify-center">
                    <div class="w-1 h-2 bg-white/60 rounded-full mt-2 animate-pulse"></div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= TENTANG KAMI ================= -->
    <section id="tentang" class="py-24 bg-[#0B1120]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- KIRI: Teks -->
                <div data-aos="fade-right" data-aos-duration="1000">
                    <span class="text-[#EA580C] font-semibold text-sm tracking-widest uppercase">Tentang Aplikasi</span>
                    <h2 class="text-4xl font-bold text-white mt-2 mb-6">Platform Digital <span class="text-gradient">Operasional Hilir Migas</span></h2>
                    <p class="text-gray-400 text-lg leading-relaxed mb-4">
                        Hilir Migas merupakan platform digital yang membantu perusahaan melakukan monitoring fasilitas CNG, infrastruktur perpipaan, aset, dan operasional lapangan secara efisien dan transparan.
                    </p>
                    <p class="text-gray-400 text-lg leading-relaxed mb-6">
                        Dengan teknologi <span class="text-white font-semibold">real-time monitoring</span> dan dashboard interaktif, kami memberikan solusi terpadu untuk mengelola seluruh aspek operasional Hilir Migas dengan mudah.
                    </p>
                    <div class="space-y-3">
                        <div class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-green-500"></div><span class="text-gray-300">Platform terintegrasi untuk semua kebutuhan monitoring</span></div>
                        <div class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div><span class="text-gray-300">Update data real-time setiap saat</span></div>
                        <div class="flex items-center gap-3"><div class="w-1.5 h-1.5 rounded-full bg-green-500"></div><span class="text-gray-300">Keamanan data tingkat enterprise</span></div>
                    </div>
                </div>

                <!-- KANAN: Gambar -->
                <div data-aos="fade-left" data-aos-duration="1000" class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-[#15803D] to-[#EA580C] rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/10">
                        <img src="{{ asset('img/parkir.jpg') }}" alt="Fasilitas Hilir Migas" class="w-full h-auto object-cover transform transition duration-700 group-hover:scale-105">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= FITUR UNGGULAN ================= -->
    <section id="fitur" class="py-24 bg-[#111827]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="1000">
                <span class="text-[#15803D] font-semibold text-sm tracking-widest uppercase">Fitur Premium</span>
                <h2 class="text-4xl font-bold text-white mt-2 mb-4">Fitur Unggulan</h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">Berbagai fitur canggih untuk mendukung operasional Hilir Migas secara real-time</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Fitur 1 -->
                <div data-aos="fade-up" data-aos-delay="0" class="card-gradient-border p-8 rounded-2xl bg-[#1F2937]/30 hover:bg-[#1F2937]/60 transition-all duration-500 group">
                    <div class="bg-gradient-to-br from-[#14532D] to-[#15803D] rounded-xl w-14 h-14 flex items-center justify-center mb-4 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Monitoring Real-Time</h3>
                    <p class="text-gray-400">Pantau kondisi fasilitas secara langsung dengan data yang update setiap detik.</p>
                </div>

                <!-- Fitur 2 -->
                <div data-aos="fade-up" data-aos-delay="100" class="card-gradient-border p-8 rounded-2xl bg-[#1F2937]/30 hover:bg-[#1F2937]/60 transition-all duration-500 group">
                    <div class="bg-gradient-to-br from-[#9A3412] to-[#EA580C] rounded-xl w-14 h-14 flex items-center justify-center mb-4 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Dashboard Interaktif</h3>
                    <p class="text-gray-400">Visualisasi data operasional dengan grafik dan statistik yang informatif.</p>
                </div>

                <!-- Fitur 3 -->
                <div data-aos="fade-up" data-aos-delay="200" class="card-gradient-border p-8 rounded-2xl bg-[#1F2937]/30 hover:bg-[#1F2937]/60 transition-all duration-500 group">
                    <div class="bg-gradient-to-br from-[#1E3A5F] to-[#3B82F6] rounded-xl w-14 h-14 flex items-center justify-center mb-4 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Manajemen Aset</h3>
                    <p class="text-gray-400">Kelola aset perusahaan dengan mudah dan terorganisir dalam satu platform.</p>
                </div>

                <!-- Fitur 4 -->
                <div data-aos="fade-up" data-aos-delay="300" class="card-gradient-border p-8 rounded-2xl bg-[#1F2937]/30 hover:bg-[#1F2937]/60 transition-all duration-500 group">
                    <div class="bg-gradient-to-br from-[#4C1D95] to-[#8B5CF6] rounded-xl w-14 h-14 flex items-center justify-center mb-4 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Pelaporan Digital</h3>
                    <p class="text-gray-400">Pelaporan cepat dan terintegrasi dengan format yang professional dan lengkap.</p>
                </div>

                <!-- Fitur 5 -->
                <div data-aos="fade-up" data-aos-delay="400" class="card-gradient-border p-8 rounded-2xl bg-[#1F2937]/30 hover:bg-[#1F2937]/60 transition-all duration-500 group">
                    <div class="bg-gradient-to-br from-[#831843] to-[#EC4899] rounded-xl w-14 h-14 flex items-center justify-center mb-4 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292 4 4 0 010-5.292z M19 1H5a4 4 0 00-4 4v10a4 4 0 004 4h14a4 4 0 004-4V5a4 4 0 00-4-4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Manajemen Pengguna</h3>
                    <p class="text-gray-400">Role-based access control untuk keamanan maksimal data perusahaan.</p>
                </div>

                <!-- Fitur 6 -->
                <div data-aos="fade-up" data-aos-delay="500" class="card-gradient-border p-8 rounded-2xl bg-[#1F2937]/30 hover:bg-[#1F2937]/60 transition-all duration-500 group">
                    <div class="bg-gradient-to-br from-[#164E63] to-[#06B6D4] rounded-xl w-14 h-14 flex items-center justify-center mb-4 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 20h8a2 2 0 002-2V6a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Mobile Friendly</h3>
                    <p class="text-gray-400">Akses sistem dari perangkat mobile kapan saja dan di mana saja.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= OPERASIONAL (DISTRIBUSI) ================= -->
    <section id="operasional" class="py-24 bg-[#0B1120]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- KIRI: Gambar -->
                <div data-aos="fade-right" data-aos-duration="1000" class="relative group order-2 lg:order-1">
                    <div class="absolute -inset-1 bg-gradient-to-r from-[#15803D] to-[#EA580C] rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/10">
                        <img src="{{ asset('img/truk.jpg') }}" alt="Distribusi Hilir Migas" class="w-full h-auto object-cover transform transition duration-700 group-hover:scale-105">
                    </div>
                </div>

                <!-- KANAN: Teks -->
                <div data-aos="fade-left" data-aos-duration="1000" class="order-1 lg:order-2">
                    <span class="text-[#EA580C] font-semibold text-sm tracking-widest uppercase">Operasional</span>
                    <h2 class="text-4xl font-bold text-white mt-2 mb-6">Distribusi & <span class="text-gradient">Transportasi</span></h2>
                    <p class="text-gray-400 text-lg leading-relaxed mb-4">
                        Kelola seluruh aktivitas distribusi, transportasi, dan operasional lapangan dengan pemantauan real-time. 
                        Sistem ini memastikan efisiensi rute, keamanan pengiriman, dan manajemen logistik CNG yang terintegrasi.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 text-gray-300"><div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Pemantauan distribusi real-time</li>
                        <li class="flex items-center gap-3 text-gray-300"><div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Manajemen transportasi terintegrasi</li>
                        <li class="flex items-center gap-3 text-gray-300"><div class="w-1.5 h-1.5 rounded-full bg-green-500"></div> Optimalisasi rute operasional</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= MONITORING & MAINTENANCE ================= -->
    <section class="py-24 bg-[#111827]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">
                <!-- KIRI: Teks -->
                <div data-aos="fade-right" data-aos-duration="1000">
                    <span class="text-[#15803D] font-semibold text-sm tracking-widest uppercase">Maintenance</span>
                    <h2 class="text-4xl font-bold text-white mt-2 mb-6">Monitoring & <span class="text-gradient">Inspeksi Fasilitas</span></h2>
                    <p class="text-gray-400 text-lg leading-relaxed mb-4">
                        Dukung aktivitas monitoring, inspeksi, dan maintenance fasilitas CNG secara digital. 
                        Sistem kami mencatat setiap jadwal perawatan, kondisi aset, dan tindakan perbaikan secara terstruktur.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-3 text-gray-300"><div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div> Jadwal inspeksi terintegrasi</li>
                        <li class="flex items-center gap-3 text-gray-300"><div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div> Laporan kondisi aset real-time</li>
                        <li class="flex items-center gap-3 text-gray-300"><div class="w-1.5 h-1.5 rounded-full bg-orange-500"></div> Tindakan perbaikan terdokumentasi</li>
                    </ul>
                </div>

                <!-- KANAN: Gambar -->
                <div data-aos="fade-left" data-aos-duration="1000" class="relative group">
                    <div class="absolute -inset-1 bg-gradient-to-r from-[#15803D] to-[#EA580C] rounded-2xl blur opacity-30 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative rounded-2xl overflow-hidden shadow-2xl border border-white/10">
                        <img src="{{ asset('img/teknisi.jpg') }}" alt="Teknisi Monitoring" class="w-full h-auto object-cover transform transition duration-700 group-hover:scale-105">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= GALERI ================= -->
    <section id="galeri" class="py-24 bg-[#0B1120]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="1000">
                <span class="text-[#EA580C] font-semibold text-sm tracking-widest uppercase">Portofolio</span>
                <h2 class="text-4xl font-bold text-white mt-2 mb-4">Galeri Operasional</h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">Dokumentasi visual dari berbagai fasilitas dan aktivitas operasional Hilir Migas</p>
            </div>

            <!-- Grid Galeri -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6">
                @php
                    $galleryImages = ['truk.jpg', 'teknisi.jpg', 'parkir.jpg', 'hilirmigas.jpg', 'gas.jpg', 'demosistem.jpg', 'fotbar.jpg', 'ptmigashilirjabar.jpg'];
                @endphp
                @foreach($galleryImages as $img)
                <div data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}" class="relative group rounded-xl overflow-hidden cursor-pointer" @click="lightboxOpen = true; lightboxImage = '{{ asset('img/' . $img) }}'">
                    <div class="aspect-square md:aspect-[4/3] overflow-hidden">
                        <img src="{{ asset('img/' . $img) }}" alt="Galeri Hilir Migas" class="w-full h-full object-cover transition duration-500 group-hover:scale-110">
                    </div>
                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition duration-300 flex items-center justify-center">
                        <svg class="w-10 h-10 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 8v6m-3-3h6"></path></svg>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- ================= STATISTIK ================= -->
    <section class="py-24 bg-[#111827]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div data-aos="fade-up" data-aos-delay="0" class="text-center">
                    <div class="text-5xl font-bold text-white mb-2">24/7</div>
                    <p class="text-gray-400">Monitoring Operasional</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="100" class="text-center">
                    <div class="text-5xl font-bold text-gradient mb-2">100%</div>
                    <p class="text-gray-400">Digital Reporting</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200" class="text-center">
                    <div class="text-5xl font-bold text-white mb-2">Real-Time</div>
                    <p class="text-gray-400">Data Update</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="300" class="text-center">
                    <div class="text-5xl font-bold text-gradient mb-2">Multi</div>
                    <p class="text-gray-400">User Access</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= KEUNGGULAN ================= -->
    <section class="py-24 bg-[#0B1120]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="1000">
                <h2 class="text-4xl font-bold text-white mt-2 mb-4">Mengapa Memilih <span class="text-gradient">Hilir Migas</span>?</h2>
                <p class="text-gray-400 text-lg max-w-2xl mx-auto">Keunggulan platform kami dalam mendukung digitalisasi operasional</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div data-aos="fade-up" data-aos-delay="0" class="p-6 rounded-xl bg-[#1F2937]/50 border border-white/5 hover:border-white/10 transition duration-300">
                    <svg class="w-8 h-8 text-[#15803D] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <h3 class="text-lg font-bold text-white mb-1">Efisiensi Operasional</h3>
                    <p class="text-gray-400 text-sm">Optimalkan proses operasional dengan sistem terintegrasi.</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="100" class="p-6 rounded-xl bg-[#1F2937]/50 border border-white/5 hover:border-white/10 transition duration-300">
                    <svg class="w-8 h-8 text-[#EA580C] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h3 class="text-lg font-bold text-white mb-1">Monitoring Real-Time</h3>
                    <p class="text-gray-400 text-sm">Pantau kondisi aset dan operasional secara live.</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200" class="p-6 rounded-xl bg-[#1F2937]/50 border border-white/5 hover:border-white/10 transition duration-300">
                    <svg class="w-8 h-8 text-[#3B82F6] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <h3 class="text-lg font-bold text-white mb-1">Data Terpusat</h3>
                    <p class="text-gray-400 text-sm">Semua data tersimpan rapi dalam satu platform.</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="300" class="p-6 rounded-xl bg-[#1F2937]/50 border border-white/5 hover:border-white/10 transition duration-300">
                    <svg class="w-8 h-8 text-[#06B6D4] mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 20h8a2 2 0 002-2V6a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    <h3 class="text-lg font-bold text-white mb-1">Akses Mobile</h3>
                    <p class="text-gray-400 text-sm">Kelola operasional dari mana saja melalui aplikasi.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= TESTIMONIAL ================= -->
    <section class="py-24 bg-[#111827]">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="1000">
                <span class="text-[#15803D] font-semibold text-sm tracking-widest uppercase">Testimoni</span>
                <h2 class="text-4xl font-bold text-white mt-2 mb-4">Apa Kata Mereka?</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div data-aos="fade-up" data-aos-delay="0" class="p-8 rounded-2xl bg-[#1F2937]/30 border border-white/5 hover:border-green-500/30 transition duration-300">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#14532D] to-[#15803D] flex items-center justify-center text-white font-bold text-lg">A</div>
                        <div><h4 class="text-white font-semibold">Andi Pratama</h4><span class="text-xs text-gray-500">Manajer Operasional</span></div>
                    </div>
                    <p class="text-gray-400 italic">"Sistem monitoring real-time sangat membantu kami dalam mengelola distribusi gas dengan lebih efisien."</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="100" class="p-8 rounded-2xl bg-[#1F2937]/30 border border-white/5 hover:border-orange-500/30 transition duration-300">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#9A3412] to-[#EA580C] flex items-center justify-center text-white font-bold text-lg">S</div>
                        <div><h4 class="text-white font-semibold">Siti Rahma</h4><span class="text-xs text-gray-500">Supervisor Lapangan</span></div>
                    </div>
                    <p class="text-gray-400 italic">"Fitur pelaporan digital sangat mempermudah pekerjaan admin, tidak perlu rekap manual lagi."</p>
                </div>
                <div data-aos="fade-up" data-aos-delay="200" class="p-8 rounded-2xl bg-[#1F2937]/30 border border-white/5 hover:border-blue-500/30 transition duration-300">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#1E3A5F] to-[#3B82F6] flex items-center justify-center text-white font-bold text-lg">R</div>
                        <div><h4 class="text-white font-semibold">Rudi Hartono</h4><span class="text-xs text-gray-500">Teknisi Maintenance</span></div>
                    </div>
                    <p class="text-gray-400 italic">"Aplikasi mobile-nya sangat responsif, memudahkan saya untuk cek jadwal inspeksi di lapangan."</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ================= DOWNLOAD SECTION ================= -->
    <section class="relative py-32 overflow-hidden bg-gradient-to-br from-[#0B1120] via-[#111827] to-[#0B1120]">
        <!-- Decorative Elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-green-500 opacity-5 rounded-full -mr-48 -mt-48 blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-orange-500 opacity-5 rounded-full -ml-48 -mb-48 blur-3xl"></div>
        
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-20" data-aos="fade-up" data-aos-duration="1000">
                <h2 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white mb-6">
                    Siap Mulai Digitalisasi?
                </h2>
                <p class="text-xl text-gray-300 max-w-2xl mx-auto">
                    Unduh aplikasi Hilir Migas sekarang dan mulai monitoring operasional secara real-time
                </p>
            </div>

            <!-- Download Options -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
                <!-- Option 1: Direct Download -->
                <div data-aos="fade-up" data-aos-delay="0" class="p-8 rounded-2xl bg-[#1F2937]/50 border border-green-500/30 hover:border-green-500/60 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#15803D] to-[#22C55E] flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Download Langsung</h3>
                    </div>
                    <p class="text-gray-300 mb-6">Unduh file APK secara langsung dan instal di perangkat Android Anda</p>
                    <a href="{{ asset('Migas.apk') }}" download class="block w-full bg-gradient-to-r from-[#15803D] to-[#22C55E] hover:from-[#166534] hover:to-[#16A34A] text-white px-6 py-4 rounded-xl font-semibold text-center transition-all duration-300 hover:shadow-lg hover:shadow-green-500/25">
                        Download APK (Direct)
                    </a>
                    <p class="text-xs text-gray-400 mt-4">Ukuran: ~45 MB | Kompatibel dengan Android 8.0+</p>
                </div>

                <!-- Option 2: QR Code -->
                <div data-aos="fade-up" data-aos-delay="100" class="p-8 rounded-2xl bg-[#1F2937]/50 border border-orange-500/30 hover:border-orange-500/60 transition-all duration-300 backdrop-blur-sm">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-[#EA580C] to-[#F97316] flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 4v16m8-8H4" stroke="currentColor" stroke-width="2" stroke-linecap="round"/></svg>
                        </div>
                        <h3 class="text-2xl font-bold text-white">Scan QR Code</h3>
                    </div>
                    <p class="text-gray-300 mb-6">Scan kode QR dengan smartphone untuk download langsung dari Expo</p>
                    <div class="bg-white rounded-lg p-4 w-full flex justify-center mb-6">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=https://expo.dev/accounts/adhwaa20/projects/hexaglass/builds/a844a4fc-11a9-4108-b7e3-7460ee85f2c3" alt="QR Code Download" class="w-40 h-40">
                    </div>
                    <p class="text-xs text-gray-400">Arahkan kamera ke QR Code untuk membuka link download</p>
                </div>
            </div>

            <!-- System Requirements -->
            <div data-aos="fade-up" data-aos-delay="200" class="bg-[#1F2937]/30 rounded-2xl p-8 border border-white/5">
                <h3 class="text-xl font-bold text-white mb-6">Persyaratan Sistem</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-white">Sistem Operasi</p>
                            <p class="text-xs text-gray-400">Android 8.0 atau lebih baru</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-white">Memori</p>
                            <p class="text-xs text-gray-400">Minimal 2 GB RAM</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-white">Penyimpanan</p>
                            <p class="text-xs text-gray-400">Minimal 50 MB ruang kosong</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-green-500 flex-shrink-0 mt-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <div>
                            <p class="text-sm font-semibold text-white">Koneksi</p>
                            <p class="text-xs text-gray-400">Internet aktif (WiFi/4G)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- CTA Button -->
            <div class="text-center mt-12" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ asset('Migas.apk') }}" download class="inline-flex items-center gap-3 bg-gradient-to-r from-[#15803D] to-[#EA580C] hover:from-[#166534] hover:to-[#C2410C] text-white px-12 py-5 rounded-2xl font-bold text-lg shadow-2xl hover:shadow-orange-500/40 transition-all duration-300 hover:-translate-y-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    Download Aplikasi Sekarang
                </a>
                <p class="text-gray-400 text-sm mt-6">Gratis • Aman • Tanpa Ads</p>
            </div>
        </div>
    </section>

    <!-- ================= FOOTER ================= -->
    <footer class="bg-[#0B1120] border-t border-white/5 pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Brand -->
                <div class="col-span-1 md:col-span-1">
                    <img src="{{ asset('img/logo.png') }}" alt="Hilir Migas" class="h-10 w-auto mb-4">
                    <h3 class="text-white font-semibold text-lg mb-2">Hilir Migas</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">Platform monitoring operasional, distribusi, infrastruktur, dan aset CNG secara digital.</p>
                </div>

                <!-- Navigasi -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Navigasi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#beranda" class="text-gray-400 hover:text-white transition-colors">Beranda</a></li>
                        <li><a href="#tentang" class="text-gray-400 hover:text-white transition-colors">Tentang</a></li>
                        <li><a href="#fitur" class="text-gray-400 hover:text-white transition-colors">Fitur</a></li>
                        <li><a href="#galeri" class="text-gray-400 hover:text-white transition-colors">Galeri</a></li>
                    </ul>
                </div>

                <!-- Aplikasi -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Aplikasi</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ asset('Migas.apk') }}" download class="text-gray-400 hover:text-white transition-colors">Download Aplikasi</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Dokumentasi</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">FAQ</a></li>
                    </ul>
                </div>

                <!-- Kontak & Sosmed -->
                <div>
                    <h4 class="text-white font-semibold mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li>Email: info@hilirmigas.id</li>
                        <li>Support: 24/7 Tersedia</li>
                    </ul>
                    <div class="flex gap-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg></a>
                        <a href="#" class="text-gray-400 hover:text-white"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0C8.74 0 8.333.015 7.053.072 5.775.132 4.905.333 4.14.63c-.789.306-1.459.717-2.126 1.384S.935 3.35.63 4.14C.333 4.905.131 5.775.072 7.053.012 8.333 0 8.74 0 12s.015 3.667.072 4.947c.06 1.277.261 2.148.558 2.913.306.788.717 1.459 1.384 2.126.667.666 1.336 1.079 2.126 1.384.766.296 1.636.499 2.913.558C8.333 23.988 8.74 24 12 24s3.667-.015 4.947-.072c1.277-.06 2.148-.262 2.913-.558.788-.306 1.459-.718 2.126-1.384.666-.667 1.079-1.335 1.384-2.126.296-.765.499-1.636.558-2.913.06-1.28.072-1.687.072-4.947s-.015-3.667-.072-4.947c-.06-1.277-.262-2.149-.558-2.913-.306-.789-.718-1.459-1.384-2.126C21.315 1.347 20.646.935 19.856.63c-.765-.297-1.636-.499-2.913-.558C15.667.012 15.26 0 12 0zm0 2.16c3.203 0 3.585.016 4.85.071 1.17.055 1.805.249 2.227.415.562.217.96.477 1.382.896.419.42.679.819.896 1.381.164.422.36 1.057.413 2.227.057 1.266.07 1.646.07 4.85s-.015 3.585-.074 4.85c-.061 1.17-.256 1.805-.421 2.227-.224.562-.479.96-.899 1.382-.419.419-.824.679-1.38.896-.42.164-1.065.36-2.235.413-1.274.057-1.649.07-4.859.07-3.211 0-3.586-.015-4.859-.074-1.171-.061-1.816-.256-2.236-.421-.569-.224-.96-.479-1.379-.899-.421-.419-.69-.824-.9-1.38-.165-.42-.359-1.065-.42-2.235-.045-1.26-.061-1.649-.061-4.844 0-3.196.016-3.586.061-4.861.061-1.17.255-1.814.42-2.234.21-.57.479-.96.9-1.381.419-.419.81-.689 1.379-.898.42-.166 1.051-.361 2.221-.421 1.275-.045 1.65-.06 4.859-.06l.045.03zm0 3.678c-3.405 0-6.162 2.76-6.162 6.162 0 3.405 2.76 6.162 6.162 6.162 3.405 0 6.162-2.76 6.162-6.162 0-3.405-2.76-6.162-6.162-6.162zM12 16c-2.21 0-4-1.79-4-4s1.79-4 4-4 4 1.79 4 4-1.79 4-4 4zm7.846-10.405c0 .795-.646 1.44-1.44 1.44-.795 0-1.44-.646-1.44-1.44 0-.794.646-1.439 1.44-1.439.793-.001 1.44.645 1.44 1.439z"/></svg></a>
                    </div>
                </div>
            </div>

            <!-- Copyright -->
            <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row justify-between items-center text-sm text-gray-500">
                <p>© {{ date('Y') }} Hilir Migas. All Rights Reserved.</p>
                <div class="flex gap-6 mt-4 md:mt-0">
                    <a href="{{ url('/hakcipta') }}" class="hover:text-white transition-colors">Hak Cipta</a>
                    <a href="#" class="hover:text-white transition-colors">Privacy</a>
                    <a href="#" class="hover:text-white transition-colors">Terms</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- ================= LIGHTBOX ================= -->
    <div x-show="lightboxOpen" x-cloak class="fixed inset-0 z-[999] bg-black/90 backdrop-blur-md flex items-center justify-center p-4" 
         x-transition.opacity.duration.300ms
         @click="lightboxOpen = false">
        <div class="max-w-5xl max-h-[90vh] relative">
            <button @click="lightboxOpen = false" class="absolute -top-12 right-0 text-white/80 hover:text-white p-2">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <img :src="lightboxImage" alt="Gallery Image" class="w-full h-auto max-h-[85vh] object-contain rounded-lg shadow-2xl border border-white/10">
        </div>
    </div>

    <!-- ================= SCRIPTS ================= -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });
    </script>
</body>
</html>