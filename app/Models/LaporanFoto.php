<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LaporanFoto extends Model
{
    use HasFactory;

    protected $table = 'laporan_foto';

    protected $fillable = [
        'laporan_id',
        'path_foto',
        'keterangan_foto',
    ];

    public function laporan()
    {
        return $this->belongsTo(Laporan::class);
    }
}