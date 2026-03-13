<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Midtrans\Snap;
use Midtrans\Config;

class OrderController extends Controller
{
    // A. Hanya menampilkan Dashboard
    public function index()
    {
        $user = auth()->user();

        // Pastikan user sudah login (lebih baik jika menggunakan middleware 'auth')
        if (!$user) {
            return redirect()->route('login');
        }

        if ($user->role == 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // PERBAIKAN: Ambil order TERBARU tanpa memfilter statusnya
        // Dengan 'latest()', kita mengambil transaksi terakhir yang dilakukan siswa
        $order = Order::where('user_id', $user->id)
            ->latest()
            ->first();

        return view('dashboard', [
            'order' => $order
        ]);
    }

    // B. Jembatan Baru: Proses Checkout saat tombol diklik
    public function checkout(Request $request)
    {
        $user = auth()->user();

        // 1. PROTEKSI: Cek apakah user sudah punya akses (status success)
        $alreadyActive = Order::where('user_id', $user->id)->where('status', 'success')->exists();
        if ($alreadyActive) {
            return response()->json(['message' => 'Akun kamu sudah aktif!'], 403);
        }

        // 2. Cari order pending atau buat baru (Reuse Logic)
        $order = Order::where('user_id', $user->id)
            ->where('status', 'pending')
            ->first();

        if (!$order) {
            $order = Order::create([
                'user_id' => $user->id,
                'order_id' => 'AMBIS-' . time() . '-' . $user->id,
                'gross_amount' => 5000000,
                'status' => 'pending',
            ]);
        }

        // 3. Konfigurasi Midtrans
        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $order->order_id,
                'gross_amount' => 5000000,
            ],
            'customer_details' => [
                'first_name' => $user->name,
                'email' => $user->email,
            ],
            'item_details' => [
                [
                    'id'       => 'PROD-01',
                    'price'    => 5000000,
                    'quantity' => 1,
                    'name'     => 'Akses Elite AmbisQuest PTN 2026',
                ]
            ],
        ];

        try {
            // 4. Ambil Token dengan Try-Catch agar tidak gampang crash
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            return response()->json([
                'snapToken' => $snapToken,
                'order_id' => $order->order_id
            ]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Gagal terhubung ke pembayaran: ' . $e->getMessage()], 500);
        }
    }

    public function cancelOrder()
    {
        // Kita TIDAK MENGHAPUS order di sini. 
        // Kita biarkan statusnya tetap PENDING di database.
        // Ini agar jika siswa ternyata SUDAH transfer lewat VA sebelumnya, Webhook tetap bisa menemukan datanya.

        return back()->with('info', 'Silakan pilih kembali metode pembayaran yang kamu inginkan.');
    }
}
