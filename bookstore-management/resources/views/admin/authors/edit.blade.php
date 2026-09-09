@extends('layouts.admin')

@section('title', __('Edit Author'))
@section('page-title', __('Edit Author'))

@section('content')
    <form class="admin-card p-4" method="POST" action="{{ route('admin.authors.update', $author) }}" enctype="multipart/form-data" data-loading-form>
        @csrf
        @method('PUT')
        @include('admin.authors._form')
        <button class="btn btn-primary mt-4" data-loading-button data-loading-text="{{ __('Saving...') }}">{{ __('Save changes') }}</button>
        <a class="btn btn-outline-secondary mt-4" href="{{ route('admin.authors.show', $author) }}">{{ __('Cancel') }}</a>
    </form>
@endsection
