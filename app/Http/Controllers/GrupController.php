<?php

namespace App\Http\Controllers;

use App\Models\Grup;
use App\Models\GrupDetail;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class GrupController extends Controller
{

    public function index(Request $request)
    {
        $query = Grup::query();
        $data['grup'] = $query->get();
        return view('datamaster.grup.index', $data);
    }

    public function create()
    {
        return view('datamaster.grup.create');
    }

    public function store(Request $request)
    {

        $request->validate([
            'kode_grup' => 'required',
            'nama_grup' => 'required'
        ]);
        try {
            //Simpan Data Grup
            Grup::create([
                'kode_grup' => $request->kode_grup,
                'nama_grup' => $request->nama_grup
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Disimpan'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }


    public function edit($kode_grup)
    {
        $kode_grup = Crypt::decrypt($kode_grup);
        $data['grup'] = Grup::where('kode_grup', $kode_grup)->first();
        return view('datamaster.grup.edit', $data);
    }

    public function update($kode_grup, Request $request)
    {
        $kode_grup = Crypt::decrypt($kode_grup);
        $request->validate([
            'kode_grup' => 'required',
            'nama_grup' => 'required'
        ]);

        try {
            Grup::where('kode_grup', $kode_grup)->update([
                'kode_grup' => $request->kode_grup,
                'nama_grup' => $request->nama_grup
            ]);

            return Redirect::back()->with(messageSuccess('Data Berhasil Diupdate'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function delete($kode_grup)
    {
        $kode_grup = Crypt::decrypt($kode_grup);
        try {
            Grup::where('kode_grup', $kode_grup)->delete();
            return Redirect::back()->with(messageSuccess('Data Berhasil Dihapus'));
        } catch (\Exception $e) {
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function detail($kode_grup)
    {
        $kode_grup = Crypt::decrypt($kode_grup);
        $grup = Grup::where('kode_grup', $kode_grup)->first();

        // Ambil karyawan yang sudah ada di grup menggunakan join
        $karyawanInGrup = DB::table('grup_detail')
            ->join('karyawan', 'grup_detail.nik', '=', 'karyawan.nik')
            ->leftJoin('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('grup_detail.kode_grup', $kode_grup)
            ->select(
                'grup_detail.id',
                'karyawan.nik',
                'karyawan.nama_karyawan',
                'karyawan.foto',
                'jabatan.nama_jabatan',
                'departemen.nama_dept'
            )
            ->get();

        // Ambil semua karyawan untuk dropdown
        $allKaryawan = Karyawan::where('status_aktif_karyawan', '1')->get();

        $data = [
            'grup' => $grup,
            'karyawanInGrup' => $karyawanInGrup,
            'allKaryawan' => $allKaryawan
        ];

        // Cek keberadaan file foto untuk setiap karyawan
        $karyawanInGrup = $karyawanInGrup->map(function ($karyawan) {
            $karyawan->foto_exists = false;
            if (!empty($karyawan->foto)) {
                $karyawan->foto_exists = Storage::disk('public')->exists('karyawan/' . $karyawan->foto);
            }
            return $karyawan;
        });

        $data = [
            'grup' => $grup,
            'karyawanInGrup' => $karyawanInGrup,
            'allKaryawan' => $allKaryawan
        ];

        return view('datamaster.grup.detail', $data);
    }

    public function addKaryawan(Request $request)
    {
        $request->validate([
            'kode_grup' => 'required',
            'nik' => 'required'
        ]);

        try {
            // Cek apakah karyawan sudah ada di grup
            $existing = GrupDetail::where('kode_grup', $request->kode_grup)
                ->where('nik', $request->nik)
                ->first();

            if ($existing) {
                if ($request->ajax()) {
                    return response()->json(['message' => 'Karyawan sudah ada di grup ini'], 400);
                }
                return Redirect::back()->with(messageError('Karyawan sudah ada di grup ini'));
            }

            GrupDetail::create([
                'kode_grup' => $request->kode_grup,
                'nik' => $request->nik
            ]);

            if ($request->ajax()) {
                return response()->json(['message' => 'Karyawan berhasil ditambahkan ke grup']);
            }
            return Redirect::back()->with(messageSuccess('Karyawan berhasil ditambahkan ke grup'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function removeKaryawan(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        $id = Crypt::decrypt($request->id);
        try {
            GrupDetail::where('id', $id)->delete();

            if ($request->ajax()) {
                return response()->json(['message' => 'Karyawan berhasil dihapus dari grup']);
            }
            return Redirect::back()->with(messageSuccess('Karyawan berhasil dihapus dari grup'));
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
            return Redirect::back()->with(messageError($e->getMessage()));
        }
    }

    public function createKaryawanForm($kode_grup)
    {
        $kode_grup = Crypt::decrypt($kode_grup);
        $grup = Grup::where('kode_grup', $kode_grup)->first();

        // Ambil data departemen dan cabang untuk filter
        $departemen = DB::table('departemen')->orderBy('nama_dept')->get();
        $cabang = DB::table('cabang')->orderBy('nama_cabang')->get();

        $data = [
            'grup' => $grup,
            'departemen' => $departemen,
            'cabang' => $cabang
        ];

        return view('datamaster.grup.create-karyawan-form', $data);
    }

    public function getAnggotaGrup($kode_grup)
    {
        $kode_grup = Crypt::decrypt($kode_grup);

        // Debug log
        //\Log::info('getAnggotaGrup called with kode_grup: ' . $kode_grup);

        // Ambil karyawan yang sudah ada di grup menggunakan join
        $karyawanInGrup = DB::table('grup_detail')
            ->join('karyawan', 'grup_detail.nik', '=', 'karyawan.nik')
            ->leftJoin('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
            ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
            ->where('grup_detail.kode_grup', $kode_grup)
            ->select(
                'grup_detail.id',
                'karyawan.nik',
                'karyawan.nama_karyawan',
                'karyawan.foto',
                'jabatan.nama_jabatan',
                'departemen.nama_dept'
            )
            ->get();

        // Debug log
        // \Log::info('Query result count: ' . $karyawanInGrup->count());
        // \Log::info('Query result: ' . $karyawanInGrup->toJson());

        // Cek keberadaan file foto untuk setiap karyawan
        $karyawanInGrup = $karyawanInGrup->map(function ($karyawan) {
            $karyawan->foto_exists = false;
            if (!empty($karyawan->foto)) {
                $karyawan->foto_exists = Storage::disk('public')->exists('karyawan/' . $karyawan->foto);
            }
            return $karyawan;
        });

        $html = view('datamaster.grup.partials.anggota-list', compact('karyawanInGrup'))->render();

        return response()->json([
            'html' => $html,
            'count' => $karyawanInGrup->count(),
            'data' => $karyawanInGrup->toArray() // Tambahkan data untuk debug
        ]);
    }

    public function setJamKerja($kode_grup)
    {
        $kode_grup = Crypt::decrypt($kode_grup);
        $grup = Grup::where('kode_grup', $kode_grup)->first();

        if (!$grup) {
            return redirect()->route('grup.index')->with(messageError('Grup tidak ditemukan'));
        }

        // Ambil data jam kerja yang tersedia
        $jamKerja = DB::table('presensi_jamkerja')->orderBy('nama_jam_kerja')->get();

        // Ambil data jam kerja by date yang sudah ada untuk grup ini
        $jamKerjaBydate = DB::table('grup_jamkerja_bydate')
            ->join('presensi_jamkerja', 'grup_jamkerja_bydate.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
            ->where('grup_jamkerja_bydate.kode_grup', $kode_grup)
            ->select(
                'grup_jamkerja_bydate.id',
                'grup_jamkerja_bydate.tanggal',
                'grup_jamkerja_bydate.kode_jam_kerja',
                'presensi_jamkerja.nama_jam_kerja',
                'presensi_jamkerja.jam_masuk',
                'presensi_jamkerja.jam_pulang'
            )
            ->orderBy('grup_jamkerja_bydate.tanggal', 'desc')
            ->get();

        // Encrypt ID untuk setiap record
        $jamKerjaBydate = $jamKerjaBydate->map(function ($item) {
            $item->encrypted_id = Crypt::encrypt($item->id);
            return $item;
        });

        $data = [
            'grup' => $grup,
            'jamKerja' => $jamKerja,
            'jamKerjaBydate' => $jamKerjaBydate
        ];

        return view('datamaster.grup.set-jam-kerja', $data);
    }

    public function updateJamKerja(Request $request, $kode_grup)
    {
        $kode_grup = Crypt::decrypt($kode_grup);

        $request->validate([
            'tanggal' => 'required|date',
            'kode_jam_kerja' => 'required|exists:presensi_jamkerja,kode_jam_kerja'
        ]);

        try {
            // Cek apakah sudah ada jam kerja untuk tanggal dan grup ini
            $existing = DB::table('grup_jamkerja_bydate')
                ->where('kode_grup', $kode_grup)
                ->where('tanggal', $request->tanggal)
                ->first();

            if ($existing) {
                // Update jika sudah ada
                DB::table('grup_jamkerja_bydate')
                    ->where('id', $existing->id)
                    ->update([
                        'kode_jam_kerja' => $request->kode_jam_kerja,
                        'updated_at' => now()
                    ]);
                $message = "Jam kerja untuk tanggal {$request->tanggal} berhasil diupdate";
            } else {
                // Insert jika belum ada
                DB::table('grup_jamkerja_bydate')->insert([
                    'kode_grup' => $kode_grup,
                    'tanggal' => $request->tanggal,
                    'kode_jam_kerja' => $request->kode_jam_kerja,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                $message = "Jam kerja untuk tanggal {$request->tanggal} berhasil ditambahkan";
            }

            return redirect()->back()->with(messageSuccess($message));
        } catch (\Exception $e) {
            return redirect()->back()->with(messageError('Terjadi kesalahan: ' . $e->getMessage()));
        }
    }

    public function deleteJamKerjaBydate(Request $request)
    {
        $request->validate([
            'id' => 'required'
        ]);

        try {
            $id = Crypt::decrypt($request->id);
            $deleted = DB::table('grup_jamkerja_bydate')->where('id', $id)->delete();

            if ($deleted > 0) {
                if ($request->ajax()) {
                    return response()->json(['message' => 'Jam kerja berhasil dihapus']);
                }
                return redirect()->back()->with(messageSuccess('Jam kerja berhasil dihapus'));
            } else {
                if ($request->ajax()) {
                    return response()->json(['message' => 'Data tidak ditemukan'], 404);
                }
                return redirect()->back()->with(messageError('Data tidak ditemukan'));
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json(['message' => $e->getMessage()], 500);
            }
            return redirect()->back()->with(messageError($e->getMessage()));
        }
    }

    public function getJamKerjaBydate(Request $request, $kode_grup)
    {
        $kode_grup = Crypt::decrypt($kode_grup);

        $request->validate([
            'bulan' => 'required|string',
            'tahun' => 'required|string'
        ]);

        try {
            $bulan = $request->bulan;
            $tahun = $request->tahun;

            // Ambil data jam kerja by date untuk bulan dan tahun tertentu
            $jamKerjaBydate = DB::table('grup_jamkerja_bydate')
                ->join('presensi_jamkerja', 'grup_jamkerja_bydate.kode_jam_kerja', '=', 'presensi_jamkerja.kode_jam_kerja')
                ->where('grup_jamkerja_bydate.kode_grup', $kode_grup)
                ->whereMonth('grup_jamkerja_bydate.tanggal', $bulan)
                ->whereYear('grup_jamkerja_bydate.tanggal', $tahun)
                ->select(
                    'grup_jamkerja_bydate.id',
                    'grup_jamkerja_bydate.tanggal',
                    'grup_jamkerja_bydate.kode_jam_kerja',
                    'presensi_jamkerja.nama_jam_kerja',
                    'presensi_jamkerja.jam_masuk',
                    'presensi_jamkerja.jam_pulang'
                )
                ->get();

            // Encrypt ID untuk setiap record
            $jamKerjaBydate = $jamKerjaBydate->map(function ($item) {
                $item->encrypted_id = Crypt::encrypt($item->id);
                return $item;
            });

            return response()->json($jamKerjaBydate);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function searchKaryawan(Request $request)
    {
        try {
            $search = $request->get('search');
            $kode_grup = $request->get('kode_grup');
            $kode_dept = $request->get('kode_dept');
            $kode_cabang = $request->get('kode_cabang');

            // Validasi parameter
            if (!$kode_grup) {
                return response()->json([
                    'error' => 'Parameter kode_grup diperlukan'
                ], 400);
            }

            // Ambil karyawan yang sudah ada di grup
            $karyawanInGrup = DB::table('grup_detail')
                ->where('kode_grup', $kode_grup)
                ->pluck('nik')
                ->toArray();

            // Query pencarian karyawan
            $query = DB::table('karyawan')
                ->leftJoin('jabatan', 'karyawan.kode_jabatan', '=', 'jabatan.kode_jabatan')
                ->leftJoin('departemen', 'karyawan.kode_dept', '=', 'departemen.kode_dept')
                ->leftJoin('cabang', 'karyawan.kode_cabang', '=', 'cabang.kode_cabang')
                ->where('karyawan.status_aktif_karyawan', '1')
                ->whereNotIn('karyawan.nik', $karyawanInGrup); // Exclude yang sudah ada di grup

            // Filter berdasarkan departemen
            if ($kode_dept) {
                $query->where('karyawan.kode_dept', $kode_dept);
            }

            // Filter berdasarkan cabang
            if ($kode_cabang) {
                $query->where('karyawan.kode_cabang', $kode_cabang);
            }

            // Pencarian berdasarkan keyword
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('karyawan.nik', 'like', '%' . $search . '%')
                        ->orWhere('karyawan.nama_karyawan', 'like', '%' . $search . '%')
                        ->orWhere('jabatan.nama_jabatan', 'like', '%' . $search . '%')
                        ->orWhere('departemen.nama_dept', 'like', '%' . $search . '%')
                        ->orWhere('cabang.nama_cabang', 'like', '%' . $search . '%');
                });
            }

            $karyawan = $query->select(
                'karyawan.nik',
                'karyawan.nama_karyawan',
                'karyawan.foto',
                'jabatan.nama_jabatan',
                'departemen.nama_dept',
                'cabang.nama_cabang'
            )->limit(20)->get();

            // Cek keberadaan file foto untuk setiap karyawan
            $karyawan = $karyawan->map(function ($k) {
                $k->foto_exists = false;
                if (!empty($k->foto)) {
                    $k->foto_exists = Storage::disk('public')->exists('karyawan/' . $k->foto);
                }
                return $k;
            });

            return response()->json([
                'karyawan' => $karyawan,
                'count' => $karyawan->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
