@extends('layouts.admin')

@section('title', __('Create Author'))
@section('page-title', __('Create Author'))

@section('content')
    <form class="admin-card p-4" method="POST" action="{{ route('admin.authors.store') }}" enctype="multipart/form-data" data-loading-form>
        @csrf
        @include('admin.authors._form')
        <button class="btn btn-primary mt-4" data-loading-button data-loading-text="{{ __('Creating...') }}">{{ __('Create author') }}</button>
        <a class="btn btn-outline-secondary mt-4" href="{{ route('admin.authors.index') }}">{{ __('Cancel') }}</a>
    </form>
@endsection
