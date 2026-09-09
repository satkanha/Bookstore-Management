@extends('layouts.app')

@section('title', __('Cart'))

@section('content')
    <div class="container">
        <h1 class="h3 mb-4">{{ __('Shopping Cart') }}</h1>
        <x-validation-errors />

        @if($cart->items->count())
            <div class="surface-card p-3">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead>
                            <tr>
                                <th>{{ __('Book') }}</th>
                                <th>{{ __('Price') }}</th>
                                <th style="width: 160px;">{{ __('Quantity') }}</th>
                                <th>{{ __('Subtotal') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($cart->items as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <img src="{{ $item->book->cover_url }}" class="book-cover" style="width: 56px;" alt="{{ __(':title cover', ['title' => $item->book->title]) }}">
                                            <div>
                                                <a class="fw-semibold text-decoration-none" href="{{ route('books.show', $item->book) }}">{{ $item->book->title }}</a>
                                                <div class="text-muted small">{{ $item->book->author->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>${{ number_format((float) $item->unit_price, 2) }}</td>
                                    <td>
                                        <form action="{{ route('cart.update', $item) }}" method="POST" class="d-flex gap-2" data-loading-form>
                                            @csrf
                                            @method('PATCH')
                                            <input class="form-control form-control-sm" type="number" name="quantity" min="1" max="{{ $item->book->stock }}" value="{{ $item->quantity }}">
                                            <button class="btn btn-sm btn-outline-primary" title="{{ __('Update quantity') }}" data-loading-button data-loading-text="{{ __('Updating...') }}"><i class="bi bi-check-lg"></i></button>
                                        </form>
                                    </td>
                                    <td>${{ number_format($item->subtotal(), 2) }}</td>
                                    <td class="text-end">
                                        <form action="{{ route('cart.destroy', $item) }}" method="POST" data-loading-form>
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger" title="{{ __('Remove item') }}" data-loading-button data-loading-text="{{ __('Removing...') }}"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <form action="{{ route('cart.clear') }}" method="POST" data-loading-form>
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-outline-secondary" type="submit" data-loading-button data-loading-text="{{ __('Clearing...') }}">{{ __('Clear cart') }}</button>
                    </form>
                    <div class="text-md-end">
                        <div class="h4">{{ __('Subtotal') }}: ${{ number_format($cart->subtotal(), 2) }}</div>
                        <a class="btn btn-primary" href="{{ route('checkout.create') }}"><i class="bi bi-credit-card"></i> {{ __('Checkout') }}</a>
                    </div>
                </div>
            </div>
        @else
            <x-empty-state :title="__('Your cart is empty')" :message="__('Find a book you like and add it to your cart.')" />
        @endif
    </div>
@endsection
