@extends('layouts.admin')

@section('title', $book->title)
@section('page-title', __('Book Details'))

@section('content')
    <div class="admin-card p-4">
        <div class="row g-4">
            <div class="col-md-3"><img class="book-cover w-100" src="{{ $book->cover_url }}" alt="{{ __(':title cover', ['title' => $book->title]) }}"></div>
            <div class="col-md-9">
                <div class="d-flex justify-content-between">
                    <h1 class="h3">{{ $book->title }}</h1>
                    <div>
                        <a class="btn btn-outline-secondary" href="{{ route('admin.books.edit', $book) }}">{{ __('Edit') }}</a>
                        <button
                            type="button"
                            class="btn btn-outline-danger js-delete-trigger"
                            data-bs-toggle="modal"
                            data-bs-target="#deleteConfirmModal"
                            data-delete-url="{{ route('admin.books.destroy', $book) }}"
                            data-delete-title="{{ __('Delete :item?', ['item' => $book->title]) }}"
                            data-delete-message="{{ __('This book will be moved to trash and can be restored later.') }}"
                        >
                            <i class="bi bi-trash"></i> {{ __('Delete') }}
                        </button>
                    </div>
                </div>
                <p class="text-muted">{{ $book->author->name }} / {{ $book->category->name }}</p>
                <p>{{ $book->description }}</p>
                <dl class="row">
                    <dt class="col-sm-3">{{ __('ISBN') }}</dt><dd class="col-sm-9">{{ $book->isbn }}</dd>
                    <dt class="col-sm-3">{{ __('Price') }}</dt><dd class="col-sm-9">${{ number_format((float) $book->price, 2) }}</dd>
                    <dt class="col-sm-3">{{ __('Stock') }}</dt><dd class="col-sm-9">{{ $book->stock }}</dd>
                    <dt class="col-sm-3">{{ __('Status') }}</dt><dd class="col-sm-9"><x-status-badge :status="$book->status" /></dd>
                </dl>
            </div>
        </div>
    </div>
@endsection
