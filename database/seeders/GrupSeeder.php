<?php

namespace Database\Seeders;

use App\Models\Grup;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GrupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $grup = [
            [
                'kode_grup' => 'GR1',
                'nama_grup' => 'Grup Administrasi'
            ],
            [
                'kode_grup' => 'GR2',
                'nama_grup' => 'Grup Produksi'
            ],
            [
                'kode_grup' => 'GR3',
                'nama_grup' => 'Grup Marketing'
            ],
            [
                'kode_grup' => 'GR4',
                'nama_grup' => 'Grup IT'
            ],
            [
                'kode_grup' => 'GR5',
                'nama_grup' => 'Grup HRD'
            ]
        ];

        foreach ($grup as $data) {
            Grup::create($data);
        }
    }
}
