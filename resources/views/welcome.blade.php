<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Bank Sampah - Kelola Sampah, Raih Rupiah</title>

        <!-- Google Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Tailwind CSS via Vite -->
        @vite('resources/css/app.css')
    </head>
    <body class="bg-[#0b1311] text-[#e2f1ed] font-['Outfit'] min-h-screen relative overflow-x-hidden selection:bg-emerald-500 selection:text-black">
        
        <!-- Background decorative blur elements -->
        <div class="absolute top-[-20%] left-[-10%] w-[500px] h-[500px] rounded-full bg-emerald-900/20 blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] rounded-full bg-teal-900/20 blur-[150px] pointer-events-none"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-6 py-6 min-h-screen flex flex-col justify-between">
            <!-- Header -->
            <header class="flex items-center justify-between border-b border-emerald-950/40 pb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-500 to-teal-400 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 1121.21 6H16"></path>
                        </svg>
                    </div>
                    <span class="text-2xl font-bold tracking-tight bg-gradient-to-r from-emerald-400 to-teal-200 bg-clip-text text-transparent">BankSampah</span>
                </div>
                
                <nav class="hidden md:flex items-center gap-8 text-sm font-medium text-emerald-300/80">
                    <a href="#" class="hover:text-emerald-400 transition-colors duration-200">Tentang Kami</a>
                    <a href="#" class="hover:text-emerald-400 transition-colors duration-200">Layanan</a>
                    <a href="#" class="hover:text-emerald-400 transition-colors duration-200">Kalkulator Sampah</a>
                    <a href="#" class="hover:text-emerald-400 transition-colors duration-200">Mitra</a>
                </nav>

                <div class="flex items-center gap-4">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-xl border border-emerald-800 hover:border-emerald-500 text-sm font-medium transition-all duration-300">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-medium text-emerald-300 hover:text-emerald-400 transition-colors duration-200">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 hover:from-emerald-400 hover:to-teal-300 text-black text-sm font-bold shadow-lg shadow-emerald-950/50 hover:shadow-emerald-400/20 transition-all duration-300">Daftar Sekarang</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </header>

            <!-- Hero Section -->
            <main class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center my-auto py-12">
                <!-- Text Content -->
                <div class="lg:col-span-7 space-y-8 text-center lg:text-left">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-950/60 border border-emerald-800/40 text-xs font-semibold text-emerald-400 uppercase tracking-wider">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Selamatkan Bumi, Dapatkan Penghasilan
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold tracking-tight leading-[1.1] text-white">
                        Ubah Sampah Jadi <br class="hidden sm:inline">
                        <span class="bg-gradient-to-r from-emerald-400 via-teal-300 to-emerald-200 bg-clip-text text-transparent">Berkah Finansial</span>
                    </h1>
                    
                    <p class="text-base sm:text-lg text-emerald-200/70 max-w-xl mx-auto lg:mx-0 font-light">
                        Aplikasi Bank Sampah modern membantu Anda mengelola limbah rumah tangga dengan mudah. Setorkan sampah daur ulang Anda, kumpulkan poinnya, dan tukarkan menjadi uang tunai atau saldo dompet digital.
                    </p>

                    <div class="flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-4">
                        <a href="#" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-400 text-black font-bold text-center shadow-lg shadow-emerald-500/20 hover:shadow-emerald-400/40 transform hover:-translate-y-0.5 transition-all duration-300">
                            Mulai Setor Sampah
                        </a>
                        <a href="#" class="w-full sm:w-auto px-8 py-4 rounded-xl bg-emerald-950/40 hover:bg-emerald-950/80 border border-emerald-800/60 text-[#e2f1ed] text-center font-semibold transition-all duration-300">
                            Pelajari Selengkapnya
                        </a>
                    </div>

                    <!-- Small Stat Grid -->
                    <div class="grid grid-cols-3 gap-6 pt-6 border-t border-emerald-950/40 max-w-md mx-auto lg:mx-0">
                        <div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-white">1,240+</div>
                            <div class="text-xs text-emerald-400/70">Ton Terdaur Ulang</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-white">15,800+</div>
                            <div class="text-xs text-emerald-400/70">Nasabah Aktif</div>
                        </div>
                        <div>
                            <div class="text-2xl sm:text-3xl font-extrabold text-white">Rp 2.4M+</div>
                            <div class="text-xs text-emerald-400/70">Poin Ditukarkan</div>
                        </div>
                    </div>
                </div>

                <!-- Interactive Features Card / Graphic -->
                <div class="lg:col-span-5 relative">
                    <div class="relative bg-gradient-to-br from-emerald-950/40 to-teal-950/40 backdrop-blur-xl border border-emerald-800/20 rounded-3xl p-8 shadow-2xl space-y-6">
                        <div class="absolute top-0 right-0 p-4">
                            <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 block shadow-lg shadow-emerald-400/50"></span>
                        </div>
                        
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="flex h-2 w-2 relative">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </span>
                            Simulasi Konversi Poin
                        </h3>

                        <!-- Interactive items simulator UI style -->
                        <div class="space-y-4">
                            <!-- Item 1 -->
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-900/10 border border-emerald-900/30">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-900/40 flex items-center justify-center text-lg">
                                        🍾
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white text-sm">Botol Plastik PET</div>
                                        <div class="text-xs text-emerald-400/70">Poin: 1.500 / Kg</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-bold text-white">5 Kg</div>
                                    <div class="text-xs text-emerald-400">7.500 Poin</div>
                                </div>
                            </div>

                            <!-- Item 2 -->
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-900/10 border border-emerald-900/30">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-900/40 flex items-center justify-center text-lg">
                                        📦
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white text-sm">Kardus Bekas</div>
                                        <div class="text-xs text-emerald-400/70">Poin: 1.200 / Kg</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-bold text-white">12 Kg</div>
                                    <div class="text-xs text-emerald-400">14.400 Poin</div>
                                </div>
                            </div>

                            <!-- Item 3 -->
                            <div class="flex items-center justify-between p-4 rounded-2xl bg-emerald-900/10 border border-emerald-900/30">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-900/40 flex items-center justify-center text-lg">
                                        🥫
                                    </div>
                                    <div>
                                        <div class="font-semibold text-white text-sm">Kaleng Aluminium</div>
                                        <div class="text-xs text-emerald-400/70">Poin: 6.000 / Kg</div>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <div class="text-sm font-bold text-white">2.5 Kg</div>
                                    <div class="text-xs text-emerald-400">15.000 Poin</div>
                                </div>
                            </div>
                        </div>

                        <!-- Total Points and Value -->
                        <div class="pt-6 border-t border-emerald-900/40 flex items-center justify-between">
                            <div>
                                <div class="text-xs text-emerald-400/70">Total Estimasi Poin</div>
                                <div class="text-2xl font-black text-white">36.900 <span class="text-xs text-emerald-400 font-semibold">Poin</span></div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-emerald-400/70">Konversi Rupiah</div>
                                <div class="text-xl font-bold text-emerald-300">Rp 36.900</div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>

            <!-- Footer -->
            <footer class="border-t border-emerald-950/40 pt-6 flex flex-col md:flex-row items-center justify-between text-xs text-emerald-500/60 gap-4">
                <div>
                    &copy; 2026 BankSampah. Hak Cipta Dilindungi Undang-Undang.
                </div>
                <div class="flex items-center gap-6">
                    <a href="#" class="hover:text-emerald-400 transition-colors duration-200">Kebijakan Privasi</a>
                    <a href="#" class="hover:text-emerald-400 transition-colors duration-200">Syarat & Ketentuan</a>
                    <a href="#" class="hover:text-emerald-400 transition-colors duration-200">Laravel v{{ Illuminate\Foundation\Application::VERSION }}</a>
                </div>
            </footer>
        </div>
    </body>
</html>
