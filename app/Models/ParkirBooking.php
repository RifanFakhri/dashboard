<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParkirBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_id',
        'parking_type',
        'plat_nomor',
        'jumlah',
        'total_harga',
        'tanggal_booking',
        'status',
        'midtrans_token',
        'midtrans_url',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}