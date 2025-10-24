<?php

namespace Database\Seeders;

use App\Models\Kunjungan;
use App\Models\Karyawan;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class KunjunganSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Koordinat area Tasikmalaya dan sekitarnya
        $locations = [
            ['lat' => -7.3256, 'lng' => 108.2140, 'name' => 'Pusat Kota Tasikmalaya'],
            ['lat' => -7.3300, 'lng' => 108.2200, 'name' => 'Alun-alun Tasikmalaya'],
            ['lat' => -7.3150, 'lng' => 108.2050, 'name' => 'Pasar Cikurubuk'],
            ['lat' => -7.3400, 'lng' => 108.2300, 'name' => 'Terminal Bus Tasikmalaya'],
            ['lat' => -7.3200, 'lng' => 108.2150, 'name' => 'Mall Tasikmalaya'],
            ['lat' => -7.3350, 'lng' => 108.2250, 'name' => 'Rumah Sakit Tasikmalaya'],
            ['lat' => -7.3100, 'lng' => 108.2000, 'name' => 'Kampus Universitas Siliwangi'],
            ['lat' => -7.3450, 'lng' => 108.2350, 'name' => 'Stasiun Kereta Api Tasikmalaya'],
            ['lat' => -7.3250, 'lng' => 108.2100, 'name' => 'Masjid Agung Tasikmalaya'],
            ['lat' => -7.3500, 'lng' => 108.2400, 'name' => 'Pasar Induk Tasikmalaya'],
            ['lat' => -7.3000, 'lng' => 108.1950, 'name' => 'Kawasan Industri Tasikmalaya'],
            ['lat' => -7.3550, 'lng' => 108.2450, 'name' => 'Taman Kota Tasikmalaya'],
            ['lat' => -7.2900, 'lng' => 108.1900, 'name' => 'Perumahan Griya Asri'],
            ['lat' => -7.3600, 'lng' => 108.2500, 'name' => 'Pusat Perbelanjaan Plaza Tasikmalaya'],
            ['lat' => -7.2800, 'lng' => 108.1850, 'name' => 'Kawasan Wisata Situ Gede'],
        ];

        // Titik pusat untuk perhitungan jarak (Pusat Kota Tasikmalaya)
        $centerLat = -7.3256;
        $centerLng = 108.2140;

        // Urutkan lokasi berdasarkan jarak dari titik pusat (dekat ke jauh)
        usort($locations, function ($a, $b) use ($centerLat, $centerLng) {
            $distanceA = $this->calculateDistance($centerLat, $centerLng, $a['lat'], $a['lng']);
            $distanceB = $this->calculateDistance($centerLat, $centerLng, $b['lat'], $b['lng']);
            return $distanceA <=> $distanceB;
        });

        // Tampilkan urutan lokasi yang akan dikunjungi
        $this->command->info('Urutan kunjungan (dari dekat ke jauh):');
        foreach ($locations as $index => $location) {
            $distance = $this->calculateDistance($centerLat, $centerLng, $location['lat'], $location['lng']);
            $this->command->line(($index + 1) . '. ' . $location['name'] . ' - Jarak: ' . number_format($distance, 2) . ' km');
        }

        // Deskripsi kunjungan yang realistis
        $descriptions = [
            'Kunjungan ke klien untuk presentasi proposal',
            'Meeting dengan tim marketing',
            'Survey lokasi untuk proyek baru',
            'Kunjungan rutin ke pelanggan setia',
            'Koordinasi dengan vendor lokal',
            'Follow up meeting dengan klien',
            'Presentasi hasil survey lapangan',
            'Koordinasi dengan tim operasional',
            'Kunjungan ke supplier bahan baku',
            'Meeting dengan investor potensial',
            'Survey pasar untuk produk baru',
            'Koordinasi dengan tim keuangan',
            'Kunjungan ke mitra strategis',
            'Presentasi laporan bulanan',
            'Meeting dengan tim HRD',
            'Koordinasi dengan tim IT',
            'Kunjungan ke lokasi proyek',
            'Meeting dengan tim legal',
            'Survey kompetitor di area',
            'Koordinasi dengan tim QC',
        ];

        // Cari karyawan dengan NIK 22.22.224
        $karyawan = Karyawan::where('nik', '22.22.224')->first();

        if (!$karyawan) {
            $this->command->warn('Karyawan dengan NIK 22.22.224 tidak ditemukan.');
            return;
        }

        // Gunakan tanggal hari ini
        $today = Carbon::now();

        // Buat kunjungan berurutan dari lokasi terdekat ke terjauh
        for ($i = 0; $i < min(10, count($locations)); $i++) {
            // Gunakan lokasi secara berurutan (sudah diurutkan dari dekat ke jauh)
            $location = $locations[$i];

            // Random deskripsi
            $description = $descriptions[array_rand($descriptions)];

            // Generate foto dummy (base64 encoded small image)
            $foto = $this->generateDummyPhoto();

            // Waktu berurutan mulai dari 08:00 dengan interval 1 jam
            $startHour = 8;
            $hour = $startHour + $i;
            $minute = rand(0, 59);
            $createdAt = $today->copy()->setTime($hour, $minute);

            Kunjungan::create([
                'nik' => $karyawan->nik,
                'deskripsi' => $description,
                'foto' => $foto,
                'lokasi' => $location['lat'] . ',' . $location['lng'],
                'tanggal_kunjungan' => $today->format('Y-m-d'),
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);
        }

        $this->command->info('KunjunganSeeder berhasil dijalankan! 10 data kunjungan untuk hari ini telah dibuat.');
    }

    /**
     * Generate dummy photo using placehold.co
     */
    private function generateDummyPhoto(): string
    {
        // Generate random size for variety (60x60 to 120x120)
        $size = rand(60, 120);

        // Random colors for variety
        $bgColors = ['f0f0f0', 'e0e0e0', 'd0d0d0', 'c0c0c0', 'b0b0b0'];
        $textColors = ['666666', '777777', '888888', '999999', 'aaaaaa'];

        $bgColor = $bgColors[array_rand($bgColors)];
        $textColor = $textColors[array_rand($textColors)];

        // Generate placeholder URL from placehold.co
        $placeholderUrl = "https://placehold.co/{$size}x{$size}/{$bgColor}/{$textColor}/png?text=Kunjungan";

        return $placeholderUrl;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     * Returns distance in kilometers
     */
    private function calculateDistance($lat1, $lng1, $lat2, $lng2): float
    {
        $earthRadius = 6371; // Earth's radius in kilometers

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLng / 2) * sin($dLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}
