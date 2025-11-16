<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Transaction; // <--- INI PENTING UNTUK MEMPERBAIKI ERROR 500

class MidtransController extends Controller
{
    public function createTransaction(Request $request)
    {
        // 1. Setup Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // 2. Buat Order ID Unik
        $orderId = 'TRX-' . time() . '-' . rand(100, 999);

        // 3. SIMPAN DATA KE DATABASE (Status Awal: Pending)
        try {
            $transaction = Transaction::create([
                'order_id'      => $orderId,
                'user_name'     => $request->first_name,
                'user_email'    => $request->email,
                'wisata_name'   => $request->wisata_name,
                'visit_date'    => $request->visit_date,
                'total_tickets' => $request->quantity,
                'total_price'   => $request->gross_amount,
                'status'        => 'pending', // Status awal
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menyimpan transaksi: ' . $e->getMessage()
            ], 500);
        }

        // 4. Siapkan Parameter Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->gross_amount,
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'email'      => $request->email,
            ],
            'item_details' => [
                [
                    'id'       => 'TIKET-WISATA',
                    'price'    => (int) ($request->gross_amount / $request->quantity),
                    'quantity' => (int) $request->quantity,
                    'name'     => "Tiket " . substr($request->wisata_name, 0, 40),
                ]
            ]
        ];

        try {
            // 5. Minta Snap Token
            $snapToken = Snap::getSnapToken($params);
            
            // Simpan snap_token ke database
            $transaction->update(['snap_token' => $snapToken]);

            return response()->json([
                'status' => 'success',
                'redirect_url' => "https://app.sandbox.midtrans.com/snap/v2/vtweb/$snapToken",
                'token' => $snapToken
            ]);

        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Midtrans Error: ' . $e->getMessage()], 500);
        }
    }

    public function notificationHandler(Request $request)
    {
        // Konfigurasi ulang untuk validasi notifikasi
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');

        try {
            $notif = new \Midtrans\Notification();

            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;

            // 1. Cari Transaksi di Database
            $dataTransaction = Transaction::where('order_id', $order_id)->first();

            if (!$dataTransaction) {
                return response()->json(['message' => 'Transaction not found'], 404);
            }

            // 2. Update Status Berdasarkan Respon Midtrans
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'challenge') {
                        $dataTransaction->update(['status' => 'challenge']);
                    } else {
                        $dataTransaction->update(['status' => 'success']);
                    }
                }
            } else if ($transaction == 'settlement') {
                // Pembayaran Berhasil
                $dataTransaction->update(['status' => 'success']);
                
            } else if ($transaction == 'pending') {
                // Menunggu Pembayaran
                $dataTransaction->update(['status' => 'pending']);
                
            } else if ($transaction == 'deny') {
                // Ditolak
                $dataTransaction->update(['status' => 'failed']);
                
            } else if ($transaction == 'expire') {
                // Kadaluarsa
                $dataTransaction->update(['status' => 'expired']);
                
            } else if ($transaction == 'cancel') {
                // Dibatalkan
                $dataTransaction->update(['status' => 'canceled']);
            }

            return response()->json(['message' => 'Notification processed']);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Error: ' . $e->getMessage()], 500);
        }
    }
}