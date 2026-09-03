<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Rapor MMQ</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logoo.png') }}">
    <meta name="description" content="Sistem Informasi Laporan Hasil Belajar (Rapor) Santri Pondok Pesantren Tahfidzul Qur'an Makkah Madinatul Qur'an Pacitan.">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="Sistem Rapor MMQ Digital">
    <meta property="og:description" content="Sistem Informasi Laporan Hasil Belajar (Rapor) Santri Pondok Pesantren Tahfidzul Qur'an Makkah Madinatul Qur'an Pacitan.">
    <meta property="og:image" content="{{ asset('images/logoo.png') }}">
    <meta property="og:site_name" content="MMQ Digital">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="Sistem Rapor MMQ Digital">
    <meta property="twitter:description" content="Sistem Informasi Laporan Hasil Belajar (Rapor) Santri Pondok Pesantren Tahfidzul Qur'an Makkah Madinatul Qur'an Pacitan.">
    <meta property="twitter:image" content="{{ asset('images/logoo.png') }}">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- ApexCharts for Modern Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <style>
        :root {
            --sidebar-color: #0f172a;
            --sidebar-hover: #1e293b;
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
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
            z-index: 1050;
            position: sticky;
            top: 0;
            height: 100vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255, 255, 255, 0.06);
        }

        /* Thin elegant scrollbar for sidebar */
        #sidebar::-webkit-scrollbar {
            width: 6px;
        }
        #sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        #sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 3px;
        }
        #sidebar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar-header { 
            padding: 24px 20px; 
            background: rgba(15, 23, 42, 0.4); 
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            display: flex;
            align-items: center;
        }

        .sidebar-header svg {
            transition: transform 0.3s ease;
        }

        .sidebar-header:hover svg {
            transform: scale(1.05) rotate(2deg);
        }
        
        #sidebar ul li a { 
            padding: 11px 16px; 
            margin: 4px 16px;
            display: flex;
            align-items: center;
            color: #94a3b8; 
            text-decoration: none; 
            font-weight: 500; 
            border-radius: 10px;
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        }
        
        .menu-label {
            padding: 24px 24px 8px 24px;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: #475569;
            display: flex;
            align-items: center;
        }

        .menu-label::after {
            content: '';
            flex-grow: 1;
            height: 1px;
            background: linear-gradient(90deg, rgba(71, 85, 105, 0.2) 0%, rgba(71, 85, 105, 0) 100%);
            margin-left: 12px;
        }

        #sidebar ul li a:hover { 
            background: rgba(255, 255, 255, 0.04);
            color: #f1f5f9; 
            padding-left: 20px;
        }

        #sidebar ul li a.active { 
            background: linear-gradient(90deg, rgba(14, 165, 233, 0.15) 0%, rgba(14, 165, 233, 0.02) 100%);
            color: #38bdf8; 
            font-weight: 600;
            border-left: 3px solid #0ea5e9;
            border-radius: 0 8px 8px 0;
            margin-left: 0;
            padding-left: 29px;
            box-shadow: inset 5px 0 15px rgba(14, 165, 233, 0.08);
        }

        #sidebar ul li a i {
            font-size: 1.15rem;
            width: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            transition: color 0.25s ease;
        }

        #sidebar ul li a:hover i {
            color: #38bdf8;
        }

        #sidebar ul li a.active i {
            color: #38bdf8;
        }

        .btn-logout {
            background: rgba(239, 68, 68, 0.05);
            color: #f87171;
            border: 1px solid rgba(239, 68, 68, 0.15) !important;
            transition: all 0.25s ease;
            font-size: 0.85rem;
            letter-spacing: 0.5px;
        }

        .btn-logout:hover {
            background: #ef4444 !important;
            color: #fff !important;
            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.2);
            border-color: #ef4444 !important;
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
            --sidebar-color: #080c14;
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
        [data-theme="dark"] .sidebar-header { background: rgba(8, 12, 20, 0.4); }

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

        /* ============================================================
           GLOBAL ADMIN PAGE STYLES
           Applied across all admin pages automatically
        ============================================================ */

        /* Page Background */
        .content-wrapper {
            background: #f8fafc;
            min-height: 100vh;
            width: 100%;
        }

        /* Page Header */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            flex-wrap: wrap;
            gap: 16px;
            margin-bottom: 24px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f1f5f9;
        }
        .page-header-left h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #0f172a;
            margin-bottom: 4px;
            letter-spacing: -0.3px;
        }
        .page-header-left p {
            font-size: 0.82rem;
            color: #94a3b8;
            margin: 0;
        }

        /* Main Card */
        .card-main {
            border: 1px solid #f1f5f9 !important;
            border-radius: 20px !important;
            background: #ffffff !important;
            box-shadow: 0 1px 3px rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.03) !important;
            backdrop-filter: none !important;
            transition: box-shadow 0.2s ease;
        }
        .card-main:hover {
            box-shadow: 0 4px 20px rgba(0,0,0,0.07) !important;
        }

        /* Premium Table */
        .table-premium {
            border-collapse: separate;
            border-spacing: 0;
        }
        .table-premium thead tr {
            background: #f8fafc;
        }
        .table-premium thead th {
            font-size: 0.68rem !important;
            text-transform: uppercase !important;
            letter-spacing: 1.2px !important;
            color: #94a3b8 !important;
            font-weight: 700 !important;
            padding: 14px 18px !important;
            border-bottom: 1px solid #f1f5f9 !important;
            border-top: none !important;
            white-space: nowrap;
        }
        .table-premium tbody td {
            padding: 14px 18px !important;
            vertical-align: middle !important;
            border-top: 1px solid #f8fafc !important;
            border-bottom: none !important;
        }
        .table-premium tbody tr {
            transition: background 0.15s ease;
        }
        .table-premium tbody tr:hover {
            background: #f8fafc !important;
        }
        .table-premium tbody tr:last-child td {
            border-bottom: none !important;
        }

        /* Action Buttons */
        .btn-action {
            width: 34px !important;
            height: 34px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 10px !important;
            transition: all 0.2s ease !important;
            border: none !important;
            background: #f1f5f9 !important;
            text-decoration: none !important;
            font-size: 0.82rem !important;
        }
        .btn-action:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
        }
        .btn-edit { color: #0369a1 !important; }
        .btn-edit:hover { background: #0ea5e9 !important; color: white !important; }
        .btn-delete { color: #dc2626 !important; }
        .btn-delete:hover { background: #ef4444 !important; color: white !important; }
        .btn-print { color: #7c3aed !important; }
        .btn-print:hover { background: #8b5cf6 !important; color: white !important; }

        /* Status Badges */
        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 12px !important;
            border-radius: 100px !important;
            font-size: 0.7rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.3px;
        }
        .status-aktif, .status-active { background: #d1fae5 !important; color: #047857 !important; }
        .status-lulus { background: #dbeafe !important; color: #1d4ed8 !important; }
        .status-pindah, .status-inactive { background: #fee2e2 !important; color: #b91c1c !important; }

        /* Avatar Circle */
        .avatar-circle {
            width: 40px !important;
            height: 40px !important;
            background: linear-gradient(135deg, #0ea5e9, #6366f1) !important;
            color: white !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 12px !important;
            font-weight: 700 !important;
            font-size: 0.88rem !important;
            box-shadow: 0 2px 8px rgba(14,165,233,0.2) !important;
            flex-shrink: 0;
        }

        /* Search Input Wrapper */
        .search-container {
            position: relative;
        }
        .search-container .form-control,
        .search-container input {
            border-radius: 12px !important;
            border: 1.5px solid #e2e8f0 !important;
            padding-left: 42px !important;
            font-size: 0.88rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .search-container .form-control:focus,
        .search-container input:focus {
            border-color: #0ea5e9 !important;
            box-shadow: 0 0 0 3px rgba(14,165,233,0.08) !important;
        }

        /* Form controls global refinement */
        .form-control, .form-select {
            border-radius: 12px !important;
            border: 1.5px solid #e2e8f0 !important;
            font-size: 0.88rem;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-control:focus, .form-select:focus {
            border-color: #0ea5e9 !important;
            box-shadow: 0 0 0 3px rgba(14,165,233,0.08) !important;
        }

        /* Badge kelas */
        .badge-kelas, .badge-info {
            background: #e0f2fe !important;
            color: #0369a1 !important;
            padding: 4px 10px !important;
            border-radius: 100px !important;
            font-size: 0.72rem !important;
            font-weight: 600 !important;
        }

        /* Primary button refinement */
        .btn-primary {
            background: linear-gradient(135deg, #0ea5e9 0%, #0284c7 100%) !important;
            border: none !important;
            font-weight: 600 !important;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%) !important;
            box-shadow: 0 6px 16px rgba(14,165,233,0.25) !important;
            transform: translateY(-1px);
        }

        /* Empty state */
        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px 20px;
            color: #94a3b8;
            text-align: center;
        }
        .empty-state i { font-size: 3rem; margin-bottom: 12px; opacity: 0.4; }
        .empty-state p { font-size: 0.88rem; margin: 0; }

        /* Dark mode: admin pages */
        [data-theme="dark"] .content-wrapper  { background: #0f172a !important; }
        [data-theme="dark"] .page-header       { border-bottom-color: #334155; }
        [data-theme="dark"] .page-header-left h1 { color: #f1f5f9 !important; }
        [data-theme="dark"] .card-main {
            background: #1e293b !important;
            border-color: #334155 !important;
            box-shadow: 0 4px 20px rgba(0,0,0,0.3) !important;
        }
        [data-theme="dark"] .table-premium thead tr { background: #0f172a; }
        [data-theme="dark"] .table-premium thead th { color: #64748b !important; border-bottom-color: #334155 !important; }
        [data-theme="dark"] .table-premium tbody td { border-top-color: #334155 !important; }
        [data-theme="dark"] .table-premium tbody tr:hover { background: #334155 !important; }
        [data-theme="dark"] .btn-action { background: #334155 !important; }
        [data-theme="dark"] .btn-edit:hover   { background: #0ea5e9 !important; }
        [data-theme="dark"] .btn-delete:hover { background: #ef4444 !important; }
        [data-theme="dark"] .badge-kelas,
        [data-theme="dark"] .badge-info   { background: #0c2d54 !important; color: #38bdf8 !important; }
        [data-theme="dark"] .status-aktif,
        [data-theme="dark"] .status-active { background: #052e16 !important; color: #34d399 !important; }
        [data-theme="dark"] .status-lulus  { background: #1e3a5f !important; color: #60a5fa !important; }
        [data-theme="dark"] .status-pindah,
        [data-theme="dark"] .status-inactive { background: #450a0a !important; color: #f87171 !important; }
        [data-theme="dark"] .form-control,
        [data-theme="dark"] .form-select   { border-color: #334155 !important; }
        [data-theme="dark"] .search-container .form-control,
        [data-theme="dark"] .search-container input { border-color: #334155 !important; background: #1e293b !important; }
    </style>
</head>
<body>

<div id="sidebar-overlay" onclick="toggleSidebar()"></div>

<div id="wrapper">
    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="me-3" style="filter: drop-shadow(0 2px 8px rgba(56, 189, 248, 0.45));">
                <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
            </svg>
            <div>
                <h5 class="fw-bold mb-0 text-white" style="letter-spacing: 0.5px; font-size: 1.05rem;">MMQ DIGITAL</h5>
                <small class="fw-medium" style="color: #38bdf8; font-size: 0.72rem; letter-spacing: 0.5px;">Sistem Rapor Praktis</small>
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
                <a href="{{ route('admin.guru.index') }}" class="{{ Request::is('admin/guru*') ? 'active' : '' }}">
                    <i class="bi bi-person-badge me-3"></i> Data Guru
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kelas.index') }}" class="{{ Request::is('admin/kelas*') ? 'active' : '' }}">
                    <i class="bi bi-school me-3"></i> Data Kelas
                </a>
            </li>
            <li>
                <a href="{{ route('admin.ekstrakurikuler.index') }}" class="{{ Request::is('admin/ekstrakurikuler*') ? 'active' : '' }}">
                    <i class="bi bi-star-fill me-3"></i> Ekstrakurikuler
                </a>
            </li>
            <li>
                <a href="{{ route('admin.santri.index') }}" class="{{ Request::is('admin/santri*') ? 'active' : '' }}">
                    <i class="bi bi-person-lines-fill me-3"></i> Data Santri
                </a>
            </li>
            <li>
                <a href="{{ route('admin.mutasi-kelas.index') }}" class="{{ Request::is('admin/mutasi-kelas*') ? 'active' : '' }}">
                    <i class="bi bi-arrow-left-right me-3"></i> Mutasi Kelas
                </a>
            </li>
            <li>
                <a href="{{ route('admin.teaching.index') }}" class="{{ Request::is('admin/teaching*') ? 'active' : '' }}">
                    <i class="bi bi-book-fill me-3"></i> Data Mengajar Guru
                </a>
            </li>
            <li>
                <a href="{{ route('raport-kmi.grid') }}" class="{{ Request::is('raport-kmi/grid') ? 'active' : '' }}">
                    <i class="bi bi-grid-3x3-gap-fill me-3 {{ Request::is('raport-kmi/grid') ? '' : 'text-warning' }}"></i> Smart Input
                </a>
            </li>
            <li>
                <a href="/input" class="{{ Request::is('input') ? 'active' : '' }}">
                    <i class="bi bi-pencil-square me-3"></i> Nilai Tahfidz
                </a>
            </li>
            <li>
                <a href="{{ route('raport-kmi.create') }}" class="{{ Request::is('raport-kmi/create') ? 'active' : '' }}">
                    <i class="bi bi-pencil-square me-3"></i> Nilai KMI  
                </a>
            </li>
            <li>
                <a href="{{ route('admin.presensi.index') }}" class="{{ Request::is('admin/presensi*') ? 'active' : '' }}">
                    <i class="bi bi-clipboard-check me-3"></i> Input Presensi
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

        <div class="mt-auto px-4 pb-4 text-center">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-logout w-100 fw-bold py-2.5 rounded-3">
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