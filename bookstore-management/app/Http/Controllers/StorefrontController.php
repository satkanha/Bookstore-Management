<?php

namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class StorefrontController extends Controller
{
    public function home(): View
    {
        return view('storefront.home', [
            'featuredBooks' => Book::with(['author', 'category'])
                ->active()
                ->where('featured', true)
                ->latest()
                ->take(8)
                ->get(),
            'latestBooks' => Book::with(['author', 'category'])
                ->active()
                ->latest()
                ->take(8)
                ->get(),
        ]);
    }

    public function books(Request $request): View
    {
        $books = Book::query()
            ->with(['author', 'category'])
            ->active()
            ->whereHas('author', fn ($query) => $query->where('status', true))
            ->whereHas('category', fn ($query) => $query->where('status', true));

        if ($search = $request->string('search')->toString()) {
            $books->where(function ($query) use ($search): void {
                $query->where('title', 'like', "%{$search}%")
                    ->orWhere('isbn', 'like', "%{$search}%")
                    ->orWhereHas('author', fn ($authorQuery) => $authorQuery->where('name', 'like', "%{$search}%"));
            });
        }

        $books->when($request->filled('category'), fn ($query) => $query->where('category_id', $request->integer('category')));
        $books->when($request->filled('author'), fn ($query) => $query->where('author_id', $request->integer('author')));
        $books->when($request->filled('min_price'), fn ($query) => $query->where('price', '>=', $request->float('min_price')));
        $books->when($request->filled('max_price'), fn ($query) => $query->where('price', '<=', $request->float('max_price')));

        match ($request->get('sort')) {
            'title' => $books->orderBy('title'),
            'price_low' => $books->orderBy('price'),
            'price_high' => $books->orderByDesc('price'),
            default => $books->latest(),
        };

        return view('storefront.books.index', [
            'books' => $books->paginate(12)->withQueryString(),
            'categories' => Category::where('status', true)->orderBy('name')->get(),
            'authors' => Author::where('status', true)->orderBy('name')->get(),
        ]);
    }

    public function show(Book $book): View
    {
        abort_unless($book->status, 404);

        $book->load(['author', 'category']);

        return view('storefront.books.show', [
            'book' => $book,
            'relatedBooks' => Book::with(['author', 'category'])
                ->active()
                ->where('id', '!=', $book->id)
                ->where('category_id', $book->category_id)
                ->latest()
                ->take(4)
                ->get(),
        ]);
    }
}
