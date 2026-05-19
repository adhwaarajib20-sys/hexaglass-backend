<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Laporan extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'laporan';

    protected $fillable = [
        'pelapor_id',
        'verifikator_id',
        'nama_pelapor',
        'perusahaan',
        'tanggal_kejadian',
        'waktu_kejadian',
        'lokasi',
        'klasifikasi',
        'deskripsi',
        'rekomendasi',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_kejadian' => 'date',
    ];

    public function pelapor()
    {
        return $this->belongsTo(User::class, 'pelapor_id');
    }

    public function verifikator()
    {
        return $this->belongsTo(User::class, 'verifikator_id');
    }

    public function foto()
    {
        return $this->hasMany(LaporanFoto::class);
    }
}