<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InformasiPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'informasi_perusahaan';

    protected $fillable = [
        'nama_perusahaan',
        'is_prioritas',
        'volume',
        'rencana_pengisian_harian',
        'keterangan',
        'status',
        'created_by',
    ];

    protected $casts = [
        'is_prioritas' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}