<?php

namespace Database\Seeders;

use App\Models\GrupDetail;
use App\Models\Karyawan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GrupDetailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil beberapa karyawan untuk dijadikan sample
        $karyawan = Karyawan::where('status_aktif_karyawan', '1')->take(10)->get();

        if ($karyawan->count() > 0) {
            // Tambahkan karyawan ke grup GR1 (Grup Administrasi)
            foreach ($karyawan->take(3) as $k) {
                GrupDetail::create([
                    'kode_grup' => 'GR1',
                    'nik' => $k->nik
                ]);
            }

            // Tambahkan karyawan ke grup GR2 (Grup Produksi)
            foreach ($karyawan->skip(3)->take(3) as $k) {
                GrupDetail::create([
                    'kode_grup' => 'GR2',
                    'nik' => $k->nik
                ]);
            }

            // Tambahkan karyawan ke grup GR3 (Grup Marketing)
            foreach ($karyawan->skip(6)->take(2) as $k) {
                GrupDetail::create([
                    'kode_grup' => 'GR3',
                    'nik' => $k->nik
                ]);
            }
        }
    }
}
