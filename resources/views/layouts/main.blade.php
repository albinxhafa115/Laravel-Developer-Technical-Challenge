<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Issue Tracker')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --primary: #4f46e5;
            --primary-hover: #4338ca;
            --primary-light: #eef2ff;
            --bg: #f8fafc;
            --surface: #ffffff;
            --border: #e2e8f0;
            --text: #0f172a;
            --text-muted: #64748b;
            --radius: 10px;
            --shadow: 0 1px 3px rgba(0,0,0,.06), 0 1px 2px rgba(0,0,0,.04);
            --shadow-md: 0 4px 12px rgba(0,0,0,.08);
            --shadow-lg: 0 8px 24px rgba(0,0,0,.12);
            --sidebar-width: 250px;
        }

        html { font-size: 16px; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
            min-height: 100vh;
        }

        a { color: var(--primary); text-decoration: none; }
        a:hover { color: var(--primary-hover); }

        /* ─── App shell ─── */
        .app { display: flex; min-height: 100vh; }

        /* ─── Sidebar ─── */
        .sidebar {
            width: var(--sidebar-width);
            flex-shrink: 0;
            background: var(--surface);
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            z-index: 200;
            transition: transform 0.25s ease;
        }

        .sidebar-header {
            padding: 20px 20px 16px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1.05rem;
            letter-spacing: -0.02em;
            color: var(--text);
            text-decoration: none;
        }

        .sidebar-brand:hover { color: var(--text); }

        .sidebar-brand-icon {
            width: 34px; height: 34px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }

        .sidebar-brand-icon .icon { width: 18px; height: 18px; stroke: #fff; }

        .sidebar-brand-text { display: flex; flex-direction: column; line-height: 1.2; }
        .sidebar-brand-text span:first-child { font-size: 0.9375rem; font-weight: 700; color: var(--text); }
        .sidebar-brand-text span:last-child { font-size: 0.6875rem; font-weight: 500; color: var(--text-muted); letter-spacing: 0.02em; }

        .sidebar-nav { flex: 1; padding: 16px 12px; overflow-y: auto; }

        .sidebar-nav-section { margin-bottom: 20px; }

        .sidebar-nav-label {
            font-size: 0.6875rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: var(--text-muted);
            padding: 0 8px; margin-bottom: 6px;
        }

        .sidebar-link {
            display: flex; align-items: center; gap: 10px;
            padding: 8px 10px; border-radius: 8px;
            font-size: 0.875rem; font-weight: 500;
            color: var(--text-muted);
            transition: all 0.15s; margin-bottom: 2px;
        }

        .sidebar-link:hover { color: var(--text); background: var(--bg); }
        .sidebar-link.active { color: var(--primary); background: var(--primary-light); }
        .sidebar-link .icon { width: 16px; height: 16px; stroke-width: 2; flex-shrink: 0; }
        .sidebar-link.active .icon { stroke: var(--primary); }

        .sidebar-profile {
            padding: 14px 16px;
            border-top: 1px solid var(--border);
            background: #fafbff;
        }

        .profile-card { display: flex; align-items: center; gap: 10px; }

        .avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.75rem; font-weight: 700; color: #fff;
            flex-shrink: 0; letter-spacing: 0.03em;
        }

        .profile-info { flex: 1; min-width: 0; }
        .profile-name { font-size: 0.8125rem; font-weight: 600; color: var(--text); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .profile-email { font-size: 0.6875rem; color: var(--text-muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .profile-logout {
            display: flex; align-items: center; justify-content: center;
            width: 28px; height: 28px; border-radius: 7px;
            background: transparent; border: 1px solid var(--border);
            color: var(--text-muted); cursor: pointer; transition: all 0.15s; flex-shrink: 0;
        }

        .profile-logout:hover { background: #fef2f2; border-color: #fca5a5; color: #dc2626; }
        .profile-logout .icon { width: 14px; height: 14px; }

        /* ─── Lang switcher ─── */
        .lang-switcher {
            display: flex; align-items: center; gap: 2px;
            background: var(--bg); border: 1px solid var(--border);
            border-radius: 8px; padding: 2px;
        }

        .lang-btn {
            padding: 4px 10px; border-radius: 6px; border: none;
            background: transparent; font-size: 0.75rem; font-weight: 600;
            font-family: inherit; color: var(--text-muted); cursor: pointer;
            transition: all 0.15s; letter-spacing: 0.03em;
        }

        .lang-btn:hover { background: var(--surface); color: var(--text); }

        .lang-btn.lang-active {
            background: var(--surface); color: var(--primary);
            box-shadow: var(--shadow);
        }

        /* ─── Sidebar overlay (mobile) ─── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.35); z-index: 190;
            backdrop-filter: blur(2px);
        }

        .sidebar-overlay.active { display: block; }

        /* ─── Main wrapper ─── */
        .main-wrapper {
            flex: 1; margin-left: var(--sidebar-width);
            display: flex; flex-direction: column;
            min-height: 100vh; min-width: 0;
        }

        /* ─── Topbar ─── */
        .topbar {
            position: sticky; top: 0; z-index: 100;
            height: 56px;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            display: flex; align-items: center;
            padding: 0 24px; gap: 16px;
        }

        .topbar-toggle {
            display: none; align-items: center; justify-content: center;
            width: 34px; height: 34px; border-radius: 8px;
            border: 1px solid var(--border); background: var(--surface);
            cursor: pointer; color: var(--text-muted); flex-shrink: 0; transition: all 0.15s;
        }

        .topbar-toggle:hover { background: var(--bg); color: var(--text); }
        .topbar-toggle .icon { width: 16px; height: 16px; }

        .topbar-breadcrumb {
            flex: 1; font-size: 0.875rem; color: var(--text-muted);
            display: flex; align-items: center; gap: 6px;
        }

        .topbar-breadcrumb .icon { width: 14px; height: 14px; }

        .topbar-end { display: flex; align-items: center; gap: 10px; margin-left: auto; }

        .topbar-user { display: flex; align-items: center; gap: 8px; }
        .topbar-user-name { font-size: 0.875rem; font-weight: 500; color: var(--text); }

        .topbar-avatar {
            width: 30px; height: 30px; border-radius: 50%;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.6875rem; font-weight: 700; color: #fff; letter-spacing: 0.03em;
        }

        /* ─── Page content ─── */
        .page-content { flex: 1; padding: 32px 28px 40px; }

        /* ─── Footer ─── */
        .footer {
            border-top: 1px solid var(--border); background: var(--surface);
            padding: 20px 28px;
            display: flex; align-items: center; justify-content: space-between;
            flex-wrap: wrap; gap: 12px;
        }

        .footer-brand { display: flex; align-items: center; gap: 8px; font-size: 0.875rem; font-weight: 600; color: var(--text); }
        .footer-brand .icon { width: 15px; height: 15px; stroke: var(--primary); }
        .footer-copy { font-size: 0.8125rem; color: var(--text-muted); }
        .footer-links { display: flex; gap: 16px; }
        .footer-links a { font-size: 0.8125rem; color: var(--text-muted); transition: color 0.15s; }
        .footer-links a:hover { color: var(--primary); }

        /* ─── Container ─── */
        .container { max-width: 1100px; margin: 0 auto; padding: 32px 24px 64px; }

        /* ─── Page Header ─── */
        .page-header {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 28px; flex-wrap: wrap; gap: 12px;
        }

        .page-header h1 {
            font-size: 1.5rem; font-weight: 700; letter-spacing: -0.02em;
            color: var(--text); display: flex; align-items: center; gap: 10px;
        }

        .page-header h1 .icon { width: 22px; height: 22px; stroke: var(--primary); }

        /* ─── Buttons ─── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 8px 16px; border-radius: 8px;
            font-size: 0.875rem; font-weight: 500; font-family: inherit;
            border: 1px solid transparent; cursor: pointer; text-decoration: none;
            transition: all 0.15s; white-space: nowrap; line-height: 1.4;
        }

        .btn .icon { width: 15px; height: 15px; flex-shrink: 0; }

        .btn-primary { background: var(--primary); color: #fff; border-color: var(--primary); }
        .btn-primary:hover { background: var(--primary-hover); border-color: var(--primary-hover); color: #fff; }

        .btn-secondary { background: var(--surface); color: var(--text); border-color: var(--border); }
        .btn-secondary:hover { background: var(--bg); color: var(--text); }

        .btn-danger { background: #fff; color: #dc2626; border-color: #fecaca; }
        .btn-danger:hover { background: #fef2f2; border-color: #fca5a5; }

        .btn-ghost { background: transparent; color: var(--text-muted); border-color: transparent; }
        .btn-ghost:hover { background: var(--bg); color: var(--text); }

        .btn-sm { padding: 5px 10px; font-size: 0.8125rem; }
        .btn-sm .icon { width: 13px; height: 13px; }

        /* ─── Cards ─── */
        .card {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; box-shadow: var(--shadow);
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .card:hover { box-shadow: var(--shadow-md); }

        .card-header {
            padding: 16px 20px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }

        .card-header h5, .card-header h6 {
            font-size: 0.9375rem; font-weight: 600; color: var(--text);
            display: flex; align-items: center; gap: 7px;
        }

        .card-header .icon { width: 16px; height: 16px; stroke: var(--text-muted); }
        .card-body { padding: 20px; }

        .card-footer {
            padding: 14px 20px; border-top: 1px solid var(--border);
            background: #fafafa; border-radius: 0 0 12px 12px;
        }

        /* ─── Badges ─── */
        .badge {
            display: inline-flex; align-items: center; gap: 4px;
            padding: 3px 10px; border-radius: 20px;
            font-size: 0.75rem; font-weight: 600; letter-spacing: 0.01em;
        }

        .badge-open        { background: #dcfce7; color: #15803d; }
        .badge-in_progress { background: #dbeafe; color: #1d4ed8; }
        .badge-closed      { background: #f1f5f9; color: #475569; }
        .badge-high        { background: #fee2e2; color: #dc2626; }
        .badge-medium      { background: #fef3c7; color: #d97706; }
        .badge-low         { background: #dcfce7; color: #16a34a; }

        /* ─── Tag Pills ─── */
        .tag-pill {
            display: inline-flex; align-items: center; gap: 5px;
            font-size: 0.75rem; font-weight: 500;
            padding: 3px 10px; border-radius: 20px; color: #fff; cursor: default;
        }

        .tag-pill .tag-x { cursor: pointer; opacity: 0.7; display: inline-flex; align-items: center; }
        .tag-pill .tag-x:hover { opacity: 1; }
        .tag-pill .icon { width: 12px; height: 12px; }

        /* ─── Forms ─── */
        .form-group { margin-bottom: 18px; }

        .form-label { display: block; font-size: 0.875rem; font-weight: 500; color: var(--text); margin-bottom: 6px; }
        .form-label .req { color: #dc2626; margin-left: 2px; }

        .form-control {
            display: block; width: 100%; padding: 9px 12px;
            border: 1px solid var(--border); border-radius: 8px;
            font-size: 0.875rem; font-family: inherit; color: var(--text);
            background: var(--surface); transition: border-color 0.15s, box-shadow 0.15s;
            appearance: none; -webkit-appearance: none;
        }

        .form-control:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 3px rgba(79,70,229,0.12); }
        .form-control.is-invalid { border-color: #dc2626; }
        .form-control.is-invalid:focus { box-shadow: 0 0 0 3px rgba(220,38,38,0.12); }

        textarea.form-control { resize: vertical; min-height: 90px; }

        select.form-control {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 12px center; padding-right: 36px;
        }

        .invalid-feedback { font-size: 0.8125rem; color: #dc2626; margin-top: 4px; display: block; }

        /* ─── Grid helpers ─── */
        .grid { display: grid; gap: 16px; }
        .grid-2 { grid-template-columns: 1fr 1fr; }
        .grid-3 { grid-template-columns: 1fr 1fr 1fr; }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }

        .flex { display: flex; }
        .flex-wrap { flex-wrap: wrap; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 8px; } .gap-3 { gap: 12px; } .gap-4 { gap: 16px; }
        .flex-1 { flex: 1; }

        .mt-1 { margin-top: 4px; }  .mt-2 { margin-top: 8px; }
        .mt-3 { margin-top: 12px; } .mt-4 { margin-top: 16px; }
        .mb-1 { margin-bottom: 4px; }  .mb-2 { margin-bottom: 8px; }
        .mb-3 { margin-bottom: 12px; } .mb-4 { margin-bottom: 16px; }
        .me-1 { margin-right: 4px; }

        .text-muted { color: var(--text-muted); }
        .text-sm { font-size: 0.875rem; } .text-xs { font-size: 0.75rem; }
        .font-semibold { font-weight: 600; } .font-medium { font-weight: 500; }

        /* ─── Alert ─── */
        .alert-success {
            background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: var(--radius);
            padding: 12px 16px; color: #15803d; font-size: 0.875rem; margin-bottom: 20px;
            display: flex; align-items: center; gap: 8px;
        }

        /* ─── Table ─── */
        .table-wrap {
            background: var(--surface); border: 1px solid var(--border);
            border-radius: 12px; box-shadow: var(--shadow); overflow: hidden;
        }

        .data-table { width: 100%; border-collapse: collapse; }

        .data-table thead th {
            padding: 11px 16px; font-size: 0.75rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.05em; color: var(--text-muted);
            background: #f8fafc; border-bottom: 1px solid var(--border);
            white-space: nowrap; text-align: left;
        }

        .data-table tbody td {
            padding: 13px 16px; border-bottom: 1px solid var(--border);
            font-size: 0.875rem; vertical-align: middle;
        }

        .data-table tbody tr:last-child td { border-bottom: none; }
        .data-table tbody tr:hover { background: #fafbfc; }

        /* ─── Empty State ─── */
        .empty-state { text-align: center; padding: 64px 24px; }

        .empty-state .icon { width: 48px; height: 48px; stroke: #cbd5e1; margin: 0 auto 16px; display: block; }
        .empty-state h3 { font-size: 1rem; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .empty-state p { color: var(--text-muted); font-size: 0.875rem; margin-bottom: 20px; }

        /* ─── Filters row ─── */
        .filter-row { display: flex; gap: 10px; align-items: center; flex-wrap: wrap; margin-bottom: 20px; }
        .filter-row .form-control { min-width: 0; }

        .search-wrapper { position: relative; flex: 1; min-width: 180px; }
        .search-wrapper .icon { position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 15px; height: 15px; stroke: var(--text-muted); }
        .search-wrapper .form-control { padding-left: 34px; }

        /* ─── Two-col layout ─── */
        .layout-2col { display: grid; grid-template-columns: 1fr 320px; gap: 24px; align-items: start; }

        /* ─── Modal Overlay ─── */
        .modal-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(15,23,42,0.45); z-index: 500;
            align-items: center; justify-content: center; backdrop-filter: blur(2px);
        }

        .modal-overlay.active { display: flex; }

        .modal-box {
            background: var(--surface); border-radius: 14px; border: 1px solid var(--border);
            box-shadow: var(--shadow-lg); width: 100%; max-width: 440px; margin: 16px; overflow: hidden;
        }

        .modal-header {
            padding: 18px 20px; border-bottom: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }

        .modal-header h5 { font-size: 1rem; font-weight: 600; color: var(--text); }
        .modal-body { padding: 20px; }

        .modal-footer {
            padding: 14px 20px; border-top: 1px solid var(--border);
            display: flex; gap: 8px; justify-content: flex-end;
        }

        .modal-close {
            background: transparent; border: none; cursor: pointer; color: var(--text-muted);
            display: flex; align-items: center; padding: 4px; border-radius: 6px; transition: background 0.15s;
        }

        .modal-close:hover { background: var(--bg); }
        .modal-close .icon { width: 18px; height: 18px; }

        /* ─── Date pill ─── */
        .date-pill {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 10px; background: var(--bg); border: 1px solid var(--border);
            border-radius: 20px; font-size: 0.75rem; color: var(--text-muted);
        }

        .date-pill .icon { width: 12px; height: 12px; }

        /* ─── Comments ─── */
        .comment-item { padding: 14px 0; border-bottom: 1px solid var(--border); }
        .comment-item:last-child { border-bottom: none; }
        .comment-author { font-size: 0.875rem; font-weight: 600; color: var(--text); }
        .comment-time { font-size: 0.75rem; color: var(--text-muted); margin-left: 8px; }
        .comment-body { font-size: 0.875rem; color: var(--text); margin-top: 4px; }

        .d-none { display: none !important; }

        /* ─── Pagination ─── */
        .pagination { display: flex; gap: 4px; margin-top: 20px; flex-wrap: wrap; }

        /* ─── Project card grid ─── */
        .project-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }

        .project-card {
            background: var(--surface); border: 1px solid var(--border); border-radius: 12px;
            box-shadow: var(--shadow); display: flex; flex-direction: column;
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .project-card:hover { box-shadow: var(--shadow-md); transform: translateY(-2px); }
        .project-card-body { padding: 20px; flex: 1; }

        .project-card-title { font-size: 1rem; font-weight: 600; color: var(--text); margin-bottom: 6px; }
        .project-card-title a { color: inherit; }
        .project-card-title a:hover { color: var(--primary); }

        .project-card-desc { font-size: 0.8125rem; color: var(--text-muted); margin-bottom: 12px; line-height: 1.5; }

        .project-card-footer {
            padding: 12px 20px; border-top: 1px solid var(--border);
            display: flex; align-items: center; justify-content: space-between;
        }

        .issues-count { font-size: 0.8125rem; font-weight: 600; color: var(--text-muted); }

        .divider { border: none; border-top: 1px solid var(--border); margin: 20px 0; }
        .form-page { max-width: 680px; margin: 0 auto; }

        .section-title {
            font-size: 0.8125rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.05em; color: var(--text-muted); margin-bottom: 12px;
        }

        input[type="color"].form-control { padding: 4px 6px; height: 40px; cursor: pointer; }

        .issue-link { font-weight: 600; color: var(--text); }
        .issue-link:hover { color: var(--primary); }

        .user-pill {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 10px; background: var(--bg); border: 1px solid var(--border);
            border-radius: 20px; font-size: 0.8125rem; font-weight: 500; color: var(--text);
        }

        .user-pill .icon { width: 13px; height: 13px; stroke: var(--text-muted); }
        .user-pill .user-detach { cursor: pointer; opacity: 0.5; display: inline-flex; }
        .user-pill .user-detach:hover { opacity: 1; }
        .user-pill .user-detach .icon { width: 12px; height: 12px; }

        /* ─── Responsive ─── */
        @media (max-width: 900px) {
            .project-grid { grid-template-columns: repeat(2, 1fr); }
            .layout-2col { grid-template-columns: 1fr; }
            .grid-3 { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 768px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.open { transform: translateX(0); box-shadow: var(--shadow-lg); }
            .main-wrapper { margin-left: 0; }
            .topbar-toggle { display: flex; }
            .topbar-user-name { display: none; }
            .footer { flex-direction: column; align-items: flex-start; gap: 8px; }
        }

        @media (max-width: 600px) {
            .project-grid { grid-template-columns: 1fr; }
            .grid-2 { grid-template-columns: 1fr; }
            .grid-3 { grid-template-columns: 1fr; }
            .filter-row { flex-direction: column; }
            .page-header { flex-direction: column; align-items: flex-start; }
            .page-content { padding: 20px 16px 32px; }
        }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div class="app">
    {{-- ─── Sidebar ─── --}}
    <aside class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <a href="{{ route('projects.index') }}" class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i data-lucide="shield" class="icon"></i>
                </div>
                <div class="sidebar-brand-text">
                    <span>{{ __('Issue Tracker') }}</span>
                    <span>{{ __('Project Management') }}</span>
                </div>
            </a>
        </div>

        <nav class="sidebar-nav">
            <div class="sidebar-nav-section">
                <div class="sidebar-nav-label">{{ __('Navigation') }}</div>
                <a href="{{ route('projects.index') }}"
                   class="sidebar-link {{ request()->routeIs('projects.*') ? 'active' : '' }}">
                    <i data-lucide="folder" class="icon"></i>
                    {{ __('Projects') }}
                </a>
                <a href="{{ route('issues.index') }}"
                   class="sidebar-link {{ request()->routeIs('issues.*') ? 'active' : '' }}">
                    <i data-lucide="bug" class="icon"></i>
                    {{ __('Issues') }}
                </a>
                <a href="{{ route('tags.index') }}"
                   class="sidebar-link {{ request()->routeIs('tags.*') ? 'active' : '' }}">
                    <i data-lucide="tag" class="icon"></i>
                    {{ __('Tags') }}
                </a>
            </div>

            <div class="sidebar-nav-section">
                <div class="sidebar-nav-label">{{ __('Quick Actions') }}</div>
                <a href="{{ route('projects.create') }}"
                   class="sidebar-link {{ request()->routeIs('projects.create') ? 'active' : '' }}">
                    <i data-lucide="folder-plus" class="icon"></i>
                    {{ __('New Project') }}
                </a>
                <a href="{{ route('issues.create') }}"
                   class="sidebar-link {{ request()->routeIs('issues.create') ? 'active' : '' }}">
                    <i data-lucide="plus-circle" class="icon"></i>
                    {{ __('New Issue') }}
                </a>
            </div>
        </nav>

        <div class="sidebar-profile">
            @auth
                <div class="profile-card">
                    <div class="avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1)) }}
                    </div>
                    <div class="profile-info">
                        <div class="profile-name">{{ auth()->user()->name }}</div>
                        <div class="profile-email">{{ auth()->user()->email }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="profile-logout" title="{{ __('Logout') }}">
                            <i data-lucide="log-out" class="icon"></i>
                        </button>
                    </form>
                </div>
            @else
                <div style="display:flex;gap:8px;">
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm" style="flex:1;justify-content:center;">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm" style="flex:1;justify-content:center;">{{ __('Register') }}</a>
                </div>
            @endauth
        </div>
    </aside>

    {{-- ─── Main wrapper ─── --}}
    <div class="main-wrapper">

        <header class="topbar">
            <button class="topbar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                <i data-lucide="menu" class="icon"></i>
            </button>

            <div class="topbar-breadcrumb">
                @if(request()->routeIs('projects.*'))
                    <i data-lucide="folder" class="icon"></i> {{ __('Projects') }}
                @elseif(request()->routeIs('issues.*'))
                    <i data-lucide="bug" class="icon"></i> {{ __('Issues') }}
                @elseif(request()->routeIs('tags.*'))
                    <i data-lucide="tag" class="icon"></i> {{ __('Tags') }}
                @else
                    <i data-lucide="layout-dashboard" class="icon"></i> {{ __('Dashboard') }}
                @endif
            </div>

            <div class="topbar-end">
                {{-- Language switcher --}}
                <div class="lang-switcher">
                    @foreach(['en' => 'EN', 'sq' => 'SQ'] as $locale => $label)
                        <form method="POST" action="{{ route('language.switch', $locale) }}" style="display:inline">
                            @csrf
                            <button type="submit" class="lang-btn {{ app()->getLocale() === $locale ? 'lang-active' : '' }}">
                                {{ $label }}
                            </button>
                        </form>
                    @endforeach
                </div>

                @auth
                    <div class="topbar-user">
                        <span class="topbar-user-name">{{ auth()->user()->name }}</span>
                        <div class="topbar-avatar">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1)) }}
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="btn btn-ghost btn-sm">{{ __('Login') }}</a>
                    <a href="{{ route('register') }}" class="btn btn-primary btn-sm">{{ __('Register') }}</a>
                @endauth
            </div>
        </header>

        <main class="page-content">
            @if(session('success'))
                <div class="alert-success">
                    <i data-lucide="check-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="footer">
            <div class="footer-brand">
                <i data-lucide="shield" class="icon"></i>
                {{ __('Issue Tracker') }}
            </div>
            <div class="footer-copy">
                &copy; {{ date('Y') }} Issue Tracker. {{ __('Built with Laravel.') }}
            </div>
            <div class="footer-links">
                <a href="{{ route('projects.index') }}">{{ __('Projects') }}</a>
                <a href="{{ route('issues.index') }}">{{ __('Issues') }}</a>
                <a href="{{ route('tags.index') }}">{{ __('Tags') }}</a>
            </div>
        </footer>

    </div>
</div>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>
    lucide.createIcons();

    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const toggle  = document.getElementById('sidebarToggle');

    function openSidebar() {
        sidebar.classList.add('open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeSidebar() {
        sidebar.classList.remove('open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    toggle.addEventListener('click', () => {
        sidebar.classList.contains('open') ? closeSidebar() : openSidebar();
    });

    overlay.addEventListener('click', closeSidebar);
</script>
@yield('scripts')
</body>
</html>
