@extends('layouts.app')

@section('title', __('Bookstore'))

@section('content')
    <section class="hero-band mb-5">
        <video class="hero-video" autoplay muted loop playsinline preload="metadata" aria-hidden="true">
            <source src="{{ asset('images/bookstore-background.mp4') }}" type="video/mp4">
        </video>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-xl-7">
                    <h1 class="hero-title">
                        @if(app()->getLocale() === 'km')
                            <span>{{ __('Bookstore Management') }}</span>
                        @else
                            <span>{{ __('Bookstore') }}</span>
                            <span class="hero-title__accent">{{ __('Management') }}</span>
                        @endif
                    </h1>
                    <p class="lead mb-4">{{ __('Browse featured titles, discover new authors, and place demo orders through a secure Laravel checkout flow.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="container mb-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">{{ __('Featured Books') }}</h2>
            <a href="{{ route('books.index') }}" class="btn btn-sm btn-outline-primary">{{ __('View all') }}</a>
        </div>
        <div class="row g-4">
            @forelse($featuredBooks as $book)
                <div class="col-6 col-md-4 col-lg-3">
                    <x-book-card :book="$book" :show-purchase-controls="false" />
                </div>
            @empty
                <x-empty-state :title="__('No featured books yet')" :message="__('Seed the database to see demo titles.')" />
            @endforelse
        </div>
    </section>

    <section class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="h4 mb-0">{{ __('Latest Books') }}</h2>
            <a href="{{ route('books.index', ['sort' => 'newest']) }}" class="btn btn-sm btn-outline-primary">{{ __('Newest') }}</a>
        </div>
        <div class="row g-4">
            @foreach($latestBooks as $book)
                <div class="col-6 col-md-4 col-lg-3">
                    <x-book-card :book="$book" :show-purchase-controls="false" />
                </div>
            @endforeach
        </div>
    </section>
@endsection
