<?php

namespace App\Http\Controllers;

use App\Models\Kunjungan;
use App\Models\Karyawan;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TrackingKunjunganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Default tanggal hari ini
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $nik = $request->get('nik');

        // Ambil semua karyawan untuk filter
        $karyawans = Karyawan::orderBy('nama_karyawan')->get();

        // Ambil data kunjungan dengan koordinat - hanya jika NIK dipilih
        $kunjungans = collect(); // Default empty collection
        if ($nik) {
            $kunjungans = $this->getKunjunganData($tanggal, $tanggal, $nik);
        }

        return view('tracking-kunjungan.index', compact('kunjungans', 'karyawans', 'tanggal', 'nik'));
    }

    /**
     * Get kunjungan data with coordinates for AJAX
     */
    public function getData(Request $request)
    {
        $tanggal = $request->get('tanggal', Carbon::today()->format('Y-m-d'));
        $nik = $request->get('nik');

        $kunjungans = $this->getKunjunganData($tanggal, $tanggal, $nik);

        return response()->json([
            'kunjungans' => $kunjungans
        ]);
    }

    /**
     * Get kunjungan data with coordinates
     */
    private function getKunjunganData($tanggal_awal, $tanggal_akhir, $nik = null)
    {
        $query = Kunjungan::select([
            'kunjungan.id',
            'kunjungan.nik',
            'kunjungan.deskripsi',
            'kunjungan.foto',
            'kunjungan.lokasi',
            'kunjungan.tanggal_kunjungan',
            'kunjungan.created_at',
            'karyawan.nama_karyawan',
            'karyawan.kode_cabang',
            'cabang.nama_cabang',
            'cabang.lokasi_cabang'
        ])
            ->join('karyawan', 'kunjungan.nik', '=', 'karyawan.nik')
            ->leftJoin('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
            ->whereBetween('kunjungan.tanggal_kunjungan', [$tanggal_awal, $tanggal_akhir])
            ->whereNotNull('kunjungan.lokasi')
            ->where('kunjungan.lokasi', '!=', '');

        // Filter by karyawan jika dipilih
        if ($nik) {
            $query->where('kunjungan.nik', $nik);
        }

        $kunjungans = $query->orderBy('kunjungan.created_at', 'asc')
            ->get();

        // Parse koordinat dari field lokasi dan tambahkan offset untuk marker yang sama
        $coordinateCount = [];
        $kunjungans->transform(function ($kunjungan, $index) use (&$coordinateCount, $kunjungans) {
            $lokasi = $kunjungan->lokasi;

            // Parse koordinat dari format "lat,lng" atau "latitude,longitude"
            if (strpos($lokasi, ',') !== false) {
                $coords = explode(',', $lokasi);
                if (count($coords) >= 2) {
                    $lat = floatval(trim($coords[0]));
                    $lng = floatval(trim($coords[1]));

                    // Buat key untuk koordinat
                    $coordKey = $lat . ',' . $lng;

                    // Hitung berapa kali koordinat ini muncul
                    if (!isset($coordinateCount[$coordKey])) {
                        $coordinateCount[$coordKey] = 0;
                    }
                    $coordinateCount[$coordKey]++;

                    // Tambahkan offset kecil untuk marker yang sama (maksimal 5 marker)
                    $offset = ($coordinateCount[$coordKey] - 1) * 0.0001; // Offset sekitar 10 meter
                    if ($coordinateCount[$coordKey] > 5) {
                        $offset = (($coordinateCount[$coordKey] - 1) % 5) * 0.0001;
                    }

                    $kunjungan->latitude = $lat + $offset;
                    $kunjungan->longitude = $lng + $offset;
                    $kunjungan->original_latitude = $lat;
                    $kunjungan->original_longitude = $lng;
                    $kunjungan->marker_count = $coordinateCount[$coordKey];
                }
            }

            // Hitung jarak dari titik sebelumnya
            if ($index > 0) {
                $prevKunjungan = $kunjungans[$index - 1];
                if (
                    isset($prevKunjungan->latitude) && isset($prevKunjungan->longitude) &&
                    isset($kunjungan->latitude) && isset($kunjungan->longitude)
                ) {
                    $distance = $this->calculateDistance(
                        $prevKunjungan->latitude,
                        $prevKunjungan->longitude,
                        $kunjungan->latitude,
                        $kunjungan->longitude
                    );
                    $kunjungan->distance_from_previous = $distance;
                } else {
                    $kunjungan->distance_from_previous = null;
                }
            } else {
                $kunjungan->distance_from_previous = null; // Titik pertama
            }

            // Hitung durasi dari titik sebelumnya
            if ($index > 0) {
                $prevKunjungan = $kunjungans[$index - 1];
                $duration = $this->calculateDuration(
                    $prevKunjungan->created_at,
                    $kunjungan->created_at
                );
                $kunjungan->duration_from_previous = $duration;
            } else {
                $kunjungan->duration_from_previous = null; // Titik pertama
            }

            return $kunjungan;
        });

        return $kunjungans;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; // Earth's radius in meters

        $lat1Rad = deg2rad($lat1);
        $lon1Rad = deg2rad($lon1);
        $lat2Rad = deg2rad($lat2);
        $lon2Rad = deg2rad($lon2);

        $deltaLat = $lat2Rad - $lat1Rad;
        $deltaLon = $lon2Rad - $lon1Rad;

        $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
            cos($lat1Rad) * cos($lat2Rad) *
            sin($deltaLon / 2) * sin($deltaLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        $distance = $earthRadius * $c;

        // Format distance
        if ($distance < 1000) {
            return round($distance, 1) . ' m';
        } else {
            return round($distance / 1000, 2) . ' km';
        }
    }

    /**
     * Calculate duration between two timestamps in minutes
     */
    private function calculateDuration($startTime, $endTime)
    {
        $start = \Carbon\Carbon::parse($startTime);
        $end = \Carbon\Carbon::parse($endTime);

        $durationInMinutes = $start->diffInMinutes($end);

        // Format duration
        if ($durationInMinutes < 60) {
            return $durationInMinutes . ' menit';
        } else {
            $hours = floor($durationInMinutes / 60);
            $minutes = $durationInMinutes % 60;
            if ($minutes > 0) {
                return $hours . ' jam ' . $minutes . ' menit';
            } else {
                return $hours . ' jam';
            }
        }
    }
}
