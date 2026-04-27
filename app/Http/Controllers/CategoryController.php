<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Category::class);

        $query = Category::with('user')->where('user_id', Auth::id());

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $categories = $query->latest()->paginate(10)->onEachSide(1)->withQueryString();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $this->authorize('create', Category::class);
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $this->authorize('create', Category::class);
        $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:income,outcome',
            'description' => 'nullable|string|max:500',
        ]);

        Category::create([
            'name'        => $request->name,
            'type'        => $request->type,
            'description' => $request->description,
            'user_id'     => Auth::id(),
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function edit(Category $category)
    {
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);
        $request->validate([
            'name'        => 'required|string|max:255',
            'type'        => 'required|in:income,outcome',
            'description' => 'nullable|string|max:500',
        ]);

        $category->update([
            'name'        => $request->name,
            'type'        => $request->type,
            'description' => $request->description,
        ]);

        return redirect()->route('categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();
        return redirect()->route('categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
