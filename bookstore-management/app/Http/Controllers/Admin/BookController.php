<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request): View
    {
        $books = Book::query()->with(['category', 'author']);

        if ($request->get('trashed') === 'only') {
            $books->onlyTrashed();
        } elseif ($request->get('trashed') === 'with') {
            $books->withTrashed();
        }

        $books->when($request->filled('search'), function ($query) use ($request): void {
            $search = $request->string('search')->toString();
            $query->where(function ($subQuery) use ($search): void {
                $subQuery->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%");
            });
        });
        $books->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')));
        $books->when($request->filled('author'), fn ($query) => $query->where('author_id', $request->integer('author')));
        $books->when($request->filled('status'), fn ($query) => $query->where('status', $request->get('status') === 'active'));

        return view('admin.books.index', [
            'books' => $books->latest()->paginate(15)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.books.create', [
            'book' => new Book(['status' => true]),
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'authors' => Author::where('status', true)->orderBy('name')->get(),
        ]);
    }

    public function store(StoreBookRequest $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('books', 'public');
        }

        $book = Book::create($data);

        return redirect()->route('admin.books.show', $book)->with('success', __('Book created.'));
    }

    public function show(Book $book): View
    {
        return view('admin.books.show', [
            'book' => $book->load(['category', 'author', 'orderItems']),
        ]);
    }

    public function edit(Book $book): View
    {
        return view('admin.books.edit', [
            'book' => $book,
            'categories' => Category::orderBy('name')->get(),
            'authors' => Author::orderBy('name')->get(),
        ]);
    }

    public function update(UpdateBookRequest $request, Book $book): RedirectResponse
    {
        $data = $this->validatedData($request, $book);

        if ($request->hasFile('cover_image')) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $data['cover_image'] = $request->file('cover_image')->store('books', 'public');
        }

        $book->update($data);

        return redirect()->route('admin.books.show', $book)->with('success', __('Book updated.'));
    }

    public function destroy(Book $book): RedirectResponse
    {
        $book->delete();

        return redirect()->route('admin.books.index')->with('success', __('Book moved to trash.'));
    }

    public function restore(int $book): RedirectResponse
    {
        $book = Book::withTrashed()->findOrFail($book);
        $book->restore();

        return redirect()->route('admin.books.index', ['trashed' => 'with'])->with('success', __('Book restored.'));
    }

    private function validatedData(StoreBookRequest|UpdateBookRequest $request, ?Book $book = null): array
    {
        $data = $request->validated();
        unset($data['cover_image']);

        $data['slug'] = $this->uniqueSlug($data['title'], $book, $data['isbn'] ?? null);
        $data['status'] = $request->boolean('status');
        $data['featured'] = $request->boolean('featured');

        return $data;
    }

    private function uniqueSlug(string $title, ?Book $book = null, ?string $isbn = null): string
    {
        $base = Str::slug($title) ?: Str::slug((string) $isbn) ?: 'book';
        $slug = $base;
        $counter = 2;

        while (Book::withTrashed()
            ->where('slug', $slug)
            ->when($book, fn ($query) => $query->whereKeyNot($book->id))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
