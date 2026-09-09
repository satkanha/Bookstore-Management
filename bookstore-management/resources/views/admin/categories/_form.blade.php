<x-validation-errors />
<div class="mb-3">
    <label class="form-label" for="name">{{ __('Name') }}</label>
    <input id="name" class="form-control" name="name" value="{{ old('name', $category->name) }}" required>
</div>
<div class="mb-3">
    <label class="form-label" for="description">{{ __('Description') }}</label>
    <textarea id="description" class="form-control" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
</div>
<input type="hidden" name="status" value="0">
<div class="form-check">
    <input class="form-check-input" type="checkbox" id="status" name="status" value="1" @checked(old('status', $category->status ?? true))>
    <label class="form-check-label" for="status">{{ __('Active') }}</label>
</div>
