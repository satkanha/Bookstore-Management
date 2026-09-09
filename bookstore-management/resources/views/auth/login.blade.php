<x-guest-layout>
    <h1 class="h4 mb-3">{{ __('Login') }}</h1>
    <x-validation-errors />
    <form method="POST" action="{{ route('login') }}" data-loading-form>
        @csrf
        <div class="mb-3">
            <label class="form-label" for="email">{{ __('Email') }}</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">{{ __('Password') }}</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password">
        </div>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">
                <input id="remember_me" class="form-check-input" type="checkbox" name="remember">
                <label class="form-check-label" for="remember_me">{{ __('Remember me') }}</label>
            </div>
            <a href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
        </div>
        <button class="btn btn-primary w-100" type="submit" data-loading-button data-loading-text="{{ __('Logging in...') }}">{{ __('Login') }}</button>
        <p class="text-center mt-3 mb-0">{{ __('No account?') }} <a href="{{ route('register') }}">{{ __('Register') }}</a></p>
    </form>
</x-guest-layout>
