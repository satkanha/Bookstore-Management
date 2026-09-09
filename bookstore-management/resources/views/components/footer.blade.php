<footer class="site-footer mt-auto">
    <div class="container">
        <div class="site-footer__main">
            <div class="row g-4 g-xl-5">
                <div class="col-md-6 col-xl-3">
                    <a class="site-footer__brand" href="{{ route('home') }}">
                        <span class="site-footer__brand-icon"><i class="bi bi-book"></i></span>
                        <span>{{ __('Bookstore') }}</span>
                    </a>
                    <p class="site-footer__description">{{ __('Discover, manage, and purchase your favorite books easily.') }}</p>
                    <div class="site-footer__socials">
                        <a href="https://www.facebook.com" target="_blank" rel="noopener" aria-label="{{ __('Facebook') }}">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="https://telegram.org" target="_blank" rel="noopener" aria-label="{{ __('Telegram') }}">
                            <i class="bi bi-telegram"></i>
                        </a>
                        <a href="https://www.youtube.com" target="_blank" rel="noopener" aria-label="{{ __('YouTube') }}">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                </div>

                <div class="col-6 col-md-3 col-xl-3">
                    <h2>{{ __('Quick Links') }}</h2>
                    <ul class="site-footer__links">
                        <li><a href="{{ route('home') }}"><i class="bi bi-chevron-right"></i>{{ __('Home') }}</a></li>
                        <li><a href="{{ route('books.index') }}"><i class="bi bi-chevron-right"></i>{{ __('Books') }}</a></li>
                        <li><a href="{{ route('pages.about') }}"><i class="bi bi-chevron-right"></i>{{ __('About Us') }}</a></li>
                    </ul>
                </div>

                <div class="col-6 col-md-3 col-xl-2">
                    <h2>{{ __('Support') }}</h2>
                    <ul class="site-footer__links">
                        <li><a href="{{ route('pages.contact') }}"><i class="bi bi-chevron-right"></i>{{ __('Contact Us') }}</a></li>
                        <li><a href="{{ route('pages.faq') }}"><i class="bi bi-chevron-right"></i>{{ __('FAQ') }}</a></li>
                        <li><a href="{{ route('pages.privacy') }}"><i class="bi bi-chevron-right"></i>{{ __('Privacy Policy') }}</a></li>
                    </ul>
                </div>

                <div class="col-md-6 col-xl-4">
                    <h2>{{ __('Contact') }}</h2>
                    <ul class="site-footer__contact">
                        <li>
                            <i class="bi bi-telephone-fill"></i>
                            <a href="tel:+85512345678">+855 12 345 678</a>
                        </li>
                        <li>
                            <i class="bi bi-envelope-fill"></i>
                            <a href="mailto:bookstore@example.com">bookstore@example.com</a>
                        </li>
                        <li>
                            <i class="bi bi-geo-alt-fill"></i>
                            <span>{{ __('Phnom Penh, Cambodia') }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="site-footer__bottom">
            <div>&copy; 2026 {{ __('Bookstore Management') }}. {{ __('All rights reserved.') }}</div>
            <div class="site-footer__legal">
                <a href="{{ route('pages.privacy') }}">{{ __('Privacy Policy') }}</a>
                <span aria-hidden="true">&middot;</span>
                <a href="{{ route('pages.terms') }}">{{ __('Terms of Use') }}</a>
            </div>
        </div>
    </div>
</footer>
