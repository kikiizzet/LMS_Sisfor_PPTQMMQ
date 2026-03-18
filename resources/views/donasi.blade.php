<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donasi - MMQ Digital Pesantren Tahfidz</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>
<body class="bg-white">

    <!-- NAVBAR -->
    <nav class="border-b border-slate-100">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center max-w-4xl">
            <a href="/" class="text-slate-400 hover:text-slate-600 transition text-sm font-medium flex items-center gap-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
            <div class="flex items-center gap-3">
                <span class="text-2xl">☪</span>
                <span class="text-sm font-bold text-slate-800">MMQ Digital</span>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="py-20 sm:py-32">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <div class="w-20 h-20 bg-emerald-100 rounded-3xl mx-auto mb-6 flex items-center justify-center">
                    <i class="bi bi-heart-fill text-emerald-600 text-3xl"></i>
                </div>
                <h1 class="text-4xl sm:text-6xl font-bold text-slate-900 mb-6 tracking-tight">
                    Dukung Kami
                </h1>
                <p class="text-xl text-slate-500 max-w-xl mx-auto leading-relaxed">
                    Setiap kontribusi Anda sangat berarti untuk kemajuan pesantren
                </p>
            </div>
        </div>
    </section>

    <!-- BANK ACCOUNTS & POSTER -->
    <section class="pb-24">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto space-y-8">
                
                @if(isset($donasiPoster) && $donasiPoster->image)
                <!-- DONASI POSTER -->
                <div class="bg-slate-50 rounded-3xl p-4 sm:p-6 mb-8 shadow-sm border border-slate-100">
                    <img src="{{ Storage::url($donasiPoster->image) }}" alt="{{ $donasiPoster->title ?? 'Poster Donasi' }}" class="w-full h-auto rounded-2xl shadow-sm object-cover">
                    @if($donasiPoster->title)
                        <p class="text-center mt-4 text-slate-700 font-medium">{{ $donasiPoster->title }}</p>
                    @endif
                </div>
                @endif
                <!-- BRI -->
                <div class="bg-slate-50 rounded-2xl p-8 hover:bg-slate-100 transition">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                            <i class="bi bi-bank text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Bank Rakyat Indonesia</p>
                            <h3 class="text-2xl font-bold text-slate-900">BRI</h3>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-400 mb-1">Nama Rekening</p>
                            <p class="font-semibold text-slate-900">PPTQ Makkah Madinatul Qur'an</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <p class="text-xs text-slate-400 mb-1">Nomor Rekening</p>
                                <p class="text-xl font-mono font-bold text-slate-900">0067-0100-2686-309</p>
                            </div>
                            <button onclick="copyToClipboard('0067-0100-2686-309', 'bri')" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition text-sm font-medium">
                                <i class="bi bi-clipboard" id="icon-bri"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Bank Jatim -->
                <div class="bg-slate-50 rounded-2xl p-8 hover:bg-slate-100 transition">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 bg-red-600 rounded-xl flex items-center justify-center">
                            <i class="bi bi-bank2 text-white text-xl"></i>
                        </div>
                        <div>
                            <p class="text-sm text-slate-500">Bank Pembangunan Daerah</p>
                            <h3 class="text-2xl font-bold text-slate-900">Bank Jatim</h3>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div>
                            <p class="text-xs text-slate-400 mb-1">Nama Rekening</p>
                            <p class="font-semibold text-slate-900">PPTQ Makkah Madinatul Qur'an</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="flex-1">
                                <p class="text-xs text-slate-400 mb-1">Nomor Rekening</p>
                                <p class="text-xl font-mono font-bold text-slate-900">1502032956</p>
                            </div>
                            <button onclick="copyToClipboard('1502032956', 'jatim')" 
                                    class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition text-sm font-medium">
                                <i class="bi bi-clipboard" id="icon-jatim"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Contact -->
                <div class="mt-12 pt-12 border-t border-slate-100">
                    <p class="text-sm text-slate-500 text-center mb-6">Butuh bantuan? Hubungi kami</p>
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="https://wa.me/62822458058775" 
                           class="flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl transition font-medium">
                            <i class="bi bi-whatsapp text-lg"></i>
                            <span>Ustadz Syarif</span>
                        </a>
                        <a href="https://wa.me/6287758046321" 
                           class="flex items-center gap-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-xl transition font-medium">
                            <i class="bi bi-whatsapp text-lg"></i>
                            <span>Ustadz Zaidi</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="border-t border-slate-100 py-8">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center">
                <p class="text-2xl font-arabic text-emerald-600 mb-2">جَزَاكَ اللهُ خَيْرًا</p>
                <p class="text-sm text-slate-400">© 2026 MMQ Digital Pesantren Tahfidz</p>
            </div>
        </div>
    </footer>

    <script>
        function copyToClipboard(text, bankId) {
            navigator.clipboard.writeText(text).then(function() {
                const icon = document.getElementById('icon-' + bankId);
                icon.className = 'bi bi-check2';
                setTimeout(function() {
                    icon.className = 'bi bi-clipboard';
                }, 2000);
            });
        }
    </script>
</body>
</html>
