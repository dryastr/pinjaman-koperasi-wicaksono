<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanPayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_application_id',
        'user_id',
        'jumlah_dibayar',
        'tanggal_pembayaran',
        'metode_pembayaran',
        'bukti_pembayaran',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
    ];

    public function loanApplication()
    {
        return $this->belongsTo(LoanApplication::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
