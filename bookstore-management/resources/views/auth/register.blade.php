<x-guest-layout>
    <h1 class="h4 mb-3">{{ __('Create Account') }}</h1>
    <x-validation-errors />
    <form method="POST" action="{{ route('register') }}" data-loading-form>
        @csrf
        <div class="mb-3">
            <label class="form-label" for="name">{{ __('Name') }}</label>
            <input id="name" class="form-control" name="name" value="{{ old('name') }}" required autofocus autocomplete="name">
        </div>
        <div class="mb-3">
            <label class="form-label" for="email">{{ __('Email') }}</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
        </div>
        <div class="mb-3">
            <label class="form-label" for="phone">{{ __('Phone') }}</label>
            <input id="phone" class="form-control" name="phone" value="{{ old('phone') }}" autocomplete="tel">
        </div>
        <div class="mb-3">
            <label class="form-label" for="address">{{ __('Address') }}</label>
            <textarea id="address" class="form-control" name="address" rows="2">{{ old('address') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">{{ __('Password') }}</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
        </div>
        <div class="mb-3">
            <label class="form-label" for="password_confirmation">{{ __('Confirm password') }}</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>
        <button class="btn btn-primary w-100" type="submit" data-loading-button data-loading-text="{{ __('Creating account...') }}">{{ __('Register') }}</button>
        <p class="text-center mt-3 mb-0">{{ __('Already registered?') }} <a href="{{ route('login') }}">{{ __('Login') }}</a></p>
    </form>
</x-guest-layout>
