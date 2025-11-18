<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DataTransaksiController extends Controller
{
    /**
     * Menampilkan halaman daftar transaksi dengan filter lengkap & dashboard summary.
     */
    public function index(Request $request)
    {
        // --- 1. LOGIKA KARTU STATISTIK (BARU) ---
        
        // Hitung Total Uang (Hanya yang sukses)
        // Asumsi kolom harga adalah 'total_price', sesuaikan jika beda
        $totalPendapatan = Transaction::whereIn('status', ['success', 'sukses'])->sum('total_price');

        // Hitung Jumlah Transaksi Sukses
        $totalSukses = Transaction::whereIn('status', ['success', 'sukses'])->count();

        // Hitung Jumlah Pending
        $totalPending = Transaction::where('status', 'pending')->count();

        // Hitung Jumlah Batal
        $totalBatal = Transaction::whereIn('status', ['canceled', 'batal'])->count();


        // --- 2. LOGIKA FILTER & PAGINATION ---
        $wisataList = Transaction::select('wisata_name')
                        ->distinct()
                        ->pluck('wisata_name');

        $query = Transaction::query();

        // Filter Search
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('user_name', 'like', '%' . $search . '%')
                  ->orWhere('order_id', 'like', '%' . $search . '%');
            });
        }

        // Filter Wisata
        if ($request->filled('wisata_name')) {
            $query->where('wisata_name', $request->input('wisata_name'));
        }

        // Filter Status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Filter Tanggal
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->whereBetween('visit_date', [
                $request->input('tgl_awal'), 
                $request->input('tgl_akhir')
            ]);
        }

        $transactions = $query->orderBy('created_at', 'desc')
                              ->paginate(6)
                              ->withQueryString();

        // --- 3. KIRIM SEMUA DATA KE VIEW ---
        return view('pages.data-transaksi', compact(
            'transactions', 
            'wisataList',
            'totalPendapatan',
            'totalSukses',
            'totalPending',
            'totalBatal'
        ));
    }

    /**
     * Mengupdate data transaksi.
     */
    public function update(Request $request, Transaction $transaction) 
    {
        $validatedData = $request->validate([
            'visit_date' => 'required|date',
            'total_tickets' => 'required|integer|min:1',
            'status' => [
                'required', 
                // Izinkan variasi status Inggris & Indo
                Rule::in(['pending', 'sukses', 'success', 'batal', 'canceled'])
            ],
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