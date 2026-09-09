<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use App\Models\Author;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AuthorController extends Controller
{
    public function index(Request $request): View
    {
        $authors = Author::query()->withCount('books');

        if ($request->get('trashed') === 'only') {
            $authors->onlyTrashed();
        } elseif ($request->get('trashed') === 'with') {
            $authors->withTrashed();
        }

        $authors->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search')->toString().'%'));
        $authors->when($request->filled('status'), fn ($query) => $query->where('status', $request->get('status') === 'active'));

        return view('admin.authors.index', [
            'authors' => $authors->orderBy('name')->paginate(15)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.authors.create', ['author' => new Author(['status' => true])]);
    }

    public function store(StoreAuthorRequest $request): RedirectResponse
    {
        $data = $request->validated();
        unset($data['photo']);
        $data['slug'] = $this->uniqueSlug($request->validated('name'));
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('authors', 'public');
        }

        $author = Author::create($data);

        return redirect()->route('admin.authors.show', $author)->with('success', __('Author created.'));
    }

    public function show(Author $author): View
    {
        return view('admin.authors.show', [
            'author' => $author->loadCount('books'),
        ]);
    }

    public function edit(Author $author): View
    {
        return view('admin.authors.edit', ['author' => $author]);
    }

    public function update(UpdateAuthorRequest $request, Author $author): RedirectResponse
    {
        $data = $request->validated();
        unset($data['photo']);
        $data['slug'] = $this->uniqueSlug($data['name'], $author);
        $data['status'] = $request->boolean('status');

        if ($request->hasFile('photo')) {
            if ($author->photo) {
                Storage::disk('public')->delete($author->photo);
            }

            $data['photo'] = $request->file('photo')->store('authors', 'public');
        }

        $author->update($data);

        return redirect()->route('admin.authors.show', $author)->with('success', __('Author updated.'));
    }

    public function destroy(Author $author): RedirectResponse
    {
        if ($author->books()->exists()) {
            return back()->with('warning', __('Authors with books are kept to protect catalog history.'));
        }

        $author->delete();

        return redirect()->route('admin.authors.index')->with('success', __('Author moved to trash.'));
    }

    public function restore(int $author): RedirectResponse
    {
        $author = Author::withTrashed()->findOrFail($author);
        $author->restore();

        return redirect()->route('admin.authors.index', ['trashed' => 'with'])->with('success', __('Author restored.'));
    }

    private function uniqueSlug(string $name, ?Author $author = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 2;

        while (Author::withTrashed()
            ->where('slug', $slug)
            ->when($author, fn ($query) => $query->whereKeyNot($author->id))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
