<x-validation-errors />
<div class="row g-3">
    <div class="col-md-8">
        <label class="form-label" for="title">{{ __('Title') }}</label>
        <input id="title" class="form-control" name="title" value="{{ old('title', $book->title) }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label" for="isbn">{{ __('ISBN') }}</label>
        <input id="isbn" class="form-control" name="isbn" value="{{ old('isbn', $book->isbn) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="category_id">{{ __('Category') }}</label>
        <select id="category_id" class="form-select" name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $book->category_id) == $category->id)>{{ $category->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label" for="author_id">{{ __('Author') }}</label>
        <select id="author_id" class="form-select" name="author_id" required>
            @foreach($authors as $author)
                <option value="{{ $author->id }}" @selected(old('author_id', $book->author_id) == $author->id)>{{ $author->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label" for="price">{{ __('Price') }}</label>
        <input id="price" class="form-control" type="number" step="0.01" name="price" value="{{ old('price', $book->price) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label" for="stock">{{ __('Stock') }}</label>
        <input id="stock" class="form-control" type="number" name="stock" value="{{ old('stock', $book->stock ?? 0) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label" for="publication_date">{{ __('Publication date') }}</label>
        <input id="publication_date" class="form-control" type="date" name="publication_date" value="{{ old('publication_date', $book->publication_date?->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label" for="cover_image">{{ __('Cover image') }}</label>
        <input id="cover_image" class="form-control" type="file" name="cover_image" accept=".jpg,.jpeg,.png,.webp">
    </div>
    <div class="col-12">
        <label class="form-label" for="description">{{ __('Description') }}</label>
        <textarea id="description" class="form-control" name="description" rows="5" required>{{ old('description', $book->description) }}</textarea>
    </div>
    <div class="col-12 d-flex gap-4">
        <input type="hidden" name="status" value="0">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="status" name="status" value="1" @checked(old('status', $book->status ?? true))>
            <label class="form-check-label" for="status">{{ __('Active') }}</label>
        </div>
        <input type="hidden" name="featured" value="0">
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="featured" name="featured" value="1" @checked(old('featured', $book->featured ?? false))>
            <label class="form-check-label" for="featured">{{ __('Featured') }}</label>
        </div>
    </div>
</div>
