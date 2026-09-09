@extends('layouts.app')

@section('title', __($title))

@section('content')
    <section class="container py-4 py-lg-5">
        <div class="row justify-content-center">
            <div class="col-lg-9">
                <div class="surface-card p-4 p-lg-5">
                    <div class="text-amber fw-semibold mb-2">{{ __($eyebrow) }}</div>
                    <h1 class="h2 fw-bold mb-3">{{ __($title) }}</h1>
                    <p class="lead text-muted mb-4">{{ __($intro) }}</p>

                    <div class="row g-3">
                        @foreach($items as $item)
                            <div class="col-md-4">
                                <div class="footer-page-card h-100">
                                    <div class="footer-page-card__icon">
                                        <i class="bi {{ $item['icon'] }}"></i>
                                    </div>
                                    <h2 class="h6 fw-bold mb-2">{{ __($item['title']) }}</h2>
                                    <p class="text-muted small mb-0">{{ __($item['body']) }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
