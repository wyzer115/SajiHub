<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('branch_id', auth()->user()->branch_id)->withCount('menus')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        
        $validated['branch_id'] = auth()->user()->branch_id;
        Category::create($validated);

        return redirect()->back()->with('success', 'Kategori berhasil dibuat.');
    }

    public function update(Request $request, Category $category)
    {
        if ($category->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $category->update($validated);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
