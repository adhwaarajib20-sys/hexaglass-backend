<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SesiAntrean extends Model
{
    use HasFactory;

    protected $table = 'sesi_antrean';

    protected $fillable = [
        'satpam_id',
        'qr_code',
        'qr_token',
        'status',
        'expired_at',
        'kendaraan_id',
        'antrean_id',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    // Cek apakah QR masih berlaku
    public function isValid(): bool
    {
        return $this->status === 'aktif' && $this->expired_at->isFuture();
    }

    // Relasi
    public function satpam()
    {
        return $this->belongsTo(User::class, 'satpam_id');
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function antrean()
    {
        return $this->belongsTo(Antrean::class);
    }
}