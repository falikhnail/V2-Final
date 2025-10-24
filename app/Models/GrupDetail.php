<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupDetail extends Model
{
    use HasFactory;

    protected $table = 'grup_detail';
    protected $fillable = [
        'kode_grup',
        'nik'
    ];

    // Relasi ke model Grup
    public function grup()
    {
        return $this->belongsTo(Grup::class, 'kode_grup', 'kode_grup');
    }

    // Relasi ke model Karyawan
    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'nik', 'nik');
    }
}


