<x-guest-layout>
    <h1 class="h4 mb-3">{{ __('Confirm Password') }}</h1>
    <x-validation-errors />
    <form method="POST" action="{{ route('password.confirm') }}" data-loading-form>
        @csrf
        <div class="mb-3">
            <label class="form-label" for="password">{{ __('Password') }}</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password">
        </div>
        <button class="btn btn-primary w-100" data-loading-button data-loading-text="{{ __('Confirming...') }}">{{ __('Confirm') }}</button>
    </form>
</x-guest-layout>
