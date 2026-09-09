<x-guest-layout>
    <h1 class="h4 mb-3">{{ __('Verify Email') }}</h1>
    <p class="text-muted">{{ __('A verification link was sent to your email address. You can request another link if needed.') }}</p>
    <form method="POST" action="{{ route('verification.send') }}" class="mb-3" data-loading-form>
        @csrf
        <button class="btn btn-primary w-100" data-loading-button data-loading-text="{{ __('Sending...') }}">{{ __('Resend verification email') }}</button>
    </form>
    <form method="POST" action="{{ route('logout') }}" data-loading-form>
        @csrf
        <button class="btn btn-outline-secondary w-100" data-loading-button data-loading-text="{{ __('Logging out...') }}">{{ __('Logout') }}</button>
    </form>
</x-guest-layout>
