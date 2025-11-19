<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAdminController extends Controller
{
    /**
     * Menampilkan halaman login untuk admin.
     */
    public function showLoginForm()
    {
        return view('auth.login'); // Memanggil view Blade yang baru kita buat
    }

    /**
     * Menangani proses login dan otentikasi role 'admin'.
     */
    public function login(Request $request)
    {
        // 1. Validasi Input
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // 2. Coba Autentikasi
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // 3. Cek Role: Hanya izinkan jika role-nya 'admin'
            if ($user->role === 'admin') {
                $request->session()->regenerate();
                
                // Redirect ke halaman Dashboard Admin
                return redirect()->intended('dashboard')->with('success', 'Selamat datang, Admin!'); 
            }

            // Jika otentikasi berhasil tapi role bukan admin, logout dan beri pesan error.
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return back()->withErrors([
                'username' => 'Akses ditolak. Hanya Admin yang diizinkan.',
            ])->onlyInput('username');
        }

        // 4. Gagal Otentikasi
        return back()->withErrors([
            'username' => 'Username atau Password salah.',
        ])->onlyInput('username');
    }

    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda berhasil logout.');
    }
}