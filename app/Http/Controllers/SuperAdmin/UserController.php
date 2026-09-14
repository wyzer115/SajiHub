<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::with('branch')->where('role', 'admin_cabang');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        $users = $query->latest()->paginate(20);
        $branches = Branch::orderBy('name')->get();

        return view('superadmin.users.index', compact('users', 'branches'));
    }

    public function create()
    {
        $branches = Branch::orderBy('name')->get();
        return view('superadmin.users.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'username'  => 'required|string|max:255|unique:users,username|alpha_dash',
            'password'  => 'required|string|min:6|confirmed',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'username'  => $validated['username'],
            'password'  => Hash::make($validated['password']),
            'role'      => 'admin_cabang',
            'branch_id' => $validated['branch_id'],
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Akun Admin Cabang "' . $validated['name'] . '" berhasil dibuat.');
    }

    public function edit(User $user)
    {
        if ($user->isSuperAdmin()) {
            abort(403, 'Tidak dapat mengedit akun Super Admin.');
        }

        $branches = Branch::orderBy('name')->get();
        return view('superadmin.users.edit', compact('user', 'branches'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->isSuperAdmin()) {
            abort(403, 'Tidak dapat mengedit akun Super Admin.');
        }

        $validated = $request->validate([
            'branch_id' => 'required|exists:branches,id',
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'username'  => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'password'  => 'nullable|string|min:6|confirmed',
        ]);

        $data = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'username'  => $validated['username'],
            'role'      => 'admin_cabang',
            'branch_id' => $validated['branch_id'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Akun Admin Cabang "' . $user->name . '" berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->isSuperAdmin()) {
            abort(403, 'Tidak dapat menghapus akun Super Admin.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Akun Admin Cabang "' . $name . '" berhasil dihapus.');
    }
}
