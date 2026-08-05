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

        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        $credentials = [
            $loginType => $request->input('login'),
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'username' => 'required|string|max:255|unique:users|alpha_dash',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|string|in:member,pelanggan',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role,
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
            'superadmin' => route('superadmin.dashboard'),
            'admin_cabang' => route('admin.dashboard'),
            'kasir' => route('kasir.orders.index'),
            'koki' => route('koki.kitchen'),
            default => route('pesan'),
        };
    }
}
