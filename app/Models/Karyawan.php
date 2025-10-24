<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Karyawan extends Model
{
    use HasFactory;
    protected $table = "karyawan";
    protected $primaryKey = "nik";
    public $incrementing = false;
    protected $guarded = [];
    protected $casts = [
        'kode_cabang_array' => 'array',
    ];

    function getRekapstatuskaryawan($request = null)
    {
        $query = Karyawan::query();
        $query->select(
            DB::raw("SUM(IF(status_karyawan = 'K', 1, 0)) as jml_kontrak"),
            DB::raw("SUM(IF(status_karyawan = 'T', 1, 0)) as jml_tetap"),
            DB::raw("SUM(IF(status_karyawan = 'O', 1, 0)) as jml_outsourcing"),
            DB::raw("SUM(IF(status_aktif_karyawan = '1', 1, 0)) as jml_aktif"),
        );
        if (!empty($request->kode_cabang)) {
            $query->where('karyawan.kode_cabang', $request->kode_cabang);
        }

        if (!empty($request->kode_dept)) {
            $query->where('karyawan.kode_dept', $request->kode_dept);
        }
        return $query->first();
    }

    // Relasi dengan Facerecognition
    public function facerecognition()
    {
        return $this->hasMany(Facerecognition::class, 'nik', 'nik');
    }

    // Relasi dengan GrupDetail
    // public function grupDetail()
    // {
    //     return $this->hasMany(GrupDetail::class, 'nik', 'nik');
    // }

    // // Relasi ke Grup melalui GrupDetail
    // public function grup()
    // {
    //     return $this->hasManyThrough(Grup::class, GrupDetail::class, 'nik', 'kode_grup', 'nik', 'kode_grup');
    // }
}
