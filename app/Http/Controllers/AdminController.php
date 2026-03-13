<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index(Request $request) // Tambahkan Request $request di sini
    {
        // Ambil kata kunci pencarian dari URL (misal: ?search=rocky)
        $search = $request->input('search');

        // 1. Data Ringkasan (Tetap sama)
        $totalOmzet = Order::where('status', 'success')->sum('gross_amount');
        $totalSiswa = User::where('role', 'siswa')->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        // 2. Riwayat Transaksi Terbaru (Tetap sama)
        $latestTransactions = Order::with('user')->latest()->take(10)->get();

        // 3. SEMUA SISWA dengan Fitur Search
        $allUsers = User::where('role', 'siswa')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->get();

        // 4. SISWA BELUM BAYAR (Tetap sama)
        $unpaidStudents = User::where('role', 'siswa')
            ->whereDoesntHave('orders', function ($query) {
                $query->where('status', 'success');
            })
            ->latest()
            ->get();

        return view('admin.dashboard', compact(
            'totalOmzet',
            'totalSiswa',
            'pendingOrders',
            'latestTransactions',
            'allUsers',
            'unpaidStudents'
        ));
    }

    public function confirmPayment(Order $order)
    {
        $order->update([
            'status' => 'success'
        ]);

        return back()->with('success', 'Pembayaran siswa ' . $order->user->name . ' berhasil dikonfirmasi manual!');
    }

    public function destroyUser(User $user)
    {
        // Hapus dulu orderannya agar tidak error (Integrity Constraint)
        $user->orders()->delete();

        // Baru hapus usernya
        $user->delete();

        return back()->with('success', 'Siswa berhasil dihapus dari sistem.');
    }
}
