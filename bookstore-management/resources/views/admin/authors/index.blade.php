@extends('layouts.admin')

@section('title', __('Authors'))
@section('page-title', __('Authors'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ __('Authors') }}</h1>
        <a class="btn btn-primary" href="{{ route('admin.authors.create') }}"><i class="bi bi-plus-lg"></i> {{ __('New author') }}</a>
    </div>
    <form class="admin-card p-3 mb-3" method="GET">
        <div class="row g-2">
            <div class="col-md-5"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="{{ __('Search authors') }}"></div>
            <div class="col-md-3"><select class="form-select" name="status"><option value="">{{ __('Any status') }}</option><option value="active" @selected(request('status') === 'active')>{{ __('Active') }}</option><option value="inactive" @selected(request('status') === 'inactive')>{{ __('Inactive') }}</option></select></div>
            <div class="col-md-3"><select class="form-select" name="trashed"><option value="">{{ __('Live only') }}</option><option value="with" @selected(request('trashed') === 'with')>{{ __('With trashed') }}</option><option value="only" @selected(request('trashed') === 'only')>{{ __('Trashed only') }}</option></select></div>
            <div class="col-md-1"><button class="btn btn-outline-primary w-100"><i class="bi bi-search"></i></button></div>
        </div>
    </form>
    <div class="admin-card p-3 table-responsive">
        <table class="table">
            <thead><tr><th>{{ __('Author') }}</th><th>{{ __('Slug') }}</th><th>{{ __('Books') }}</th><th>{{ __('Status') }}</th><th></th></tr></thead>
            <tbody>
                @foreach($authors as $author)
                    <tr>
                        <td>{{ $author->name }}</td>
                        <td>{{ $author->slug }}</td>
                        <td>{{ $author->books_count }}</td>
                        <td><x-status-badge :status="$author->trashed() ? 'deleted' : $author->status" /></td>
                        <td class="text-end">
                            @if($author->trashed())
                                <form method="POST" action="{{ route('admin.authors.restore', $author->id) }}" data-loading-form>@csrf<button class="btn btn-sm btn-outline-success" data-loading-button data-loading-text="{{ __('Restoring...') }}">{{ __('Restore') }}</button></form>
                            @else
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.authors.show', $author) }}">{{ __('View') }}</a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.authors.edit', $author) }}">{{ __('Edit') }}</a>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger js-delete-trigger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteConfirmModal"
                                    data-delete-url="{{ route('admin.authors.destroy', $author) }}"
                                    data-delete-title="{{ __('Delete :item?', ['item' => $author->name]) }}"
                                    data-delete-message="{{ __('This author will be moved to trash if no books are attached.') }}"
                                >
                                    <i class="bi bi-trash"></i> {{ __('Delete') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $authors->links() }}
    </div>
@endsection
