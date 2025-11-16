<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}