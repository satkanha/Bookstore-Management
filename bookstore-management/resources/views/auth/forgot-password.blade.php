<x-guest-layout>
    <h1 class="h4 mb-3">{{ __('Forgot Password') }}</h1>
    <p class="text-muted">{{ __('Enter your email and Laravel will log the reset link locally.') }}</p>
    <x-validation-errors />
    <form method="POST" action="{{ route('password.email') }}" data-loading-form>
        @csrf
        <div class="mb-3">
            <label class="form-label" for="email">{{ __('Email') }}</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus>
        </div>
        <button class="btn btn-primary w-100" type="submit" data-loading-button data-loading-text="{{ __('Sending...') }}">{{ __('Send reset link') }}</button>
    </form>
</x-guest-layout>
