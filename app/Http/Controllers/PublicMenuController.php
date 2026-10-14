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

        // Tentukan cabang aktif default jika parameter branch_id tidak disediakan
        // 1. Jika staf/admin login, ikuti cabang milik staf tersebut
        // 2. Jika pengunjung umum, gunakan cabang utama (misal: SajiHUB Jakarta Selatan / ID cabang pertama)
        $defaultBranchId = null;
        if (auth()->check() && auth()->user()->branch_id) {
            $defaultBranchId = (string) auth()->user()->branch_id;
        } else {
            $defaultBranchId = (string) (Branch::first()?->id ?? 11);
        }

        // Tentukan branch_id yang sedang dipilih
        $currentBranchId = $request->has('branch_id') ? $request->get('branch_id') : $defaultBranchId;

        // Filter cabang: Jika 'all' atau kosong maka tampilkan semua, selain itu filter sesuai ID cabang
        if (!empty($currentBranchId) && $currentBranchId !== 'all') {
            $query->where('branch_id', $currentBranchId);
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

        // Search by keyword (cari berdasarkan nama menu atau kategori)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhereHas('category', function($cq) use ($search) {
                      $cq->where('name', 'like', "%{$search}%");
                  });
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

        return view('menu_catalog', compact('menus', 'branches', 'currentBranchId'));
    }
}
