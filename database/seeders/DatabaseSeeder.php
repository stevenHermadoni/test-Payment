<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. BUAT AKUN ADMIN (Utama)
        $admin = User::create([
            'name' => 'Steve Admin',
            'email' => 'steve@gmail.com',
            'password' => Hash::make('admin1234'),
            'role' => 'admin',
        ]);

        // 2. BUAT BEBERAPA AKUN SISWA CONTOH
        $siswa1 = User::create([
            'name' => 'Debruyne',
            'email' => 'debruyne@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
        ]);

        $siswa2 = User::create([
            'name' => 'Erling Haaland',
            'email' => 'haaland@gmail.com',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
        ]);

        // 3. BUAT RIWAYAT TRANSAKSI (Agar Dashboard Admin Tidak Kosong)
        Order::create([
            'user_id' => $siswa1->id,
            'order_id' => 'AMBIS-TEST-001',
            'gross_amount' => 5000000,
            'status' => 'success',
        ]);

        Order::create([
            'user_id' => $siswa2->id,
            'order_id' => 'AMBIS-TEST-002',
            'gross_amount' => 5000000,
            'status' => 'pending',
        ]);

        $this->command->info('Data Admin, Siswa, dan Transaksi berhasil dibuat!');
    }
}
