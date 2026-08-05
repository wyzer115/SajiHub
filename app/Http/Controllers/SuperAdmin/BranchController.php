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
        ]);

        Branch::create($validated);
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
        ]);

        $branch->update($validated);
        return redirect()->route('superadmin.branches.index')->with('success', 'Cabang berhasil diperbarui.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return redirect()->route('superadmin.branches.index')->with('success', 'Cabang berhasil dihapus.');
    }
}
