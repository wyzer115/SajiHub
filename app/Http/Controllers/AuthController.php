<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect($this->redirectBasedOnRole(Auth::user()));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginInput = trim($request->input('login'));
        $loginType = filter_var($loginInput, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if ($loginType === 'username') {
            $aliases = [
                'admin_jkt'      => 'admin_jakarta',
                'admin_bdg'      => 'admin_bandung',
                'admin_sby'      => 'admin_surabaya',
                'owner_jakarta'  => 'owner',
                'owner_bandung'  => 'owner',
                'owner_surabaya' => 'owner',
                'owner_jkt'      => 'owner',
                'owner_bdg'      => 'owner',
                'owner_sby'      => 'owner',
                'spv_jakarta'    => 'spv_jkt',
                'spv_bandung'    => 'spv_bdg',
                'spv_surabaya'   => 'spv_sby',
                'kasir_jakarta'  => 'kasir_jkt',
                'kasir_bandung'  => 'kasir_bdg',
                'kasir_surabaya' => 'kasir_sby',
                'koki_jakarta'   => 'koki_jkt',
                'koki_bandung'   => 'koki_bdg',
                'koki_surabaya'  => 'koki_sby',
                'dapur_jkt'      => 'koki_jkt',
                'dapur_bdg'      => 'koki_bdg',
                'dapur_sby'      => 'koki_sby',
            ];
            $loginInput = $aliases[$loginInput] ?? $loginInput;
        }

        $credentials = [
            $loginType => $loginInput,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended($this->redirectBasedOnRole(Auth::user()));
        }

        return back()->withErrors([
            'login' => 'Kredensial yang Anda masukkan tidak cocok.',
        ])->onlyInput('login');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect($this->redirectBasedOnRole(Auth::user()));
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $loginInput = $request->input('username_or_email');
        $isEmail = filter_var($loginInput, FILTER_VALIDATE_EMAIL);

        if ($isEmail) {
            $request->merge([
                'email' => $loginInput,
                'username' => explode('@', $loginInput)[0],
            ]);
        } else {
            $request->merge([
                'username' => $loginInput,
                'email' => $loginInput . '@sajihub.com',
            ]);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:customer,kasir,dapur,koki',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'role' => $request->role === 'koki' ? 'dapur' : $request->role,
        ]);

        Auth::login($user);

        return redirect()->route('landing')->with('success', 'Registrasi berhasil! Selamat bergabung di Waroeng SajiHUB.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    private function redirectBasedOnRole(User $user): string
    {
        return match ($user->role) {
            'superadmin'   => route('superadmin.dashboard'),
            'admin_cabang' => route('admin.dashboard'),
            'owner'        => route('owner.dashboard'),
            'supervisor'   => route('supervisor.inventory.index'),
            'kasir'        => route('kasir.orders.index'),
            'dapur', 'koki'=> route('koki.kitchen'),
            default        => route('pesan'),
        };
    }
}
