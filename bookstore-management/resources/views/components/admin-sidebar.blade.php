<aside class="admin-sidebar p-3">
    <a class="d-flex align-items-center gap-2 text-white text-decoration-none mb-4" href="{{ route('admin.dashboard') }}">
        <span class="brand-mark"><i class="bi bi-book"></i></span>
        <span class="fw-bold">{{ __('Bookstore Admin') }}</span>
    </a>
    <nav class="nav flex-column gap-1">
        <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>{{ __('Dashboard') }}</a>
        <a class="nav-link {{ request()->routeIs('admin.books.*') ? 'active' : '' }}" href="{{ route('admin.books.index') }}"><i class="bi bi-book-half me-2"></i>{{ __('Books') }}</a>
        <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" href="{{ route('admin.categories.index') }}"><i class="bi bi-tags me-2"></i>{{ __('Categories') }}</a>
        <a class="nav-link {{ request()->routeIs('admin.authors.*') ? 'active' : '' }}" href="{{ route('admin.authors.index') }}"><i class="bi bi-pen me-2"></i>{{ __('Authors') }}</a>
        <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}"><i class="bi bi-people me-2"></i>{{ __('Customers') }}</a>
        <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}"><i class="bi bi-receipt me-2"></i>{{ __('Orders') }}</a>
        <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}"><i class="bi bi-graph-up me-2"></i>{{ __('Reports') }}</a>
    </nav>
</aside>
