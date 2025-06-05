<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanApplication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_pinjaman',
        'jumlah_pinjaman',
        'durasi_bulan',
        'sisa_durasi_pinjaman',
        'status',
        'petugas_id',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function petugas()
    {
        return $this->belongsTo(User::class, 'petugas_id');
    }

    public function payments()
    {
        return $this->hasMany(LoanPayment::class);
    }
}
