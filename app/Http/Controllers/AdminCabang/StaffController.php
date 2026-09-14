<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class StaffController extends Controller
{
    private function branchId(): int
    {
        return auth()->user()->branch_id;
    }

    public function index()
    {
        $staff = User::where('branch_id', $this->branchId())
            ->whereIn('role', ['owner', 'supervisor', 'kasir', 'dapur', 'koki'])
            ->latest()
            ->get();

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        return view('admin.staff.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'username' => 'required|string|max:255|unique:users,username|alpha_dash',
            'password' => 'required|string|min:6|confirmed',
            'role'     => 'required|in:owner,supervisor,kasir,dapur,koki',
        ]);

        User::create([
            'name'      => $validated['name'],
            'email'     => $validated['email'],
            'username'  => $validated['username'],
            'password'  => Hash::make($validated['password']),
            'role'      => $validated['role'] === 'koki' ? 'dapur' : $validated['role'],
            'branch_id' => $this->branchId(),
        ]);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Akun staff "' . $validated['name'] . '" berhasil dibuat.');
    }

    public function edit(User $user)
    {
        if ($user->branch_id !== $this->branchId()) {
            abort(403, 'Tidak dapat mengedit staff dari cabang lain.');
        }

        return view('admin.staff.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->branch_id !== $this->branchId()) {
            abort(403, 'Tidak dapat mengedit staff dari cabang lain.');
        }

        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:6|confirmed',
            'role'     => 'required|in:owner,supervisor,kasir,dapur,koki',
        ]);

        $data = [
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'username' => $validated['username'],
            'role'     => $validated['role'] === 'koki' ? 'dapur' : $validated['role'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $user->update($data);

        return redirect()->route('admin.staff.index')
            ->with('success', 'Akun staff "' . $user->name . '" berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        if ($user->branch_id !== $this->branchId()) {
            abort(403, 'Tidak dapat menghapus staff dari cabang lain.');
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.staff.index')
            ->with('success', 'Akun staff "' . $name . '" berhasil dihapus.');
    }
}
