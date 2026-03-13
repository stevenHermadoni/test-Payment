<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    // Tambahkan ini agar Laravel tahu kalau Order punya User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Opsional: Daftarkan kolom yang boleh diisi (mass assignment)
    protected $fillable = ['user_id', 'order_id', 'gross_amount', 'status'];
}
