<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanPengisian extends Model
{
    use HasFactory;

    protected $table = 'laporan_pengisian';

    protected $fillable = [
        'antrean_id',
        'kendaraan_id',
        'operator_id',
        'tanggal',
        'jumlah_gas_liter',
        'durasi_menit',
        'estimasi_menit',
        'is_prioritas',
        'alasan_prioritas',
        'catatan',
        'status',
    ];

    protected $casts = [
        'tanggal'      => 'date',
        'is_prioritas' => 'boolean',
    ];

    public function antrean()
    {
        return $this->belongsTo(Antrean::class);
    }

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class);
    }

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }
}