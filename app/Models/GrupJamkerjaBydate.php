<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupJamkerjaBydate extends Model
{
    use HasFactory;

    protected $table = 'grup_jamkerja_bydate';

    protected $fillable = [
        'kode_grup',
        'tanggal',
        'kode_jam_kerja'
    ];

    protected $casts = [
        'tanggal' => 'date'
    ];

    // Relasi ke Grup
    public function grup()
    {
        return $this->belongsTo(Grup::class, 'kode_grup', 'kode_grup');
    }

    // Relasi ke Jamkerja
    public function jamkerja()
    {
        return $this->belongsTo(Jamkerja::class, 'kode_jam_kerja', 'kode_jam_kerja');
    }
}
