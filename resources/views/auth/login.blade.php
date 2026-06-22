<x-guest-layout>
    @if (session('status'))
        <div class="session-status">{{ session('status') }}</div>
    @endif

    <h1 class="auth-title">Welcome back</h1>
    <p class="auth-subtitle">Sign in to your account</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">Email address</label>
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
            <label class="form-label" for="password">Password</label>
            <input
                id="password"
                type="password"
                name="password"
                class="form-control @error('password') is-invalid @enderror"
                required
                autocomplete="current-password"
                placeholder="Your password"
            >
            @error('password')
                <span class="invalid-feedback">{{ $message }}</span>
            @enderror
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display:flex;align-items:center;gap:8px;font-size:0.875rem;color:#64748b;cursor:pointer;">
                <input type="checkbox" name="remember" style="accent-color:#4f46e5;">
                Remember me
            </label>
        </div>

        <button type="submit" class="btn btn-primary">Sign in</button>

        @if (Route::has('password.request'))
            <div class="auth-footer" style="margin-top:16px;">
                <a href="{{ route('password.request') }}">Forgot your password?</a>
            </div>
        @endif
    </form>

    <div class="auth-footer">
        Don't have an account? <a href="{{ route('register') }}">Create account</a>
    </div>
</x-guest-layout>
