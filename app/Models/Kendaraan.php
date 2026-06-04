<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraan';

    protected $fillable = [
        'user_id',
        'nama_supir',
        'no_hp_supir',
        'nomor_polisi',
        'jenis_kendaraan',
        'kapasitas_tangki',
        'perusahaan',
        'status_validasi',
        'is_perusahaan_vip',
    ];

    // Relasi ke User (Supir)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Antrean
    public function antrean()
    {
        return $this->hasMany(Antrean::class);
    }
}