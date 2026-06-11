<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hilir Migas - Sistem Monitoring Operasional Digital</title>
    <meta name="description" content="Platform digital untuk monitoring operasional, distribusi, infrastruktur, dan pengelolaan aset Hilir Migas secara real-time.">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- AOS Library -->
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f3f4f6;
        }
        ::-webkit-scrollbar-thumb {
            background: #16a34a;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #15803d;
        }

        /* Smooth Scroll */
        html {
            scroll-behavior: smooth;
        }

        /* Hero gradient overlay */
        .hero-overlay {
            background: linear-gradient(135deg, rgba(0, 0, 0, 0.5) 0%, rgba(0, 0, 0, 0.3) 100%);
        }

        /* Glassmorphism effect */
        .glass {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        /* Fade in animation */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Float animation */
        @keyframes float {
            0%, 100% {
                transform: translateY(0px);
            }
            50% {
                transform: translateY(-10px);
            }
        }

        /* Pulse animation */
        @keyframes pulse-custom {
            0%, 100% {
                opacity: 1;
            }
            50% {
                opacity: 0.7;
            }
        }

        .animate-fadeIn {
            animation: fadeIn 1s ease-out;
        }

        .animate-float {
            animation: float 3s ease-in-out infinite;
        }

        .animate-pulse-custom {
            animation: pulse-custom 2s ease-in-out infinite;
        }

        /* Counter animation */
        .counter {
            font-variant-numeric: tabular-nums;
        }

        /* Smooth transitions */
        .transition-smooth {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Image hover zoom */
        .image-zoom {
            overflow: hidden;
        }

        .image-zoom img {
            transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .image-zoom:hover img {
            transform: scale(1.1);
        }

        /* Gradient text */
        .gradient-text {
            background: linear-gradient(135deg, #16a34a 0%, #f97316 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Glow effect */
        .glow-green {
            box-shadow: 0 0 30px rgba(22, 163, 74, 0.3);
        }

        .glow-orange {
            box-shadow: 0 0 30px rgba(249, 115, 22, 0.3);
        }
    </style>
</head>
<body class="bg-white text-gray-900">
    <!-- Navigation -->
    <nav class="fixed top-0 left-0 right-0 bg-white bg-opacity-95 backdrop-blur-md z-50 shadow-md" x-data="{ open: false }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <a href="#" class="flex items-center space-x-2">
                    <img src="{{ asset('img/logo.png') }}" alt="Hilir Migas Logo" class="h-10 w-10">
                    <span class="font-bold text-xl hidden sm:inline bg-gradient-to-r from-green-600 to-orange-500 bg-clip-text text-transparent">Hilir Migas</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="#beranda" class="text-gray-700 hover:text-green-600 transition-smooth">Beranda</a>
                    <a href="#tentang" class="text-gray-700 hover:text-green-600 transition-smooth">Tentang</a>
                    <a href="#fitur" class="text-gray-700 hover:text-green-600 transition-smooth">Fitur</a>
                    <a href="#galeri" class="text-gray-700 hover:text-green-600 transition-smooth">Galeri</a>
                </div>

                <!-- Mobile Menu Button -->
                <button @click="open = !open" class="md:hidden">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>

                <!-- CTA Buttons -->
                <div class="hidden md:flex items-center space-x-4">
                    <a href="{{ route('download') }}" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-2 rounded-lg font-semibold transition-smooth">
                        Download Aplikasi
                    </a>
                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-green-600 transition-smooth font-semibold">
                        Login Dashboard
                    </a>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div x-show="open" class="md:hidden pb-4 space-y-2" x-transition>
                <a href="#beranda" class="block text-gray-700 hover:text-green-600 py-2">Beranda</a>
                <a href="#tentang" class="block text-gray-700 hover:text-green-600 py-2">Tentang</a>
                <a href="#fitur" class="block text-gray-700 hover:text-green-600 py-2">Fitur</a>
                <a href="#galeri" class="block text-gray-700 hover:text-green-600 py-2">Galeri</a>
                <a href="{{ route('download') }}" class="block bg-gradient-to-r from-green-600 to-green-700 text-white px-4 py-2 rounded-lg font-semibold text-center">
                    Download Aplikasi
                </a>
                <a href="{{ route('login') }}" class="block text-gray-700 hover:text-green-600 transition-smooth font-semibold text-center py-2">
                    Login Dashboard
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="beranda" class="relative h-screen flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
            <img src="{{ asset('img/hilirmigas.jpg.jpeg') }}" alt="Hilir Migas" class="w-full h-full object-cover">
            <div class="hero-overlay absolute inset-0"></div>
        </div>
        
        <div class="relative z-10 text-center text-white px-4 sm:px-6 lg:px-8 max-w-4xl mx-auto" data-aos="fade-up" data-aos-duration="1000">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold mb-6 leading-tight animate-fadeIn">
                Hilir Migas Monitoring System
            </h1>
            <p class="text-lg sm:text-xl lg:text-2xl mb-8 text-gray-200 leading-relaxed">
                Sistem digital untuk monitoring operasional, distribusi, infrastruktur, dan pengelolaan aset Hilir Migas secara real-time.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('download') }}" class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-8 py-4 rounded-lg font-bold text-lg transition-smooth hover:shadow-2xl hover:glow-green">
                    Download Aplikasi
                </a>
                <button onclick="document.getElementById('tentang').scrollIntoView({ behavior: 'smooth' })" class="glass text-white px-8 py-4 rounded-lg font-bold text-lg hover:bg-opacity-20 transition-smooth">
                    Pelajari Lebih Lanjut
                </button>
                <a href="{{ route('login') }}" class="bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white px-8 py-4 rounded-lg font-bold text-lg transition-smooth hover:shadow-2xl">
                    Login Dashboard
                </a>
            </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 z-10">
            <div class="animate-bounce">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="tentang" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 items-center">
                <!-- Left - Text -->
                <div data-aos="fade-right" data-aos-duration="1000">
                    <h2 class="text-4xl font-bold mb-6 gradient-text">Tentang Aplikasi</h2>
                    <p class="text-gray-600 text-lg leading-relaxed mb-4">
                        Hilir Migas merupakan platform digital yang membantu perusahaan dalam melakukan monitoring fasilitas CNG, infrastruktur perpipaan, aset, dan operasional lapangan secara efisien.
                    </p>
                    <p class="text-gray-600 text-lg leading-relaxed mb-6">
                        Dengan teknologi real-time monitoring dan dashboard interaktif, kami memberikan solusi terpadu untuk mengelola seluruh aspek operasional Hilir Migas dengan mudah dan transparan.
                    </p>
                    
                    <div class="flex flex-col space-y-3">
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700">Platform terintegrasi untuk semua kebutuhan monitoring</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700">Update data real-time setiap saat</span>
                        </div>
                        <div class="flex items-start space-x-3">
                            <div class="flex-shrink-0">
                                <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <span class="text-gray-700">Keamanan data tingkat enterprise</span>
                        </div>
                    </div>
                </div>

                <!-- Right - Image -->
                <div data-aos="fade-left" data-aos-duration="1000" class="image-zoom">
                    <img src="{{ asset('img/parkir.jpg.jpeg') }}" alt="Fasilitas Hilir Migas" class="rounded-3xl shadow-2xl">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="fitur" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="1000">
                <h2 class="text-4xl font-bold mb-4 gradient-text">Fitur Unggulan</h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Berbagai fitur canggih untuk mendukung operasional Hilir Migas</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="0" class="glass rounded-2xl p-8 hover:shadow-2xl hover:bg-opacity-20 transition-smooth">
                    <div class="bg-gradient-to-br from-green-100 to-green-50 rounded-lg p-4 w-14 h-14 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900">Monitoring Real-time</h3>
                    <p class="text-gray-600">Pantau kondisi fasilitas secara langsung dengan data yang update setiap detik.</p>
                </div>

                <!-- Feature 2 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100" class="glass rounded-2xl p-8 hover:shadow-2xl hover:bg-opacity-20 transition-smooth">
                    <div class="bg-gradient-to-br from-orange-100 to-orange-50 rounded-lg p-4 w-14 h-14 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900">Manajemen Aset</h3>
                    <p class="text-gray-600">Kelola aset perusahaan dengan mudah dan terorganisir dalam satu platform.</p>
                </div>

                <!-- Feature 3 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="glass rounded-2xl p-8 hover:shadow-2xl hover:bg-opacity-20 transition-smooth">
                    <div class="bg-gradient-to-br from-blue-100 to-blue-50 rounded-lg p-4 w-14 h-14 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900">Dashboard Interaktif</h3>
                    <p class="text-gray-600">Visualisasi data operasional dengan grafik dan statistik yang informatif.</p>
                </div>

                <!-- Feature 4 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="glass rounded-2xl p-8 hover:shadow-2xl hover:bg-opacity-20 transition-smooth">
                    <div class="bg-gradient-to-br from-purple-100 to-purple-50 rounded-lg p-4 w-14 h-14 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900">Laporan Digital</h3>
                    <p class="text-gray-600">Pelaporan cepat dan terintegrasi dengan format yang professional dan lengkap.</p>
                </div>

                <!-- Feature 5 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="400" class="glass rounded-2xl p-8 hover:shadow-2xl hover:bg-opacity-20 transition-smooth">
                    <div class="bg-gradient-to-br from-red-100 to-red-50 rounded-lg p-4 w-14 h-14 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292 4 4 0 010-5.292z M19 1H5a4 4 0 00-4 4v10a4 4 0 004 4h14a4 4 0 004-4V5a4 4 0 00-4-4z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900">Manajemen Operator</h3>
                    <p class="text-gray-600">Pengelolaan pengguna dan role-based access control untuk keamanan maksimal.</p>
                </div>

                <!-- Feature 6 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="500" class="glass rounded-2xl p-8 hover:shadow-2xl hover:bg-opacity-20 transition-smooth">
                    <div class="bg-gradient-to-br from-cyan-100 to-cyan-50 rounded-lg p-4 w-14 h-14 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 20h8a2 2 0 002-2V6a2 2 0 00-2-2H8a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold mb-2 text-gray-900">Mobile Friendly</h3>
                    <p class="text-gray-600">Dapat diakses dari perangkat mobile kapan saja dan di mana saja dengan mudah.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Section -->
    <section id="galeri" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16" data-aos="fade-up" data-aos-duration="1000">
                <h2 class="text-4xl font-bold mb-4 gradient-text">Galeri Operasional</h2>
                <p class="text-gray-600 text-lg max-w-2xl mx-auto">Dokumentasi visual dari berbagai fasilitas dan operasional Hilir Migas</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-8">
                <!-- Gallery Item 1 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="0" class="image-zoom rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-smooth">
                    <img src="{{ asset('img/truk.jpg.jpeg') }}" alt="Gas Truck" class="w-full h-64 md:h-80 object-cover">
                </div>

                <!-- Gallery Item 2 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100" class="image-zoom rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-smooth">
                    <img src="{{ asset('img/teknisi.jpg.jpeg') }}" alt="Teknisi" class="w-full h-64 md:h-80 object-cover">
                </div>

                <!-- Gallery Item 3 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="image-zoom rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-smooth">
                    <img src="{{ asset('img/parkir.jpg.jpeg') }}" alt="Fasilitas" class="w-full h-64 md:h-80 object-cover">
                </div>

                <!-- Gallery Item 4 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="image-zoom rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-smooth">
                    <img src="{{ asset('img/hilirmigas.jpg.jpeg') }}" alt="Infrastruktur" class="w-full h-64 md:h-80 object-cover">
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-20 bg-gradient-to-r from-green-600 to-green-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- Stat 1 -->
                <div data-aos="fade-up" data-aos-duration="1000" class="text-center text-white">
                    <div class="text-5xl font-bold mb-2 counter">100%</div>
                    <p class="text-lg text-green-100">Monitoring Digital</p>
                </div>

                <!-- Stat 2 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="100" class="text-center text-white">
                    <div class="text-5xl font-bold mb-2">24/7</div>
                    <p class="text-lg text-green-100">Operasional Monitoring</p>
                </div>

                <!-- Stat 3 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="200" class="text-center text-white">
                    <div class="text-5xl font-bold mb-2">Real-time</div>
                    <p class="text-lg text-green-100">Data Update</p>
                </div>

                <!-- Stat 4 -->
                <div data-aos="fade-up" data-aos-duration="1000" data-aos-delay="300" class="text-center text-white">
                    <div class="text-5xl font-bold mb-2">Multi</div>
                    <p class="text-lg text-green-100">User Access</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-gradient-to-r from-green-600 via-green-600 to-orange-500 relative overflow-hidden">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-white opacity-5 rounded-full -mr-48 -mt-48"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-white opacity-5 rounded-full -ml-48 -mb-48"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10" data-aos="fade-up" data-aos-duration="1000">
            <h2 class="text-4xl sm:text-5xl font-bold text-white mb-6">
                Mulai Digitalisasi Operasional Hilir Migas Anda
            </h2>
            <p class="text-xl text-green-50 mb-8">
                Akses sistem monitoring dan manajemen operasional kapan saja melalui aplikasi Hilir Migas. Tingkatkan efisiensi dan transparansi operasional Anda hari ini.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('download') }}" class="inline-block bg-white text-green-600 px-10 py-4 rounded-lg font-bold text-lg hover:bg-green-50 transition-smooth hover:shadow-2xl">
                    Download Sekarang →
                </a>
                <a href="{{ route('login') }}" class="inline-block bg-yellow-400 text-gray-800 px-10 py-4 rounded-lg font-bold text-lg hover:bg-yellow-300 transition-smooth hover:shadow-2xl">
                    Login Dashboard
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12">
                <!-- Brand -->
                <div>
                    <img src="{{ asset('img/logo.png') }}" alt="Hilir Migas Logo" class="h-10 w-10 mb-4">
                    <h3 class="text-white font-bold text-lg mb-2">Hilir Migas</h3>
                    <p class="text-gray-400 text-sm">Sistem Monitoring dan Operasional Hilir Migas berbasis digital untuk masa depan yang lebih baik.</p>
                </div>

                <!-- Menu 1 -->
                <div>
                    <h4 class="text-white font-bold mb-4">Navigasi</h4>
                    <ul class="space-y-2">
                        <li><a href="#beranda" class="hover:text-green-400 transition-smooth">Beranda</a></li>
                        <li><a href="#tentang" class="hover:text-green-400 transition-smooth">Tentang</a></li>
                        <li><a href="#fitur" class="hover:text-green-400 transition-smooth">Fitur</a></li>
                        <li><a href="#galeri" class="hover:text-green-400 transition-smooth">Galeri</a></li>
                    </ul>
                </div>

                <!-- Menu 2 -->
                <div>
                    <h4 class="text-white font-bold mb-4">Aplikasi</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('download') }}" class="hover:text-green-400 transition-smooth">Download Aplikasi</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-green-400 transition-smooth">Login Dashboard Web</a></li>
                        <li><a href="#" class="hover:text-green-400 transition-smooth">Dokumentasi</a></li>
                    </ul>
                </div>

                <!-- Menu 3 -->
                <div>
                    <h4 class="text-white font-bold mb-4">Kontak</h4>
                    <ul class="space-y-2">
                        <li class="text-gray-400 text-sm">Email: info@hilirmigas.id</li>
                        <li class="text-gray-400 text-sm">Support 24/7 tersedia untuk Anda</li>
                    </ul>
                </div>
            </div>

            <!-- Divider -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <p class="text-gray-400 text-sm">© {{ date('Y') }} Hilir Migas. All Rights Reserved.</p>
                    <div class="flex space-x-6 mt-4 md:mt-0">
                        <a href="#" class="text-gray-400 hover:text-green-400 transition-smooth">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-green-400 transition-smooth">Terms of Service</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- Back to top button -->
    <button @click="window.scrollTo({ top: 0, behavior: 'smooth' })" x-data="{ show: false }" x-on:scroll.window="show = window.scrollY > 300" x-show="show" class="fixed bottom-8 right-8 bg-green-600 hover:bg-green-700 text-white p-3 rounded-full shadow-lg transition-smooth z-40">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
        </svg>
    </button>

    <!-- AOS JS -->
    <script src="https://unpkg.com/aos@next/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 1000,
            easing: 'ease-in-out',
            once: true,
            mirror: false
        });

        // Smooth scroll enhancement
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Counter animation
        const observerOptions = {
            threshold: 0.5
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counters = entry.target.querySelectorAll('.counter');
                    counters.forEach(counter => {
                        counter.classList.add('animate-pulse-custom');
                    });
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        const statsSection = document.querySelector('section:has(.counter)');
        if (statsSection) {
            observer.observe(statsSection);
        }
    </script>
</body>
</html>
