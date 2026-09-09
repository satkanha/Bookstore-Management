@if ($errors->any())
    <div class="alert alert-danger fade show">
        <strong>{{ __('Check the form and try again.') }}</strong>
        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
