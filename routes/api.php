<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User; 
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\ParkirBookingController;

Route::prefix('dolanbanyumas')->group(function () {

    // Rute Publik (Register/Login)
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    
    // Rute Pembuatan Transaksi (Bisa juga diproteksi)
    Route::post('/midtrans/transaction', [MidtransController::class, 'createTransaction']);
    Route::post('/midtrans/booking-parkir', [ParkirBookingController::class, 'createBooking']);
    
    // Rute Notifikasi (HARUS PUBLIK agar bisa diakses Midtrans)
    Route::post('/midtrans/notification', [MidtransController::class, 'notificationHandler']);

    // --- RUTE YANG DIPROTEKSI ---
    // Semua rute di dalam grup ini memerlukan Token otentikasi
    Route::middleware('auth:sanctum')->group(function () {
        
        // Rute GET untuk riwayat (Sekarang aman)
        Route::get('/midtrans/transactions', [MidtransController::class, 'getTransactions']);
        Route::get('/midtrans/booking-parkir', [ParkirBookingController::class, 'getAllBookings']);

        // Anda bisa pindahkan rute GET user ke sini juga
        Route::get('/users', function () {
            $users = User::where('role', 'user')->get();
            $users->makeHidden('password'); 
            return response()->json($users);
        });
    });
});