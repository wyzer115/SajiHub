<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class PublicMenuController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::all();
        $query = Menu::with(['branch', 'category']);

        // Filter by branch
        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        } else {
            // Default to branch 1 so only 9 unique menus are listed matching POS Kasir
            $query->where('branch_id', 1);
        }

        // Filter by simple category type: 'makanan' or 'minuman'
        if ($request->filled('category_type')) {
            $type = strtolower($request->category_type);
            if ($type === 'makanan') {
                $query->whereHas('category', function($cq) {
                    $cq->where('name', 'like', '%makanan%');
                });
            } elseif ($type === 'minuman') {
                $query->whereHas('category', function($cq) {
                    $cq->where('name', 'like', '%minuman%');
                });
            }
        }

        // Search by keyword
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sort = $request->get('sort', 'popular');
        if ($sort === 'price_low') {
            $query->orderBy('price', 'asc');
        } elseif ($sort === 'price_high') {
            $query->orderBy('price', 'desc');
        } elseif ($sort === 'name') {
            $query->orderBy('name', 'asc');
        } else {
            // Default: Popularity / order count sum
            $query->withSum('orderItems as total_orders', 'quantity')
                  ->orderByDesc('total_orders')
                  ->orderBy('name', 'asc');
        }

        $menus = $query->get();

        return view('menu_catalog', compact('menus', 'branches'));
    }
}
