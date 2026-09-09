@extends('layouts.admin')

@section('title', __('Edit Category'))
@section('page-title', __('Edit Category'))

@section('content')
    <form class="admin-card p-4" method="POST" action="{{ route('admin.categories.update', $category) }}" data-loading-form>
        @csrf
        @method('PUT')
        @include('admin.categories._form')
        <button class="btn btn-primary mt-4" data-loading-button data-loading-text="{{ __('Saving...') }}">{{ __('Save changes') }}</button>
        <a class="btn btn-outline-secondary mt-4" href="{{ route('admin.categories.show', $category) }}">{{ __('Cancel') }}</a>
    </form>
@endsection
