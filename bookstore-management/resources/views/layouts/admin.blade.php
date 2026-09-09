<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', __('Admin')) - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bookstore-page-shell" data-loading-text="{{ __('Loading...') }}">
    <div class="admin-shell d-lg-flex">
        <x-admin-sidebar />
        <div class="admin-content flex-grow-1 d-flex flex-column min-vh-100">
            <nav class="navbar bg-white border-bottom">
                <div class="container-fluid">
                    <span class="navbar-brand mb-0 h1">@yield('page-title', __('Admin'))</span>
                    <div class="d-flex align-items-center gap-2 flex-wrap justify-content-end">
                        <x-language-switcher variant="light" />
                        <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-shop"></i> {{ __('Store') }}</a>
                        <form method="POST" action="{{ route('logout') }}" data-loading-form>
                            @csrf
                            <button class="btn btn-sm btn-outline-danger" type="submit" data-loading-button data-loading-text="{{ __('Logging out...') }}"><i class="bi bi-box-arrow-right"></i> {{ __('Logout') }}</button>
                        </form>
                    </div>
                </div>
            </nav>
            <x-flash-messages />
            <main class="container-fluid py-4 flex-grow-1">
                @yield('content')
            </main>
            <x-footer />
        </div>
    </div>
    <x-confirm-delete-modal />
</body>
</html>
