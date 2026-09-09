@extends('layouts.admin')

@section('title', __('Books'))
@section('page-title', __('Books'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ __('Books') }}</h1>
        <a class="btn btn-primary" href="{{ route('admin.books.create') }}"><i class="bi bi-plus-lg"></i> {{ __('New book') }}</a>
    </div>

    <form class="admin-card p-3 mb-3" method="GET">
        <div class="row g-2">
            <div class="col-md-3"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="{{ __('Search title or ISBN') }}"></div>
            <div class="col-md-2">
                <select class="form-select" name="category"><option value="">{{ __('All categories') }}</option>@foreach($categories as $category)<option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>@endforeach</select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="author"><option value="">{{ __('All authors') }}</option>@foreach($authors as $author)<option value="{{ $author->id }}" @selected(request('author') == $author->id)>{{ $author->name }}</option>@endforeach</select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="status"><option value="">{{ __('Any status') }}</option><option value="active" @selected(request('status') === 'active')>{{ __('Active') }}</option><option value="inactive" @selected(request('status') === 'inactive')>{{ __('Inactive') }}</option></select>
            </div>
            <div class="col-md-2">
                <select class="form-select" name="trashed"><option value="">{{ __('Live only') }}</option><option value="with" @selected(request('trashed') === 'with')>{{ __('With trashed') }}</option><option value="only" @selected(request('trashed') === 'only')>{{ __('Trashed only') }}</option></select>
            </div>
            <div class="col-md-1"><button class="btn btn-outline-primary w-100"><i class="bi bi-search"></i></button></div>
        </div>
    </form>

    <div class="admin-card p-3 table-responsive">
        <table class="table">
            <thead><tr><th>{{ __('Book') }}</th><th>{{ __('Category') }}</th><th>{{ __('Author') }}</th><th>{{ __('Price') }}</th><th>{{ __('Stock') }}</th><th>{{ __('Status') }}</th><th></th></tr></thead>
            <tbody>
                @forelse($books as $book)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <img class="book-cover" src="{{ $book->cover_url }}" alt="" style="width: 44px;">
                                <div>
                                    <div class="fw-semibold">{{ $book->title }}</div>
                                    <div class="text-muted small">{{ $book->isbn }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ $book->category?->name }}</td>
                        <td>{{ $book->author?->name }}</td>
                        <td>${{ number_format((float) $book->price, 2) }}</td>
                        <td><span class="badge {{ $book->stock <= 5 ? 'text-bg-danger' : 'text-bg-success' }}">{{ $book->stock }}</span></td>
                        <td><x-status-badge :status="$book->trashed() ? 'deleted' : $book->status" /></td>
                        <td class="text-end">
                            @if($book->trashed())
                                <form method="POST" action="{{ route('admin.books.restore', $book->id) }}" data-loading-form>@csrf<button class="btn btn-sm btn-outline-success" data-loading-button data-loading-text="{{ __('Restoring...') }}">{{ __('Restore') }}</button></form>
                            @else
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.books.show', $book) }}">{{ __('View') }}</a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.books.edit', $book) }}">{{ __('Edit') }}</a>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger js-delete-trigger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteConfirmModal"
                                    data-delete-url="{{ route('admin.books.destroy', $book) }}"
                                    data-delete-title="{{ __('Delete :item?', ['item' => $book->title]) }}"
                                    data-delete-message="{{ __('This book will be moved to trash and can be restored later.') }}"
                                >
                                    <i class="bi bi-trash"></i> {{ __('Delete') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7"><x-empty-state :title="__('No books found')" /></td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $books->links() }}
    </div>
@endsection
