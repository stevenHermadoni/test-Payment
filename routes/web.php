<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

/* |-------------------------------------------------------------------------- | Web Routes |-------------------------------------------------------------------------- | | Here is where you can register web routes for your application. These | routes are loaded by the RouteServiceProvider within a group which | contains the "web" middleware group. Now create something great! | */

Route::get('/', [OrderController::class, 'index'])->name('pembayaran');
Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout')->middleware('auth');
Route::post('/order/cancel', [OrderController::class, 'cancelOrder'])->name('order.cancel')->middleware('auth');

// Rute ini hanya bisa diakses jika: 1. Login (auth), 2. Admin (is_admin)
Route::middleware(['auth', 'is_admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    // TAMBAHKAN INI:
    Route::post('/orders/{order}/confirm', [AdminController::class, 'confirmPayment'])->name('admin.orders.confirm');
    Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});

// Pastikan ini ada agar alamat /ujian/mulai/ bisa diakses
Route::get('/ujian/mulai/{order_id}', function ($order_id) {
    // 1. Cari data order berdasarkan order_id
    $order = \App\Models\Order::where('order_id', $order_id)
        ->where('user_id', auth()->id()) // Tambahan keamanan: pastikan ini order milik si user
        ->first();

    // 2. CEK STATUS: Jika tidak ada atau belum sukses, kembalikan ke dashboard
    if (!$order || $order->status !== 'success') {
        return redirect()->route('dashboard')->with('error', 'Akses ditolak! Silakan selesaikan pembayaran terlebih dahulu.');
    }

    // 3. Jika sudah sukses, tampilkan halaman soal
    return view('ujian_soal', ['id' => $order_id]);
})->middleware('auth');

require __DIR__ . '/auth.php';

Route::get('/dashboard', [OrderController::class, 'index'])
    ->middleware(['auth'])
    ->name('dashboard');

// Rute sementara untuk migrasi database di Vercel
Route::get('/init-db', function () {
    try {
        // Menjalankan migration dan seeder
        Artisan::call('migrate:fresh --seed');

        return "<h1>✅ Database Sukses Dimigrasi!</h1><p>Semua tabel dan data awal sudah masuk ke Postgres.</p>";
    } catch (\Exception $e) {
        // Jika ada error, tampilkan pesannya
        return "<h1>❌ Gagal Migrasi:</h1><pre>" . $e->getMessage() . "</pre>";
    }
});
