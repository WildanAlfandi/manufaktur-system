<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string'
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil diupdate!');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->count() > 0) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena masih memiliki produk!');
        }

        $category->delete();
        return redirect()->route('categories.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }

    public function show(Category $category)
    {
        $category->load(['products' => function ($query) {
            $query->latest()->take(10);
        }]);

        $stats = [
            'total_products' => $category->products()->count(),
            'total_stock' => $category->products()->sum('stock'),
            'total_value' => $category->products()->sum(\DB::raw('stock * price')),
            'low_stock_products' => $category->products()->where('stock', '<=', \DB::raw('min_stock'))->count(),
        ];

        return view('categories.show', compact('category', 'stats'));
    }
}
