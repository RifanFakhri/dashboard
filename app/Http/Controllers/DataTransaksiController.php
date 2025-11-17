<?php

namespace App\Http\Controllers;

use App\Models\Transaction; // Pastikan ini model Transaksi Anda
use App\Models\User; // Pastikan ini model User Anda
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DataTransaksiController extends Controller
{
    /**
     * Menampilkan halaman daftar transaksi dengan search dan pagination.
     */
    public function index(Request $request)
    {
        // --- PERUBAHAN: Kita HANYA load relasi 'user' ---
        $query = Transaction::with('user'); // Asumsi relasi user() ada di model Transaction

        // --- Logika Search (Termasuk relasi) ---
        if ($request->filled('search')) {
            $search = $request->input('search');
            
            $query->where(function($q) use ($search) {
                // Cari di tabel transactions (order_id DAN wisata_name)
                $q->where('order_id', 'like', '%' . $search . '%')
                  ->orWhere('wisata_name', 'like', '%' . $search . '%'); // <-- PERUBAHAN DI SINI
                  
                // Cari di relasi user (user_name)
                $q->orWhereHas('user', function($userQuery) use ($search) {
                    // SESUAIKAN: 'name' dengan kolom nama di tabel users
                    $userQuery->where('name', 'like', '%' . $search . '%');
                });
            });
        }

        // --- Pagination ---
        $transactions = $query->orderBy('created_at', 'desc')
                              ->paginate(6)
                              ->withQueryString(); 

        return view('pages.data-transaksi', compact('transactions'));
    }

    /**
     * Mengupdate data transaksi.
     */
    public function update(Request $request, Transaction $transaction) 
    {
        // Validasi ini masih sama, karena 'wisata_name' mungkin tidak untuk diedit
        $validatedData = $request->validate([
            'visit_date' => 'required|date',
            'total_tickets' => 'required|integer|min:1',
            'status' => [
                'required',
                Rule::in(['pending', 'sukses', 'batal']), 
            ],
            // Jika wisata_name juga BISA diedit, tambahkan di sini:
            // 'wisata_name' => 'required|string|max:255',
        ]);

        $transaction->update($validatedData);

        return redirect()->route('transaksi.index')->with('success', 'Data transaksi berhasil diperbarui.');
    }

    /**
     * Menghapus data transaksi.
     */
    public function destroy(Transaction $transaction)
    {
        $transaction->delete();

        return redirect()->route('transaksi.index')->with('success', 'Data transaksi berhasil dihapus.');
    }
}