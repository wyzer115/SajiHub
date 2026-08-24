<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index()
    {
        $branches = Branch::withCount(['users', 'orders'])->get();
        return view('superadmin.branches.index', compact('branches'));
    }

    public function create()
    {
        return view('superadmin.branches.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'nullable|string|in:buka,tutup,maintenance',
        ]);

        $validated['status'] = $validated['status'] ?? 'buka';

        $branch = Branch::create($validated);

        // Seed default categories for this branch
        $defaultCategories = ['Makanan Utama', 'Minuman', 'Appetizer', 'Dessert'];
        foreach ($defaultCategories as $catName) {
            \App\Models\Category::create([
                'branch_id' => $branch->id,
                'name' => $catName
            ]);
        }

        return redirect()->route('superadmin.branches.index')->with('success', 'Cabang berhasil dibuat.');
    }

    public function edit(Branch $branch)
    {
        return view('superadmin.branches.edit', compact('branch'));
    }

    public function update(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'nullable|string|max:20',
            'status' => 'required|string|in:buka,tutup,maintenance',
        ]);

        $branch->update($validated);
        return redirect()->route('superadmin.branches.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('superadmin.branches.index')->with('success', 'Cabang berhasil dihapus.');
    }

    public function toggleStatus(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:buka,tutup,maintenance',
        ]);

        $updateData = ['status' => $validated['status']];
        if ($validated['status'] === 'buka') {
            $updateData['status_note'] = null;
        }

        $branch->update($updateData);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status cabang berhasil diperbarui.',
                'status' => $branch->status,
                'status_note' => $branch->status_note
            ]);
        }

        return redirect()->back()->with('success', 'Status cabang berhasil diperbarui.');
    }

    public function updateStatus(Request $request, Branch $branch)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:buka,tutup,maintenance',
            'status_note' => 'nullable|string',
        ]);

        if ($validated['status'] === 'buka') {
            $validated['status_note'] = null;
        }

        $branch->update($validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status operasional cabang berhasil diperbarui.',
                'status' => $branch->status,
                'status_note' => $branch->status_note
            ]);
        }

        return redirect()->back()->with('success', 'Status operasional cabang berhasil diperbarui.');
    }
}
