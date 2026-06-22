<x-guest-layout>
    @if (session('status'))
        <div class="session-status">{{ session('status') }}</div>
    @endif

    <h1 class="auth-title">{{ __('Welcome back') }}</h1>
    <p class="auth-subtitle">{{ __('Sign in to your account') }}</p>

    <div style="margin-bottom:24px;">
        <p style="font-size:0.75rem;font-weight:600;text-transform:uppercase;letter-spacing:0.08em;color:#94a3b8;margin-bottom:10px;">{{ __('Demo accounts — click to fill') }}</p>
        <div style="display:flex;flex-direction:column;gap:8px;">
            <button type="button" onclick="fillLogin('alice@example.com','password')"
                style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;cursor:pointer;text-align:left;transition:all 0.15s;width:100%;font-family:inherit;"
                onmouseover="this.style.background='#eef2ff';this.style.borderColor='#a5b4fc'"
                onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0'">
                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#4f46e5,#7c3aed);display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#fff;flex-shrink:0;">AJ</div>
                <div>
                    <div style="font-size:0.875rem;font-weight:600;color:#0f172a;">Alice Johnson</div>
                    <div style="font-size:0.75rem;color:#64748b;">alice@example.com</div>
                </div>
                <div style="margin-left:auto;font-size:0.75rem;color:#94a3b8;display:flex;align-items:center;gap:4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    {{ __('Use') }}
                </div>
            </button>
            <button type="button" onclick="fillLogin('bob@example.com','password')"
                style="display:flex;align-items:center;gap:12px;padding:10px 14px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:10px;cursor:pointer;text-align:left;transition:all 0.15s;width:100%;font-family:inherit;"
                onmouseover="this.style.background='#eef2ff';this.style.borderColor='#a5b4fc'"
                onmouseout="this.style.background='#f8fafc';this.style.borderColor='#e2e8f0'">
                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#0ea5e9,#6366f1);display:flex;align-items:center;justify-content:center;font-size:0.75rem;font-weight:700;color:#fff;flex-shrink:0;">BS</div>
                <div>
                    <div style="font-size:0.875rem;font-weight:600;color:#0f172a;">Bob Smith</div>
                    <div style="font-size:0.75rem;color:#64748b;">bob@example.com</div>
                </div>
                <div style="margin-left:auto;font-size:0.75rem;color:#94a3b8;display:flex;align-items:center;gap:4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 18l6-6-6-6"/></svg>
                    {{ __('Use') }}
                </div>
            </button>
        </div>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">{{ __('Email address') }}</label>
            <input
                id="email"
                type="email"
                name="email"
                class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email') }}"
                required
                autofocus
                autocomplete="username"
                placeholder="you@example.com"
            >
            @error('email')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">{{ __('Password') }}</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="current-password"
                placeholder="••••••••"
            >
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display:flex;align-items:center;gap:8px;font-size:0.875rem;color:#64748b;cursor:pointer;">
                <input type="checkbox" name="remember" style="accent-color:#4f46e5;">
                {{ __('Remember me') }}
            </label>
        </div>

        <button type="submit" class="btn btn-primary">{{ __('Sign in') }}</button>

        @if (Route::has('password.request'))
            <div class="auth-footer" style="margin-top:16px;">
                <a href="{{ route('password.request') }}">{{ __('Forgot your password?') }}</a>
            </div>
        @endif
    </form>

    <div class="auth-footer">
        {{ __("Don't have an account?") }} <a href="{{ route('register') }}">{{ __('Create account') }}</a>
    </div>

    <script>
        function fillLogin(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
            document.getElementById('email').focus();
        }
    </script>
</x-guest-layout>
