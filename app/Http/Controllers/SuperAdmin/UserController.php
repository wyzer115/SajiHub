<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
<<<<<<< HEAD
use App\Models\Branch;
use App\Models\User;
=======
use App\Models\User;
use App\Models\Branch;
>>>>>>> a471717247185442b2b06268d4e157d25322f3c7
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
<<<<<<< HEAD
    public function index(Request $request)
    {
        $query = User::with('branch')->where('role', '!=', 'superadmin');

        if ($request->filled('branch_id')) {
            $query->where('branch_id', $request->branch_id);
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(20);
        $branches = Branch::orderBy('name')->get();

        return view('superadmin.users.index', compact('users', 'branches'));
=======
    public function index()
    {
        $users = User::where('role', 'admin_cabang')->with('branch')->get();
        return view('superadmin.users.index', compact('users'));
>>>>>>> a471717247185442b2b06268d4e157d25322f3c7
    }

    public function create()
    {
<<<<<<< HEAD
        $branches = Branch::orderBy('name')->get();
=======
        $branches = Branch::all();
>>>>>>> a471717247185442b2b06268d4e157d25322f3c7
        return view('superadmin.users.create', compact('branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
<<<<<<< HEAD
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'username'  => 'required|string|max:255|unique:users,username|alpha_dash',
            'password'  => 'required|string|min:6|confirmed',
            'role'      => 'required|in:admin_cabang,kasir,koki',
            'branch_id' => 'required|exists:branches,id',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'username'  => $validated['username'],
            'password'  => Hash::make($validated['password']),
            'role'      => $validated['role'],
            'branch_id' => $validated['branch_id'],
        ]);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Akun "' . $validated['name'] . '" berhasil dibuat.');
=======
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
>>>>>>> a471717247185442b2b06268d4e157d25322f3c7
    }

    public function edit(User $user)
    {
<<<<<<< HEAD
        if ($user->isSuperAdmin()) {
            abort(403, 'Tidak dapat mengedit akun Super Admin.');
        }
        $branches = Branch::orderBy('name')->get();
=======
        if ($user->role !== 'admin_cabang') {
            abort(403);
        }
        $branches = Branch::all();
>>>>>>> a471717247185442b2b06268d4e157d25322f3c7
        return view('superadmin.users.edit', compact('user', 'branches'));
    }

    public function update(Request $request, User $user)
    {
<<<<<<< HEAD
        if ($user->isSuperAdmin()) {
            abort(403, 'Tidak dapat mengedit akun Super Admin.');
        }

        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'username'  => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'password'  => 'nullable|string|min:6|confirmed',
            'role'      => 'required|in:admin_cabang,kasir,koki',
            'branch_id' => 'required|exists:branches,id',
        ]);

        $data = [
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'username'  => $validated['username'],
            'role'      => $validated['role'],
            'branch_id' => $validated['branch_id'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Akun "' . $user->name . '" berhasil diperbarui.');
=======
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
>>>>>>> a471717247185442b2b06268d4e157d25322f3c7
    }

    public function destroy(User $user)
    {
<<<<<<< HEAD
        if ($user->isSuperAdmin()) {
            abort(403, 'Tidak dapat menghapus akun Super Admin.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('superadmin.users.index')
            ->with('success', 'Akun "' . $name . '" berhasil dihapus.');
=======
        if ($user->role !== 'admin_cabang') {
            abort(403);
        }

        $user->delete();

        return redirect()->route('superadmin.users.index')->with('success', 'Akun Admin Cabang berhasil dihapus.');
>>>>>>> a471717247185442b2b06268d4e157d25322f3c7
    }
}
