<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rapor MMQ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- ApexCharts for Modern Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        :root {
            --sidebar-color: #1e293b;
            --sidebar-hover: #334155;
            --accent-blue: #0ea5e9;
            --bg-light: #f8fafc;
            --glass-bg: rgba(255, 255, 255, 0.95);
            --glass-border: rgba(255, 255, 255, 0.8);
            --table-border: #f1f4f9;
            --input-bg: #ffffff;
            --text-main: #334155;
            --text-muted: #64748b;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: var(--bg-light); 
            color: #334155;
            overflow-x: hidden;
        }

        #wrapper { display: flex; align-items: stretch; min-height: 100vh; position: relative; }
        
        /* Sidebar Style */
        #sidebar { 
            min-width: 280px; 
            max-width: 280px; 
            background: var(--sidebar-color); 
            color: #fff; 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 4px 0 10px rgba(0,0,0,0.05);
            z-index: 1050;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
        }

        .sidebar-header { 
            padding: 30px 25px; 
            background: #0f172a; 
            border-bottom: 1px solid rgba(255,255,255,0.05);
            display: flex;
            align-items: center;
        }
        
        #sidebar ul li a { 
            padding: 12px 20px; 
            margin: 4px 15px;
            display: flex;
            align-items: center;
            color: #cbd5e1; 
            text-decoration: none; 
            font-weight: 500; 
            border-radius: 12px;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .menu-label {
            padding: 20px 25px 8px 30px;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: #64748b;
            display: block;
        }

        #sidebar ul li a:hover { 
            background: rgba(255,255,255,0.05);
            color: #fff; 
            transform: translateX(4px);
        }

        #sidebar ul li a.active { 
            background: linear-gradient(135deg, #38bdf8 0%, #0ea5e9 100%);
            color: #fff; 
            font-weight: 600;
            box-shadow: 0 4px 12px rgba(14, 165, 233, 0.25);
        }

        #sidebar ul li a i {
            font-size: 1.25rem;
            width: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Topbar Style */
        .top-navbar {
            background: #fff;
            padding: 15px 25px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Content Container */
        #main-wrapper { width: 100%; display: flex; flex-direction: column; min-width: 0; }
        #content { padding: 25px; flex-grow: 1; }

        /* Mobile Sidebar Behavior */
        @media (max-width: 991.98px) {
            #sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                box-shadow: 10px 0 20px rgba(0,0,0,0.2);
            }
            #sidebar.active {
                left: 0;
            }
            #content { padding: 20px 15px; }
            .top-navbar { padding: 15px; }
        }

        /* Sidebar Backdrop */
        #sidebar-overlay {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(2px);
            z-index: 1040;
            display: none;
        }

        #sidebar-overlay.active {
            display: block;
        }

        /* Card & Button Enhancements */
        .card { 
            border: none; 
            border-radius: 16px; 
            box-shadow: 0 4px 20px rgba(0,0,0,0.04); 
            background: #fff;
            margin-bottom: 20px;
        }

        /* ===== DARK MODE ===== */
        [data-theme="dark"] {
            --bg-light: #0f172a;
            --sidebar-color: #0c1222;
            --sidebar-hover: #1e293b;
            --glass-bg: rgba(30, 41, 59, 0.95);
            --glass-border: rgba(71, 85, 105, 0.5);
            --table-border: #334155;
            --primary-soft: #1e3a5f;
            --input-bg: #1e293b;
            --text-main: #e2e8f0;
            --text-muted: #94a3b8;
        }

        [data-theme="dark"] body,
        [data-theme="dark"] { color: var(--text-main); }

        /* Smooth transition */
        body, .top-navbar, .card, .section-card, .stat-card-premium,
        .leaderboard-item, .table, .form-control, .form-select,
        .modal-content, #content, .score-cell, .grid-input-wrapper,
        .content-wrapper, .input-group-text, .page-item .page-link {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        /* ---- Navbar ---- */
        [data-theme="dark"] .top-navbar {
            background: #1e293b;
            border-bottom-color: #334155;
        }
        [data-theme="dark"] .top-navbar .bg-light,
        [data-theme="dark"] .top-navbar .d-none.d-sm-flex {
            background: #334155 !important;
            border-color: #475569 !important;
        }
        [data-theme="dark"] .top-navbar .text-dark { color: #e2e8f0 !important; }
        [data-theme="dark"] .top-navbar .text-muted { color: #94a3b8 !important; }
        [data-theme="dark"] .top-navbar .fw-bold.small.text-uppercase { color: #e2e8f0 !important; }
        [data-theme="dark"] .top-navbar .btn-light {
            background: #334155; border-color: #475569; color: #e2e8f0;
        }

        /* ---- Main content area ---- */
        [data-theme="dark"] #content { background: #0f172a; }
        [data-theme="dark"] .content-wrapper { background: #0f172a !important; }
        [data-theme="dark"] .grid-input-wrapper { background: #0f172a !important; }
        [data-theme="dark"] .container-fluid { color: #cbd5e1; }

        /* ---- Cards ---- */
        [data-theme="dark"] .card,
        [data-theme="dark"] .card-main,
        [data-theme="dark"] .section-card { background: #1e293b !important; box-shadow: 0 4px 20px rgba(0,0,0,0.3) !important; color: var(--text-main); }
        [data-theme="dark"] .card-body { background: transparent !important; }
        [data-theme="dark"] .stat-card-premium {
            background: #1e293b !important;
            border-color: #334155 !important;
        }
        [data-theme="dark"] .stat-card-premium:hover {
            border-color: #0ea5e9 !important;
            box-shadow: 0 15px 30px rgba(0,0,0,0.3) !important;
        }
        [data-theme="dark"] .leaderboard-item {
            background: #334155 !important;
            border-color: #475569 !important;
            color: var(--text-main);
        }
        [data-theme="dark"] .leaderboard-item:hover { background: #3b4f6b !important; }

        /* ---- Tables (all pages) ---- */
        [data-theme="dark"] .table,
        [data-theme="dark"] .table-premium { color: var(--text-main); background: transparent; }
        [data-theme="dark"] .table thead th,
        [data-theme="dark"] .table-premium thead th,
        [data-theme="dark"] .table-hover tbody tr:hover { background: #334155 !important; color: var(--text-main) !important; }
        [data-theme="dark"] .table td,
        [data-theme="dark"] .table th { border-color: #334155 !important; color: var(--text-main); }
        [data-theme="dark"] .table tbody tr { background: transparent; }
        [data-theme="dark"] .table .fw-bold.text-dark,
        [data-theme="dark"] .table .text-dark { color: #e2e8f0 !important; }
        [data-theme="dark"] .table-premium thead th { border-bottom-color: #334155 !important; }

        /* ---- Bootstrap & Tailwind utilities ---- */
        html[data-theme="dark"] .bg-light,
        html[data-theme="dark"] .bg-gray-50 { background-color: #0f172a !important; color: #e2e8f0; }
        html[data-theme="dark"] .bg-white { background-color: #1e293b !important; color: #e2e8f0; }
        html[data-theme="dark"] .shadow-lg { box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important; }
        
        [data-theme="dark"] .table,
        [data-theme="dark"] .table tbody,
        [data-theme="dark"] .table thead,
        [data-theme="dark"] .table tr,
        [data-theme="dark"] .table td, 
        [data-theme="dark"] .table th {
            background-color: transparent !important; /* Allow container bg to show */
        }
        
        [data-theme="dark"] .border { border-color: #334155 !important; }
        [data-theme="dark"] .border-bottom { border-color: #334155 !important; }
        [data-theme="dark"] .shadow-sm { box-shadow: 0 2px 8px rgba(0,0,0,0.3) !important; }

        /* ---- Text overrides (bright & readable) ---- */
        [data-theme="dark"] .text-dark { color: #ffffff !important; }
        [data-theme="dark"] .text-muted,
        [data-theme="dark"] .text-gray-500 { color: #a8b8cd !important; }
        [data-theme="dark"] .text-secondary { color: #a8b8cd !important; }
        [data-theme="dark"] .text-slate-800 { color: #f1f5f9 !important; }
        [data-theme="dark"] .text-gray-600 { color: #cbd5e1 !important; }
        [data-theme="dark"] .text-gray-700 { color: #e2e8f0 !important; }
        [data-theme="dark"] .text-gray-800 { color: #ffffff !important; font-weight: bold; }
        [data-theme="dark"] h1, [data-theme="dark"] h2, [data-theme="dark"] h3,
        [data-theme="dark"] h4, [data-theme="dark"] h5, [data-theme="dark"] h6 { color: #ffffff; }
        [data-theme="dark"] .fw-bold { color: #ffffff; }
        [data-theme="dark"] .fw-medium { color: #e2e8f0; }
        [data-theme="dark"] .badge.bg-light { background: #334155 !important; color: #a8b8cd !important; }
        [data-theme="dark"] label,
        [data-theme="dark"] .form-label { color: #e2e8f0; }
        [data-theme="dark"] p { color: #cbd5e1; }
        [data-theme="dark"] span { color: inherit; }
        [data-theme="dark"] td { color: #e2e8f0; }
        [data-theme="dark"] small { color: #a8b8cd; }
        [data-theme="dark"] .small { color: #a8b8cd; }
        [data-theme="dark"] .text-uppercase { color: #ffffff; }
        [data-theme="dark"] .text-truncate { color: #e2e8f0; }
        [data-theme="dark"] .musyrif-name { color: #a8b8cd !important; }
        [data-theme="dark"] .santri-name { color: #ffffff !important; }

        /* ---- Progress bars ---- */
        [data-theme="dark"] .progress { background: #334155; }

        /* ---- Forms & Inputs ---- */
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select,
        [data-theme="dark"] .form-control-minimal,
        [data-theme="dark"] input[type="text"],
        [data-theme="dark"] input[type="number"],
        [data-theme="dark"] input[type="email"],
        [data-theme="dark"] input[type="password"],
        [data-theme="dark"] textarea {
            background-color: var(--input-bg) !important; 
            border-color: #475569 !important; 
            color: #e2e8f0 !important;
        }
        [data-theme="dark"] .form-control::placeholder,
        [data-theme="dark"] input::placeholder { color: #64748b; }
        [data-theme="dark"] .form-control:focus,
        [data-theme="dark"] .form-select:focus,
        [data-theme="dark"] input:focus {
            background-color: #3b4f6b !important; 
            border-color: #0ea5e9 !important; 
            color: #ffffff !important;
        }
        
        /* Score cells (Smart Grid inputs) */
        [data-theme="dark"] .score-cell {
            background: #334155 !important;
            border-color: #475569 !important;
            color: #e2e8f0 !important;
        }
        [data-theme="dark"] .score-cell:focus {
            border-color: #3b82f6 !important;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2) !important;
        }
        /* Search container */
        [data-theme="dark"] .search-container {
            background: #334155 !important;
            border-color: #475569 !important;
        }

        /* ---- Modals ---- */
        [data-theme="dark"] .modal-content { background: #1e293b !important; border-color: #334155; }
        [data-theme="dark"] .modal-header { border-bottom-color: #334155; }
        [data-theme="dark"] .modal-footer { border-top-color: #334155; }
        [data-theme="dark"] .btn-close { filter: invert(1); }
        [data-theme="dark"] .list-group-item { background: #334155 !important; border-color: #475569; color: #cbd5e1; }

        /* ---- Sidebar ---- */
        [data-theme="dark"] .sidebar-header { background: #070d1a; }

        /* ---- Buttons ---- */
        [data-theme="dark"] .btn-light {
            background: #334155 !important; border-color: #475569 !important; color: #e2e8f0 !important;
        }
        [data-theme="dark"] .btn-outline-primary { border-color: #3b82f6; color: #60a5fa; }
        [data-theme="dark"] .btn-outline-primary:hover { background: #3b82f6; color: white; }
        [data-theme="dark"] .btn-action { background: #334155 !important; }
        [data-theme="dark"] .btn-action.btn-edit { background: rgba(59,130,246,0.15) !important; color: #60a5fa; }
        [data-theme="dark"] .btn-action.btn-print { background: rgba(239,68,68,0.15) !important; color: #f87171; }
        [data-theme="dark"] .btn-action.btn-delete { background: rgba(107,114,128,0.15) !important; color: #94a3b8; }

        /* ---- Avatar circle (Daftar page) ---- */
        [data-theme="dark"] .avatar-circle { background: linear-gradient(135deg, #1e40af 0%, #0ea5e9 100%) !important; }

        /* ---- Card footer & alerts ---- */
        [data-theme="dark"] .card-footer { background: #1e293b !important; border-top-color: #334155 !important; }
        [data-theme="dark"] .alert { border-color: #475569; background-color: #1e293b; color: #e2e8f0; }
        [data-theme="dark"] .alert-success { background-color: rgba(22, 163, 74, 0.1); border-color: rgba(22, 163, 74, 0.2); color: #4ade80; }

        /* ---- Page-specific misc ---- */
        [data-theme="dark"] .link-box { background: #334155 !important; border-color: #475569 !important; color: #38bdf8 !important; }
        [data-theme="dark"] .swal2-popup { background: #1e293b !important; color: #e2e8f0 !important; }
        [data-theme="dark"] .swal2-title { color: #f1f5f9 !important; }
        [data-theme="dark"] .swal2-html-container { color: #cbd5e1 !important; }
        [data-theme="dark"] .dropdown-menu { background-color: #1e293b; border-color: #334155; }
        [data-theme="dark"] .dropdown-item { color: #e2e8f0; }
        [data-theme="dark"] .dropdown-item:hover { background-color: #334155; color: #fff; }

        /* Special colored text that should keep their color in dark mode */
        [data-theme="dark"] .text-success { color: #34d399 !important; }
        [data-theme="dark"] .text-danger { color: #f87171 !important; }
        [data-theme="dark"] .text-warning { color: #fbbf24 !important; }
        [data-theme="dark"] .text-info { color: #38bdf8 !important; }
        [data-theme="dark"] .text-primary { color: #60a5fa !important; }

        /* Theme Toggle */
        .theme-toggle {
            width: 38px; height: 38px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            display: flex; align-items: center; justify-content: center;
            cursor: pointer; transition: all 0.3s;
            font-size: 1.1rem;
        }
        .theme-toggle:hover { background: #e2e8f0; transform: scale(1.05); }
        [data-theme="dark"] .theme-toggle { background: #334155; border-color: #475569; color: #fbbf24; }
        [data-theme="dark"] .theme-toggle:hover { background: #475569; }
    </style>
</head>
<body>

<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<div id="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('images/logoo.png') }}" alt="Logo" width="50" class="me-3">
            <div>
                <h5 class="fw-bold mb-0 text-white">MMQ DIGITAL</h5>
                <small class="text-info opacity-75">Sistem Rapor Praktis</small>
            </div>
            <button class="btn btn-link text-white d-lg-none ms-auto p-0" onclick="toggleSidebar()">
                <i class="bi bi-x-lg fs-4"></i>
            </button>
        </div>
        
        <ul class="list-unstyled mt-2">
            <li class="menu-label">Menu Utama</li>
            <li>
                <a href="{{ route('dashboard') }}" class="{{ Request::is('dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill me-3"></i> Dashboard
                </a>
            </li>

            <li class="menu-label">Input & Master Data</li>
            <li>
                <a href="{{ route('raport-kmi.grid') }}" class="{{ Request::is('raport-kmi/grid') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap-fill me-3 {{ Request::is('raport-kmi/grid') ? '' : 'text-warning' }}"></i> Smart Grid Input
                </a>
            </li>
            <li>
                <a href="/input" class="{{ Request::is('input') ? 'active' : '' }}">
                    <i class="bi bi-pencil-square me-3"></i> Nilai Tahfidz
                </a>
            </li>
            <li>
                <a href="{{ route('raport-kmi.create') }}" class="{{ Request::is('raport-kmi/create') ? 'active' : '' }}">
                    <i class="bi bi-pencil-square me-3"></i> Nilai KMI (Legacy)
                </a>
            </li>

            <li class="menu-label">Laporan & Rekap</li>
            <li>
                <a href="/daftar" class="{{ Request::is('daftar*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill me-3"></i> Data Santri Tahfidz
                </a>
            </li>
            <li>
                <a href="{{ route('raport-kmi.index') }}" class="{{ Request::is('raport-kmi*') ? 'active' : '' }}">
                    <i class="bi bi-journal-text me-3"></i> Data Raport KMI
                </a>
            </li>
            <li>
                <a href="{{ route('rekapitulasi') }}" class="{{ Request::is('rekapitulasi') ? 'active' : '' }}">
                    <i class="bi bi-bar-chart-line-fill me-3"></i> Rekapitulasi Nilai
                </a>
            </li>
            
            <li class="menu-label">Kelola Konten Web</li>
            <li>
                <a href="{{ route('admin.prestasi.index') }}" class="{{ Request::is('admin/prestasi*') ? 'active' : '' }}">
                    <i class="bi bi-trophy-fill me-3"></i> Data Prestasi
                </a>
            </li>
            <li>
                <a href="{{ route('admin.penghargaan.index') }}" class="{{ Request::is('admin/penghargaan*') ? 'active' : '' }}">
                    <i class="bi bi-award-fill me-3"></i> Data Penghargaan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.testimoni.index') }}" class="{{ Request::is('admin/testimoni*') ? 'active' : '' }}">
                    <i class="bi bi-star-fill me-3"></i> Data Testimoni
                </a>
            </li>
            <li>
                <a href="{{ route('admin.donasi.index') }}" class="{{ Request::is('admin/donasi*') ? 'active' : '' }}">
                    <i class="bi bi-card-image me-3"></i> Data Donasi Poster
                </a>
            </li>
            <li>
                <a href="{{ route('admin.questions.index') }}" class="{{ Request::is('admin/questions*') ? 'active' : '' }}">
                    <i class="bi bi-chat-square-text me-3"></i> Kelola FAQ
                </a>
            </li>
        </ul>

        <div class="mt-auto px-3 pb-4 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 fw-bold py-2 rounded-3">
                    <i class="bi bi-box-arrow-left me-2"></i> KELUAR
                </button>
            </form>
        </div>
    </nav>

    <div id="main-wrapper">
        <header class="top-navbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none shadow-sm rounded-3 border" onclick="toggleSidebar()">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <div class="d-none d-sm-flex align-items-center bg-light rounded-pill px-3 py-2 border shadow-sm">
                    <div class="d-flex align-items-center border-end pe-3 me-3">
                        <i class="bi bi-calendar3 me-2 text-primary"></i>
                        <span class="text-dark fw-bold small text-nowrap">
                            {{ now()->translatedFormat('d M Y') }}
                        </span>
                    </div>
                    <div class="d-flex align-items-center">
                        <i class="bi bi-clock me-2 text-warning"></i>
                        <span id="liveClock" class="text-dark fw-bold small">00:00</span>
                    </div>
                </div>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button class="theme-toggle" onclick="toggleTheme()" title="Toggle Dark Mode" id="themeToggleBtn">
                    <i class="bi bi-moon-fill"></i>
                </button>
                <div class="text-end me-2 d-none d-sm-block">
                    <p class="mb-0 fw-bold small text-uppercase">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="mb-0 text-muted small" style="font-size: 0.65rem; letter-spacing: 1px;">SISTEM RAPOR</p>
                </div>
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; border: 3px solid #f1f5f9;">
                    <i class="bi bi-person-fill text-white fs-5"></i>
                </div>
            </div>
        </header>

        <div id="content">
            @yield('main-content')
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
        
        if (sidebar.classList.contains('active')) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = '';
        }

        // Trigger resize event to make sure charts/grids adjust
        setTimeout(() => {
            window.dispatchEvent(new Event('resize'));
        }, 300);
    }

    function updateClock() {
        const now = new Date();
        const options = { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false };
        const clockElement = document.getElementById('liveClock');
        if (clockElement) { clockElement.textContent = now.toLocaleTimeString('en-GB', options); }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Theme Toggle
    function toggleTheme() {
        const html = document.documentElement;
        const current = html.getAttribute('data-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        html.setAttribute('data-theme', next);
        localStorage.setItem('mmq-theme', next);
        updateThemeIcon(next);
    }
    function updateThemeIcon(theme) {
        const btn = document.getElementById('themeToggleBtn');
        if (btn) btn.innerHTML = theme === 'dark'
            ? '<i class="bi bi-sun-fill"></i>'
            : '<i class="bi bi-moon-fill"></i>';
    }
    // Apply saved theme on load
    (function() {
        const saved = localStorage.getItem('mmq-theme') || 'light';
        document.documentElement.setAttribute('data-theme', saved);
        updateThemeIcon(saved);
    })();
</script>
</body>
</html>