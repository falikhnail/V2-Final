<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grup extends Model
{
    use HasFactory;

    protected $table = 'grup';
    protected $primaryKey = 'kode_grup';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'kode_grup',
        'nama_grup'
    ];

    // Relasi ke GrupDetail
    public function grupDetail()
    {
        return $this->hasMany(GrupDetail::class, 'kode_grup', 'kode_grup');
    }

    // Relasi ke Karyawan melalui GrupDetail
    public function karyawan()
    {
        return $this->hasManyThrough(Karyawan::class, GrupDetail::class, 'kode_grup', 'nik', 'kode_grup', 'nik');
    }
}
