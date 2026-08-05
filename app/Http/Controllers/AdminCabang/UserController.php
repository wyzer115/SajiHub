<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $branchId = auth()->user()->branch_id;
        $users = User::where('branch_id', $branchId)
            ->whereIn('role', ['kasir', 'koki'])
            ->get();
            
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username|alpha_dash',
            'password' => 'required|string|min:6',
            'role' => 'required|string|in:kasir,koki',
        ]);

        $validated['branch_id'] = auth()->user()->branch_id;
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.users.index')->with('success', 'Akun Karyawan berhasil dibuat.');
    }

    public function edit(User $user)
    {
        if ($user->branch_id !== auth()->user()->branch_id || !in_array($user->role, ['kasir', 'koki'])) {
            abort(403);
        }
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->branch_id !== auth()->user()->branch_id || !in_array($user->role, ['kasir', 'koki'])) {
            abort(403);
        }

        $validated = $request->validate([
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
            'role' => 'required|string|in:kasir,koki',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Akun Karyawan berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->branch_id !== auth()->user()->branch_id || !in_array($user->role, ['kasir', 'koki'])) {
            abort(403);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Akun Karyawan berhasil dihapus.');
    }
}
