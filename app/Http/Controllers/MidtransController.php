<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Order;
use Midtrans\Config;
use Midtrans\Notification;

class MidtransController extends Controller
{
    public function callback(Request $request)
    {
        // 1. Konfigurasi Standar
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = false;
        // HAPUS BARIS isVerifyPeer karena ini penyebab Error 500!

        try {
            $notification = new Notification();

            $order_id = $notification->order_id;
            $transaction_status = $notification->transaction_status;

            Log::info("Webhook Masuk: $order_id - Status: $transaction_status");

            $order = Order::where('order_id', $order_id)->first();

            if (!$order) {
                return response()->json(['message' => 'Order tidak ditemukan'], 404);
            }

            // 2. Logika Update yang Pasti Jalan
            if ($transaction_status == 'settlement' || $transaction_status == 'capture') {
                $order->update(['status' => 'success']);
                Log::info("DATABASE BERHASIL DIUPDATE: Order $order_id sekarang SUCCESS.");
            }

            return response()->json(['message' => 'OK']);
        } catch (\Exception $e) {
            Log::error("Webhook Crash: " . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
