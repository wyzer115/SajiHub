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
            if (\App\Models\SystemSetting::isMaintenanceMode() && !Auth::user()->isSuperAdmin()) {
                Auth::logout();
                session()->invalidate();
                session()->regenerateToken();
                return view('auth.login')->with('warning', 'Website sedang dalam mode pemeliharaan. Hanya Super Admin yang diizinkan masuk.');
            }
            return redirect($this->redirectBasedOnRole(Auth::user()));
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Mendukung input email (khusus email, tanpa username / 'atau')
        $email = trim($request->input('email', $request->input('login', '')));
        $password = $request->input('password', '');
        $request->merge(['email' => $email]);

        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required'    => 'Alamat email wajib diisi.',
            'email.email'       => 'Format email tidak valid. Masukkan alamat email yang benar.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // 1. Cek apakah email terdaftar di database
        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Alamat email tidak terdaftar dalam sistem.',
            ])->onlyInput('email');
        }

        // 2. Cek apakah password benar
        if (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            return back()->withErrors([
                'password' => 'Kata sandi yang Anda masukkan salah.',
            ])->onlyInput('email');
        }

        // 3. Autentikasi user
        Auth::login($user);

        if (\App\Models\SystemSetting::isMaintenanceMode() && !$user->isSuperAdmin()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return back()->withErrors([
                'email' => 'Sistem sedang dalam masa pemeliharaan (Maintenance Mode). Hanya Super Admin yang diizinkan masuk saat ini.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        return redirect()->intended($this->redirectBasedOnRole($user));
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
