@extends('layouts.admin')

@section('title', __('Edit Book'))
@section('page-title', __('Edit Book'))

@section('content')
    <form class="admin-card p-4" method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data" data-loading-form>
        @csrf
        @method('PUT')
        @include('admin.books._form')
        <button class="btn btn-primary mt-4" type="submit" data-loading-button data-loading-text="{{ __('Saving...') }}">{{ __('Save changes') }}</button>
        <a class="btn btn-outline-secondary mt-4" href="{{ route('admin.books.show', $book) }}">{{ __('Cancel') }}</a>
    </form>
@endsection
