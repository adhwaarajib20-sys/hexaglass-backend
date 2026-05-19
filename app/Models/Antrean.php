<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Antrean extends Model
{
    use HasFactory;

    protected $table = 'antrean';

    protected $fillable = [
    'kendaraan_id',
    'operator_id',
    'satpam_id',
    'nomor_antrean',
    'tanggal',
    'status',
    'status_validasi_satpam',
    'prioritas',
    'is_prioritas',
    'alasan_prioritas',
    'waktu_daftar',
    'waktu_dipanggil',
    'waktu_selesai',
    'estimasi_menit',
    'estimasi_selesai',
    'jumlah_gas_liter',
    'catatan_pengisian',
    'keterangan',
    'alasan_penolakan',
    'informasi_perusahaan_id',
];

protected $casts = [
    'tanggal'          => 'date',
    'waktu_daftar'     => 'datetime',
    'waktu_dipanggil'  => 'datetime',
    'waktu_selesai'    => 'datetime',
    'estimasi_selesai' => 'datetime',
    'is_prioritas'     => 'boolean',
];

// Generate nomor antrean otomatis
public static function generateNomor($tanggal)
{
    $count = self::whereDate('tanggal', $tanggal)->count();
    $nomor = str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    // Format: ANT-001-070526 (ANT - nomor - DDMMYY)
    $dateFormatted = date('dmy', strtotime($tanggal));
    return "ANT-{$nomor}-{$dateFormatted}";
}

public function informasiPerusahaan()
{
    return $this->belongsTo(InformasiPerusahaan::class);
}

public function kendaraan()
{
    return $this->belongsTo(Kendaraan::class);
}

public function operator()
{
    return $this->belongsTo(User::class, 'operator_id');
}

public function satpam()
{
    return $this->belongsTo(User::class, 'satpam_id');
}

public function laporanPengisian()
{
    return $this->hasOne(LaporanPengisian::class);
}
}