<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', 'admin_cabang')->with('branch')->get();
        return view('superadmin.users.index', compact('users'));
    }

    public function create()
    {
        $branches = Branch::all();
        return view('superadmin.users.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username|alpha_dash',
            'password' => 'required|string|min:6',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'admin_cabang';

        User::create($validated);

        return redirect()->route('superadmin.users.index')->with('success', 'Akun Admin Cabang berhasil dibuat.');
    }

    public function edit(User $user)
    {
        if ($user->role !== 'admin_cabang') {
            abort(403);
        }
        $branches = Branch::all();
        return view('superadmin.users.edit', compact('user', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->role !== 'admin_cabang') {
            abort(403);
        }

        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'username' => [
                'required',
                'string',
                'max:255',
                'alpha_dash',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('superadmin.users.index')->with('success', 'Akun Admin Cabang berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->role !== 'admin_cabang') {
            abort(403);
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')->with('success', 'Akun Admin Cabang berhasil dihapus.');
    }
}
