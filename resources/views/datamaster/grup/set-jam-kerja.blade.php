@extends('layouts.app')
@section('titlepage', 'Set Jam Kerja - ' . $grup->nama_grup)

@section('content')
@section('navigasi')
    <span>Set Jam Kerja - {{ $grup->nama_grup }}</span>
@endsection

<div class="row">
    <!-- Kolom Data Karyawan Grup -->
    <div class="col-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="card-title mb-0">Anggota Grup</h5>
            </div>
            <div class="card-body p-3" style="max-height: 600px; overflow-y: auto;">
                <div id="anggota-grup-container">
                    <!-- Data anggota grup akan dimuat di sini -->
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat data anggota...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Set Jam Kerja -->
    <div class="col-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Set Jam Kerja untuk Grup: {{ $grup->nama_grup }}</h5>
                    <a href="{{ route('grup.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Info Grup -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-info-circle me-2"></i>
                                <div>
                                    <strong>Grup:</strong> {{ $grup->nama_grup }} ({{ $grup->kode_grup }})<br>
                                    <small class="text-muted">Drag and drop jam kerja ke kalender untuk mengatur jam kerja grup per tanggal.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filter Bulan/Tahun -->
                <div class="row mb-4">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                            <select name="bulan" id="bulan" class="form-select">
                                <option value="">Bulan</option>
                                @php
                                    $list_bulan = [
                                        ['kode_bulan' => '01', 'nama_bulan' => 'Januari'],
                                        ['kode_bulan' => '02', 'nama_bulan' => 'Februari'],
                                        ['kode_bulan' => '03', 'nama_bulan' => 'Maret'],
                                        ['kode_bulan' => '04', 'nama_bulan' => 'April'],
                                        ['kode_bulan' => '05', 'nama_bulan' => 'Mei'],
                                        ['kode_bulan' => '06', 'nama_bulan' => 'Juni'],
                                        ['kode_bulan' => '07', 'nama_bulan' => 'Juli'],
                                        ['kode_bulan' => '08', 'nama_bulan' => 'Agustus'],
                                        ['kode_bulan' => '09', 'nama_bulan' => 'September'],
                                        ['kode_bulan' => '10', 'nama_bulan' => 'Oktober'],
                                        ['kode_bulan' => '11', 'nama_bulan' => 'November'],
                                        ['kode_bulan' => '12', 'nama_bulan' => 'Desember'],
                                    ];
                                @endphp
                                @foreach ($list_bulan as $d)
                                    <option {{ $d['kode_bulan'] == date('m') ? 'selected' : '' }} value="{{ $d['kode_bulan'] }}">
                                        {{ $d['nama_bulan'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <div class="form-group mb-3">
                            <select name="tahun" id="tahun" class="form-select">
                                <option value="">Tahun</option>
                                @php
                                    $start_year = date('Y') - 2;
                                @endphp
                                @for ($t = $start_year; $t <= date('Y') + 1; $t++)
                                    <option {{ $t == date('Y') ? 'selected' : '' }} value="{{ $t }}">{{ $t }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Jam Kerja Templates (Draggable) -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h6 class="mb-3">Jam Kerja Tersedia (Drag ke Kalender):</h6>
                        <div class="d-flex flex-wrap gap-2" id="jamKerjaTemplates">
                            @foreach ($jamKerja as $index => $d)
                                @php
                                    // Gunakan hash yang sama seperti di JavaScript
                                    $hash = 0;
                                    for ($i = 0; $i < strlen($d->kode_jam_kerja); $i++) {
                                        $hash = ord($d->kode_jam_kerja[$i]) + (($hash << 5) - $hash);
                                    }
                                    $colors = [
                                        '#3b82f6',
                                        '#ef4444',
                                        '#10b981',
                                        '#f59e0b',
                                        '#8b5cf6',
                                        '#06b6d4',
                                        '#f97316',
                                        '#84cc16',
                                        '#ec4899',
                                        '#6b7280',
                                    ];
                                    $color = $colors[abs($hash) % count($colors)];
                                @endphp
                                <div class="jam-kerja-template" data-kode="{{ $d->kode_jam_kerja }}" data-nama="{{ $d->nama_jam_kerja }}"
                                    data-jam="{{ $d->jam_masuk }} - {{ $d->jam_pulang }}" data-color="{{ $index }}"
                                    style="cursor: grab; user-select: none;">
                                    <span class="badge p-2" style="font-size: 12px; background-color: {{ $color }}; color: white;">
                                        <i class="ti ti-clock me-1"></i>
                                        {{ $d->nama_jam_kerja }}
                                        <br>
                                        <small>{{ $d->jam_masuk }} - {{ $d->jam_pulang }}</small>
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Calendar Container -->
                <div class="row">
                    <div class="col-12">
                        <div id="calendar-container">
                            <!-- Calendar will be rendered here -->
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <p class="mt-2">Memuat kalender...</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Hidden form for AJAX -->
                <form action="#" id="formJamkerjabydate" style="display: none;">
                    <input type="hidden" name="tanggal" id="tanggal">
                    <input type="hidden" name="kode_jam_kerja" id="kode_jam_kerja">
                </form>
            </div>
        </div>
    </div>
</div>

<!-- jQuery UI for drag and drop -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css">

<style>
    .calendar-navigation {
        text-align: center;
        margin-bottom: 20px;
    }

    .calendar-title {
        font-size: 1.5rem;
        font-weight: 600;
        color: #374151;
        margin: 0;
    }

    .calendar-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .calendar-header {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background-color: #e5e7eb;
        border-radius: 8px 8px 0 0;
        overflow: hidden;
    }

    .calendar-body {
        display: grid;
        grid-template-columns: repeat(7, 1fr);
        gap: 1px;
        background-color: #e5e7eb;
        border-radius: 0 0 8px 8px;
        overflow: hidden;
    }

    .calendar-day-header {
        background-color: #374151;
        color: white;
        padding: 12px 8px;
        text-align: center;
        font-weight: 600;
        font-size: 14px;
    }

    .calendar-day {
        background-color: white;
        min-height: 80px;
        padding: 8px;
        border: 1px solid #e5e7eb;
        position: relative;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .calendar-day:hover {
        background-color: #f9fafb;
    }

    .calendar-day.other-month {
        background-color: #f9fafb;
        color: #9ca3af;
    }

    .calendar-day.today {
        background-color: #dbeafe;
        border-color: #3b82f6;
    }

    .calendar-day.has-jam-kerja {
        background-color: #fef3c7;
        border-color: #f59e0b;
    }

    .calendar-day.drag-over {
        background-color: #d1fae5;
        border-color: #10b981;
        transform: scale(1.02);
    }

    .day-number {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .jam-kerja-badge {
        color: white;
        padding: 4px 6px;
        border-radius: 4px;
        font-size: 10px;
        margin-bottom: 2px;
        display: block;
        cursor: pointer;
        position: relative;
        text-align: center;
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.2s ease;
    }

    .jam-kerja-badge:hover {
        opacity: 0.8;
        transform: scale(1.02);
    }

    .delete-jam-kerja {
        position: absolute;
        top: -2px;
        right: -2px;
        background-color: #dc2626;
        color: white;
        border: none;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        font-size: 10px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        line-height: 1;
    }

    .delete-jam-kerja:hover {
        background-color: #b91c1c;
    }

    .jam-kerja-template.dragging {
        opacity: 0.5;
        transform: rotate(5deg);
    }

    .jam-kerja-template {
        transition: all 0.2s ease;
    }

    .jam-kerja-template:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* Styling untuk anggota grup */
    #anggota-grup-container {
        max-width: 100%;
        overflow: hidden;
    }

    #anggota-grup-container .card {
        border: 1px solid #e3e6f0;
        border-radius: 8px;
        transition: all 0.2s ease;
        box-shadow: none;
        margin-bottom: 12px;
    }

    #anggota-grup-container .card:hover {
        border-color: #d1d5db;
        transform: none;
    }

    #anggota-grup-container .card-body {
        padding: 12px;
    }

    #anggota-grup-container img {
        border: 2px solid #f8f9fa;
    }

    #anggota-grup-container h6 {
        font-size: 14px;
        line-height: 1.3;
    }

    #anggota-grup-container .small {
        font-size: 11px;
        line-height: 1.2;
    }
