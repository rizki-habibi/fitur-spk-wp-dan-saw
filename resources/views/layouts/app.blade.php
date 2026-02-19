<!DOCTYPE html>
<html lang="id" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SPK WP & SAW') - SPK Penerimaan Beasiswa Prestasi</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <style>
        /* ============================================================
           THEME VARIABLES - Light & Dark Mode + Accent Colors
           ============================================================ */
        :root {
            --sidebar-width: 280px;
            --font-base: 'Inter', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --transition-speed: 0.3s;
            --border-radius: 0.75rem;
        }

        /* ---- LIGHT THEME ---- */
        [data-theme="light"] {
            --bg-body: #f1f5f9;
            --bg-card: #ffffff;
            --bg-topbar: #ffffff;
            --bg-input: #ffffff;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --text-muted: #94a3b8;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.08);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --sidebar-bg-start: #1e1b4b;
            --sidebar-bg-end: #312e81;
            --sidebar-text: #c7d2fe;
            --sidebar-label: #818cf8;
            --sidebar-hover-bg: rgba(255,255,255,0.08);
            --sidebar-active-bg: rgba(79, 70, 229, 0.3);
            --sidebar-active-border: #818cf8;
            --table-header-color: #64748b;
            --badge-bg: #f1f5f9;
            --alert-success-bg: #dcfce7;
            --alert-danger-bg: #fee2e2;
            --guide-bg: #f8fafc;
            --stat-overlay: rgba(0,0,0,0);
        }

        /* ---- DARK THEME ---- */
        [data-theme="dark"] {
            --bg-body: #0f172a;
            --bg-card: #1e293b;
            --bg-topbar: #1e293b;
            --bg-input: #334155;
            --text-primary: #f1f5f9;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --border-color: #334155;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.3);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.4);
            --sidebar-bg-start: #0f0e27;
            --sidebar-bg-end: #1a1744;
            --sidebar-text: #a5b4fc;
            --sidebar-label: #6366f1;
            --sidebar-hover-bg: rgba(255,255,255,0.05);
            --sidebar-active-bg: rgba(99, 102, 241, 0.3);
            --sidebar-active-border: #6366f1;
            --table-header-color: #94a3b8;
            --badge-bg: #334155;
            --alert-success-bg: #064e3b;
            --alert-danger-bg: #7f1d1d;
            --guide-bg: #1e293b;
            --stat-overlay: rgba(0,0,0,0.15);
        }

        /* ---- ACCENT COLORS ---- */
        [data-accent="indigo"] { --accent: #4f46e5; --accent-hover: #4338ca; --accent-light: #e0e7ff; --accent-rgb: 79,70,229; }
        [data-accent="blue"]   { --accent: #2563eb; --accent-hover: #1d4ed8; --accent-light: #dbeafe; --accent-rgb: 37,99,235; }
        [data-accent="emerald"]{ --accent: #059669; --accent-hover: #047857; --accent-light: #d1fae5; --accent-rgb: 5,150,105; }
        [data-accent="rose"]   { --accent: #e11d48; --accent-hover: #be123c; --accent-light: #ffe4e6; --accent-rgb: 225,29,72; }
        [data-accent="amber"]  { --accent: #d97706; --accent-hover: #b45309; --accent-light: #fef3c7; --accent-rgb: 217,119,6; }
        [data-accent="violet"] { --accent: #7c3aed; --accent-hover: #6d28d9; --accent-light: #ede9fe; --accent-rgb: 124,58,237; }
        [data-accent="cyan"]   { --accent: #0891b2; --accent-hover: #0e7490; --accent-light: #cffafe; --accent-rgb: 8,145,178; }

        /* ---- FONT SIZES ---- */
        [data-fontsize="small"] { font-size: 13px; }
        [data-fontsize="medium"] { font-size: 15px; }
        [data-fontsize="large"] { font-size: 17px; }

        /* ---- LAYOUT DENSITY ---- */
        [data-density="compact"] .card-body { padding: 0.75rem; }
        [data-density="compact"] .content-area { padding: 1rem; }
        [data-density="compact"] .table td, [data-density="compact"] .table th { padding: 0.35rem 0.5rem; }
        [data-density="compact"] .stat-card { padding: 1rem; }
        [data-density="compact"] .stat-card h3 { font-size: 1.5rem; }
        [data-density="comfortable"] .card-body { padding: 1.25rem; }
        [data-density="comfortable"] .content-area { padding: 2rem; }

        /* ---- GLASSMORPHISM ---- */
        [data-glass="true"][data-theme="dark"] .card {
            background: rgba(30, 41, 59, 0.7) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.08);
        }
        [data-glass="true"][data-theme="dark"] .top-bar {
            background: rgba(30, 41, 59, 0.7) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        [data-glass="true"][data-theme="dark"] .modal-content {
            background: rgba(30, 41, 59, 0.9) !important;
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }

        /* ---- ANIMATIONS TOGGLE ---- */
        [data-animations="false"] * {
            animation: none !important;
            transition: none !important;
        }

        /* ============================================================
           BASE STYLES
           ============================================================ */
        * { font-family: var(--font-base); }

        body {
            background: var(--bg-body);
            color: var(--text-primary);
            min-height: 100vh;
            transition: background var(--transition-speed) ease, color var(--transition-speed) ease;
        }

        /* Sidebar */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-bg-start) 0%, var(--sidebar-bg-end) 100%);
            z-index: 1050;
            transition: transform 0.3s ease, background 0.3s ease;
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.15) transparent;
        }

        .sidebar::-webkit-scrollbar { width: 4px; }
        .sidebar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); border-radius: 2px; }

        .sidebar-brand {
            padding: 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-brand h4 {
            color: #fff;
            font-weight: 700;
            margin: 0;
            font-size: 1.2rem;
        }

        .sidebar-brand small {
            color: #a5b4fc;
            font-size: 0.75rem;
        }

        .sidebar-nav {
            padding: 1rem 0;
        }

        .sidebar-nav .nav-label {
            color: var(--sidebar-label);
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 0.75rem 1.5rem 0.5rem;
        }

        .sidebar-nav .nav-link {
            color: var(--sidebar-text);
            padding: 0.6rem 1.5rem;
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }

        .sidebar-nav .nav-link:hover {
            background: var(--sidebar-hover-bg);
            color: #fff;
        }

        .sidebar-nav .nav-link.active {
            background: var(--sidebar-active-bg);
            color: #fff;
            border-left-color: var(--sidebar-active-border);
            font-weight: 600;
        }

        .sidebar-nav .nav-link i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        .top-bar {
            background: var(--bg-topbar);
            padding: 1rem 2rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: background var(--transition-speed) ease;
            position: sticky;
            top: 0;
            z-index: 1020;
        }

        .top-bar h5 { color: var(--text-primary); }

        .content-area {
            padding: 2rem;
        }

        /* Cards */
        .card {
            border: none;
            box-shadow: var(--shadow-sm);
            border-radius: var(--border-radius);
            background: var(--bg-card);
            transition: background var(--transition-speed) ease, box-shadow var(--transition-speed) ease;
        }

        .card-header {
            background: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            font-weight: 600;
            padding: 1rem 1.25rem;
            border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
            color: var(--text-primary);
        }

        .card-body { color: var(--text-primary); }

        .stat-card {
            border-radius: var(--border-radius);
            padding: 1.5rem;
            color: #fff;
            position: relative;
            overflow: hidden;
        }

        .stat-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background: var(--stat-overlay);
            pointer-events: none;
        }

        .stat-card .stat-icon {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 3rem;
            opacity: 0.2;
        }

        .stat-card h3 { font-size: 2rem; font-weight: 700; margin: 0; position: relative; z-index: 1; }
        .stat-card p { margin: 0; opacity: 0.9; font-size: 0.875rem; position: relative; z-index: 1; }

        /* Table */
        .table { color: var(--text-primary); }
        .table th {
            font-weight: 600;
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: var(--table-header-color);
            border-bottom: 2px solid var(--border-color);
        }

        .table td {
            vertical-align: middle;
            border-color: var(--border-color);
        }

        .table-hover tbody tr:hover {
            background-color: rgba(var(--accent-rgb), 0.05);
        }

        /* Dark mode tables */
        [data-theme="dark"] .table { --bs-table-bg: transparent; --bs-table-hover-bg: rgba(255,255,255,0.03); }
        [data-theme="dark"] .table-striped > tbody > tr:nth-of-type(odd) > * { --bs-table-bg-type: rgba(255,255,255,0.02); }

        /* Badges */
        .badge-benefit { background: #10b981; }
        .badge-cost { background: #ef4444; }

        /* Form controls in dark mode */
        [data-theme="dark"] .form-control, [data-theme="dark"] .form-select {
            background-color: var(--bg-input);
            border-color: var(--border-color);
            color: var(--text-primary);
        }
        [data-theme="dark"] .form-control::placeholder { color: var(--text-muted); }
        [data-theme="dark"] .form-control:focus, [data-theme="dark"] .form-select:focus {
            background-color: var(--bg-input);
            border-color: var(--accent);
            color: var(--text-primary);
            box-shadow: 0 0 0 0.2rem rgba(var(--accent-rgb), 0.25);
        }

        /* Modal dark mode */
        [data-theme="dark"] .modal-content {
            background-color: var(--bg-card);
            color: var(--text-primary);
            border-color: var(--border-color);
        }
        [data-theme="dark"] .modal-header { border-bottom-color: var(--border-color); }
        [data-theme="dark"] .modal-footer { border-top-color: var(--border-color); }
        [data-theme="dark"] .btn-close { filter: invert(1); }
        [data-theme="dark"] .modal-header .btn-close { filter: none; }

        /* Alert dark */
        [data-theme="dark"] .alert-success { background: var(--alert-success-bg); color: #6ee7b7; border-color: #065f46; }
        [data-theme="dark"] .alert-danger { background: var(--alert-danger-bg); color: #fca5a5; border-color: #991b1b; }

        /* Guide Modal */
        .guide-step {
            padding: 1rem;
            border-left: 3px solid var(--accent);
            background: var(--guide-bg);
            margin-bottom: 1rem;
            border-radius: 0 0.5rem 0.5rem 0;
        }

        .guide-step h6 {
            color: var(--accent);
            font-weight: 600;
        }

        /* Responsive */
        .sidebar-toggle { display: none; }

        @media (max-width: 992px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: inline-block; }
            .sidebar-overlay {
                display: none;
                position: fixed; inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 1040;
            }
            .sidebar-overlay.show { display: block; }
        }

        /* Animations */
        .fade-in { animation: fadeIn 0.5s ease-in; }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Print */
        @media print {
            .sidebar, .top-bar, .btn, .no-print, #settingsPanel, .settings-fab { display: none !important; }
            .main-content { margin-left: 0 !important; }
            .content-area { padding: 0 !important; }
            body { background: #fff !important; color: #000 !important; }
        }

        /* Guide/Accent buttons */
        .btn-guide {
            background: linear-gradient(135deg, var(--accent), var(--accent-hover));
            color: #fff;
            border: none;
            border-radius: 50px;
            padding: 0.4rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .btn-guide:hover { filter: brightness(1.1); color: #fff; }

        .btn-accent { background: var(--accent); color: #fff; border: none; }
        .btn-accent:hover { background: var(--accent-hover); color: #fff; }

        /* ============================================================
           SETTINGS PANEL (Slide-out)
           ============================================================ */
        .settings-panel {
            position: fixed;
            top: 0;
            right: -380px;
            width: 370px;
            height: 100vh;
            background: var(--bg-card);
            z-index: 1100;
            transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            box-shadow: -4px 0 20px rgba(0,0,0,0.15);
            border-left: 1px solid var(--border-color);
        }

        .settings-panel.show { right: 0; }

        .settings-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.4);
            z-index: 1090;
            backdrop-filter: blur(2px);
        }

        .settings-overlay.show { display: block; }

        .settings-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            background: var(--bg-card);
            z-index: 5;
        }

        .settings-header h5 { margin: 0; font-weight: 700; color: var(--text-primary); }

        .settings-body { padding: 1.5rem; }

        .settings-section { margin-bottom: 1.75rem; }

        .settings-section-title {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            color: var(--text-muted);
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        /* Theme toggle cards */
        .theme-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; }

        .theme-option {
            padding: 0.6rem;
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--text-secondary);
        }

        .theme-option:hover { border-color: var(--accent); color: var(--accent); }
        .theme-option.active { border-color: var(--accent); background: rgba(var(--accent-rgb), 0.1); color: var(--accent); }
        .theme-option i { font-size: 1.3rem; display: block; margin-bottom: 0.3rem; }

        /* Accent color picker */
        .accent-options { display: flex; gap: 0.5rem; flex-wrap: wrap; }

        .accent-dot {
            width: 36px; height: 36px;
            border-radius: 50%;
            cursor: pointer;
            border: 3px solid transparent;
            transition: all 0.2s;
            position: relative;
        }

        .accent-dot:hover { transform: scale(1.15); }
        .accent-dot.active { border-color: var(--text-primary); box-shadow: 0 0 0 2px var(--bg-card); }
        .accent-dot.active::after {
            content: '\2713';
            position: absolute; inset: 0;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-weight: 700; font-size: 0.8rem;
        }

        .accent-dot[data-accent="indigo"] { background: #4f46e5; }
        .accent-dot[data-accent="blue"]   { background: #2563eb; }
        .accent-dot[data-accent="emerald"]{ background: #059669; }
        .accent-dot[data-accent="rose"]   { background: #e11d48; }
        .accent-dot[data-accent="amber"]  { background: #d97706; }
        .accent-dot[data-accent="violet"] { background: #7c3aed; }
        .accent-dot[data-accent="cyan"]   { background: #0891b2; }

        /* Toggle switch */
        .setting-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .setting-toggle:hover { border-color: var(--accent); }
        .setting-toggle .toggle-info { display: flex; align-items: center; gap: 0.75rem; }
        .setting-toggle .toggle-info i { font-size: 1.1rem; color: var(--accent); width: 24px; text-align: center; }
        .setting-toggle .toggle-label { font-size: 0.85rem; font-weight: 500; color: var(--text-primary); }
        .setting-toggle .toggle-desc { font-size: 0.7rem; color: var(--text-muted); }

        /* Custom switch */
        .custom-switch { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
        .custom-switch input { opacity: 0; width: 0; height: 0; }
        .custom-switch .slider {
            position: absolute; cursor: pointer; inset: 0;
            background: var(--border-color); border-radius: 24px; transition: 0.3s;
        }
        .custom-switch .slider::before {
            content: '';
            position: absolute; height: 18px; width: 18px;
            left: 3px; bottom: 3px;
            background: white; border-radius: 50%; transition: 0.3s;
        }
        .custom-switch input:checked + .slider { background: var(--accent); }
        .custom-switch input:checked + .slider::before { transform: translateX(20px); }

        /* Size options */
        .size-options { display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem; }

        .size-option {
            padding: 0.5rem;
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            text-align: center;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            color: var(--text-secondary);
        }

        .size-option:hover { border-color: var(--accent); color: var(--accent); }
        .size-option.active { border-color: var(--accent); background: rgba(var(--accent-rgb), 0.1); color: var(--accent); }

        /* Density options */
        .density-options { display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.5rem; }

        .density-option {
            padding: 0.6rem;
            border: 2px solid var(--border-color);
            border-radius: 0.5rem;
            text-align: center;
            cursor: pointer;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.2s;
            color: var(--text-secondary);
        }

        .density-option:hover { border-color: var(--accent); color: var(--accent); }
        .density-option.active { border-color: var(--accent); background: rgba(var(--accent-rgb), 0.1); color: var(--accent); }
        .density-option i { display: block; font-size: 1.2rem; margin-bottom: 0.2rem; }

        /* Settings footer */
        .settings-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--border-color);
            position: sticky;
            bottom: 0;
            background: var(--bg-card);
        }

        /* Focus Mode */
        body.focus-mode .sidebar { transform: translateX(-100%); }
        body.focus-mode .main-content { margin-left: 0; }
        body.focus-mode .top-bar { border-left: 4px solid var(--accent); }

        /* Floating settings button */
        .settings-fab {
            position: fixed;
            bottom: 1.5rem;
            right: 1.5rem;
            width: 48px; height: 48px;
            border-radius: 50%;
            background: var(--accent);
            color: #fff;
            border: none;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            box-shadow: 0 4px 15px rgba(var(--accent-rgb), 0.4);
            z-index: 1060;
            transition: all 0.3s;
            animation: fabPulse 2s infinite;
        }

        .settings-fab:hover { transform: scale(1.1) rotate(30deg); color: #fff; }

        @keyframes fabPulse {
            0%, 100% { box-shadow: 0 4px 15px rgba(var(--accent-rgb), 0.4); }
            50% { box-shadow: 0 4px 25px rgba(var(--accent-rgb), 0.6); }
        }

        /* Neon glow effect (dark mode) */
        [data-theme="dark"][data-neon="true"] .sidebar-nav .nav-link.active { text-shadow: 0 0 8px rgba(var(--accent-rgb), 0.6); }
        [data-theme="dark"][data-neon="true"] .stat-card { box-shadow: 0 0 20px rgba(var(--accent-rgb), 0.2); }
        [data-theme="dark"][data-neon="true"] .settings-fab { box-shadow: 0 0 25px rgba(var(--accent-rgb), 0.5); }
        [data-theme="dark"][data-neon="true"] .btn-accent,
        [data-theme="dark"][data-neon="true"] .btn-guide { box-shadow: 0 0 15px rgba(var(--accent-rgb), 0.3); }
        [data-theme="dark"][data-neon="true"] .card { box-shadow: 0 0 12px rgba(var(--accent-rgb), 0.08); }

        /* Particle bg */
        .particle-bg {
            display: none;
            position: fixed; inset: 0;
            z-index: -1;
            pointer-events: none;
        }
        [data-theme="dark"][data-particles="true"] .particle-bg { display: block; }

        /* Clock widget */
        .clock-widget {
            font-family: var(--font-mono);
            font-size: 0.8rem;
            color: var(--text-secondary);
            display: flex; align-items: center; gap: 0.5rem;
            padding: 0.3rem 0.8rem;
            background: var(--badge-bg);
            border-radius: 50px;
        }

        /* Keyboard shortcut hints */
        .kbd {
            font-family: var(--font-mono);
            font-size: 0.65rem;
            padding: 0.15rem 0.4rem;
            border-radius: 4px;
            background: var(--badge-bg);
            border: 1px solid var(--border-color);
            color: var(--text-muted);
        }

        /* Export All button */
        .btn-export-all {
            background: linear-gradient(135deg, #059669, #10b981);
            color: #fff; border: none;
            border-radius: 50px;
            padding: 0.4rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
        }
        .btn-export-all:hover { filter: brightness(1.1); color: #fff; }

        /* Live badge */
        .live-badge {
            display: inline-flex; align-items: center; gap: 0.3rem;
            font-size: 0.65rem; font-weight: 600;
            color: #10b981; text-transform: uppercase; letter-spacing: 0.5px;
        }
        .live-badge::before {
            content: ''; width: 6px; height: 6px;
            background: #10b981; border-radius: 50%;
            animation: livePulse 1.5s infinite;
        }
        @keyframes livePulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--border-color); border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--text-muted); }

        /* Progress bar accent matching */
        .progress { background: var(--badge-bg); }
    </style>
    @stack('styles')
</head>
<body data-theme="light" data-accent="indigo" data-fontsize="medium" data-density="comfortable" data-glass="false" data-neon="false" data-particles="false" data-animations="true">

    <!-- Particle Background -->
    <canvas class="particle-bg" id="particleCanvas"></canvas>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

    <!-- Settings Overlay -->
    <div class="settings-overlay" id="settingsOverlay" onclick="toggleSettings()"></div>

    <!-- Sidebar -->
    <nav class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            @php $activeProyek = session('proyek_id') ? \App\Models\Proyek::find(session('proyek_id')) : null; @endphp
            <h4><i class="bi {{ $activeProyek ? $activeProyek->icon : 'bi-calculator' }}"></i> {{ $activeProyek ? Str::limit($activeProyek->nama, 16) : 'SPK Multi' }}</h4>
            <small>{{ $activeProyek ? 'Proyek Aktif' : 'Pilih Proyek Dulu' }}</small>
        </div>

        <div class="sidebar-nav">
            <div class="nav-label">Proyek</div>

            <a href="{{ route('proyek.index') }}" class="nav-link {{ request()->routeIs('proyek.*') ? 'active' : '' }}">
                <i class="bi bi-collection"></i> Kelola Proyek
            </a>

            @if($activeProyek)
            <div class="nav-label mt-2">Menu Utama</div>

            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>

            <div class="nav-label mt-2">Data Master</div>

            <a href="{{ route('kriteria.index') }}" class="nav-link {{ request()->routeIs('kriteria.*') ? 'active' : '' }}">
                <i class="bi bi-list-check"></i> Data Kriteria
            </a>

            <a href="{{ route('alternatif.index') }}" class="nav-link {{ request()->routeIs('alternatif.*') ? 'active' : '' }}">
                <i class="bi bi-people"></i> Data Alternatif
            </a>

            <div class="nav-label mt-2">Analisis</div>

            <a href="{{ route('penilaian.index') }}" class="nav-link {{ request()->routeIs('penilaian.*') ? 'active' : '' }}">
                <i class="bi bi-clipboard-data"></i> Penilaian
            </a>

            <a href="{{ route('perhitungan.wp') }}" class="nav-link {{ request()->routeIs('perhitungan.wp') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i> Perhitungan WP
            </a>

            <a href="{{ route('perhitungan.saw') }}" class="nav-link {{ request()->routeIs('perhitungan.saw') ? 'active' : '' }}">
                <i class="bi bi-bar-chart-line"></i> Perhitungan SAW
            </a>

            <a href="{{ route('perhitungan.perbandingan') }}" class="nav-link {{ request()->routeIs('perhitungan.perbandingan') ? 'active' : '' }}">
                <i class="bi bi-arrows-angle-expand"></i> Perbandingan WP &amp; SAW
            </a>

            <div class="nav-label mt-2">Laporan</div>

            <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
                <i class="bi bi-journal-text"></i> Laporan Lengkap
            </a>

            <a href="{{ route('export.allinone') }}" class="nav-link">
                <i class="bi bi-file-earmark-spreadsheet"></i> Export Excel
            </a>
            @else
            <div class="px-3 py-3">
                <div class="alert alert-warning mb-0 small py-2">
                    <i class="bi bi-exclamation-triangle me-1"></i> Pilih proyek terlebih dahulu untuk mengakses menu.
                </div>
            </div>
            @endif
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Top Bar -->
        <div class="top-bar no-print">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-sm btn-outline-secondary sidebar-toggle" onclick="toggleSidebar()">
                    <i class="bi bi-list"></i>
                </button>
                <h5 class="mb-0 fw-bold">@yield('page-title', 'Dashboard')</h5>
            </div>
            <div class="d-flex align-items-center gap-2">
                <div class="clock-widget no-print" id="clockWidget">
                    <i class="bi bi-clock"></i>
                    <span id="liveClock">--:--:--</span>
                </div>
                @yield('top-actions')
                <a href="{{ route('export.allinone') }}" class="btn btn-export-all no-print" title="Export Semua Data ke Excel">
                    <i class="bi bi-file-earmark-spreadsheet me-1"></i> Export All
                </a>
                <button class="btn btn-guide" onclick="showGuide()">
                    <i class="bi bi-question-circle me-1"></i> Panduan
                </button>
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area fade-in">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Settings FAB -->
    <button class="settings-fab no-print" onclick="toggleSettings()" title="Pengaturan Tampilan (Ctrl+,)">
        <i class="bi bi-gear-fill" id="settingsIcon"></i>
    </button>

    <!-- Settings Panel -->
    <div class="settings-panel" id="settingsPanel">
        <div class="settings-header">
            <div>
                <h5><i class="bi bi-palette me-2"></i> Pengaturan</h5>
                <span class="live-badge">Live Preview</span>
            </div>
            <button class="btn btn-sm btn-outline-secondary" onclick="toggleSettings()" style="border-radius: 50%; width: 32px; height: 32px; padding: 0;">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <div class="settings-body">
            <!-- THEME MODE -->
            <div class="settings-section">
                <div class="settings-section-title"><i class="bi bi-moon-stars"></i> Mode Tampilan</div>
                <div class="theme-options">
                    <div class="theme-option" data-theme-mode="light" onclick="setTheme('light')">
                        <i class="bi bi-sun-fill"></i> Light
                    </div>
                    <div class="theme-option" data-theme-mode="dark" onclick="setTheme('dark')">
                        <i class="bi bi-moon-fill"></i> Dark
                    </div>
                    <div class="theme-option" data-theme-mode="auto" onclick="setTheme('auto')">
                        <i class="bi bi-circle-half"></i> Auto
                    </div>
                </div>
            </div>

            <!-- ACCENT COLOR -->
            <div class="settings-section">
                <div class="settings-section-title"><i class="bi bi-palette2"></i> Warna Aksen</div>
                <div class="accent-options">
                    <div class="accent-dot" data-accent="indigo" onclick="setAccent('indigo')" title="Indigo"></div>
                    <div class="accent-dot" data-accent="blue" onclick="setAccent('blue')" title="Blue"></div>
                    <div class="accent-dot" data-accent="emerald" onclick="setAccent('emerald')" title="Emerald"></div>
                    <div class="accent-dot" data-accent="rose" onclick="setAccent('rose')" title="Rose"></div>
                    <div class="accent-dot" data-accent="amber" onclick="setAccent('amber')" title="Amber"></div>
                    <div class="accent-dot" data-accent="violet" onclick="setAccent('violet')" title="Violet"></div>
                    <div class="accent-dot" data-accent="cyan" onclick="setAccent('cyan')" title="Cyan"></div>
                </div>
            </div>

            <!-- FONT SIZE -->
            <div class="settings-section">
                <div class="settings-section-title"><i class="bi bi-type"></i> Ukuran Font</div>
                <div class="size-options">
                    <div class="size-option" data-size="small" onclick="setFontSize('small')"><small>A</small> Kecil</div>
                    <div class="size-option" data-size="medium" onclick="setFontSize('medium')">A Sedang</div>
                    <div class="size-option" data-size="large" onclick="setFontSize('large')"><big>A</big> Besar</div>
                </div>
            </div>

            <!-- LAYOUT DENSITY -->
            <div class="settings-section">
                <div class="settings-section-title"><i class="bi bi-layout-text-window"></i> Kepadatan Layout</div>
                <div class="density-options">
                    <div class="density-option" data-density="compact" onclick="setDensity('compact')">
                        <i class="bi bi-arrows-collapse"></i> Compact
                    </div>
                    <div class="density-option" data-density="comfortable" onclick="setDensity('comfortable')">
                        <i class="bi bi-arrows-expand"></i> Comfortable
                    </div>
                </div>
            </div>

            <!-- DARK MODE EXCLUSIVE -->
            <div class="settings-section" id="darkFeatures">
                <div class="settings-section-title"><i class="bi bi-stars"></i> Fitur Eksklusif Dark Mode</div>

                <div class="setting-toggle" onclick="document.getElementById('toggleGlass').click()">
                    <div class="toggle-info">
                        <i class="bi bi-transparency"></i>
                        <div>
                            <div class="toggle-label">Glassmorphism</div>
                            <div class="toggle-desc">Efek blur transparan pada kartu</div>
                        </div>
                    </div>
                    <label class="custom-switch" onclick="event.stopPropagation()">
                        <input type="checkbox" id="toggleGlass" onchange="toggleGlass()">
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-toggle" onclick="document.getElementById('toggleNeon').click()">
                    <div class="toggle-info">
                        <i class="bi bi-lightbulb"></i>
                        <div>
                            <div class="toggle-label">Neon Glow</div>
                            <div class="toggle-desc">Efek cahaya neon pada elemen aktif</div>
                        </div>
                    </div>
                    <label class="custom-switch" onclick="event.stopPropagation()">
                        <input type="checkbox" id="toggleNeon" onchange="toggleNeon()">
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-toggle" onclick="document.getElementById('toggleParticles').click()">
                    <div class="toggle-info">
                        <i class="bi bi-snow2"></i>
                        <div>
                            <div class="toggle-label">Particle Background</div>
                            <div class="toggle-desc">Animasi partikel mengambang</div>
                        </div>
                    </div>
                    <label class="custom-switch" onclick="event.stopPropagation()">
                        <input type="checkbox" id="toggleParticles" onchange="toggleParticles()">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <!-- GENERAL FEATURES -->
            <div class="settings-section">
                <div class="settings-section-title"><i class="bi bi-sliders"></i> Fitur Umum</div>

                <div class="setting-toggle" onclick="document.getElementById('toggleFocus').click()">
                    <div class="toggle-info">
                        <i class="bi bi-eye"></i>
                        <div>
                            <div class="toggle-label">Focus Mode</div>
                            <div class="toggle-desc">Sembunyikan sidebar <span class="kbd">Ctrl+F1</span></div>
                        </div>
                    </div>
                    <label class="custom-switch" onclick="event.stopPropagation()">
                        <input type="checkbox" id="toggleFocus" onchange="toggleFocus()">
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-toggle" onclick="document.getElementById('toggleAnimations').click()">
                    <div class="toggle-info">
                        <i class="bi bi-play-circle"></i>
                        <div>
                            <div class="toggle-label">Animasi</div>
                            <div class="toggle-desc">Efek transisi dan animasi halaman</div>
                        </div>
                    </div>
                    <label class="custom-switch" onclick="event.stopPropagation()">
                        <input type="checkbox" id="toggleAnimations" onchange="toggleAnimations()" checked>
                        <span class="slider"></span>
                    </label>
                </div>

                <div class="setting-toggle" onclick="document.getElementById('toggleSticky').click()">
                    <div class="toggle-info">
                        <i class="bi bi-pin-angle"></i>
                        <div>
                            <div class="toggle-label">Sticky Top Bar</div>
                            <div class="toggle-desc">Top bar tetap terlihat saat scroll</div>
                        </div>
                    </div>
                    <label class="custom-switch" onclick="event.stopPropagation()">
                        <input type="checkbox" id="toggleSticky" onchange="toggleStickyBar()" checked>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <!-- KEYBOARD SHORTCUTS -->
            <div class="settings-section">
                <div class="settings-section-title"><i class="bi bi-keyboard"></i> Keyboard Shortcuts</div>
                <div class="d-flex flex-column gap-2" style="font-size: 0.8rem;">
                    <div class="d-flex justify-content-between"><span style="color: var(--text-secondary)">Toggle Dark Mode</span> <span><span class="kbd">Ctrl</span>+<span class="kbd">D</span></span></div>
                    <div class="d-flex justify-content-between"><span style="color: var(--text-secondary)">Buka Pengaturan</span> <span><span class="kbd">Ctrl</span>+<span class="kbd">,</span></span></div>
                    <div class="d-flex justify-content-between"><span style="color: var(--text-secondary)">Focus Mode</span> <span><span class="kbd">Ctrl</span>+<span class="kbd">F1</span></span></div>
                    <div class="d-flex justify-content-between"><span style="color: var(--text-secondary)">Quick Export</span> <span><span class="kbd">Ctrl</span>+<span class="kbd">E</span></span></div>
                </div>
            </div>
        </div>

        <div class="settings-footer">
            <button class="btn btn-sm btn-outline-danger w-100" onclick="resetSettings()">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Reset ke Default
            </button>
        </div>
    </div>

    <!-- Guide Modal -->
    <div class="modal fade" id="guideModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header" style="background: linear-gradient(135deg, var(--accent), var(--accent-hover)); color: #fff;">
                    <h5 class="modal-title"><i class="bi bi-book me-2"></i> @yield('guide-title', 'Panduan Penggunaan')</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @yield('guide-content')

                    @hasSection('guide-content')
                    @else
                    <div class="guide-step">
                        <h6><i class="bi bi-1-circle me-1"></i> Dashboard</h6>
                        <p>Melihat ringkasan data: total kriteria, alternatif, penilaian, dan ranking dari kedua metode.</p>
                    </div>
                    <div class="guide-step">
                        <h6><i class="bi bi-2-circle me-1"></i> Data Kriteria</h6>
                        <p>Menambah, edit, hapus kriteria. <strong>Benefit</strong> = semakin tinggi semakin baik. <strong>Cost</strong> = semakin rendah semakin baik.</p>
                    </div>
                    <div class="guide-step">
                        <h6><i class="bi bi-3-circle me-1"></i> Data Alternatif</h6>
                        <p>Menambah, edit, hapus alternatif (kandidat) yang akan dinilai.</p>
                    </div>
                    <div class="guide-step">
                        <h6><i class="bi bi-4-circle me-1"></i> Penilaian</h6>
                        <p>Memberikan nilai untuk setiap alternatif pada setiap kriteria.</p>
                    </div>
                    <div class="guide-step">
                        <h6><i class="bi bi-5-circle me-1"></i> Perhitungan WP & SAW</h6>
                        <p>Melihat detail perhitungan dengan rumus lengkap dan ranking final.</p>
                    </div>
                    <div class="guide-step">
                        <h6><i class="bi bi-6-circle me-1"></i> Perbandingan</h6>
                        <p>Membandingkan hasil kedua metode untuk keputusan yang lebih valid.</p>
                    </div>
                    <div class="guide-step">
                        <h6><i class="bi bi-7-circle me-1"></i> Export All-in-One</h6>
                        <p>Download semua data + perhitungan + perbandingan dalam satu file Excel.</p>
                    </div>
                    <div class="guide-step">
                        <h6><i class="bi bi-8-circle me-1"></i> Pengaturan Tampilan</h6>
                        <p>Klik tombol gear (<i class="bi bi-gear-fill"></i>) di pojok kanan bawah: Dark Mode, warna aksen, ukuran font, glassmorphism, neon glow, particle background, focus mode, dan masih banyak lagi.</p>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // ============================================================
        // SETTINGS ENGINE
        // ============================================================
        const SETTINGS_KEY = 'spk_settings';
        const defaults = {
            theme: 'light', accent: 'indigo', fontSize: 'medium',
            density: 'comfortable', glass: false, neon: false,
            particles: false, animations: true, focus: false, sticky: true,
        };

        function getSettings() {
            try {
                const stored = localStorage.getItem(SETTINGS_KEY);
                return stored ? { ...defaults, ...JSON.parse(stored) } : { ...defaults };
            } catch { return { ...defaults }; }
        }

        function saveSettings(s) { localStorage.setItem(SETTINGS_KEY, JSON.stringify(s)); }

        function applySettings(s) {
            const html = document.documentElement;
            const body = document.body;

            let effectiveTheme = s.theme;
            if (s.theme === 'auto') {
                effectiveTheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            html.setAttribute('data-theme', effectiveTheme);
            body.setAttribute('data-theme', effectiveTheme);
            html.setAttribute('data-accent', s.accent);
            body.setAttribute('data-accent', s.accent);
            body.setAttribute('data-fontsize', s.fontSize);
            body.setAttribute('data-density', s.density);
            body.setAttribute('data-glass', s.glass ? 'true' : 'false');
            html.setAttribute('data-neon', s.neon ? 'true' : 'false');
            html.setAttribute('data-particles', s.particles ? 'true' : 'false');
            body.setAttribute('data-animations', s.animations ? 'true' : 'false');
            body.classList.toggle('focus-mode', s.focus);

            const topBar = document.querySelector('.top-bar');
            if (topBar) topBar.style.position = s.sticky ? 'sticky' : 'relative';

            const darkFeatures = document.getElementById('darkFeatures');
            if (darkFeatures) darkFeatures.style.display = effectiveTheme === 'dark' ? 'block' : 'none';

            if (s.particles && effectiveTheme === 'dark') { initParticles(); } else { stopParticles(); }

            updateUIControls(s);
        }

        function updateUIControls(s) {
            document.querySelectorAll('.theme-option').forEach(el => el.classList.toggle('active', el.dataset.themeMode === s.theme));
            document.querySelectorAll('.accent-dot').forEach(el => el.classList.toggle('active', el.dataset.accent === s.accent));
            document.querySelectorAll('.size-option').forEach(el => el.classList.toggle('active', el.dataset.size === s.fontSize));
            document.querySelectorAll('.density-option').forEach(el => el.classList.toggle('active', el.dataset.density === s.density));

            const m = { toggleGlass: s.glass, toggleNeon: s.neon, toggleParticles: s.particles, toggleAnimations: s.animations, toggleFocus: s.focus, toggleSticky: s.sticky };
            for (const [id, val] of Object.entries(m)) { const el = document.getElementById(id); if (el) el.checked = val; }
        }

        function setTheme(mode)   { const s = getSettings(); s.theme = mode;      saveSettings(s); applySettings(s); }
        function setAccent(c)     { const s = getSettings(); s.accent = c;         saveSettings(s); applySettings(s); }
        function setFontSize(sz)  { const s = getSettings(); s.fontSize = sz;      saveSettings(s); applySettings(s); }
        function setDensity(d)    { const s = getSettings(); s.density = d;        saveSettings(s); applySettings(s); }

        function toggleGlass()      { const s = getSettings(); s.glass = document.getElementById('toggleGlass').checked;           saveSettings(s); applySettings(s); }
        function toggleNeon()       { const s = getSettings(); s.neon = document.getElementById('toggleNeon').checked;             saveSettings(s); applySettings(s); }
        function toggleParticles()  { const s = getSettings(); s.particles = document.getElementById('toggleParticles').checked;   saveSettings(s); applySettings(s); }
        function toggleAnimations() { const s = getSettings(); s.animations = document.getElementById('toggleAnimations').checked; saveSettings(s); applySettings(s); }
        function toggleFocus()      { const s = getSettings(); s.focus = document.getElementById('toggleFocus').checked;           saveSettings(s); applySettings(s); }
        function toggleStickyBar()  { const s = getSettings(); s.sticky = document.getElementById('toggleSticky').checked;         saveSettings(s); applySettings(s); }

        function resetSettings() { localStorage.removeItem(SETTINGS_KEY); applySettings(defaults); saveSettings(defaults); }

        // ============================================================
        // PANELS
        // ============================================================
        function toggleSettings() {
            const panel = document.getElementById('settingsPanel');
            const overlay = document.getElementById('settingsOverlay');
            const icon = document.getElementById('settingsIcon');
            panel.classList.toggle('show');
            overlay.classList.toggle('show');
            if (icon) icon.className = panel.classList.contains('show') ? 'bi bi-x-lg' : 'bi bi-gear-fill';
        }

        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
            document.getElementById('sidebarOverlay').classList.toggle('show');
        }

        function showGuide() { new bootstrap.Modal(document.getElementById('guideModal')).show(); }

        // ============================================================
        // LIVE CLOCK
        // ============================================================
        function updateClock() {
            const now = new Date();
            const el = document.getElementById('liveClock');
            if (el) el.textContent = [now.getHours(), now.getMinutes(), now.getSeconds()].map(v => String(v).padStart(2, '0')).join(':');
        }
        setInterval(updateClock, 1000);
        updateClock();

        // ============================================================
        // PARTICLE SYSTEM
        // ============================================================
        let particleAnimId = null;
        let particles = [];

        function initParticles() {
            const canvas = document.getElementById('particleCanvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            canvas.width = window.innerWidth;
            canvas.height = window.innerHeight;

            if (particles.length === 0) {
                for (let i = 0; i < 60; i++) {
                    particles.push({
                        x: Math.random() * canvas.width, y: Math.random() * canvas.height,
                        size: Math.random() * 2.5 + 0.5,
                        speedX: (Math.random() - 0.5) * 0.5, speedY: (Math.random() - 0.5) * 0.5,
                        opacity: Math.random() * 0.5 + 0.1,
                    });
                }
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                const accentColors = { indigo:'99,102,241', blue:'96,165,250', emerald:'52,211,153', rose:'251,113,133', amber:'251,191,36', violet:'167,139,250', cyan:'34,211,238' };
                const color = accentColors[getSettings().accent] || '99,102,241';

                particles.forEach(p => {
                    p.x += p.speedX; p.y += p.speedY;
                    if (p.x < 0) p.x = canvas.width; if (p.x > canvas.width) p.x = 0;
                    if (p.y < 0) p.y = canvas.height; if (p.y > canvas.height) p.y = 0;
                    ctx.beginPath(); ctx.arc(p.x, p.y, p.size, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(${color}, ${p.opacity})`; ctx.fill();
                });

                for (let i = 0; i < particles.length; i++) {
                    for (let j = i + 1; j < particles.length; j++) {
                        const dx = particles[i].x - particles[j].x;
                        const dy = particles[i].y - particles[j].y;
                        const dist = Math.sqrt(dx * dx + dy * dy);
                        if (dist < 120) {
                            ctx.beginPath();
                            ctx.strokeStyle = `rgba(${color}, ${0.08 * (1 - dist / 120)})`;
                            ctx.lineWidth = 0.5;
                            ctx.moveTo(particles[i].x, particles[i].y);
                            ctx.lineTo(particles[j].x, particles[j].y);
                            ctx.stroke();
                        }
                    }
                }
                particleAnimId = requestAnimationFrame(animate);
            }

            if (particleAnimId) cancelAnimationFrame(particleAnimId);
            animate();
        }

        function stopParticles() {
            if (particleAnimId) { cancelAnimationFrame(particleAnimId); particleAnimId = null; }
            const c = document.getElementById('particleCanvas');
            if (c) c.getContext('2d').clearRect(0, 0, c.width, c.height);
        }

        window.addEventListener('resize', () => {
            const c = document.getElementById('particleCanvas');
            if (c) { c.width = window.innerWidth; c.height = window.innerHeight; }
        });

        // ============================================================
        // KEYBOARD SHORTCUTS
        // ============================================================
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey && e.key === 'd') { e.preventDefault(); const s = getSettings(); setTheme(s.theme === 'dark' ? 'light' : 'dark'); }
            if (e.ctrlKey && e.key === ',') { e.preventDefault(); toggleSettings(); }
            if (e.ctrlKey && e.key === 'F1') { e.preventDefault(); const s = getSettings(); s.focus = !s.focus; saveSettings(s); applySettings(s); }
            if (e.ctrlKey && e.key === 'e') { e.preventDefault(); window.location.href = "{{ route('export.allinone') }}"; }
            if (e.key === 'Escape') { if (document.getElementById('settingsPanel').classList.contains('show')) toggleSettings(); }
        });

        // Auto dark mode
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', () => { const s = getSettings(); if (s.theme === 'auto') applySettings(s); });

        // Auto-close alerts
        document.querySelectorAll('.alert').forEach(a => setTimeout(() => { const b = bootstrap.Alert.getOrCreateInstance(a); if (b) b.close(); }, 5000));

        // INIT
        (function() { applySettings(getSettings()); })();
    </script>

    @stack('scripts')
</body>
</html>
