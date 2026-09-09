@extends('layouts.app')

@section('title', $book->title)

@section('content')
    <div class="container">
        <nav aria-label="{{ __('breadcrumb') }}" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('books.index') }}">{{ __('Books') }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $book->title }}</li>
            </ol>
        </nav>

        <div class="surface-card p-4 mb-5">
            <div class="row g-4">
                <div class="col-md-4 col-lg-3">
                    <img class="book-cover w-100" src="{{ $book->cover_url }}" alt="{{ __(':title cover', ['title' => $book->title]) }}">
                </div>
                <div class="col-md-8 col-lg-9">
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        <span class="badge text-bg-light">{{ __($book->category->name) }}</span>
                        @if($book->featured)
                            <span class="badge text-bg-warning">{{ __('Featured') }}</span>
                        @endif
                    </div>
                    <h1 class="h2">{{ $book->title }}</h1>
                    <p class="text-muted mb-2">{{ __('by :author', ['author' => $book->author->name]) }}</p>
                    <div class="h3 text-amber mb-3">${{ number_format((float) $book->price, 2) }}</div>
                    <dl class="row">
                        <dt class="col-sm-3">{{ __('ISBN') }}</dt><dd class="col-sm-9">{{ $book->isbn }}</dd>
                        <dt class="col-sm-3">{{ __('Stock') }}</dt><dd class="col-sm-9">{{ __(':count available', ['count' => $book->stock]) }}</dd>
                        <dt class="col-sm-3">{{ __('Published') }}</dt><dd class="col-sm-9">{{ $book->publication_date?->format('M d, Y') ?? __('Not listed') }}</dd>
                    </dl>
                    <p>{{ $book->description }}</p>

                    @auth
                        @if(auth()->user()->isCustomer())
                            <div class="d-flex flex-wrap align-items-end gap-2">
                                <form class="d-flex flex-wrap align-items-end gap-2" action="{{ route('cart.store') }}" method="POST" data-loading-form>
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <div>
                                        <label class="form-label" for="quantity">{{ __('Quantity') }}</label>
                                        <input id="quantity" class="form-control" type="number" name="quantity" value="1" min="1" max="{{ max($book->stock, 1) }}" @disabled($book->stock < 1)>
                                    </div>
                                    <button class="btn btn-primary" type="submit" data-loading-button data-loading-text="{{ __('Adding...') }}" @disabled($book->stock < 1)>
                                        <i class="bi bi-bag-plus"></i> {{ __('Add to cart') }}
                                    </button>
                                </form>
                                <button
                                    type="button"
                                    class="btn btn-warning js-order-trigger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#bookOrderModal"
                                    data-order-url="{{ route('books.order', $book) }}"
                                    data-book-id="{{ $book->id }}"
                                    data-book-title="{{ $book->title }}"
                                    data-book-author="{{ $book->author->name }}"
                                    data-book-category="{{ __($book->category->name) }}"
                                    data-book-price="{{ $book->price }}"
                                    data-book-stock="{{ $book->stock }}"
                                    data-book-cover="{{ $book->cover_url }}"
                                    data-book-cover-alt="{{ __(':title cover', ['title' => $book->title]) }}"
                                    data-book-stock-label="{{ $book->stock > 0 ? __(':count in stock', ['count' => $book->stock]) : __('Out of stock') }}"
                                    @disabled($book->stock < 1)
                                >
                                    <i class="bi bi-bag-check"></i> {{ __('Order') }}
                                </button>
                            </div>
                        @endif
                    @else
                        <button
                            type="button"
                            class="btn btn-warning js-order-trigger"
                            data-bs-toggle="modal"
                            data-bs-target="#bookOrderModal"
                            data-book-id="{{ $book->id }}"
                            data-book-title="{{ $book->title }}"
                            data-book-author="{{ $book->author->name }}"
                            data-book-category="{{ __($book->category->name) }}"
                            data-book-price="{{ $book->price }}"
                            data-book-stock="{{ $book->stock }}"
                            data-book-cover="{{ $book->cover_url }}"
                            data-book-cover-alt="{{ __(':title cover', ['title' => $book->title]) }}"
                            data-book-stock-label="{{ $book->stock > 0 ? __(':count in stock', ['count' => $book->stock]) : __('Out of stock') }}"
                            @disabled($book->stock < 1)
                        >
                            <i class="bi bi-bag-check"></i> {{ __('Order') }}
                        </button>
                        <a class="btn btn-primary" href="{{ route('login') }}">{{ __('Login to add to cart') }}</a>
                    @endauth
                </div>
            </div>
        </div>

        <h2 class="h4 mb-3">{{ __('Related Books') }}</h2>
        <div class="row g-4">
            @forelse($relatedBooks as $related)
                <div class="col-6 col-md-3">
                    <x-book-card :book="$related" />
                </div>
            @empty
                <x-empty-state :title="__('No related books')" :message="__('Check back after more catalog titles are added.')" />
            @endforelse
        </div>
    </div>
@endsection
