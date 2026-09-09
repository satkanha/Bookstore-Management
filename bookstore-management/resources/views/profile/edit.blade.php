<x-app-layout>
    <x-slot name="header">
        <h1 class="h3 mb-0">{{ __('Profile') }}</h1>
    </x-slot>

    <div class="container">
        <div class="row g-4">
            <div class="col-lg-7">
                <div class="surface-card p-4">
                    <h2 class="h5">{{ __('Profile Information') }}</h2>
                    <x-validation-errors />
                    <form method="POST" action="{{ route('profile.update') }}" data-loading-form>
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="form-label" for="name">{{ __('Name') }}</label>
                            <input id="name" class="form-control" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="email">{{ __('Email') }}</label>
                            <input id="email" class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="phone">{{ __('Phone') }}</label>
                            <input id="phone" class="form-control" name="phone" value="{{ old('phone', $user->phone) }}">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="address">{{ __('Address') }}</label>
                            <textarea id="address" class="form-control" name="address" rows="3">{{ old('address', $user->address) }}</textarea>
                        </div>
                        <button class="btn btn-primary" data-loading-button data-loading-text="{{ __('Saving...') }}">{{ __('Save profile') }}</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="surface-card p-4">
                    <h2 class="h5">{{ __('Update Password') }}</h2>
                    <form method="POST" action="{{ route('password.update') }}" data-loading-form>
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label" for="current_password">{{ __('Current password') }}</label>
                            <input id="current_password" class="form-control" type="password" name="current_password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="new_password">{{ __('New password') }}</label>
                            <input id="new_password" class="form-control" type="password" name="password">
                        </div>
                        <div class="mb-3">
                            <label class="form-label" for="password_confirmation">{{ __('Confirm new password') }}</label>
                            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation">
                        </div>
                        <button class="btn btn-outline-primary" data-loading-button data-loading-text="{{ __('Updating...') }}">{{ __('Update password') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
