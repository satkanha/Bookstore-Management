<x-validation-errors />
<div class="mb-3">
    <label class="form-label" for="name">{{ __('Name') }}</label>
    <input id="name" class="form-control" name="name" value="{{ old('name', $author->name) }}" required>
</div>
<div class="mb-3">
    <label class="form-label" for="biography">{{ __('Biography') }}</label>
    <textarea id="biography" class="form-control" name="biography" rows="4">{{ old('biography', $author->biography) }}</textarea>
</div>
<div class="mb-3">
    <label class="form-label" for="photo">{{ __('Photo') }}</label>
    <input id="photo" class="form-control" type="file" name="photo" accept=".jpg,.jpeg,.png,.webp">
</div>
<input type="hidden" name="status" value="0">
<div class="form-check">
    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" @checked(old('status', $author->status ?? true))>
    <label class="form-check-label" for="status">{{ __('Active') }}</label>
</div>
