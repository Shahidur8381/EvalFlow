<x-guest-layout>
    <h2 style="color:#fff;font-size:1.3rem;font-weight:700;margin:0 0 6px">Create Account</h2>
    <p style="color:#94a3b8;font-size:.85rem;margin:0 0 24px">Join EvalFlow to get started</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="form-row">
            <label for="name">Full Name</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus placeholder="John Doe">
            @error('name')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
            <label for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required placeholder="you@example.com">
            @error('email')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
            <label for="password">Password</label>
            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 characters">
            @error('password')<div class="error-msg">{{ $message }}</div>@enderror
        </div>

        <div class="form-row">
            <label for="password_confirmation">Confirm Password</label>
            <input id="password_confirmation" type="password" name="password_confirmation" required placeholder="Repeat password">
        </div>

        <button type="submit" class="btn-auth">Create Account</button>

        <div style="text-align:center;margin-top:20px;color:#64748b;font-size:.85rem">
            Already have an account? <a href="{{ route('login') }}" class="auth-link">Sign in</a>
        </div>
    </form>
</x-guest-layout>
