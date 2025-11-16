<?php

return [
    'merchant_id'   => env('MIDTRANS_MERCHANT_ID'),
    'server_key'    => env('MIDTRANS_SERVER_KEY'),
    'client_key'    => env('MIDTRANS_CLIENT_KEY'), // <-- TAMBAHKAN BARIS INI

    'is_production' => env('MIDTRANS_IS_PRODUCTION', false), // <-- Lebih baik ambil dari .env juga
    'is_sanitized'  => true,
    'is_3ds'        => true,
];