<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', __('Bookstore Management')))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-soft bookstore-page-shell" data-loading-text="{{ __('Loading...') }}">
    <main class="container py-5 flex-grow-1">
        <div class="row justify-content-center">
            <div class="col-sm-10 col-md-7 col-lg-5">
                <div class="text-center mb-3">
                    <a class="d-inline-flex align-items-center gap-2 text-decoration-none" href="{{ route('home') }}">
                        <span class="brand-mark"><i class="bi bi-book"></i></span>
                        <span class="h4 mb-0 fw-bold text-dark">{{ __('Bookstore') }}</span>
                    </a>
                </div>
                <div class="d-flex justify-content-center mb-4">
                    <x-language-switcher variant="light" />
                </div>
                <div class="surface-card p-4">
                    <x-flash-messages />
                    {{ $slot }}
                </div>
            </div>
        </div>
    </main>
    <x-footer />
</body>
</html>
