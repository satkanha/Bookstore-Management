<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request): View
    {
        $categories = Category::query()->withCount('books');

        if ($request->get('trashed') === 'only') {
            $categories->onlyTrashed();
        } elseif ($request->get('trashed') === 'with') {
            $categories->withTrashed();
        }

        $categories->when($request->filled('search'), fn ($query) => $query->where('name', 'like', '%'.$request->string('search')->toString().'%'));
        $categories->when($request->filled('status'), fn ($query) => $query->where('status', $request->get('status') === 'active'));

        return view('admin.categories.index', [
            'categories' => $categories->orderBy('name')->paginate(15)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.categories.create', ['category' => new Category(['status' => true])]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['status'] = $request->boolean('status');

        $category = Category::create($data);

        return redirect()->route('admin.categories.show', $category)->with('success', __('Category created.'));
    }

    public function show(Category $category): View
    {
        return view('admin.categories.show', [
            'category' => $category->loadCount('books'),
        ]);
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', ['category' => $category]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['name'], $category);
        $data['status'] = $request->boolean('status');

        $category->update($data);

        return redirect()->route('admin.categories.show', $category)->with('success', __('Category updated.'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        if ($category->books()->exists()) {
            return back()->with('warning', __('Categories with books are kept to protect catalog history.'));
        }

        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', __('Category moved to trash.'));
    }

    public function restore(int $category): RedirectResponse
    {
        $category = Category::withTrashed()->findOrFail($category);
        $category->restore();

        return redirect()->route('admin.categories.index', ['trashed' => 'with'])->with('success', __('Category restored.'));
    }

    private function uniqueSlug(string $name, ?Category $category = null): string
    {
        $base = Str::slug($name);
        $slug = $base;
        $counter = 2;

        while (Category::withTrashed()
            ->where('slug', $slug)
            ->when($category, fn ($query) => $query->whereKeyNot($category->id))
            ->exists()) {
            $slug = "{$base}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}
