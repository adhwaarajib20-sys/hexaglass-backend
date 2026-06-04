<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanKetidaksesuaian extends Model
{
    protected $table = 'laporan_ketidaksesuaian';

    protected $fillable = [
        'user_id',
        'kategori',
        'deskripsi',
        'status',
        'catatan',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
