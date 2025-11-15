<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash; // <-- Import Hash
use Illuminate\Validation\Rule;       // <-- Import Rule

class UserController extends Controller
{
    /**
     * Menampilkan halaman daftar pengguna.
     */
    public function index()
    {
        // Ambil semua user, urutkan berdasarkan nama
        // Kita tetap select() agar password tidak ikut terambil ke controller
        $users = User::select('id', 'username', 'nama_lengkap', 'no_wa', 'role')
                     ->orderBy('nama_lengkap')
                     ->get();

        // Menggunakan nama view dari file pertama Anda
        return view('pages.data-user', compact('users')); 
    }

    /**
     * Menyimpan pengguna baru.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'nama_lengkap' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => 'required|string|min:8|confirmed', // 'confirmed' akan cek 'password_confirmation'
        ]);

        // HASH PASSWORD sebelum disimpan
        $validatedData['password'] = Hash::make($validatedData['password']);

        User::create($validatedData);

        return redirect()->route('pages.data-user')->with('success', 'User baru berhasil ditambahkan.');
    }

    /**
     * Mengupdate data pengguna.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id), // Abaikan unique check untuk user ini
            ],
            'nama_lengkap' => 'required|string|max:255',
            'no_wa' => 'required|string|max:20',
            'role' => ['required', Rule::in(['admin', 'user'])],
            'password' => 'nullable|string|min:8|confirmed', // Password boleh kosong (nullable)
        ]);

        // --- Logika Update Password ---
        // 1. Cek jika field password diisi
        if ($request->filled('password')) {
            // 2. Jika diisi, hash password baru
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            // 3. Jika kosong, hapus 'password' dari array agar tidak meng-update password lama
            unset($validatedData['password']);
        }
        // ------------------------------

        $user->update($validatedData);

        return redirect()->route('pages.data-user')->with('success', 'Data user berhasil diperbarui.');
    }

    /**
     * Menghapus data pengguna.
     */
    public function destroy(User $user)
    {
        // Opsi: Tambahkan logika untuk mencegah user menghapus diri sendiri
        if (auth()->id() == $user->id) {
            return redirect()->route('users.index')
                ->withErrors(['error' => 'Gagal! Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $user->delete();
        
        return redirect()->route('pages.data-user')->with('success', 'Data user berhasil dihapus.');
    }
}