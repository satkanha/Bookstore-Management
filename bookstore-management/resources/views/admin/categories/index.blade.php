@extends('layouts.admin')

@section('title', __('Categories'))
@section('page-title', __('Categories'))

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ __('Categories') }}</h1>
        <a class="btn btn-primary" href="{{ route('admin.categories.create') }}"><i class="bi bi-plus-lg"></i> {{ __('New category') }}</a>
    </div>
    <form class="admin-card p-3 mb-3" method="GET">
        <div class="row g-2">
            <div class="col-md-5"><input class="form-control" name="search" value="{{ request('search') }}" placeholder="{{ __('Search categories') }}"></div>
            <div class="col-md-3"><select class="form-select" name="status"><option value="">{{ __('Any status') }}</option><option value="active" @selected(request('status') === 'active')>{{ __('Active') }}</option><option value="inactive" @selected(request('status') === 'inactive')>{{ __('Inactive') }}</option></select></div>
            <div class="col-md-3"><select class="form-select" name="trashed"><option value="">{{ __('Live only') }}</option><option value="with" @selected(request('trashed') === 'with')>{{ __('With trashed') }}</option><option value="only" @selected(request('trashed') === 'only')>{{ __('Trashed only') }}</option></select></div>
            <div class="col-md-1"><button class="btn btn-outline-primary w-100"><i class="bi bi-search"></i></button></div>
        </div>
    </form>
    <div class="admin-card p-3 table-responsive">
        <table class="table">
            <thead><tr><th>{{ __('Name') }}</th><th>{{ __('Slug') }}</th><th>{{ __('Books') }}</th><th>{{ __('Status') }}</th><th></th></tr></thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->books_count }}</td>
                        <td><x-status-badge :status="$category->trashed() ? 'deleted' : $category->status" /></td>
                        <td class="text-end">
                            @if($category->trashed())
                                <form method="POST" action="{{ route('admin.categories.restore', $category->id) }}" data-loading-form>@csrf<button class="btn btn-sm btn-outline-success" data-loading-button data-loading-text="{{ __('Restoring...') }}">{{ __('Restore') }}</button></form>
                            @else
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.categories.show', $category) }}">{{ __('View') }}</a>
                                <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.categories.edit', $category) }}">{{ __('Edit') }}</a>
                                <button
                                    type="button"
                                    class="btn btn-sm btn-outline-danger js-delete-trigger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteConfirmModal"
                                    data-delete-url="{{ route('admin.categories.destroy', $category) }}"
                                    data-delete-title="{{ __('Delete :item?', ['item' => $category->name]) }}"
                                    data-delete-message="{{ __('This category will be moved to trash if it has no books attached.') }}"
                                >
                                    <i class="bi bi-trash"></i> {{ __('Delete') }}
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $categories->links() }}
    </div>
@endsection
