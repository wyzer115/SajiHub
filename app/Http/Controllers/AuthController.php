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

        $request->session()->forget('url.intended');
        $request->session()->regenerate();
        return redirect($this->redirectBasedOnRole($user));
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
