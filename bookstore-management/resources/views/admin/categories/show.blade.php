@extends('layouts.admin')

@section('title', $category->name)
@section('page-title', __('Category Details'))

@section('content')
    <div class="admin-card p-4">
        <div class="d-flex justify-content-between">
            <h1 class="h3">{{ $category->name }}</h1>
            <div>
                <a class="btn btn-outline-secondary" href="{{ route('admin.categories.edit', $category) }}">{{ __('Edit') }}</a>
                <button
                    type="button"
                    class="btn btn-outline-danger js-delete-trigger"
                    data-bs-toggle="modal"
                    data-bs-target="#deleteConfirmModal"
                    data-delete-url="{{ route('admin.categories.destroy', $category) }}"
                    data-delete-title="{{ __('Delete :item?', ['item' => $category->name]) }}"
                    data-delete-message="{{ __('This category will be moved to trash if it has no books attached.') }}"
                >
                    <i class="bi bi-trash"></i> {{ __('Delete') }}
                </button>
            </div>
        </div>
        <p class="text-muted">{{ $category->slug }}</p>
        <p>{{ $category->description }}</p>
        <div><x-status-badge :status="$category->status" /> <span class="badge text-bg-light">{{ __(':count books', ['count' => $category->books_count]) }}</span></div>
    </div>
@endsection
