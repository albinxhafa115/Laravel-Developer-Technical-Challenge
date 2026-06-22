<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue Tracker — Project Management</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        }

        html { font-size: 16px; scroll-behavior: smooth; }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text);
            line-height: 1.6;
        }

        a { text-decoration: none; }

        /* ─── Top nav ─── */
        .nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255,255,255,0.92);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border);
            height: 60px;
            display: flex;
            align-items: center;
        }

        .nav-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .nav-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            font-size: 1rem;
            color: var(--text);
        }

        .nav-brand-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .nav-brand-icon .icon { width: 16px; height: 16px; stroke: #fff; }

        .nav-actions { display: flex; align-items: center; gap: 8px; }

        /* ─── Buttons ─── */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 18px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            font-family: inherit;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
            white-space: nowrap;
            line-height: 1.4;
        }

        .btn .icon { width: 15px; height: 15px; flex-shrink: 0; }

        .btn-ghost {
            background: transparent;
            color: var(--text-muted);
            border-color: transparent;
        }
        .btn-ghost:hover { background: var(--bg); color: var(--text); }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border-color: var(--primary);
        }
        .btn-outline:hover { background: var(--primary-light); }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
        }
        .btn-primary:hover { background: var(--primary-hover); border-color: var(--primary-hover); color: #fff; }

        .btn-lg { padding: 12px 28px; font-size: 1rem; border-radius: 10px; }
        .btn-lg .icon { width: 18px; height: 18px; }

        /* ─── Hero ─── */
        .hero {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 60%, #9333ea 100%);
            position: relative;
            overflow: hidden;
            padding: 96px 24px 100px;
        }

        .hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        .hero-inner {
            max-width: 760px;
            margin: 0 auto;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 20px;
            font-size: 0.8125rem;
            font-weight: 500;
            color: rgba(255,255,255,0.9);
            margin-bottom: 28px;
            backdrop-filter: blur(4px);
        }

        .hero-badge .icon { width: 13px; height: 13px; stroke: rgba(255,255,255,0.8); }

        .hero h1 {
            font-size: clamp(2rem, 5vw, 3.25rem);
            font-weight: 800;
            color: #fff;
            line-height: 1.15;
            letter-spacing: -0.03em;
            margin-bottom: 20px;
        }

        .hero h1 span {
            color: rgba(255,255,255,0.75);
        }

        .hero p {
            font-size: 1.0625rem;
            color: rgba(255,255,255,0.8);
            max-width: 540px;
            margin: 0 auto 36px;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-white {
            background: #fff;
            color: var(--primary);
            border-color: #fff;
        }
        .btn-white:hover { background: #f0f0ff; border-color: #f0f0ff; color: var(--primary-hover); }

        .btn-glass {
            background: rgba(255,255,255,0.12);
            color: #fff;
            border-color: rgba(255,255,255,0.3);
            backdrop-filter: blur(4px);
        }
        .btn-glass:hover { background: rgba(255,255,255,0.2); border-color: rgba(255,255,255,0.4); color: #fff; }

        /* ─── Stats bar ─── */
        .stats-bar {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
        }

        .stats-inner {
            max-width: 1100px;
            margin: 0 auto;
            padding: 0 24px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
        }

        .stat-item {
            padding: 28px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            border-right: 1px solid var(--border);
        }

        .stat-item:last-child { border-right: none; }

        .stat-icon {
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-icon .icon { width: 20px; height: 20px; }

        .stat-icon-indigo { background: var(--primary-light); }
        .stat-icon-indigo .icon { stroke: var(--primary); }
        .stat-icon-green  { background: #dcfce7; }
        .stat-icon-green .icon { stroke: #16a34a; }
        .stat-icon-amber  { background: #fef3c7; }
        .stat-icon-amber .icon { stroke: #d97706; }
        .stat-icon-purple { background: #f3e8ff; }
        .stat-icon-purple .icon { stroke: #9333ea; }

        .stat-value {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.03em;
            line-height: 1;
        }

        .stat-label {
            font-size: 0.8125rem;
            color: var(--text-muted);
            margin-top: 3px;
        }

        /* ─── Features ─── */
        .features {
            padding: 80px 24px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 56px;
        }

        .section-kicker {
            font-size: 0.8125rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--primary);
            margin-bottom: 10px;
        }

        .section-header h2 {
            font-size: clamp(1.5rem, 3vw, 2.125rem);
            font-weight: 800;
            color: var(--text);
            letter-spacing: -0.03em;
            margin-bottom: 12px;
            line-height: 1.2;
        }

        .section-header p {
            font-size: 1rem;
            color: var(--text-muted);
            max-width: 480px;
            margin: 0 auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .feature-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 28px;
            box-shadow: var(--shadow);
            transition: box-shadow 0.2s, transform 0.2s;
        }

        .feature-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-3px);
        }

        .feature-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 18px;
        }

        .feature-icon .icon { width: 22px; height: 22px; }

        .fi-indigo { background: var(--primary-light); }
        .fi-indigo .icon { stroke: var(--primary); }
        .fi-green  { background: #dcfce7; }
        .fi-green .icon { stroke: #16a34a; }
        .fi-purple { background: #f3e8ff; }
        .fi-purple .icon { stroke: #9333ea; }
        .fi-amber  { background: #fef3c7; }
        .fi-amber .icon { stroke: #d97706; }
        .fi-sky    { background: #e0f2fe; }
        .fi-sky .icon { stroke: #0284c7; }
        .fi-rose   { background: #fce7f3; }
        .fi-rose .icon { stroke: #be185d; }

        .feature-card h3 {
            font-size: 1rem;
            font-weight: 700;
            color: var(--text);
            margin-bottom: 8px;
        }

        .feature-card p {
            font-size: 0.875rem;
            color: var(--text-muted);
            line-height: 1.6;
        }

        /* ─── CTA section ─── */
        .cta {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            padding: 72px 24px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -10%;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.05);
            border-radius: 50%;
        }

        .cta::after {
            content: '';
            position: absolute;
            bottom: -30%;
            right: -5%;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
        }

        .cta-inner {
            position: relative;
            z-index: 1;
            max-width: 560px;
            margin: 0 auto;
        }

        .cta h2 {
            font-size: clamp(1.5rem, 3vw, 2rem);
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.03em;
            margin-bottom: 12px;
        }

        .cta p {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
            margin-bottom: 32px;
        }

        .cta-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* ─── Footer ─── */
        .footer {
            background: var(--surface);
            border-top: 1px solid var(--border);
            padding: 24px;
        }

        .footer-inner {
            max-width: 1100px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .footer-brand {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--text);
        }

        .footer-brand .icon { width: 14px; height: 14px; stroke: var(--primary); }

        .footer-copy {
            font-size: 0.8125rem;
            color: var(--text-muted);
        }

        .footer-links {
            display: flex;
            gap: 16px;
        }

        .footer-links a {
            font-size: 0.8125rem;
            color: var(--text-muted);
            transition: color 0.15s;
        }
        .footer-links a:hover { color: var(--primary); }

        /* ─── Responsive ─── */
        @media (max-width: 900px) {
            .features-grid { grid-template-columns: repeat(2, 1fr); }
            .stats-inner   { grid-template-columns: repeat(2, 1fr); }
            .stat-item:nth-child(2) { border-right: none; }
            .stat-item:nth-child(3) { border-top: 1px solid var(--border); }
            .stat-item:nth-child(4) { border-top: 1px solid var(--border); border-right: none; }
        }

        @media (max-width: 600px) {
            .hero { padding: 72px 20px 80px; }
            .features-grid { grid-template-columns: 1fr; }
            .stats-inner { grid-template-columns: repeat(2, 1fr); }
            .footer-inner { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

{{-- Top nav --}}
<nav class="nav">
    <div class="nav-inner">
        <div class="nav-brand">
            <div class="nav-brand-icon">
                <i data-lucide="shield" class="icon"></i>
            </div>
            Issue Tracker
        </div>
        <div class="nav-actions">
            <a href="{{ route('login') }}" class="btn btn-ghost">Login</a>
            <a href="{{ route('register') }}" class="btn btn-primary">Get Started</a>
        </div>
    </div>
</nav>

{{-- Hero --}}
<section class="hero">
    <div class="hero-inner">
        <div class="hero-badge">
            <i data-lucide="zap" class="icon"></i>
            Fast, focused project management
        </div>
        <h1>
            Track issues.<br>
            <span>Ship better software.</span>
        </h1>
        <p>
            A clean, powerful issue tracker for developers and teams. Organize projects,
            prioritize work, and ship with confidence.
        </p>
        <div class="hero-actions">
            <a href="{{ route('register') }}" class="btn btn-white btn-lg">
                <i data-lucide="arrow-right" class="icon"></i>
                Get Started Free
            </a>
            <a href="{{ route('login') }}" class="btn btn-glass btn-lg">
                <i data-lucide="log-in" class="icon"></i>
                Sign In
            </a>
        </div>
    </div>
</section>

{{-- Stats bar --}}
<div class="stats-bar">
    <div class="stats-inner">
        <div class="stat-item">
            <div class="stat-icon stat-icon-indigo">
                <i data-lucide="folder" class="icon"></i>
            </div>
            <div>
                <div class="stat-value">Projects</div>
                <div class="stat-label">Organized workspaces</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon stat-icon-green">
                <i data-lucide="bug" class="icon"></i>
            </div>
            <div>
                <div class="stat-value">Issues</div>
                <div class="stat-label">Track every task</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon stat-icon-amber">
                <i data-lucide="tag" class="icon"></i>
            </div>
            <div>
                <div class="stat-value">Tags</div>
                <div class="stat-label">Smart categorization</div>
            </div>
        </div>
        <div class="stat-item">
            <div class="stat-icon stat-icon-purple">
                <i data-lucide="users" class="icon"></i>
            </div>
            <div>
                <div class="stat-value">Teams</div>
                <div class="stat-label">Assign &amp; collaborate</div>
            </div>
        </div>
    </div>
</div>

{{-- Features --}}
<section class="features">
    <div class="section-header">
        <div class="section-kicker">Everything you need</div>
        <h2>Built for real developer workflows</h2>
        <p>From quick bug reports to complex feature planning — Issue Tracker keeps your team aligned.</p>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon fi-indigo">
                <i data-lucide="folder-open" class="icon"></i>
            </div>
            <h3>Project Workspaces</h3>
            <p>Group related issues under projects with descriptions, start dates, and deadlines. Keep every team focused on what matters.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon fi-green">
                <i data-lucide="bug" class="icon"></i>
            </div>
            <h3>Issue Management</h3>
            <p>Create detailed issues with title, description, priority, status, and due dates. Everything a developer needs at a glance.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon fi-purple">
                <i data-lucide="tag" class="icon"></i>
            </div>
            <h3>Smart Tagging</h3>
            <p>Attach color-coded tags to issues for instant visual categorization. Filter by any combination to find exactly what you need.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon fi-sky">
                <i data-lucide="users" class="icon"></i>
            </div>
            <h3>Team Assignment</h3>
            <p>Assign issues to one or multiple team members. Everyone knows who owns what, reducing confusion and dropped tasks.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon fi-amber">
                <i data-lucide="message-square" class="icon"></i>
            </div>
            <h3>Threaded Comments</h3>
            <p>Discuss issues directly in context with a live comment feed. Load more comments on demand without leaving the page.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon fi-rose">
                <i data-lucide="shield-check" class="icon"></i>
            </div>
            <h3>Access Control</h3>
            <p>Project owners have full control over their workspaces. Policies ensure only authorized users can edit or delete projects.</p>
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="cta">
    <div class="cta-inner">
        <h2>Ready to ship better software?</h2>
        <p>Create your account in seconds and start organizing your projects today.</p>
        <div class="cta-actions">
            <a href="{{ route('register') }}" class="btn btn-white btn-lg">
                <i data-lucide="user-plus" class="icon"></i>
                Create Free Account
            </a>
            <a href="{{ route('login') }}" class="btn btn-glass btn-lg">
                <i data-lucide="log-in" class="icon"></i>
                Sign In
            </a>
        </div>
    </div>
</section>

{{-- Footer --}}
<footer class="footer">
    <div class="footer-inner">
        <div class="footer-brand">
            <i data-lucide="shield" class="icon"></i>
            Issue Tracker
        </div>
        <div class="footer-copy">&copy; {{ date('Y') }} Issue Tracker. Built with Laravel.</div>
        <div class="footer-links">
            <a href="{{ route('login') }}">Login</a>
            <a href="{{ route('register') }}">Register</a>
        </div>
    </div>
</footer>

<script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
<script>lucide.createIcons();</script>
</body>
</html>
