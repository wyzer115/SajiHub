<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('branch_id', auth()->user()->branch_id)->withCount('menus')->get();
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $branchId = auth()->user()->branch_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where('branch_id', $branchId),
            ],
        ], [
            'name.unique' => 'Nama kategori sudah digunakan di cabang ini.',
        ]);
        
        $validated['branch_id'] = $branchId;
        Category::create($validated);

        return redirect()->back()->with('success', 'Kategori berhasil dibuat.');
    }

    public function update(Request $request, Category $category)
    {
        if ($category->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $branchId = auth()->user()->branch_id;

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->where('branch_id', $branchId)->ignore($category->id),
            ],
        ], [
            'name.unique' => 'Nama kategori sudah digunakan di cabang ini.',
        ]);

        $category->update($validated);

        return redirect()->back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        if ($category->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        // Keamanan data: Mencegah cascade delete yang tidak disengaja terhadap seluruh menu dalam kategori
        if ($category->menus()->exists()) {
            return redirect()->back()->with('error', 'Kategori "' . $category->name . '" tidak dapat dihapus karena masih memuat menu. Hapus atau pindahkan menu terlebih dahulu.');
        }

        $category->delete();

        return redirect()->back()->with('success', 'Kategori berhasil dihapus.');
    }
}
