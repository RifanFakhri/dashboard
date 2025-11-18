<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\ParkirBooking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // --- 1. DATA KARTU STATISTIK (GABUNGAN 2 TABLE) ---

        // Total Transaksi (Semua data)
        $totalTransaksi = Transaction::count() + ParkirBooking::count();

        // Total Terbayar (Status Success/Sukses)
        $totalSukses = Transaction::whereIn('status', ['success', 'sukses'])->count() 
                     + ParkirBooking::whereIn('status', ['success', 'sukses'])->count();

        // Total Pending (Status Pending)
        $totalPending = Transaction::where('status', 'pending')->count() 
                      + ParkirBooking::where('status', 'pending')->count();

        // Total Batal (Status Canceled/Batal)
        $totalBatal = Transaction::whereIn('status', ['canceled', 'batal'])->count() 
                    + ParkirBooking::whereIn('status', ['canceled', 'batal'])->count();


        // --- 2. DATA TABEL TERBARU (LIMIT 4) ---

        // Ambil 4 Transaksi Wisata Terbaru
        $latestTransactions = Transaction::latest()->take(4)->get();

        // Ambil 4 Booking Parkir Terbaru
        $latestParkings = ParkirBooking::with('user')->latest()->take(4)->get();

        return view('pages.dashboard', compact(
            'totalTransaksi',
            'totalSukses',
            'totalPending',
            'totalBatal',
            'latestTransactions',
            'latestParkings'
        ));
    }
}