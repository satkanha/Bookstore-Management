@extends('layouts.admin')

@section('title', $author->name)
@section('page-title', __('Author Details'))

@section('content')
    <div class="admin-card p-4">
        <div class="d-flex justify-content-between">
            <h1 class="h3">{{ $author->name }}</h1>
            <div>
                <a class="btn btn-outline-secondary" href="{{ route('admin.authors.edit', $author) }}">{{ __('Edit') }}</a>
                <button
                    type="button"
                    class="btn btn-outline-danger js-delete-trigger"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteConfirmModal"
                    data-delete-url="{{ route('admin.authors.destroy', $author) }}"
                    data-delete-title="{{ __('Delete :item?', ['item' => $author->name]) }}"
                    data-delete-message="{{ __('This author will be moved to trash if no books are attached.') }}"
                >
                    <i class="bi bi-trash"></i> {{ __('Delete') }}
                </button>
            </div>
        </div>
        <p class="text-muted">{{ $author->slug }}</p>
        <p>{{ $author->biography }}</p>
        <div><x-status-badge :status="$author->status" /> <span class="badge text-bg-light">{{ __(':count books', ['count' => $author->books_count]) }}</span></div>
    </div>
@endsection
