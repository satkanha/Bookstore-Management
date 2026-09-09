@extends('layouts.app')

@section('title', __('Books'))

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">{{ __('Books') }}</h1>
                <div class="text-muted">{{ __('Search, filter, and sort the catalog.') }}</div>
            </div>
        </div>

        <form class="surface-card p-3 mb-4" method="GET" action="{{ route('books.index') }}">
            <div class="row g-3 align-items-end">
                <div class="col-12 col-lg-10">
                    <label class="form-label" for="search">{{ __('Search') }}</label>
                    <input id="search" class="form-control" type="search" name="search" value="{{ request('search') }}" placeholder="{{ __('Title, ISBN, or author') }}">
                </div>
                <div class="col-12 col-lg-2">
                    <button class="btn btn-primary w-100" type="submit"><i class="bi bi-search"></i> {{ __('Search') }}</button>
                </div>
            </div>
        </form>

        @if($books->count())
            <div class="row g-4">
                @foreach($books as $book)
                    <div class="col-6 col-md-4 col-lg-3">
                        <x-book-card :book="$book" />
                    </div>
                @endforeach
            </div>
            <div class="mt-4">{{ $books->links() }}</div>
        @else
            <x-empty-state :title="__('No books found')" :message="__('Try a broader search or remove a filter.')" />
        @endif
    </div>
@endsection
