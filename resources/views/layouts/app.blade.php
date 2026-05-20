<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'MigasQueue' }} — PT Migas Hilir Jabar</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui']
                    },
                    colors: {
                        primary:   { DEFAULT: '#0b3d91', dark: '#082b6a', light: '#eaf2ff' },
                        accent:    { DEFAULT: '#17a34a', light: '#eaf8ee' },
                        accent2:   { DEFAULT: '#e8650a', light: '#fff4e8' },
                        sidebar:   { DEFAULT: '#071632', light: '#0b274a' },
                        surface:   { DEFAULT: '#ffffff', muted: '#f6f7fb' }
                    },
                    boxShadow: {
                        'soft': '0 6px 18px rgba(10, 25, 47, 0.06)'
                    }
                }
            }
        }
    </script>

    <!-- ApexCharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        :root {
            --color-primary: #0b3d91;
            --color-primary-dark: #082b6a;
            --color-surface: #ffffff;
            --color-sidebar: #071632;
            --color-accent: #17a34a;
            --shadow-soft: 0 6px 18px rgba(10,25,47,0.06);
        }

        html, body { font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; background-color: #f6f7fb; }

        /* Sidebar */
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            border-radius: 0.5rem;
            color: rgba(255,255,255,0.95);
            font-weight: 600;
            font-size: 0.875rem;
            transition: all 0.18s ease;
        }
        .sidebar-link svg { width: 1.25rem; height: 1.25rem; color: rgba(255,255,255,0.78); flex-shrink:0; }
        .sidebar-link:hover { background: rgba(11,61,145,0.08); color: #fff; }
        .sidebar-link:hover svg { color: rgba(255,255,255,0.95); }
        .sidebar-link.active {
            background: linear-gradient(90deg, var(--color-primary), rgba(11,61,145,0.75));
            color: #fff;
            box-shadow: var(--shadow-soft);
            border-left: 4px solid var(--color-accent);
        }
        .sidebar-section { color: rgba(255,255,255,0.7); font-size: 0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; padding:0.5rem 0.75rem; }
        .sidebar-card { background: rgba(11,38,72,0.06); border-radius: 0.75rem; padding: 1rem; border: 1px solid rgba(255,255,255,0.04); }

        /* Topbar */
        header { background: var(--color-surface); border-bottom: 1px solid #eef2f7; box-shadow: 0 1px 4px rgba(16,24,40,0.03); }

        /* Cards & Surface */
        .stat-card { background: var(--color-surface); border-radius: 1rem; padding: 1.25rem; box-shadow: var(--shadow-soft); border: 1px solid #f0f2f6; transition: box-shadow .18s ease; }
        .stat-card:hover { box-shadow: 0 10px 30px rgba(10,25,47,0.06); }
        .card { background: var(--color-surface); border-radius: 1rem; padding: 1.25rem; box-shadow: var(--shadow-soft); border: 1px solid #f0f2f6; }

        /* Buttons */
        .btn-primary { background: var(--color-primary); color: #fff; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight:600; font-size:0.9rem; border: none; display:inline-flex; align-items:center; gap:0.5rem; }
        .btn-primary:hover { background: var(--color-primary-dark); }
        .btn-accent { background: var(--color-accent); color:#fff; padding:0.5rem 1rem; border-radius:0.5rem; font-weight:600; }
        .btn-outline { border:1px solid var(--color-primary); color:var(--color-primary); padding:0.5rem 1rem; border-radius:0.5rem; }

        /* Badges */
        .badge { display:inline-flex; align-items:center; padding:0.25rem 0.6rem; border-radius:9999px; font-size:0.75rem; font-weight:600; background:#f3f4f6; color:#374151; }

        /* Responsive tweaks */
        @media (max-width: 768px) {
            aside { width: 4rem !important; }
            .sidebar-link span { display: none !important; }
        }
    </style>
</head>
<body class="bg-gray-50 font-sans" x-data="{ sidebarOpen: true }">

    <div class="flex h-screen overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="w-64 bg-sidebar flex-shrink-0 flex flex-col transition-all duration-300"
               :class="sidebarOpen ? 'w-64' : 'w-16'">

            {{-- Logo --}}
            <div class="flex items-center gap-3 p-5 border-b border-white/10">
                <div class="w-9 h-9 bg-primary rounded-lg flex items-center justify-center flex-shrink-0">
                    <span class="text-white font-bold text-sm">MQ</span>
                </div>
                <div x-show="sidebarOpen" x-cloak>
                    <p class="text-white font-bold text-sm leading-none">MigasQueue</p>
                    <p class="text-gray-400 text-xs mt-0.5">PT Migas Hilir Jabar</p>
                </div>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 p-3 space-y-1 overflow-y-auto">

                @if(auth()->user()->hasRole('admin'))
                {{-- ADMIN MENU --}}
                <p x-show="sidebarOpen" class="sidebar-section">Admin</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>

                <a href="{{ route('admin.antrean.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.antrean.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span x-show="sidebarOpen">Data Antrean</span>
                </a>

                <a href="{{ route('admin.laporan.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span x-show="sidebarOpen">Laporan</span>
                </a>

                <a href="{{ route('admin.pengisian.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.pengisian.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span x-show="sidebarOpen">Rekap Pengisian</span>
                </a>

                <a href="{{ route('admin.perusahaan.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.perusahaan.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span x-show="sidebarOpen">Perusahaan</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                   class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span x-show="sidebarOpen">Manajemen User</span>
                </a>

                @elseif(auth()->user()->hasRole('operator'))
                {{-- OPERATOR MENU --}}
                <p x-show="sidebarOpen" class="sidebar-section">Operator</p>

                <a href="{{ route('operator.dashboard') }}"
                   class="sidebar-link {{ request()->routeIs('operator.dashboard') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span x-show="sidebarOpen">Dashboard</span>
                </a>

                <a href="{{ route('operator.antrean.index') }}"
                   class="sidebar-link {{ request()->routeIs('operator.antrean.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    <span x-show="sidebarOpen">Antrean Aktif</span>
                </a>

                <a href="{{ route('operator.pengisian.index') }}"
                   class="sidebar-link {{ request()->routeIs('operator.pengisian.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                    <span x-show="sidebarOpen">Input Pengisian</span>
                </a>

                <a href="{{ route('operator.laporan.index') }}"
                   class="sidebar-link {{ request()->routeIs('operator.laporan.*') ? 'active' : '' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <span x-show="sidebarOpen">Laporan</span>
                </a>
                @endif

            </nav>

            {{-- User Info --}}
            <div class="p-3 border-t border-white/10">
                <div class="flex items-center gap-3 px-3 py-2">
                    <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center flex-shrink-0">
                        <span class="text-white text-sm font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                    </div>
                    <div x-show="sidebarOpen" x-cloak class="min-w-0">
                        <p class="text-white text-sm font-medium truncate">{{ auth()->user()->name }}</p>
                        <p class="text-gray-400 text-xs capitalize">{{ auth()->user()->role }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="mt-1">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-2 rounded-lg text-gray-400 hover:text-red-400 hover:bg-red-400/10 transition-all text-sm">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span x-show="sidebarOpen">Keluar</span>
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <div class="flex-1 flex flex-col overflow-hidden">

            {{-- TOPBAR --}}
            <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg font-bold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
                        <p class="text-xs text-gray-500">{{ now()->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Notifikasi --}}
                    <button class="relative p-2 text-gray-500 hover:text-primary hover:bg-primary-light rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                    </button>
                    {{-- Profile --}}
                    <div class="flex items-center gap-2 pl-3 border-l border-gray-200">
                        <div class="w-8 h-8 bg-primary rounded-full flex items-center justify-center">
                            <span class="text-white text-sm font-bold">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </span>
                        </div>
                        <div class="hidden md:block">
                            <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                    </div>
                </div>
            </header>

            {{-- PAGE CONTENT --}}
            <main class="flex-1 overflow-y-auto p-6">
                {{-- Flash Messages --}}
                @if(session('success'))
                <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-xl text-green-700 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('success') }}
                </div>
                @endif

                @if(session('error'))
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    {{ session('error') }}
                </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

</body>
</html>