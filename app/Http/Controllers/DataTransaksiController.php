<?php

namespace App\Http\Controllers;

use App\Models\Transaction; // <-- SESUAIKAN: Ganti jika nama model Anda berbeda
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DataTransaksiController extends Controller
{
    /**
     * Menampilkan halaman daftar transaksi dengan search dan pagination.
     */
    public function index(Request $request)
    {
        $query = Transaksi::query(); // Gunakan model Transaksi Anda

        // --- Logika Search ---
        // SESUAIKAN: Ganti 'kode_transaksi' dan 'nama_pelanggan' dengan kolom yang ingin Anda cari
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('kode_transaksi', 'like', '%' . $search . '%')
                  ->orWhere('nama_pelanggan', 'like', '%' . $search . '%');
            });
        }

        // --- Pagination ---
        // Ambil data, urutkan (misal: terbaru dulu), dan paginasi 6 per halaman
        $transaksis = $query->orderBy('created_at', 'desc')
                            ->paginate(6)
                            ->withQueryString(); // Agar pagination tetap membawa query search

        return view('pages.data-transaksi', compact('transaksis')); // Kirim data ke view
    }

    /**
     * Mengupdate data transaksi.
     * (Asumsi menggunakan Route-Model Binding)
     */
    public function update(Request $request, Transaksi $transaksi)
    {
        // --- Validasi Data ---
        // SESUAIKAN: Ganti dengan kolom dan aturan validasi Anda
        $validatedData = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
            'status' => [
                'required',
                Rule::in(['pending', 'sukses', 'batal']), // Contoh validasi status
            ],
        ]);

        $transaksi->update($validatedData);

        return redirect()->route('transaksi.index')->with('success', 'Data transaksi berhasil diperbarui.');
    }

    /**
     * Menghapus data transaksi.
     * (Asumsi menggunakan Route-Model Binding)
     */
    public function destroy(Transaksi $transaksi)
    {
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Data transaksi berhasil dihapus.');
    }
}