<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BorrowerProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'alamat',
        'tanggal_lahir',
        'tempat_lahir',
        'pekerjaan',
        'foto_ktp',
        'foto_kk',
        'foto_diri',
        'jenis_simpanan',
        'tabungan_pokok',
        'status',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