</style>

@endsection

@push('myscript')
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    $(document).ready(function() {
        // Calendar variables
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        let jamKerjaData = [];

        // Array warna untuk jam kerja
        const jamKerjaColors = [
            '#3b82f6', // Blue
            '#ef4444', // Red
            '#10b981', // Green
            '#f59e0b', // Yellow
            '#8b5cf6', // Purple
            '#06b6d4', // Cyan
            '#f97316', // Orange
            '#84cc16', // Lime
            '#ec4899', // Pink
            '#6b7280' // Gray
        ];

        // Fungsi untuk mendapatkan warna berdasarkan kode jam kerja
        function getJamKerjaColor(kodeJamKerja) {
            // Gunakan hash sederhana untuk konsistensi warna
            let hash = 0;
            for (let i = 0; i < kodeJamKerja.length; i++) {
                hash = kodeJamKerja.charCodeAt(i) + ((hash << 5) - hash);
            }
            return jamKerjaColors[Math.abs(hash) % jamKerjaColors.length];
        }

        // Initialize calendar
        console.log('Initializing calendar...');
        initializeCalendar();

        // Reload data when month/year changes
        $('#bulan, #tahun').on('change', function() {
            loadJamKerjaData();
        });

        // Force render calendar first, then load data
        setTimeout(function() {
            renderCalendar();
            loadJamKerjaData();
            loadAnggotaGrup();
        }, 500);

        function initializeCalendar() {
            renderCalendar();
            setupDragAndDrop();
        }

        function renderCalendar() {
            console.log('Rendering calendar...');

            const bulan = $("#bulan").val() || (currentMonth + 1).toString().padStart(2, '0');
            const tahun = $("#tahun").val() || currentYear;
            const month = parseInt(bulan) - 1;
            const year = parseInt(tahun);

            const monthNames = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            const dayNames = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

            let calendarHTML = `
                <div class="calendar-navigation">
                    <h3 class="calendar-title">${monthNames[month]} ${year}</h3>
                </div>
                <div class="calendar-container">
                    <div class="calendar-header">
                        ${dayNames.map(day => `<div class="calendar-day-header">${day}</div>`).join('')}
                    </div>
                    <div class="calendar-body">
            `;

            // Generate 42 days (6 weeks)
            const firstDay = new Date(year, month, 1);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());

            for (let i = 0; i < 42; i++) {
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);

                const isCurrentMonth = date.getMonth() === month;
                const isToday = date.toDateString() === new Date().toDateString();
                const dayNumber = date.getDate();
                // Format tanggal sebagai YYYY-MM-DD untuk menghindari masalah timezone
                const dateString = date.getFullYear() + '-' +
                    String(date.getMonth() + 1).padStart(2, '0') + '-' +
                    String(date.getDate()).padStart(2, '0');

                // Cari jam kerja untuk tanggal ini
                const jamKerjaForDay = jamKerjaData.filter(jk => {
                    // Handle both string and date formats
                    const jkDate = jk.tanggal ? jk.tanggal.split(' ')[0] : '';
                    return jkDate === dateString;
                });

                let jamKerjaHTML = '';
                jamKerjaForDay.forEach(jk => {
                    const jamMasuk = jk.jam_masuk || '';
                    const jamPulang = jk.jam_pulang || '';
                    const namaJamKerja = jk.nama_jam_kerja || 'Jam Kerja';
                    const kodeJamKerja = jk.kode_jam_kerja || '';
                    const color = getJamKerjaColor(kodeJamKerja);

                    jamKerjaHTML += `
                        <div class="jam-kerja-badge" data-id="${jk.id}" data-encrypted-id="${jk.encrypted_id}" title="Klik untuk hapus" style="background-color: ${color};">
                            <button class="delete-jam-kerja" type="button">&times;</button>
                            ${namaJamKerja}
                            <br><small>${jamMasuk} - ${jamPulang}</small>
                        </div>
                    `;
                });

                const hasJamKerja = jamKerjaForDay.length > 0;

                calendarHTML += `
                    <div class="calendar-day ${!isCurrentMonth ? 'other-month' : ''} ${isToday ? 'today' : ''} ${hasJamKerja ? 'has-jam-kerja' : ''}" data-date="${dateString}">
                        <div class="day-number">${dayNumber}</div>
                        ${jamKerjaHTML}
                    </div>
                `;
            }

            calendarHTML += `
                    </div>
                </div>
            `;

            $('#calendar-container').html(calendarHTML);
            console.log('Calendar rendered successfully');
        }

        function setupDragAndDrop() {
            if (typeof $.fn.draggable === 'undefined') {
                console.warn('jQuery UI not available, using click-to-add fallback');
                setupClickToAdd();
                return;
            }

            // Make jam kerja templates draggable
            $('.jam-kerja-template').draggable({
                helper: 'clone',
                cursor: 'grabbing'
            });

            // Make calendar days droppable
            $('.calendar-day').droppable({
                accept: '.jam-kerja-template',
                hoverClass: 'drag-over',
                drop: function(e, ui) {
                    const draggedTemplate = ui.draggable;
                    const targetDate = $(this).data('date');
                    const kodeJamKerja = draggedTemplate.data('kode');
                    const namaJamKerja = draggedTemplate.data('nama');
                    const jamKerja = draggedTemplate.data('jam');

                    addJamKerja(targetDate, kodeJamKerja, namaJamKerja, jamKerja);
                }
            });

            // Click to remove jam kerja
            $(document).on('click', '.delete-jam-kerja', function(e) {
                e.stopPropagation();
                const badge = $(this).closest('.jam-kerja-badge');
                const id = badge.data('id');
                const encryptedId = badge.data('encrypted-id');

                Swal.fire({
                    title: "Konfirmasi",
                    text: "Apakah Anda yakin ingin menghapus jam kerja ini?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteJamKerja(id, encryptedId);
                    }
                });
            });
        }

        function setupClickToAdd() {
            $('.calendar-day').on('click', function() {
                const targetDate = $(this).data('date');
                let options = '';
                @foreach ($jamKerja as $d)
                    options +=
                        `<option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }} ({{ $d->jam_masuk }} - {{ $d->jam_pulang }})</option>`;
                @endforeach

                Swal.fire({
                    title: 'Pilih Jam Kerja',
                    html: `<select id="jamKerjaSelect" class="form-select">${options}</select>`,
                    showCancelButton: true,
                    confirmButtonText: 'Tambah',
                    cancelButtonText: 'Batal',
                    preConfirm: () => {
                        const kodeJamKerja = document.getElementById('jamKerjaSelect').value;
                        if (!kodeJamKerja) {
                            Swal.showValidationMessage('Pilih jam kerja');
                            return false;
                        }
                        return kodeJamKerja;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const kodeJamKerja = result.value;
                        addJamKerja(targetDate, kodeJamKerja, '', '');
                    }
                });
            });
        }

        function addJamKerja(tanggal, kodeJamKerja, namaJamKerja, jamKerja) {
            $.ajax({
                type: 'POST',
                url: "{{ route('grup.updateJamKerja', Crypt::encrypt($grup->kode_grup)) }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    _method: 'PUT',
                    tanggal: tanggal,
                    kode_jam_kerja: kodeJamKerja
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Jam kerja berhasil ditambahkan',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                    loadJamKerjaData();
                },
                error: function(xhr) {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat menambahkan jam kerja',
                        icon: 'error'
                    });
                }
            });
        }

        function deleteJamKerja(id, encryptedId) {
            $.ajax({
                type: 'DELETE',
                url: "{{ route('grup.deleteJamKerjaBydate') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    id: encryptedId
                },
                success: function(response) {
                    // Remove from local data
                    jamKerjaData = jamKerjaData.filter(jk => jk.id != id);

                    // Re-render calendar
                    renderCalendar();
                    setupDragAndDrop();

                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Jam kerja berhasil dihapus',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    });
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan saat menghapus jam kerja';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        title: 'Error!',
                        text: message,
                        icon: 'error'
                    });
                }
            });
        }

        function loadAnggotaGrup() {
            $.ajax({
                type: 'GET',
                url: "{{ route('grup.getAnggotaGrup', Crypt::encrypt($grup->kode_grup)) }}",
                success: function(response) {
                    console.log('Response anggota grup:', response);
                    console.log('Response type:', typeof response);
                    console.log('Response keys:', Object.keys(response || {}));

                    if (response && response.html) {
                        // Jika response memiliki HTML, gunakan langsung
                        $('#anggota-grup-container').html(response.html);
                    } else if (response && response.length > 0) {
                        // Jika response adalah array langsung
                        let anggotaHTML = '';
                        response.forEach(anggota => {
                            const fotoUrl = anggota.foto_exists && anggota.foto ?
                                `{{ asset('storage/karyawan/') }}/${anggota.foto}` :
                                `{{ asset('assets/img/avatars/1.png') }}`;

                            anggotaHTML += `
                                <div class="card border mb-3">
                                    <div class="card-body p-3">
                                        <div class="d-flex align-items-center">
                                            <div class="flex-shrink-0 me-3">
                                                <img src="${fotoUrl}" alt="${anggota.nama_karyawan}" class="rounded-circle" style="width: 50px; height: 50px; object-fit: cover;">
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-semibold">${anggota.nama_karyawan}</h6>
                                                <p class="mb-1 text-muted small">
                                                    <i class="ti ti-id me-1"></i>${anggota.nik}
                                                </p>
                                                <p class="mb-1 text-muted small">
                                                    <i class="ti ti-briefcase me-1"></i>${anggota.nama_jabatan || 'Tidak ada jabatan'}
                                                </p>
                                                <p class="mb-0 text-muted small">
                                                    <i class="ti ti-building me-1"></i>${anggota.nama_dept || 'Tidak ada departemen'}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            `;
                        });
                        $('#anggota-grup-container').html(anggotaHTML);
                    } else {
                        // Jika tidak ada data
                        $('#anggota-grup-container').html(`
                            <div class="text-center py-4">
                                <div class="text-muted">
                                    <i class="ti ti-users-off" style="font-size: 48px; opacity: 0.3;"></i>
                                    <p class="mt-2 mb-0">Belum ada anggota grup</p>
                                </div>
                            </div>
                        `);
                    }
                },
                error: function(xhr) {
                    console.log('Error loading anggota grup:', xhr);
                    $('#anggota-grup-container').html(`
                        <div class="text-center py-4 text-danger">
                            <i class="ti ti-alert-circle" style="font-size: 48px;"></i>
                            <p class="mt-2 mb-0">Gagal memuat data anggota</p>
                        </div>
                    `);
                }
            });
        }

        function loadJamKerjaData() {
            const bulan = $("#bulan").val() || (currentMonth + 1).toString().padStart(2, '0');
            const tahun = $("#tahun").val() || currentYear;

            $.ajax({
                type: 'POST',
                url: "{{ route('grup.getJamKerjaBydate', Crypt::encrypt($grup->kode_grup)) }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    bulan: bulan,
                    tahun: tahun
                },
                success: function(response) {
                    jamKerjaData = response || [];
                    renderCalendar();
                    setupDragAndDrop();
                },
                error: function(xhr) {
                    console.log('Error loading jam kerja data:', xhr);
                }
            });
        }
    });
</script>
@endpush
