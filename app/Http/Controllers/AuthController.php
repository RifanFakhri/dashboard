<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // <--- Tambahkan
use Illuminate\Validation\ValidationException; // <--- Tambahkan

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'nama_lengkap' => 'required',
            'no_wa' => 'required',
            'password' => 'required|min:6',
            'password_confirmation' => 'required|same:password',
        ]);

        $user = User::create([
            'username' => $request->username,
            'nama_lengkap' => $request->nama_lengkap,
            'no_wa' => $request->no_wa,
            'password' => Hash::make($request->password),
            'role' => 'user', // <--- role default
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil!',
            'data' => $user
        ], 201);
    }
    public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // 2. Cari user berdasarkan username
        $user = User::where('username', $request->username)->first();

        // 3. Cek user dan password
        if (! $user || ! Hash::check($request->password, $user->password)) {
            // Jika salah, kirim pesan error
            return response()->json([
                'message' => 'Username atau password salah'
            ], 401); // 401 = Unauthorized
        }

        // 4. Jika berhasil, buat token
        $token = $user->createToken('auth_token_dolanbanyumas')->plainTextToken;

        // 5. Kirim respons yang BERHASIL
        // Ini adalah JSON yang diharapkan oleh kode Flutter kita
        return response()->json([
            'message' => 'Login Berhasil',
            'user' => $user, // Mengirim data user (termasuk 'nama_lengkap')
            'token' => $token, // Mengirim token
        ], 200); // 200 = OK
    }

}
