<x-guest-layout>
    @if (session('status'))
        <div class="status-msg">{{ session('status') }}</div>
    @endif

    <h2 style="color:#fff;font-size:1.3rem;font-weight:700;margin:0 0 6px">Welcome back</h2>
    <p style="color:#94a3b8;font-size:.85rem;margin:0 0 24px">Sign in to your EvalFlow account</p>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-row">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="you@example.com">
            @error('email')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
            @error('password')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px">
            <label class="checkbox-label">
                <input type="checkbox" name="remember"> Remember me
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link">Forgot password?</a>
            @endif
        </div>

        <button type="submit" class="btn-auth">Sign In</button>

        @if (Route::has('register'))
        <div style="text-align:center;margin-top:20px;color:#64748b;font-size:.85rem">
            Don't have an account? <a href="{{ route('register') }}" class="auth-link">Register here</a>
        </div>
        @endif
    </form>
</x-guest-layout>
