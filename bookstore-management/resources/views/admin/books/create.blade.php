@extends('layouts.admin')

@section('title', __('Create Book'))
@section('page-title', __('Create Book'))

@section('content')
    <form class="admin-card p-4" method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" data-loading-form>
        @csrf
        @include('admin.books._form')
        <button class="btn btn-primary mt-4" type="submit" data-loading-button data-loading-text="{{ __('Creating...') }}">{{ __('Create book') }}</button>
        <a class="btn btn-outline-secondary mt-4" href="{{ route('admin.books.index') }}">{{ __('Cancel') }}</a>
    </form>
@endsection
