<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::where('branch_id', auth()->user()->branch_id)->with('category');
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $menus = $query->paginate(15);
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $categories = Category::where('branch_id', auth()->user()->branch_id)->get();
        return view('admin.menus.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,sold_out',
            'image' => 'nullable|string'
        ]);

        $validated['branch_id'] = auth()->user()->branch_id;
        Menu::create($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dibuat.');
    }

    public function edit(Menu $menu)
    {
        if ($menu->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }
        $categories = Category::where('branch_id', auth()->user()->branch_id)->get();
        return view('admin.menus.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, Menu $menu)
    {
        if ($menu->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,sold_out',
            'image' => 'nullable|string'
        ]);

        $menu->update($validated);

        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(Menu $menu)
    {
        if ($menu->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu berhasil dihapus.');
    }
}
