<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Issue Tracker') }}</title>
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
        }

        html, body {
            height: 100%;
            font-family: 'Inter', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
        }

        /* Left panel */
        .guest-left {
            width: 45%;
            min-height: 100vh;
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px;
            position: relative;
            overflow: hidden;
        }

        .guest-left::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255,255,255,0.06);
            border-radius: 50%;
            top: -100px;
            right: -100px;
        }

        .guest-left::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255,255,255,0.04);
            border-radius: 50%;
            bottom: -80px;
            left: -80px;
        }

        .guest-left-content {
            position: relative;
            z-index: 1;
            text-align: center;
            max-width: 340px;
        }

        .guest-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            justify-content: center;
            margin-bottom: 24px;
        }

        .guest-logo-icon {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.2);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            backdrop-filter: blur(8px);
        }

        .guest-logo-icon svg {
            width: 26px;
            height: 26px;
            stroke: #fff;
            stroke-width: 2;
            fill: none;
        }

        .guest-logo-text {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            letter-spacing: -0.02em;
        }

        .guest-tagline {
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .guest-features {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 14px;
            text-align: left;
        }

        .guest-features li {
            display: flex;
            align-items: center;
            gap: 12px;
            color: rgba(255,255,255,0.85);
            font-size: 0.875rem;
        }

        .feature-dot {
            width: 8px;
            height: 8px;
            background: rgba(255,255,255,0.5);
            border-radius: 50%;
            flex-shrink: 0;
        }

        /* Right panel */
        .guest-right {
            flex: 1;
            min-height: 100vh;
            background: var(--surface);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px;
        }

        .guest-form-wrap {
            width: 100%;
            max-width: 380px;
        }

        /* Form styles for auth pages */
        .auth-title {
            font-size: 1.625rem;
            font-weight: 700;
            color: var(--text);
            letter-spacing: -0.02em;
            margin-bottom: 4px;
        }

        .auth-subtitle {
            color: var(--text-muted);
            font-size: 0.9rem;
            margin-bottom: 32px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        .form-label {
            display: block;
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 6px;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--border);
            border-radius: 8px;
            font-size: 0.875rem;
            font-family: inherit;
            color: var(--text);
            background: var(--surface);
            transition: border-color 0.15s, box-shadow 0.15s;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(79,70,229,0.12);
        }

        .form-control.is-invalid {
            border-color: #dc2626;
        }

        .invalid-feedback {
            font-size: 0.8rem;
            color: #dc2626;
            margin-top: 4px;
            display: block;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.875rem;
            font-weight: 500;
            font-family: inherit;
            border: 1px solid transparent;
            cursor: pointer;
            transition: all 0.15s;
            text-decoration: none;
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
            border-color: var(--primary);
            width: 100%;
            padding: 11px;
            font-size: 0.9375rem;
            font-weight: 600;
        }

        .btn-primary:hover {
            background: var(--primary-hover);
            border-color: var(--primary-hover);
            color: #fff;
        }

        .auth-footer {
            text-align: center;
            margin-top: 24px;
            font-size: 0.875rem;
            color: var(--text-muted);
        }

        .auth-footer a {
            color: var(--primary);
            font-weight: 500;
            text-decoration: none;
        }

        .auth-footer a:hover { color: var(--primary-hover); text-decoration: underline; }

        .session-status {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            border-radius: 8px;
            padding: 10px 14px;
            color: #15803d;
            font-size: 0.875rem;
            margin-bottom: 20px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            body { flex-direction: column; }
            .guest-left {
                width: 100%;
                min-height: auto;
                padding: 32px 24px;
            }
            .guest-features { display: none; }
            .guest-right { padding: 32px 24px; }
        }
    </style>
</head>
<body>
    <div class="guest-left">
        <div class="guest-left-content">
            <div class="guest-logo">
                <div class="guest-logo-icon">
                    <svg viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    </svg>
                </div>
                <span class="guest-logo-text">Issue Tracker</span>
            </div>
            <p class="guest-tagline">Manage projects, track issues, ship faster.</p>
            <ul class="guest-features">
                <li><span class="feature-dot"></span>Organize work across multiple projects</li>
                <li><span class="feature-dot"></span>Track bugs and feature requests</li>
                <li><span class="feature-dot"></span>Assign team members and set priorities</li>
                <li><span class="feature-dot"></span>Comment and collaborate in real time</li>
            </ul>
        </div>
    </div>

    <div class="guest-right">
        <div class="guest-form-wrap">
            {{ $slot }}
        </div>
    </div>
</body>
</html>
