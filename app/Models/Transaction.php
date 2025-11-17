<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User; // <-- TAMBAHKAN IMPORT User

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'user_name',
        'user_email',
        'wisata_name',
        'visit_date',
        'total_tickets',
        'total_price',
        'status',
        'snap_token'
    ];

   public function user()
    {
        // Ganti 'user_id' menjadi nama kolom Anda yang sebenarnya
        return $this->belongsTo(User::class, 'id_user'); 
    }
}