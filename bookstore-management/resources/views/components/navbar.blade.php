@php
    $cartCount = 0;
    if (auth()->check() && auth()->user()->isCustomer()) {
        $cartCount = auth()->user()->cart?->items()->sum('quantity') ?? 0;
    }
@endphp

<nav class="navbar navbar-expand-lg navbar-dark navbar-bookstore sticky-top">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2 fw-bold" href="{{ route('home') }}">
            <span class="brand-mark"><i class="bi bi-book"></i></span>
            {{ __('Bookstore') }}
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">{{ __('Home') }}</a></li>
                <li class="nav-item"><a class="nav-link {{ request()->routeIs('books.*') ? 'active' : '' }}" href="{{ route('books.index') }}">{{ __('Books') }}</a></li>
            </ul>
            <form class="nav-search d-flex me-lg-3 mb-3 mb-lg-0" action="{{ route('books.index') }}" method="GET">
                <input class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('Search books') }}">
                <button class="btn btn-warning" type="submit" aria-label="{{ __('Search') }}"><i class="bi bi-search"></i></button>
            </form>
            <x-language-switcher class="me-lg-3 mb-3 mb-lg-0" />
            <ul class="navbar-nav align-items-lg-center">
                @auth
                    @if(auth()->user()->isAdmin())
                        <li class="nav-item"><a class="nav-link" href="{{ route('admin.dashboard') }}">{{ __('Admin') }}</a></li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                                <i class="bi bi-bag"></i> {{ __('Cart') }}
                                @if($cartCount > 0)
                                    <span class="badge rounded-pill text-bg-warning">{{ $cartCount }}</span>
                                @endif
                            </a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('orders.index') }}">{{ __('Orders') }}</a></li>
                    @endif
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">{{ auth()->user()->name }}</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}" data-loading-form>
                                    @csrf
                                    <button class="dropdown-item" type="submit" data-loading-button data-loading-text="{{ __('Logging out...') }}">{{ __('Logout') }}</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a></li>
                    <li class="nav-item"><a class="btn btn-sm btn-warning ms-lg-2" href="{{ route('register') }}">{{ __('Register') }}</a></li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
