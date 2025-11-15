<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect('/dashboard');
});

Route::get('/dashboard', function () {
    return view('pages.home_screen');
})->name('dashboard');

// ROUTES USER
Route::get('/users', [UserController::class, 'index'])->name('pages.data-user'); // <-- PENTING
Route::post('/users', [UserController::class, 'store'])->name('users.store');
Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

// ROUTES ADMIN
Route::get('/admins', [AdminController::class, 'index'])->name('pages.data-admin'); // <-- PENTING
Route::post('/admins', [AdminController::class, 'store'])->name('admins.store');
Route::put('/admins/{admin}', [AdminController::class, 'update'])->name('admins.update');
Route::delete('/admins/{admin}', [AdminController::class, 'destroy'])->name('admins.destroy');  

