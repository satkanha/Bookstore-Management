<x-guest-layout>
    <h1 class="h4 mb-3">{{ __('Reset Password') }}</h1>
    <x-validation-errors />
    <form method="POST" action="{{ route('password.store') }}" data-loading-form>
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
        <div class="mb-3">
            <label class="form-label" for="email">{{ __('Email') }}</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
        </div>
        <div class="mb-3">
            <label class="form-label" for="password">{{ __('Password') }}</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password">
        </div>
        <div class="mb-3">
            <label class="form-label" for="password_confirmation">{{ __('Confirm password') }}</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password">
        </div>
        <button class="btn btn-primary w-100" data-loading-button data-loading-text="{{ __('Resetting...') }}">{{ __('Reset password') }}</button>
    </form>
</x-guest-layout>
