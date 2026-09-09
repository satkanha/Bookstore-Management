@extends('layouts.admin')

@section('title', __('Create Category'))
@section('page-title', __('Create Category'))

@section('content')
    <form class="admin-card p-4" method="POST" action="{{ route('admin.categories.store') }}" data-loading-form>
        @csrf
        @include('admin.categories._form')
        <button class="btn btn-primary mt-4" data-loading-button data-loading-text="{{ __('Creating...') }}">{{ __('Create category') }}</button>
        <a class="btn btn-outline-secondary mt-4" href="{{ route('admin.categories.index') }}">{{ __('Cancel') }}</a>
    </form>
@endsection
