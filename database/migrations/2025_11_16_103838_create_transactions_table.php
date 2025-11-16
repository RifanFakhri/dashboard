<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('transactions', function (Blueprint $table) {
        $table->id();
        $table->string('order_id')->unique(); // ID Transaksi Midtrans
        $table->string('user_name'); // Nama User (Relasi sederhana by nama/email)
        $table->string('user_email');
        $table->string('wisata_name'); // Nama Tempat Wisata
        $table->date('visit_date');    // Tanggal Berkunjung
        $table->integer('total_tickets'); // Jumlah Tiket
        $table->decimal('total_price', 15, 2); // Total Harga
        $table->string('status')->default('pending'); // Status: pending, success, failed
        $table->string('snap_token')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
