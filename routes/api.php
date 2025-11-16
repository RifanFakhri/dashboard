<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User; // <-- TAMBAHKAN IMPORT User
use App\Http\Controllers\MidtransController;

Route::prefix('dolanbanyumas')->group(function () {

    // TEST API
    Route::get('/test', function () {
        return response()->json(['message' => 'API sudah aktif!']);
    });

    // REGISTER
    Route::post('/register', [AuthController::class, 'register']);

    // LOGIN
    Route::post('/login', [AuthController::class, 'login']);
    
    // GET ALL USERS (SESUAI PERMINTAAN ANDA)
    Route::get('/users', function () {
        
        // 1. Ambil data user yang rolenya 'user'
        $users = User::where('role', 'user')->get();
        
        // 2. Sembunyikan atribut 'password' dari semua koleksi user
        //    Ini tidak menghapus dari database, hanya dari respons JSON
        $users->makeHidden('password'); 

        return response()->json($users);
    });

    // MIDTRANS TRANSACTION
    Route::post('/midtrans/transaction', [MidtransController::class, 'createTransaction']);

    // MIDTRANS NOTIFICATION
    Route::post('/midtrans/notification', [MidtransController::class, 'notificationHandler']);
});