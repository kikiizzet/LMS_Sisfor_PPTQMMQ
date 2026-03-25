<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pondok Pesantren Tahfidz Al-Qur'an - Membentuk Generasi Penghafal Al-Qur'an</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Amiri:wght@400;700&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- AOS - Animate On Scroll Library -->
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Tailwind CSS via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <style>
        html, body {
            max-width: 100%;
            overflow-x: hidden;
            position: relative;
        }

        .faq-swiper {
            height: 540px;
            padding: 10px 5px;
        }

        @media (max-width: 1024px) {
            .faq-swiper {
                height: auto;
                padding-bottom: 30px;
            }
        }

        /* Chatbot Styles */
        .chatbot-float {
            position: fixed;
            bottom: 180px;
            right: 30px;
            z-index: 999;
        }

        .chatbot-button {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            cursor: pointer;
            box-shadow: 0 4px 20px rgba(4, 120, 87, 0.4);
            transition: all 0.3s ease;
            border: none;
        }

        .chatbot-button:hover {
            transform: scale(1.1);
            box-shadow: 0 6px 30px rgba(4, 120, 87, 0.6);
        }

        .chatbot-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #f59e0b;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 10px;
            font-weight: bold;
        }

        .chatbot-window {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 380px;
            height: calc(100vh - 120px);
            max-height: 650px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            display: none;
            flex-direction: column;
            z-index: 9999;
            overflow: hidden;
        }

        .chatbot-window.active {
            display: flex;
        }

        .chatbot-header {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chatbot-header-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chatbot-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
        }

        .chatbot-close {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            font-size: 22px;
            cursor: pointer;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .chatbot-close:hover {
            background: rgba(255, 255, 255, 0.35);
        }

        .chatbot-messages {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            background: #f8fafc;
            min-height: 0;
        }

        .chatbot-message {
            margin-bottom: 16px;
            display: flex;
            gap: 10px;
        }

        .chatbot-message.user {
            flex-direction: row-reverse;
        }

        .message-bubble {
            max-width: 75%;
            padding: 12px 16px;
            border-radius: 16px;
            font-size: 14px;
            line-height: 1.5;
        }

        .message-bubble.bot {
            background: white;
            color: #1e293b;
            border: 1px solid #e2e8f0;
        }

        .message-bubble.user {
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            color: white;
        }

        .chatbot-quick-replies {
            padding: 8px 16px;
            display: flex;
            gap: 6px;
            flex-wrap: wrap;
            background: white;
            border-top: 1px solid #e2e8f0;
            max-height: 100px;
            overflow-y: auto;
            flex-shrink: 0;
        }

        .quick-reply-btn {
            padding: 8px 14px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 20px;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.2s;
            color: #475569;
        }

        .quick-reply-btn:hover {
            background: #047857;
            color: white;
            border-color: #047857;
        }

        .chatbot-input-area {
            padding: 12px 16px;
            background: white;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }

        .chatbot-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #e2e8f0;
            border-radius: 25px;
            font-size: 14px;
            outline: none;
            transition: border-color 0.2s;
        }

        .chatbot-input:focus {
            border-color: #047857;
        }

        .chatbot-send-btn {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #047857 0%, #059669 100%);
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s;
        }

        .chatbot-send-btn:hover {
            transform: scale(1.1);
        }

        .chatbot-send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .typing-indicator {
            display: flex;
            gap: 4px;
            padding: 12px 16px;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background: #94a3b8;
            border-radius: 50%;
            animation: typing 1.4s infinite;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing {
            0%, 60%, 100% {
                transform: translateY(0);
            }
            30% {
                transform: translateY(-10px);
            }
        }

        @media (max-width: 768px) {
            .chatbot-window {
                width: 100vw !important;
                right: 0 !important;
                left: 0 !important;
                bottom: 0 !important;
                margin: 0 !important;
                height: 85vh !important;
                max-height: 85vh !important;
                border-radius: 24px 24px 0 0 !important;
                box-shadow: 0 -10px 40px rgba(0, 0, 0, 0.1) !important;
            }

            .chatbot-float {
                right: 15px !important;
                bottom: 160px !important;
            }

            .message-bubble {
                max-width: 85% !important;
                font-size: 15px !important;
            }

            .chatbot-input {
                font-size: 16px !important; /* Prevent zoom on iOS */
            }
        }

        .glass-navbar {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .islamic-pattern-bg {
            background-color: #f8fafc;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='80' height='80' viewBox='0 0 80 80'%3E%3Cg fill='%23047857' fill-opacity='0.03'%3E%3Cpath d='M0 0h80v80H0z'/%3E%3Cpath d='M40 0l40 40-40 40L0 40z'/%3E%3C/g%3E%3C/svg%3E");
        }

        .hero-gradient {
            background: linear-gradient(135deg, #065f46 0%, #047857 50%, #064e3b 100%);
        }

        .card-premium {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .card-premium:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
        }

        /* Prevent AOS horizontal overflow */
        [data-aos] {
            pointer-events: none;
        }
        [data-aos].aos-animate {
            pointer-events: auto;
        }
        
        /* WhatsApp Floating Button */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #25D366 0%, #128C7E 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            box-shadow: 0 8px 20px rgba(37, 211, 102, 0.4);
            z-index: 9000;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: pulse 2s infinite;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 28px rgba(37, 211, 102, 0.5);
        }

        @keyframes pulse {
            0%, 100% {
                box-shadow: 0 8px 20px rgba(37, 211, 102, 0.4);
            }
            50% {
                box-shadow: 0 8px 20px rgba(37, 211, 102, 0.6), 0 0 0 15px rgba(37, 211, 102, 0.1);
            }
        }

        .whatsapp-tooltip {
            position: absolute;
            right: 75px;
            background: white;
            padding: 8px 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            white-space: nowrap;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .whatsapp-float:hover .whatsapp-tooltip {
            opacity: 1;
        }

        /* Donation Floating Button */
        .donate-float {
            position: fixed;
            bottom: 105px;
            right: 30px;
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 28px;
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
            z-index: 9000;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            animation: pulse-donate 2s infinite;
        }

        .donate-float:hover {
            transform: scale(1.1);
            box-shadow: 0 12px 28px rgba(245, 158, 11, 0.5);
        }

        @keyframes pulse-donate {
            0%, 100% {
                box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);
            }
            50% {
                box-shadow: 0 8px 20px rgba(245, 158, 11, 0.6), 0 0 0 15px rgba(245, 158, 11, 0.1);
            }
        }

        .donate-tooltip {
            position: absolute;
            right: 75px;
            background: white;
            padding: 8px 16px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            white-space: nowrap;
            font-size: 14px;
            font-weight: 600;
            color: #334155;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .donate-float:hover .donate-tooltip {
            opacity: 1;
        }

        @media (max-width: 640px) {
            .whatsapp-float {
                width: 56px;
                height: 56px;
                bottom: 20px;
                right: 20px;
                font-size: 28px;
            }
            .donate-float {
                width: 56px;
                height: 56px;
                bottom: 90px;
                right: 20px;
                font-size: 24px;
            }
            .donate-tooltip {
                display: none;
            }
            .whatsapp-tooltip {
                display: none;
            }
        }
        
        /* Mobile Specific Overrides */
        @media (max-width: 640px) {
            .container {
                padding-left: 20px;
                padding-right: 20px;
            }
        }
    </style>
</head>
<body class="font-sans text-slate-800 antialiased overflow-x-hidden islamic-pattern-bg">

    <!-- NAVBAR -->
    <nav id="navbar" class="fixed top-0 w-full z-50 transition-all duration-300 py-4 lg:py-5">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center">
            <a href="#" class="flex items-center gap-2 sm:gap-3">
                <span class="text-2xl sm:text-3xl font-arabic text-islamic-gold-500">☪</span>
                <div class="flex flex-col">
                    <span id="nav-brand-title" class="text-sm sm:text-lg font-bold text-white leading-tight transition-colors">MMQ DIGITAL</span>
                    <span id="nav-brand-subtitle" class="text-[8px] sm:text-[10px] text-emerald-200 tracking-widest font-semibold uppercase transition-colors">Pesantren Tahfidz</span>
                </div>
            </a>

            <!-- Desktop Menu -->
            <div id="nav-links" class="hidden lg:flex items-center gap-6 xl:gap-8 text-white transition-colors">
                <a href="#tentang" class="text-sm font-medium hover:text-islamic-gold-400 transition-colors">Tentang</a>
                <a href="#program" class="text-sm font-medium hover:text-islamic-gold-400 transition-colors">Program</a>
                <a href="#fasilitas" class="text-sm font-medium hover:text-islamic-gold-400 transition-colors">Fasilitas</a>
                <a href="#testimoni" class="text-sm font-medium hover:text-islamic-gold-400 transition-colors">Testimoni</a>
                <a href="#kontak" class="text-sm font-medium hover:text-islamic-gold-400 transition-colors">Kontak</a>
                <a href="#daftar" class="bg-islamic-green-700 hover:bg-islamic-green-800 text-white px-6 py-2.5 rounded-full text-sm font-semibold transition-all shadow-lg hover:shadow-islamic-green-200/50 border border-white/10">
                    Daftar Santri
                </a>
            </div>

            <!-- Mobile Toggle -->
            <button id="nav-mobile-toggle" class="lg:hidden text-white focus:outline-none p-2 relative z-[9999] transition-colors" aria-label="Toggle Menu" onclick="toggleMobileMenu()">
                <i id="menu-icon" class="bi bi-list text-3xl"></i>
            </button>
        </div>
    </nav>

    <!-- Mobile Menu Shade -->
    <div id="mobile-menu-shade" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[9998] hidden lg:hidden" onclick="toggleMobileMenu()"></div>

    <!-- Mobile Menu Panel -->
    <div id="mobile-menu" class="fixed top-0 right-0 h-full w-[85%] max-w-sm bg-white z-[9999] shadow-2xl translate-x-full transition-transform duration-300 ease-in-out lg:hidden flex flex-col p-8 pt-24 gap-6 overflow-y-auto">
        <a href="#tentang" class="text-lg font-bold py-3 border-b border-emerald-50 text-islamic-green-900 flex items-center gap-4 group" onclick="toggleMobileMenu()">
            <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-islamic-green-600 group-hover:bg-islamic-green-600 group-hover:text-white transition-all">
                <i class="bi bi-info-circle"></i>
            </div>
            <span>Tentang Kami</span>
        </a>
        <a href="#program" class="text-lg font-bold py-3 border-b border-emerald-50 text-islamic-green-900 flex items-center gap-4 group" onclick="toggleMobileMenu()">
            <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-islamic-green-600 group-hover:bg-islamic-green-600 group-hover:text-white transition-all">
                <i class="bi bi-grid-1x2"></i>
            </div>
            <span>Program Unggulan</span>
        </a>
        <a href="#fasilitas" class="text-lg font-bold py-3 border-b border-emerald-50 text-islamic-green-900 flex items-center gap-4 group" onclick="toggleMobileMenu()">
            <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-islamic-green-600 group-hover:bg-islamic-green-600 group-hover:text-white transition-all">
                <i class="bi bi-building"></i>
            </div>
            <span>Fasilitas</span>
        </a>
        <a href="#testimoni" class="text-lg font-bold py-3 border-b border-emerald-50 text-islamic-green-900 flex items-center gap-4 group" onclick="toggleMobileMenu()">
            <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-islamic-green-600 group-hover:bg-islamic-green-600 group-hover:text-white transition-all">
                <i class="bi bi-chat-quote"></i>
            </div>
            <span>Testimoni</span>
        </a>
        <a href="#kontak" class="text-lg font-bold py-3 border-b border-emerald-50 text-islamic-green-900 flex items-center gap-4 group" onclick="toggleMobileMenu()">
            <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center text-islamic-green-600 group-hover:bg-islamic-green-600 group-hover:text-white transition-all">
                <i class="bi bi-envelope"></i>
            </div>
            <span>Kontak</span>
        </a>
        <a href="#daftar" class="bg-islamic-green-700 text-white text-center py-4 rounded-2xl font-bold mt-auto shadow-xl shadow-emerald-200" onclick="toggleMobileMenu()">Daftar Santri Sekarang</a>
    </div>

    <!-- HERO SECTION -->
    <section class="relative min-h-[95vh] sm:min-h-screen flex items-center pt-24 pb-16 overflow-hidden hero-gradient text-white">
        <!-- Abstract Shapes -->
        <div class="absolute -top-24 -right-24 w-64 h-64 sm:w-96 sm:h-96 bg-white/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-24 -left-24 w-64 h-64 sm:w-96 sm:h-96 bg-islamic-gold-500/10 rounded-full blur-3xl"></div>

        <div class="container mx-auto px-4 sm:px-6 relative z-10">
            <div class="grid lg:grid-cols-2 gap-12 sm:gap-20 items-center">
                <div data-aos="fade-up" data-aos-duration="1000" class="text-center lg:text-left">
                    
                    <!-- ADMISSION BANNER -->
                    <a href="#daftar" class="inline-flex items-center gap-3 bg-islamic-gold-500/10 hover:bg-islamic-gold-500/20 border border-islamic-gold-500/30 px-4 py-2 sm:px-5 sm:py-2.5 rounded-full mb-6 sm:mb-8 transition-all group backdrop-blur-sm self-center lg:self-start">
                        <span class="relative flex h-3 w-3">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-islamic-gold-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-3 w-3 bg-islamic-gold-500"></span>
                        </span>
                        <span class="text-xs sm:text-sm font-bold text-islamic-gold-400 tracking-wide uppercase">Daftar Santri Baru 2026/2027</span>
                        <i class="bi bi-chevron-right text-islamic-gold-400 text-xs group-hover:translate-x-1 transition-transform"></i>
                    </a>

                    <p class="font-arabic text-2xl sm:text-3xl mb-4 sm:mb-6 text-islamic-gold-400">بِسْمِ اللَّهِ الرَّحْمَٰنِ الرَّحِيمِ</p>
                    <h1 class="text-3xl sm:text-5xl xl:text-6xl font-extrabold mb-6 leading-tight">
                        Membentuk Generasi <span class="text-islamic-gold-400">Qur'ani</span> Berakhlakul Karimah
                    </h1>
                    <p class="text-base sm:text-lg text-emerald-50/80 mb-8 sm:mb-10 leading-relaxed max-w-xl mx-auto lg:mx-0">
                        Pondok Pesantren Tahfidz Al-Qur'an dengan bimbingan ustadz berpengalaman, fasilitas modern, dan lingkungan yang asri untuk menemani perjalanan menghafal Al-Qur'an.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                        <a href="#daftar" class="bg-islamic-gold-500 hover:bg-islamic-gold-600 text-white px-8 py-4 rounded-full font-bold transition-all shadow-xl shadow-amber-900/20 flex items-center justify-center gap-2 group">
                            <span>Daftar Sekarang</span>
                            <i class="bi bi-arrow-down group-hover:translate-y-1 transition-transform"></i>
                        </a>
                        <a href="#program" class="bg-white/10 hover:bg-white/20 backdrop-blur-md px-8 py-4 rounded-full font-bold transition-all border border-white/20 text-center">
                            Lihat Program
                        </a>
                    </div>
                </div>
                <div class="relative flex items-center justify-center lg:justify-end" data-aos="zoom-in" data-aos-duration="1000" data-aos-delay="200">
                    <div class="relative w-full max-w-md sm:max-w-lg">
                        <!-- Decorative Frame -->
                        <div class="absolute -inset-3 sm:-inset-4 border-2 border-islamic-gold-500/30 rounded-3xl -rotate-3 z-0"></div>
                        <div class="absolute -inset-3 sm:-inset-4 border-2 border-white/10 rounded-3xl rotate-3 z-0"></div>
                        
                        <img src="{{ asset('images/cover1.png') }}" 
                             alt="Santri Mengaji" 
                             class="rounded-2xl shadow-2xl relative z-10 w-full object-cover aspect-[4/3]">
                        
                        <!-- Floating Badge -->
                        <div class="absolute -bottom-4 -right-4 sm:-bottom-6 sm:-right-6 bg-white p-4 sm:p-6 rounded-2xl shadow-xl z-20 hidden sm:block" data-aos="fade-up" data-aos-delay="800">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 sm:w-12 sm:h-12 bg-islamic-green-100 rounded-full flex items-center justify-center text-islamic-green-600">
                                    <i class="bi bi-book-half text-xl sm:text-2xl"></i>
                                </div>
                                <div>
                                    <p class="text-islamic-green-900 font-bold text-base sm:text-lg">Program</p>
                                    <p class="text-slate-500 text-[10px] sm:text-xs">Tahfidz & Formal</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- STATS SECTION -->
    <section class="py-10 bg-white border-b border-slate-100 relative z-20 -mt-8 sm:-mt-10 mx-4 md:mx-auto max-w-5xl rounded-2xl shadow-lg">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 sm:gap-8 divide-x-0 md:divide-x divide-slate-100">
                <div class="text-center px-2" data-aos="fade-up">
                    <p class="text-2xl sm:text-3xl font-bold text-islamic-green-700">100+</p>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium uppercase tracking-wider">Santri Mukim</p>
                </div>
                <div class="text-center px-2" data-aos="fade-up" data-aos-delay="100">
                    <p class="text-2xl sm:text-3xl font-bold text-islamic-green-700">5 Juz +</p>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium uppercase tracking-wider">Target Tahunan</p>
                </div>
                <div class="text-center px-2" data-aos="fade-up" data-aos-delay="200">
                    <p class="text-2xl sm:text-3xl font-bold text-islamic-green-700">10+</p>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium uppercase tracking-wider">Ustadz Pembimbing</p>
                </div>
                <div class="text-center px-2" data-aos="fade-up" data-aos-delay="300">
                    <p class="text-2xl sm:text-3xl font-bold text-islamic-green-700">3 Th</p>
                    <p class="text-xs sm:text-sm text-slate-500 font-medium uppercase tracking-wider">Berkhidmat</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT SECTION -->
    <section id="tentang" class="py-16 sm:py-24">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-2 gap-12 sm:gap-20 items-center">
                <div class="order-2 lg:order-1" data-aos="fade-right">
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-10 h-[2px] bg-islamic-gold-500"></span>
                        <span class="text-islamic-gold-600 font-bold tracking-widest text-xs sm:text-sm uppercase">Tentang Kami</span>
                    </div>
                    <h2 class="text-2xl sm:text-4xl font-extrabold text-islamic-green-900 mb-6 leading-tight">
                        Dedikasi Kami Melahirkan Generasi Penghafal Al-Qur'an
                    </h2>
                    <p class="text-base sm:text-lg text-slate-600 mb-8 leading-relaxed">
                        MMQ Digital Pesantren Tahfidz hadir dengan visi yang kuat untuk mengintegrasikan nilai-nilai luhur Al-Qur'an dalam kemajuan zaman.
                    </p>
                    
                    <div class="space-y-6 sm:space-y-8">
                        <div class="flex gap-4 sm:gap-5">
                            <div class="flex-shrink-0 w-12 h-12 bg-emerald-100/50 rounded-xl flex items-center justify-center text-islamic-green-600">
                                <i class="bi bi-eye-fill text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-slate-900 mb-1">Visi Kami</h4>
                                <p class="text-sm sm:text-base text-slate-600 leading-relaxed">Terwujudnya generasi Qur'ani yang unggul, mandiri, dan berakhlak mulia di era global.</p>
                            </div>
                        </div>
                        <div class="flex gap-4 sm:gap-5">
                            <div class="flex-shrink-0 w-12 h-12 bg-emerald-100/50 rounded-xl flex items-center justify-center text-islamic-green-600">
                                <i class="bi bi-flag-fill text-xl"></i>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-slate-900 mb-1">Misi Kami</h4>
                                <ul class="text-sm sm:text-base text-slate-600 space-y-2">
                                    <li class="flex items-start gap-2">
                                        <i class="bi bi-check2 text-islamic-green-600 font-bold"></i>
                                        <span>Membimbing santri menghafal 30 juz dengan lancar</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i class="bi bi-check2 text-islamic-green-600 font-bold"></i>
                                        <span>Membiasakan karakter islami dalam keseharian</span>
                                    </li>
                                    <li class="flex items-start gap-2">
                                        <i class="bi bi-check2 text-islamic-green-600 font-bold"></i>
                                        <span>Penguasaan bahasa Arab dan keilmuan kontemporer</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="relative order-1 lg:order-2" data-aos="fade-left">
                    <img src="{{ asset('images/covermmq.png') }}" 
                         alt="Gedung Pesantren" 
                         class="rounded-3xl shadow-2xl w-full h-[300px] sm:h-[500px] object-cover">
                    <div class="absolute -bottom-6 -left-4 sm:-bottom-10 sm:-left-10 bg-islamic-green-800 text-white p-6 sm:p-8 rounded-3xl shadow-xl hidden sm:block max-w-[280px]">
                        <p class="font-arabic text-xl sm:text-2xl mb-2">مَنْ تَعَلَّمَ الْقُرْآنَ عَظُمَتْ قِيمَتُهُ</p>
                        <p class="text-xs opacity-70 italic">"Barangsiapa mempelajari Al-Qur'an, maka agunglah nilai dirinya."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- PROGRAMS SECTION -->
    <section id="program" class="py-16 sm:py-24 bg-islamic-green-900 text-white relative overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <div class="text-center max-w-3xl mx-auto mb-12 sm:mb-16">
                <span class="text-islamic-gold-400 font-bold tracking-widest text-xs sm:text-sm uppercase">Program Unggulan</span>
                <h2 class="text-2xl sm:text-5xl font-bold mt-4 mb-6">Pilihan Kurikulum Terbaik Untuk Masa Depan</h2>
                <div class="h-1 w-20 bg-islamic-gold-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Program 1 -->
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-6 sm:p-10 rounded-3xl hover:bg-white/10 transition-all group" data-aos="fade-up">
                    <div class="w-14 h-14 bg-islamic-gold-500/20 text-islamic-gold-400 rounded-2xl flex items-center justify-center mb-6 sm:mb-8 group-hover:scale-110 transition-transform">
                        <i class="bi bi-book text-3xl"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold mb-4">Tahfidz 30 Juz</h3>
                    <p class="text-sm sm:text-base text-emerald-50/70 leading-relaxed mb-6">
                        Program intensif menghafal Al-Qur'an dengan sistem talaqqi, murojaah harian, dan tasmi' berkala untuk menjaga kualitas hafalan.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-sm text-emerald-100">
                            <i class="bi bi-patch-check text-islamic-gold-500"></i>
                            Target Khatam 3-4 Tahun
                        </li>
                        <li class="flex items-center gap-2 text-sm text-emerald-100">
                            <i class="bi bi-patch-check text-islamic-gold-500"></i>
                            Mutqin Bersanad
                        </li>
                    </ul>
                </div>

                <!-- Program 2 -->
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-6 sm:p-10 rounded-3xl hover:bg-white/10 transition-all group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-islamic-gold-500/20 text-islamic-gold-400 rounded-2xl flex items-center justify-center mb-6 sm:mb-8 group-hover:scale-110 transition-transform">
                        <i class="bi bi-translate text-3xl"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold mb-4">Bahasa & Literasi</h3>
                    <p class="text-sm sm:text-base text-emerald-50/70 leading-relaxed mb-6">
                        Penguasaan Bahasa Arab dan Inggris sebagai kunci memahami literatur klasik (Kitab Kuning) serta menjawab tantangan global.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-sm text-emerald-100">
                            <i class="bi bi-patch-check text-islamic-gold-500"></i>
                            Kajian Kitab Turats
                        </li>
                        <li class="flex items-center gap-2 text-sm text-emerald-100">
                            <i class="bi bi-patch-check text-islamic-gold-500"></i>
                            English & Arabic Club
                        </li>
                    </ul>
                </div>

                <!-- Program 3 -->
                <div class="bg-white/5 backdrop-blur-sm border border-white/10 p-6 sm:p-10 rounded-3xl hover:bg-white/10 transition-all group lg:col-span-1 sm:col-span-2 lg:col-start-auto" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-islamic-gold-500/20 text-islamic-gold-400 rounded-2xl flex items-center justify-center mb-6 sm:mb-8 group-hover:scale-110 transition-transform">
                        <i class="bi bi-mortarboard text-3xl"></i>
                    </div>
                    <h3 class="text-xl sm:text-2xl font-bold mb-4">Pendidikan Formal</h3>
                    <p class="text-sm sm:text-base text-emerald-50/70 leading-relaxed mb-6">
                        Sistem pendidikan yang menyelaraskan kurikulum Kemdikbud/Kemenag (SMP-SMA) tanpa mengurangi jam pelajaran keagamaan.
                    </p>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-sm text-emerald-100">
                            <i class="bi bi-patch-check text-islamic-gold-500"></i>
                            Ijazah Resmi Negara
                        </li>
                        <li class="flex items-center gap-2 text-sm text-emerald-100">
                            <i class="bi bi-patch-check text-islamic-gold-500"></i>
                            Persiapan PTN/Timteng
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Decoration SVG -->
        <div class="absolute bottom-[-10%] right-[-10%] opacity-10 w-64 md:w-[400px]">
            <svg viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                <path fill="#F59E0B" d="M44.7,-76.4C58.2,-69.2,69.7,-59.1,78.2,-46.9C86.7,-34.7,92.2,-20.4,92.6,-5.9C93,8.6,88.3,23.3,81.1,36.9C73.9,50.5,64.2,63,51.8,71.2C39.4,79.4,24.3,83.3,9.4,85.1C-5.5,86.9,-20.2,86.6,-34.2,81.3C-48.2,76,-61.5,65.7,-71.4,52.8C-81.3,39.9,-87.8,24.4,-89.4,8.5C-91,-7.4,-87.7,-23.7,-80,-37.7C-72.3,-51.7,-60.2,-63.4,-46.5,-70.5C-32.8,-77.6,-17.5,-79.9,-1.2,-78C15.1,-76.1,30.2,-70,44.7,-71.1Z" transform="translate(100 100)" />
            </svg>
        </div>
    </section>

    <!-- FACILITIES SECTION -->
    <section id="fasilitas" class="py-16 sm:py-24">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 sm:mb-16 gap-6 text-center md:text-left">
                <div class="max-w-xl">
                    <span class="text-islamic-gold-600 font-bold tracking-widest text-xs sm:text-sm uppercase">Sarana Prasarana</span>
                    <h2 class="text-2xl sm:text-5xl font-bold text-islamic-green-900 mt-4">Kenyamanan Beribadah & Belajar</h2>
                </div>
                <div class="hidden md:block">
                    <p class="text-slate-500 max-w-sm">Fasilitas memadai untuk mendukung efektivitas belajar dan kenyamanan santri selama di pondok.</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <!-- Facility Item -->
                <div class="group card-premium rounded-3xl overflow-hidden bg-white shadow-sm border border-slate-100" data-aos="fade-up">
                    <div class="relative h-56 sm:h-64 overflow-hidden">
                        <img src="{{ asset('images/masjidmmq.png') }}" alt="Masjid" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-islamic-green-900/60 to-transparent"></div>
                        <div class="absolute bottom-5 left-5 text-white">
                            <h4 class="text-lg sm:text-xl font-bold">Masjid Jami'</h4>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-600 leading-relaxed">Pusat kegiatan ibadah harian, kajian kitab, dan tempat tasmi' hafalan Al-Qur'an.</p>
                    </div>
                </div>

                <div class="group card-premium rounded-3xl overflow-hidden bg-white shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative h-56 sm:h-64 overflow-hidden">
                        <img src="{{ asset('images/kelasmmq.png') }}" alt="Ruang Kelas" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-islamic-green-900/60 to-transparent"></div>
                        <div class="absolute bottom-5 left-5 text-white">
                            <h4 class="text-lg sm:text-xl font-bold">Kelas Nyaman</h4>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-600 leading-relaxed">Ruang kelas yang bersih dan representatif, menciptakan suasana belajar yang fokus dan inspiratif.</p>
                    </div>
                </div>

                <div class="group card-premium rounded-3xl overflow-hidden bg-white shadow-sm border border-slate-100" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative h-56 sm:h-64 overflow-hidden">
                        <img src="{{ asset('images/labmmq.png') }}" alt="Laboratorium" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-islamic-green-900/60 to-transparent"></div>
                        <div class="absolute bottom-5 left-5 text-white">
                            <h4 class="text-lg sm:text-xl font-bold"> Ruang Laboratorium</h4>
                        </div>
                    </div>
                    <div class="p-6">
                        <p class="text-sm text-slate-600 leading-relaxed">Ruang laboratorium yang dilengkapi dengan alat-alat modern untuk mendukung belajar ilmu pengetahuan.</p>
                    </div>
                </div>
            </div>

            <!-- ACHIEVEMENT SECTION [NEW] -->
            <div class="mt-20 sm:mt-32">
                <div class="text-center mb-12 sm:mb-16">
                    <span class="text-islamic-gold-600 font-bold tracking-widest text-[10px] sm:text-xs uppercase bg-islamic-gold-50 px-4 py-1.5 rounded-full">Achievements</span>
                    <h2 class="text-2xl sm:text-4xl font-bold text-islamic-green-900 mt-4">Prestasi & Penghargaan</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                    @foreach($prestasis as $prestasi)
                    <div class="bg-gradient-to-br from-white to-slate-50 p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group cursor-pointer flex flex-col h-full" 
                         data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}"
                         data-title="{{ $prestasi->title }}" 
                         data-desc="{{ $prestasi->description }}" 
                         data-image="{{ $prestasi->image ? Storage::url($prestasi->image) : '' }}" 
                         data-icon="bi-trophy-fill"
                         onclick="openDetailModal(this)">
                        <div class="w-14 h-14 bg-islamic-gold-500/10 text-islamic-gold-600 rounded-2xl flex items-center justify-center mb-6 overflow-hidden group-hover:scale-110 transition-transform shrink-0">
                            @if($prestasi->image)
                                <img src="{{ Storage::url($prestasi->image) }}" class="w-full h-full object-cover">
                            @else
                                <i class="bi bi-trophy-fill text-3xl"></i>
                            @endif
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">{{ $prestasi->title }}</h4>
                        <p class="text-sm text-slate-600 leading-relaxed flex-grow">{{ Str::limit($prestasi->description, 100) }}</p>
                    </div>
                    @endforeach

                    @foreach($penghargaans as $penghargaan)
                    <div class="bg-gradient-to-br from-white to-slate-50 p-6 sm:p-8 rounded-3xl border border-slate-100 shadow-sm hover:shadow-md transition-all group cursor-pointer flex flex-col h-full" 
                         data-aos="fade-up" data-aos-delay="{{ ($loop->index + count($prestasis)) * 100 }}"
                         data-title="{{ $penghargaan->title }}" 
                         data-desc="{{ $penghargaan->description }}" 
                         data-image="{{ $penghargaan->image ? Storage::url($penghargaan->image) : '' }}" 
                         data-icon="bi-award-fill"
                         onclick="openDetailModal(this)">
                        <div class="w-14 h-14 bg-islamic-green-500/10 text-islamic-green-600 rounded-2xl flex items-center justify-center mb-6 overflow-hidden group-hover:scale-110 transition-transform shrink-0">
                            @if($penghargaan->image)
                                <img src="{{ Storage::url($penghargaan->image) }}" class="w-full h-full object-cover">
                            @else
                                <i class="bi bi-award-fill text-3xl"></i>
                            @endif
                        </div>
                        <h4 class="text-xl font-bold text-slate-900 mb-3">{{ $penghargaan->title }}</h4>
                        <p class="text-sm text-slate-600 leading-relaxed flex-grow">{{ Str::limit($penghargaan->description, 100) }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- TESTIMONIALS SECTION -->
    <section id="testimoni" class="py-16 sm:py-24 bg-islamic-gold-50/50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12 sm:mb-16">
                <span class="text-islamic-gold-600 font-bold tracking-widest text-xs sm:text-sm uppercase">Testimoni</span>
                <h2 class="text-2xl sm:text-5xl font-bold text-islamic-green-900 mt-4 mb-6">Cerita Mereka Yang Telah Bergabung</h2>
                <div class="h-1 w-20 bg-islamic-gold-500 mx-auto rounded-full"></div>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($testimonis as $testimoni)
                <div class="bg-white p-8 sm:p-10 rounded-[32px] shadow-sm border border-slate-50 relative group flex flex-col h-full" data-aos="zoom-in" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="absolute -top-5 left-10 w-10 h-10 bg-islamic-gold-500 rounded-full flex items-center justify-center text-white shadow-lg shadow-amber-500/30">
                        <i class="bi bi-quote text-2xl"></i>
                    </div>
                    
                    <div class="flex items-center gap-1 mb-6 mt-2">
                        @for($i = 1; $i <= 5; $i++)
                            @if($i <= $testimoni->rating)
                                <i class="bi bi-star-fill text-amber-400 text-sm"></i>
                            @else
                                <i class="bi bi-star text-slate-300 text-sm"></i>
                            @endif
                        @endfor
                    </div>

                    <p class="text-sm sm:text-base text-slate-600 italic mb-8 leading-relaxed flex-grow">
                        "{{ $testimoni->content }}"
                    </p>
                    <div class="flex items-center gap-4 mt-auto">
                        @if($testimoni->image)
                            <img src="{{ Storage::url($testimoni->image) }}" class="w-12 h-12 bg-islamic-green-100 rounded-full object-cover flex-shrink-0 border-2 border-slate-100">
                        @else
                            <div class="w-12 h-12 bg-islamic-green-100 rounded-full flex items-center justify-center text-islamic-green-700 font-bold text-lg flex-shrink-0">
                                {{ strtoupper(substr($testimoni->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="overflow-hidden">
                            <h5 class="font-bold text-slate-900 truncate">{{ $testimoni->name }}</h5>
                            @if($testimoni->role)
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest truncate">{{ $testimoni->role }}</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>


    <!-- CTA / REGISTRATION SECTION -->
    <section id="daftar" class="py-16 sm:py-24 relative overflow-hidden bg-slate-50">
        <div class="container mx-auto px-4">
            <div class="bg-islamic-green-800 rounded-[40px] sm:rounded-[60px] p-8 sm:p-20 relative overflow-hidden shadow-2xl">
                <!-- Background Pattern -->
                <div class="absolute inset-0 opacity-10 pointer-events-none">
                    <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
                        <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.3"/>
                        </pattern>
                        <rect width="100" height="100" fill="url(#grid)" />
                    </svg>
                </div>

                <div class="relative z-10 text-center text-white max-w-3xl mx-auto">
                    <h2 class="text-3xl sm:text-5xl font-bold mb-6 sm:mb-8" data-aos="fade-up">Daftarkan Putra-Putri Anda <span class="text-islamic-gold-400 font-arabic">اِقْرَأْ</span> Sekarang!</h2>
                    <p class="text-base sm:text-xl text-emerald-100/80 mb-10 sm:mb-12" data-aos="fade-up" data-aos-delay="100">
                        Bergabunglah dengan komunitas penghafal Al-Qur'an dan raih keutamaan menjadi keluarga Allah di bumi.
                    </p>
                    
                    <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center" data-aos="fade-up" data-aos-delay="200">
                        <a href="https://wa.me/62812345678" class="bg-white text-islamic-green-900 px-8 py-4 sm:px-10 sm:py-5 rounded-2xl font-bold text-base sm:text-lg hover:bg-emerald-50 transition-all flex items-center justify-center gap-3 shadow-xl">
                            <i class="bi bi-whatsapp text-xl sm:text-2xl"></i>
                            Daftar via WhatsApp
                        </a>
                        <a href="tel:+62812345678" class="border-2 border-white/30 hover:bg-white/10 px-8 py-4 sm:px-10 sm:py-5 rounded-2xl font-bold text-base sm:text-lg transition-all flex items-center justify-center gap-3 backdrop-blur-sm">
                            <i class="bi bi-telephone text-xl sm:text-2xl"></i>
                            Hubungi Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEEDBACK SECTION -->
    <section class="py-16 sm:py-24 bg-white">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-12 gap-12 sm:gap-16 items-start">
                <div class="lg:col-span-5 text-center lg:text-left" data-aos="fade-right">
                    <span class="text-islamic-gold-600 font-bold tracking-widest text-xs sm:text-sm uppercase">Feedback</span>
                    <h2 class="text-2xl sm:text-4xl font-bold text-islamic-green-900 mt-4 mb-6 leading-tight">Suara Anda Adalah Amanah Bagi Kami</h2>
                    <p class="text-sm sm:text-base text-slate-500 mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
                        Kami sangat menghargai setiap saran dan masukan untuk terus meningkatkan kualitas pelayanan dan pendidikan di MMQ Digital.
                    </p>
                    
                    <div class="bg-emerald-50 p-6 sm:p-8 rounded-3xl border border-emerald-100 inline-block text-left">
                        <div class="flex items-center gap-4 mb-4">
                            <div class="w-10 h-10 bg-islamic-green-600 text-white rounded-full flex items-center justify-center">
                                <i class="bi bi-headset"></i>
                            </div>
                            <h4 class="font-bold text-islamic-green-900">Bantuan 24/7</h4>
                        </div>
                        <p class="text-xs sm:text-sm text-islamic-green-800/70 mb-0">Layanan informasi pendaftaran dan konsultasi pendidikan tersedia setiap hari kerja.</p>
                    </div>
                </div>
                
                <div class="lg:col-span-7" data-aos="fade-left">
                    <div class="bg-white shadow-2xl shadow-slate-200/50 p-8 sm:p-12 rounded-[32px] sm:rounded-[40px] border border-slate-50">
                        @if(session('success'))
                            <div class="bg-emerald-50 border border-emerald-100 text-islamic-green-800 p-4 rounded-2xl mb-6 flex items-center gap-3">
                                <i class="bi bi-check-circle-fill text-xl"></i>
                                <span class="text-sm font-bold">{{ session('success') }}</span>
                            </div>
                        @endif

                        @if($errors->any())
                            <div class="bg-red-50 border border-red-100 text-red-800 p-4 rounded-2xl mb-6">
                                <ul class="list-disc list-inside text-xs font-bold">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('questions.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <div class="grid sm:grid-cols-2 gap-6">
                                <div class="space-y-2">
                                    <label class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                                    <input type="text" name="name" value="{{ old('name') }}" required class="w-full px-5 py-4 rounded-xl sm:rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-islamic-green-500 transition-all placeholder:text-slate-300 text-sm sm:text-base" placeholder="Fulan bin Fulan">
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Email / WhatsApp</label>
                                    <input type="text" name="email" value="{{ old('email') }}" required class="w-full px-5 py-4 rounded-xl sm:rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-islamic-green-500 transition-all placeholder:text-slate-300 text-sm sm:text-base" placeholder="0812xxxxxx">
                                </div>
                            </div>
                            <div class="space-y-2">
                                <label class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-widest ml-1">Pesan / Pertanyaan</label>
                                <textarea name="question" rows="4" required class="w-full px-5 py-4 rounded-xl sm:rounded-2xl bg-slate-50 border-none focus:ring-2 focus:ring-islamic-green-500 transition-all placeholder:text-slate-300 text-sm sm:text-base" placeholder="Tuliskan pertanyaan atau saran Anda di sini...">{{ old('question') }}</textarea>
                            </div>
                            <button type="submit" class="w-full py-4 sm:py-5 bg-islamic-green-700 hover:bg-islamic-green-800 text-white font-bold rounded-xl sm:rounded-2xl transition-all shadow-xl shadow-islamic-green-200/50 flex items-center justify-center gap-3 group">
                                <span class="text-sm sm:text-lg">Kirim Masukan</span>
                                <i class="bi bi-send group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer id="kontak" class="bg-islamic-green-950 text-white pt-20 pb-10">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-12 gap-10 sm:gap-12 mb-16">
                <div class="lg:col-span-5 space-y-6 sm:space-y-8 text-center lg:text-left">
                    <a href="#" class="inline-flex items-center gap-3">
                        <span class="text-4xl font-arabic text-islamic-gold-500">☪</span>
                        <div class="flex flex-col text-left">
                            <span class="text-xl sm:text-2xl font-bold text-white tracking-tight">MMQ DIGITAL</span>
                            <span class="text-[10px] text-islamic-gold-400 tracking-[0.3em] font-bold uppercase">Boarding School</span>
                        </div>
                    </a>
                    <p class="text-emerald-100/60 leading-relaxed max-w-sm mx-auto lg:mx-0 text-sm sm:text-base">
                        Lembaga pendidikan Al-Qur'an terpercaya yang berfokus pada kualitas hafalan dan karakter santri yang berakhlakul karimah.
                    </p>
                    <div class="flex gap-4 justify-center lg:justify-start">
                        <a href="#" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-islamic-gold-500 hover:border-islamic-gold-500 hover:scale-110 transition-all">
                            <i class="bi bi-facebook text-lg sm:text-xl"></i>
                        </a>
                        <a href="https://www.instagram.com/pptqmmq_pacitan" target="_blank" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-islamic-gold-500 hover:border-islamic-gold-500 hover:scale-110 transition-all">
                            <i class="bi bi-instagram text-lg sm:text-xl"></i>
                        </a>
                        <a href="#" class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center hover:bg-islamic-gold-500 hover:border-islamic-gold-500 hover:scale-110 transition-all">
                            <i class="bi bi-youtube text-lg sm:text-xl"></i>
                        </a>
                    </div>
                </div>
                
                <div class="lg:col-span-3 text-center lg:text-left">
                    <h5 class="text-lg font-bold mb-6 sm:mb-8 text-islamic-gold-400">Tautan Cepat</h5>
                    <ul class="space-y-3 sm:space-y-4 text-emerald-100/60 text-sm sm:text-base">
                        <li><a href="#tentang" class="hover:text-islamic-gold-400 transition-colors">Tentang Kami</a></li>
                        <li><a href="#program" class="hover:text-islamic-gold-400 transition-colors">Program Unggulan</a></li>
                        <li><a href="#fasilitas" class="hover:text-islamic-gold-400 transition-colors">Fasilitas Pondok</a></li>
                        <li><a href="#testimoni" class="hover:text-islamic-gold-400 transition-colors">Suara Santri</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-islamic-gold-400 transition-colors flex items-center gap-2 justify-center lg:justify-start"><i class="bi bi-shield-lock"></i> Area Admin</a></li>
                    </ul>
                </div>
                
                <div class="lg:col-span-4 text-center lg:text-left">
                    <h5 class="text-lg font-bold mb-6 sm:mb-8 text-islamic-gold-400">Informasi Kontak</h5>
                    <ul class="space-y-5 sm:space-y-6">
                        <li class="flex flex-col lg:flex-row items-center lg:items-start gap-3 lg:gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center text-islamic-gold-500">
                                <i class="bi bi-geo-alt"></i>
                            </div>
                            <span class="text-emerald-100/60 text-sm">Dusun Barong Wetan, Desa Candi, Kecamatan Pringkuku, Pacitan, Jawa Timur.</span>
                        </li>
                        <li class="flex flex-col lg:flex-row items-center lg:items-start gap-3 lg:gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center text-islamic-gold-500">
                                <i class="bi bi-whatsapp"></i>
                            </div>
                            <span class="text-emerald-100/60 text-sm">+62 812 3456 7890</span>
                        </li>
                        <li class="flex flex-col lg:flex-row items-center lg:items-start gap-3 lg:gap-4">
                            <div class="flex-shrink-0 w-10 h-10 bg-white/5 rounded-lg flex items-center justify-center text-islamic-gold-500">
                                <i class="bi bi-envelope"></i>
                            </div>
                            <span class="text-emerald-100/60 text-sm">info@mmqdigital.sch.id</span>
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-white/5 pt-10 text-center">
                <p class="text-[10px] sm:text-xs text-emerald-100/30 font-medium">
                    &copy; 2026 MMQ DIGITAL Pesantren Tahfidz. All Rights Reserved. <br class="sm:hidden"> جَزَاكَ اللهُ خَيْرًا
                    <br> Developed By Izzetnity</a>
                </p>
            </div>
        </div>
    </footer>

    <!-- Detail Modal -->
    <div id="detailModal" class="fixed inset-0 z-[10000] hidden items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" onclick="closeDetailModal()"></div>
        
        <!-- Modal Content -->
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden transform scale-95 transition-transform duration-300" id="detailModalContent">
            <button onclick="closeDetailModal()" class="absolute top-4 right-4 w-10 h-10 bg-black/50 hover:bg-black/70 text-white rounded-full flex items-center justify-center transition-colors z-10">
                <i class="bi bi-x text-2xl"></i>
            </button>
            
            <div id="detailModalImageContainer" class="w-full h-[50vh] sm:h-[60vh] bg-slate-900 flex items-center justify-center flex-shrink-0 relative">
                <img id="detailModalImage" src="" alt="Detail Image" class="w-full h-full object-contain hidden" style="padding: 1rem;">
                <div id="detailModalIconContainer" class="hidden text-islamic-green-600 bg-islamic-green-50 w-full h-full flex items-center justify-center">
                    <i id="detailModalIcon" class="text-6xl sm:text-8xl"></i>
                </div>
            </div>
            
            <div class="p-6 sm:p-8 overflow-y-auto">
                <h3 id="detailModalTitle" class="text-2xl sm:text-3xl font-bold text-islamic-green-900 mb-4"></h3>
                <p id="detailModalDesc" class="text-slate-600 leading-relaxed text-sm sm:text-base whitespace-pre-wrap"></p>
            </div>
        </div>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <!-- AOS - Animate On Scroll JS -->
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    
    <script>
        // Initialize AOS
        AOS.init({
            duration: 900,
            easing: 'ease-out-quad',
            once: true,
            offset: 40,
            disable: 'mobile' 
        });

        // Initialize Swiper for FAQ
        var swiper = new Swiper(".faq-swiper", {
            direction: "vertical",
            slidesPerView: 2,
            spaceBetween: 16,
            pagination: {
                el: ".swiper-pagination-custom",
                clickable: true,
                renderBullet: function (index, className) {
                    return '<span class="' + className + ' w-2 h-2 rounded-full bg-slate-200 cursor-pointer pointer-events-auto transition-all [&.swiper-pagination-bullet-active]:bg-islamic-green-600 [&.swiper-pagination-bullet-active]:w-5"></span>';
                },
            },
            mousewheel: true,
            breakpoints: {
                320: {
                    direction: "horizontal",
                    slidesPerView: 1,
                },
                1024: {
                    direction: "vertical",
                    slidesPerView: 2,
                }
            }
        });

        // Navbar Scroll Effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            const navBrandTitle = document.getElementById('nav-brand-title');
            const navBrandSubtitle = document.getElementById('nav-brand-subtitle');
            const navLinks = document.getElementById('nav-links');
            const navMobileToggle = document.getElementById('nav-mobile-toggle');

            if (window.scrollY > 40) {
                // Scrolled State (White Background)
                navbar.classList.add('glass-navbar', 'py-3', 'shadow-2xl', 'shadow-islamic-green-950/5');
                navbar.classList.remove('py-4', 'lg:py-5');
                
                // Switch to Dark Text
                navBrandTitle.classList.replace('text-white', 'text-islamic-green-800');
                navBrandSubtitle.classList.replace('text-emerald-200', 'text-islamic-green-600');
                
                navLinks.classList.remove('text-white');
                navLinks.classList.add('text-slate-600');
                
                navMobileToggle.classList.replace('text-white', 'text-islamic-green-800');
            } else {
                // Top State (Transparent/Dark Background)
                navbar.classList.remove('glass-navbar', 'py-3', 'shadow-2xl', 'shadow-islamic-green-950/5');
                navbar.classList.add('py-4', 'lg:py-5');
                
                // Switch to Light Text
                navBrandTitle.classList.replace('text-islamic-green-800', 'text-white');
                navBrandSubtitle.classList.replace('text-islamic-green-600', 'text-emerald-200');
                
                navLinks.classList.remove('text-slate-600');
                navLinks.classList.add('text-white');
                
                navMobileToggle.classList.replace('text-islamic-green-800', 'text-white');
            }
        });

        // Mobile Menu Toggle Logic
        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            const shade = document.getElementById('mobile-menu-shade');
            const icon = document.getElementById('menu-icon');
            
            const isOpen = !menu.classList.contains('translate-x-full');
            
            if (!isOpen) {
                menu.classList.remove('translate-x-full');
                shade.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                menu.classList.add('translate-x-full');
                shade.classList.add('hidden');
                document.body.style.overflow = '';
            }
        }

        // Detail Modal Logic
        function openDetailModal(element) {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('detailModalContent');
            const imgEl = document.getElementById('detailModalImage');
            const iconContainer = document.getElementById('detailModalIconContainer');
            const iconEl = document.getElementById('detailModalIcon');
            const titleEl = document.getElementById('detailModalTitle');
            const descEl = document.getElementById('detailModalDesc');

            const title = element.getAttribute('data-title');
            const desc = element.getAttribute('data-desc');
            const image = element.getAttribute('data-image');
            const iconClass = element.getAttribute('data-icon');

            titleEl.textContent = title;
            descEl.textContent = desc;

            if (image && image !== '') {
                imgEl.src = image;
                imgEl.classList.remove('hidden');
                iconContainer.classList.add('hidden');
                iconContainer.classList.remove('flex');
            } else {
                imgEl.classList.add('hidden');
                iconContainer.classList.remove('hidden');
                iconContainer.classList.add('flex');
                iconEl.className = 'text-6xl sm:text-8xl bi ' + iconClass;
            }

            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Trigger animation
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.classList.add('opacity-100');
                content.classList.remove('scale-95');
                content.classList.add('scale-100');
                document.body.style.overflow = 'hidden';
            }, 10);
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            const content = document.getElementById('detailModalContent');
            
            modal.classList.remove('opacity-100');
            modal.classList.add('opacity-0');
            content.classList.remove('scale-100');
            content.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.body.style.overflow = '';
            }, 300);
        }
    </script>

    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/62822444751558?text=Assalamu%27alaikum%20Wr.%20Wb.%20Saya%20ingin%20bertanya%20tentang%20pendaftaran%20santri%20di%20MMQ%20Digital%20Pesantren%20Tahfidz" 
       class="whatsapp-float" 
       target="_blank"
       aria-label="Chat WhatsApp">
        <span class="whatsapp-tooltip">Chat dengan Kami</span>
        <i class="bi bi-whatsapp"></i>
    </a>

    <!-- Donation Floating Button -->
    <a href="/donasi" 
       class="donate-float" 
       aria-label="Donasi Pembangunan">
        <span class="donate-tooltip">Dukung Pembangunan</span>
        <i class="bi bi-heart-fill"></i>
    </a>

    <!-- AI Chatbot Widget -->
    <div class="chatbot-float">
        <button class="chatbot-button" id="chatbotToggle" aria-label="Buka Chat AI">
            <i class="bi bi-robot"></i>
            <span class="chatbot-badge">AI</span>
        </button>
    </div>

    <div class="chatbot-window" id="chatbotWindow">
        <div class="chatbot-header">
            <div class="chatbot-header-info">
                <div class="chatbot-avatar">
                    <i class="bi bi-robot"></i>
                </div>
                <div>
                    <div style="font-weight: 600; font-size: 15px;">Asisten MMQ</div>
                    <div style="font-size: 11px; opacity: 0.9;">Siap membantu Anda</div>
                </div>
            </div>
            <button class="chatbot-close" id="chatbotClose">
                <i class="bi bi-x"></i>
            </button>
        </div>

        <div class="chatbot-messages" id="chatbotMessages">
            <!-- Messages will be inserted here -->
        </div>

        <div class="chatbot-quick-replies" id="chatbotQuickReplies" style="display: none;">
            <!-- Quick replies will be inserted here -->
        </div>

        <div class="chatbot-input-area">
            <input type="text" 
                   class="chatbot-input" 
                   id="chatbotInput" 
                   placeholder="Ketik pertanyaan Anda..."
                   maxlength="1000">
            <button class="chatbot-send-btn" id="chatbotSend">
                <i class="bi bi-send-fill"></i>
            </button>
        </div>
    </div>

    <script>
        // Chatbot functionality
        const chatbotToggle = document.getElementById('chatbotToggle');
        const chatbotWindow = document.getElementById('chatbotWindow');
        const chatbotClose = document.getElementById('chatbotClose');
        const chatbotMessages = document.getElementById('chatbotMessages');
        const chatbotInput = document.getElementById('chatbotInput');
        const chatbotSend = document.getElementById('chatbotSend');
        const chatbotQuickReplies = document.getElementById('chatbotQuickReplies');

        let chatHistory = [];
        let isTyping = false;

        // Toggle chat window
        chatbotToggle.addEventListener('click', () => {
            chatbotWindow.classList.add('active');
            if (chatHistory.length === 0) {
                addBotMessage("Assalamu'alaikum! Saya Asisten Virtual MMQ. Ada yang bisa saya bantu tentang PPTQ Makkah Madinatul Qur'an?");
                loadQuickReplies();
            }
            chatbotInput.focus();
        });

        chatbotClose.addEventListener('click', () => {
            chatbotWindow.classList.remove('active');
        });

        // Load quick replies
        function loadQuickReplies() {
            fetch('/api/chatbot/quick-replies')
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.quick_replies) {
                        chatbotQuickReplies.innerHTML = '';
                        data.quick_replies.forEach(reply => {
                            const btn = document.createElement('button');
                            btn.className = 'quick-reply-btn';
                            btn.textContent = reply;
                            btn.onclick = () => sendMessage(reply);
                            chatbotQuickReplies.appendChild(btn);
                        });
                        chatbotQuickReplies.style.display = 'flex';
                    }
                })
                .catch(error => console.error('Error loading quick replies:', error));
        }

        // Add message to chat
        function addMessage(message, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `chatbot-message ${isUser ? 'user' : 'bot'}`;
            
            const bubble = document.createElement('div');
            bubble.className = `message-bubble ${isUser ? 'user' : 'bot'}`;
            bubble.textContent = message;
            
            messageDiv.appendChild(bubble);
            chatbotMessages.appendChild(messageDiv);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        function addBotMessage(message) {
            addMessage(message, false);
        }

        function addUserMessage(message) {
            addMessage(message, true);
        }

        // Show typing indicator
        function showTyping() {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'chatbot-message bot';
            typingDiv.id = 'typing-indicator';
            
            const bubble = document.createElement('div');
            bubble.className = 'message-bubble bot typing-indicator';
            bubble.innerHTML = '<div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div>';
            
            typingDiv.appendChild(bubble);
            chatbotMessages.appendChild(typingDiv);
            chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
        }

        function hideTyping() {
            const typingIndicator = document.getElementById('typing-indicator');
            if (typingIndicator) {
                typingIndicator.remove();
            }
        }

        // Send message
        async function sendMessage(message = null) {
            const userMessage = message || chatbotInput.value.trim();
            
            if (!userMessage || isTyping) return;

            // Add user message
            addUserMessage(userMessage);
            chatbotInput.value = '';
            chatbotQuickReplies.style.display = 'none';

            // Show typing
            isTyping = true;
            chatbotSend.disabled = true;
            showTyping();

            try {
                console.log('Sending message to chatbot API:', userMessage);
                
                const csrfToken = document.querySelector('meta[name="csrf-token"]');
                if (!csrfToken) {
                    console.error('CSRF token not found!');
                    throw new Error('CSRF token tidak ditemukan');
                }

                const response = await fetch('/api/chatbot/message', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken.content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        message: userMessage,
                        history: chatHistory
                    })
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    const errorText = await response.text();
                    console.error('API Error Response:', errorText);
                    throw new Error(`HTTP ${response.status}: ${errorText}`);
                }

                const data = await response.json();
                console.log('API Response:', data);
                
                hideTyping();
                
                if (data.success) {
                    addBotMessage(data.message);
                    
                    // Update history
                    chatHistory.push(
                        { role: 'user', content: userMessage },
                        { role: 'model', content: data.message }
                    );
                    
                    // Limit history to last 10 exchanges
                    if (chatHistory.length > 20) {
                        chatHistory = chatHistory.slice(-20);
                    }
                    
                    // Show quick replies again after bot responds
                    chatbotQuickReplies.style.display = 'flex';
                } else {
                    console.error('API returned success=false:', data);
                    addBotMessage(data.message || 'Maaf, terjadi kesalahan. Silakan coba lagi.');
                    chatbotQuickReplies.style.display = 'flex';
                }
            } catch (error) {
                hideTyping();
                console.error('Chat error:', error);
                addBotMessage('Maaf, terjadi kesalahan: ' + error.message + '. Silakan refresh halaman dan coba lagi.');
            } finally {
                isTyping = false;
                chatbotSend.disabled = false;
            }
        }

        // Event listeners
        chatbotSend.addEventListener('click', () => sendMessage());
        chatbotInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });
    </script>
</body>
</html>