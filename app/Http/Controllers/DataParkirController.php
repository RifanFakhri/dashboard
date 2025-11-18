<?php

namespace App\Http\Controllers;

use App\Models\ParkirBooking;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class DataParkirController extends Controller
{
    public function index(Request $request)
    {
        // --- 1. LOGIKA KARTU STATISTIK (BARU) ---
        // Hitung Total Uang (Hanya yang sukses)
        $totalPendapatan = ParkirBooking::whereIn('status', ['success', 'sukses'])->sum('total_harga');

        // Hitung Jumlah Orang Sukses
        $totalSukses = ParkirBooking::whereIn('status', ['success', 'sukses'])->count();

        // Hitung Jumlah Pending
        $totalPending = ParkirBooking::where('status', 'pending')->count();

        // Hitung Jumlah Batal
        $totalBatal = ParkirBooking::whereIn('status', ['canceled', 'batal'])->count();


        // --- 2. LOGIKA FILTER & PAGINATION (SEPERTI SEBELUMNYA) ---
        $parkingTypes = ParkirBooking::select('parking_type')->distinct()->pluck('parking_type');
        $query = ParkirBooking::with('user');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('order_id', 'like', '%' . $search . '%')
                  ->orWhere('plat_nomor', 'like', '%' . $search . '%');
                $q->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('username', 'like', '%' . $search . '%');
                });
            });
        }

        if ($request->filled('parking_type')) {
            $query->where('parking_type', $request->input('parking_type'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        if ($request->filled('tgl_awal') && $request->filled('tgl_akhir')) {
            $query->whereBetween('tanggal_booking', [$request->input('tgl_awal'), $request->input('tgl_akhir')]);
        }

        $parkirBookings = $query->orderBy('created_at', 'desc')->paginate(6)->withQueryString(); 

        // --- 3. KIRIM SEMUA DATA KE VIEW ---
        return view('pages.data-parkir', compact(
            'parkirBookings', 
            'parkingTypes',
            'totalPendapatan', // Variable baru
            'totalSukses',     // Variable baru
            'totalPending',    // Variable baru
            'totalBatal'       // Variable baru
        ));
    }

    // ... (Function update dan destroy biarkan tetap sama)
    public function update(Request $request, ParkirBooking $parkirBooking) 
    {
        $validatedData = $request->validate([
            'tanggal_booking' => 'required|date',
            'jumlah' => 'required|integer|min:1',
            'plat_nomor' => 'required|string|max:20', 
            'status' => ['required', Rule::in(['pending', 'success', 'sukses', 'canceled', 'batal'])],
        ]);
        $parkirBooking->update($validatedData);
        return redirect()->route('parkir.index')->with('success', 'Data parkir berhasil diperbarui.');
    }

    public function destroy(ParkirBooking $parkirBooking)
    {
        $parkirBooking->delete();
        return redirect()->route('parkir.index')->with('success', 'Data parkir berhasil dihapus.');
    }
}