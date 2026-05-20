<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — MigasQueue</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: { DEFAULT: '#1a7a2e', dark: '#145c22', light: '#e8f5ec' },
                        accent:  { DEFAULT: '#e8650a' },
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gradient-to-br from-sidebar to-primary-dark min-h-screen flex items-center justify-center p-4"
      style="--tw-gradient-from: #0f2318; --tw-gradient-to: #145c22;">

    <div class="w-full max-w-md">

        {{-- Logo --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg">
                <span class="text-white font-bold text-2xl">MQ</span>
            </div>
            <h1 class="text-white text-2xl font-bold">MigasQueue</h1>
            <p class="text-green-300 text-sm mt-1">PT Migas Hilir Jabar</p>
        </div>

        {{-- Card --}}
        <div class="bg-white rounded-2xl p-8 shadow-2xl">
            <h2 class="text-xl font-bold text-gray-800 mb-1">Masuk ke Sistem</h2>
            <p class="text-sm text-gray-500 mb-6">Silakan masukkan kredensial Anda</p>

            @if ($errors->any())
            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-xl text-red-600 text-sm">
                {{ $errors->first() }}
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                        placeholder="email@perusahaan.com">
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-4 py-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all"
                        placeholder="••••••••">
                </div>

                <button type="submit"
                    class="w-full bg-primary text-white py-3 rounded-xl font-semibold text-sm hover:bg-primary-dark transition-colors">
                    Masuk ke Sistem
                </button>
            </form>

            <div class="mt-6 pt-6 border-t border-gray-100">
                <p class="text-xs text-gray-400 text-center">
                    Sistem Antrean Pengisian Gas Digital<br>
                    Terintegrasi dengan Aplikasi Mobile
                </p>
            </div>
        </div>
    </div>

</body>
</html>