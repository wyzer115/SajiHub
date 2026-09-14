<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Category;
use App\Models\Inventory;
use App\Models\MenuIngredient;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::where('branch_id', auth()->user()->branch_id)->with(['category', 'ingredients.inventory']);
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        $menus = $query->paginate(15);
        return view('admin.menus.index', compact('menus'));
    }

    public function create()
    {
        $branchId = auth()->user()->branch_id;
        $categories = Category::where('branch_id', $branchId)->get();
        if ($categories->isEmpty()) {
            $defaultCategories = ['Makanan', 'Minuman'];
            foreach ($defaultCategories as $catName) {
                Category::create([
                    'branch_id' => $branchId,
                    'name' => $catName
                ]);
            }
            $categories = Category::where('branch_id', $branchId)->get();
        }
        $inventories = Inventory::where('branch_id', $branchId)->get();
        return view('admin.menus.create', compact('categories', 'inventories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:available,sold_out',
            'image' => 'nullable|file|image|max:2048',
            'image_url' => 'nullable|string|max:1000',
            'ingredients' => 'nullable|array',
            'ingredients.*.inventory_id' => 'required_with:ingredients.*.quantity|exists:inventories,id',
            'ingredients.*.quantity' => 'required_with:ingredients.*.inventory_id|numeric|min:0.001',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('menus', 'public');
            $validated['image'] = $path;
        } elseif (!empty($request->image_url)) {
            $validated['image'] = $request->image_url;
        }

        $validated['branch_id'] = auth()->user()->branch_id;
        $menu = Menu::create($validated);

        if ($request->has('ingredients') && is_array($request->ingredients)) {
            foreach ($request->ingredients as $ing) {
                if (!empty($ing['inventory_id']) && !empty($ing['quantity'])) {
                    MenuIngredient::create([
                        'menu_id' => $menu->id,
                        'inventory_id' => $ing['inventory_id'],
                        'quantity' => $ing['quantity'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.menus.index')->with('success', 'Menu dan resep bahan (BOM) berhasil dibuat.');
    }

    public function edit(Menu $menu)
    {
        if ($menu->branch_id !== auth()->user()->branch_id) {
            abort(403);
        }
        $branchId = auth()->user()->branch_id;
        $categories = Category::where('branch_id', $branchId)->get();
        if ($categories->isEmpty()) {
            $defaultCategories = ['Makanan', 'Minuman'];
            foreach ($defaultCategories as $catName) {
                Category::create([
                    'branch_id' => $branchId,
                    'name' => $catName
                ]);
            }
            $categories = Category::where('branch_id', $branchId)->get();
        }
        $inventories = Inventory::where('branch_id', $branchId)->get();
        $menu->load('ingredients.inventory');
        return view('admin.menus.edit', compact('menu', 'categories', 'inventories'));
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
            'image' => 'nullable|file|image|max:2048',
            'image_url' => 'nullable|string|max:1000',
            'ingredients' => 'nullable|array',
            'ingredients.*.inventory_id' => 'required_with:ingredients.*.quantity|exists:inventories,id',
            'ingredients.*.quantity' => 'required_with:ingredients.*.inventory_id|numeric|min:0.001',
        ]);

        if ($request->hasFile('image')) {
            if ($menu->image && !str_starts_with($menu->image, 'http')) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($menu->image);
            }
            $path = $request->file('image')->store('menus', 'public');
            $validated['image'] = $path;
        } elseif (!empty($request->image_url)) {
            $validated['image'] = $request->image_url;
        } else {
            $validated['image'] = $menu->image;
        }

        $menu->update($validated);

        // Sync ingredients
        $menu->ingredients()->delete();
        if ($request->has('ingredients') && is_array($request->ingredients)) {
            foreach ($request->ingredients as $ing) {
                if (!empty($ing['inventory_id']) && !empty($ing['quantity'])) {
                    MenuIngredient::create([
                        'menu_id' => $menu->id,
                        'inventory_id' => $ing['inventory_id'],
                        'quantity' => $ing['quantity'],
                    ]);
                }
            }
        }

        return redirect()->route('admin.menus.index')->with('success', 'Menu dan resep bahan (BOM) berhasil diperbarui.');
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
