<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Kunjungan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 18px;
            color: #333;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 12px;
            color: #666;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
        }

        td {
            font-size: 10px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .badge {
            background-color: #007bff;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }

        .page-break {
            page-break-before: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>LAPORAN KUNJUNGAN KARYAWAN</h1>
        <p>Periode: {{ request('tanggal_awal') ? \Carbon\Carbon::parse(request('tanggal_awal'))->format('d/m/Y') : 'Semua' }}
            - {{ request('tanggal_akhir') ? \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') : 'Semua' }}</p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i:s') }}</p>
    </div>

    @if ($kunjungan->count() > 0)
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Karyawan</th>
                    <th style="width: 25%;">Deskripsi</th>
                    <th style="width: 10%;">Lokasi</th>
                    <th style="width: 10%;">Tanggal Kunjungan</th>
                    <th style="width: 10%;">Cabang</th>
                    <th style="width: 5%;">Dept</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($kunjungan as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $item->nama_karyawan ?? 'N/A' }}</strong><br>
                            <small>{{ $item->nik }}</small>
                        </td>
                        <td>{{ $item->deskripsi ?? '-' }}</td>
                        <td>
                            @if ($item->lokasi)
                                <span class="badge">{{ $item->lokasi }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="text-center">{{ $item->tanggal_kunjungan->format('d/m/Y') }}</td>
                        <td>{{ $item->nama_cabang ?? '-' }}</td>
                        <td>{{ $item->nama_dept ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            <p>Total Data: {{ $kunjungan->count() }} kunjungan</p>
        </div>
    @else
        <div class="text-center" style="margin-top: 50px;">
            <p>Tidak ada data kunjungan untuk periode yang dipilih.</p>
        </div>
    @endif
</body>

</html>
