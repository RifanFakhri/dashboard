<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parkir_bookings', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique(); // ID Pesanan unik (Contoh: PARK-12345)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('parking_type'); // Nama parkir, e.g., 'Parkir Roda 2 (dua)'
            $table->string('plat_nomor');
            $table->integer('jumlah');
            $table->decimal('total_harga', 15, 2);
            $table->date('tanggal_booking');
            $table->string('status')->default('pending'); // pending, success, failed
            $table->string('midtrans_token')->nullable();
            $table->string('midtrans_url')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parkir_bookings');
    }
};