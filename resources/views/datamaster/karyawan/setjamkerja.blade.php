<div class="row">
    <div class="col">
        <table class="table">
            <tr>
                <th>NIK</th>
                <td>{{ $karyawan->nik }}</td>
            </tr>
            <tr>
                <th>Nama</th>
                <td>{{ $karyawan->nama_karyawan }}</td>
            </tr>
            <tr>
                <th>Departemen</th>
                <td>{{ textUpperCase($karyawan->nama_dept) }}</td>
            </tr>
            <tr>
                <th>Cabang</th>
                <td>{{ textUpperCase($karyawan->nama_cabang) }}</td>
            </tr>

        </table>

    </div>
</div>
<div class="row">
    <div class="col">
        <div class="nav-align-top  mb-6">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link waves-effect active" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-home"
                        aria-controls="navs-top-home" aria-selected="true">Set Jam Kerja</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button type="button" class="nav-link waves-effect" role="tab" data-bs-toggle="tab" data-bs-target="#navs-top-profile"
                        aria-controls="navs-top-profile" aria-selected="false" tabindex="-1">Set Jam Kerja By Date</button>
                </li>

            </ul>
            <div class="tab-content">
                <div class="tab-pane fade active show" id="navs-top-home" role="tabpanel">
                    <form action="{{ route('karyawan.storejamkerjabyday', Crypt::encrypt($karyawan->nik)) }}" id="formSetJamkerja" method="POST">
                        @csrf
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $nama_hari = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'];
                                @endphp
                                @foreach ($nama_hari as $hari)
                                    <tr>
                                        <td class="text-capitalize" style="width: 10%">
                                            <input type="hidden" name="hari[]" value="{{ $hari }}">
                                            {{ $hari }}
                                        </td>
                                        <td>
                                            <div class="form-group p-0" style="margin-bottom: 0px !important">
                                                <select name="kode_jam_kerja[]" id="kode_jam_kerja" class="form-select">
                                                    <option value="">Pilih Jam Kerja</option>
                                                    @foreach ($jamkerja as $d)
                                                        @if (array_key_exists($hari, $jamkerjabyday) && $jamkerjabyday[$hari] == $d->kode_jam_kerja)
                                                            <option value="{{ $d->kode_jam_kerja }}" selected>{{ $d->nama_jam_kerja }}
                                                                ({{ $d->jam_masuk }} -
                                                                {{ $d->jam_pulang }})
                                                            </option>
                                                        @else
                                                            <option value="{{ $d->kode_jam_kerja }}">{{ $d->nama_jam_kerja }}
                                                                ({{ $d->jam_masuk }} -
                                                                {{ $d->jam_pulang }})
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach


                            </tbody>
                        </table>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary w-100" id="btnSimpan"><i class="ti ti-send me-1"></i> Update Jam
                                Kerja</button></button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="navs-top-profile" role="tabpanel">
                    <!-- Calendar View -->
                    <div class="row mb-4">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <div class="form-group mb-3">
                                <select name="bulan" id="bulan" class="form-select">
                                    <option value="">Bulan</option>
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
                                    @for ($t = $start_year; $t <= date('Y'); $t++)
                                        <option {{ $t == date('Y') ? 'selected' : '' }} value="{{ $t }}">{{ $t }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                    </div>

                    <!-- Jam Kerja Templates (Draggable) -->
                    <div class="row mb-4">
                        <div class="col-12">

                            <div class="d-flex flex-wrap gap-2" id="jamKerjaTemplates">
                                @foreach ($jamkerja as $d)
                                    <div class="jam-kerja-template" data-kode="{{ $d->kode_jam_kerja }}" data-nama="{{ $d->nama_jam_kerja }}"
                                        data-jam="{{ $d->jam_masuk }} - {{ $d->jam_pulang }}" style="cursor: grab; user-select: none;">
                                        <span class="badge bg-primary p-2" style="font-size: 12px;">
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
</div>

<!-- jQuery UI for drag and drop -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
    // Check if jQuery UI is loaded
    if (typeof $.fn.draggable === 'undefined') {
        console.error('jQuery UI draggable not loaded');
    }
</script>

<style>
    .calendar-container {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        padding: 20px;

        border-radius: 8px;

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
        min-height: 120px;
        padding: 8px;
        border: 1px solid #e5e7eb;
        position: relative;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .calendar-day:hover {
        background-color: #f9fafb;
        border-color: #3b82f6;
    }

    .calendar-day.other-month {
        background-color: #f9fafb;
        color: #9ca3af;
    }

    .calendar-day.today {
        background-color: #dbeafe;
        border-color: #3b82f6;
        font-weight: 600;
    }

    .calendar-day.selected {
        background-color: #3b82f6;
        color: white;
    }

    .calendar-day.has-jam-kerja {
        background-color: #f0f9ff;
        border-color: #3b82f6;
        border-width: 2px;
    }

    .calendar-day.has-jam-kerja:hover {
        background-color: #e0f2fe;
    }

    .day-number {
        font-weight: 600;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .jam-kerja-badge {
        background-color: #3b82f6;
        color: white;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 10px;
        margin: 2px 0;
        display: block;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .jam-kerja-badge:hover {
        background-color: #1d4ed8;
        transform: scale(1.05);
    }

    .jam-kerja-badge.different-shift {
        background-color: #10b981;
    }

    .jam-kerja-badge.night-shift {
        background-color: #8b5cf6;
    }

    .jam-kerja-badge.weekend-shift {
        background-color: #f59e0b;
    }

    .jam-kerja-template {
        transition: all 0.2s ease;
    }

    .jam-kerja-template:hover {
        transform: scale(1.05);
    }

    .jam-kerja-template.dragging {
        opacity: 0.5;
        transform: rotate(5deg);
    }

    .jam-kerja-template.selected-template {
        background-color: #10b981 !important;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4);
    }

    .calendar-day.drag-over {
        background-color: #dbeafe;
        border: 2px dashed #3b82f6;
    }

    .calendar-navigation {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 0 10px;
    }

    .calendar-title {
        font-size: 20px;
        font-weight: 600;
        color: #1f2937;
    }

    .delete-jam-kerja {
        position: absolute;
        top: 2px;
        right: 2px;
        background-color: #ef4444;
        color: white;
        border: none;
        border-radius: 50%;
        width: 16px;
        height: 16px;
        font-size: 10px;
        cursor: pointer;
        opacity: 0;
        transition: opacity 0.2s ease;
    }

    .jam-kerja-badge:hover .delete-jam-kerja {
        opacity: 1;
    }
</style>

<script>
    $(document).ready(function() {
        $('.flatpickr-date').flatpickr();
        const formJamkerjabydate = $("#formJamkerjabydate");

        // Calendar variables
        let currentDate = new Date();
        let currentMonth = currentDate.getMonth();
        let currentYear = currentDate.getFullYear();
        let jamKerjaData = [];

        // Initialize calendar
        initializeCalendar();

        // Reload data when modal is shown
        $('#modalSetJamkerja').on('shown.bs.modal', function() {
            // Initialize dropdown values
            const currentDate = new Date();
            const currentMonth = currentDate.getMonth() + 1;
            const currentYear = currentDate.getFullYear();

            $("#bulan").val(currentMonth.toString().padStart(2, '0'));
            $("#tahun").val(currentYear);

            loadJamKerjaData();
        });

        // Reload data when tab "Set Jam Kerja By Date" is clicked
        $('button[data-bs-target="#navs-top-profile"]').on('click', function() {
            setTimeout(function() {
                // Initialize dropdown values
                const currentDate = new Date();
                const currentMonth = currentDate.getMonth() + 1;
                const currentYear = currentDate.getFullYear();

                $("#bulan").val(currentMonth.toString().padStart(2, '0'));
                $("#tahun").val(currentYear);

                loadJamKerjaData();
            }, 100); // Small delay to ensure tab is fully shown
        });

        function initializeCalendar() {
            renderCalendar();
            loadJamKerjaData();
            setupDragAndDrop();
        }

        function renderCalendar() {
            const monthNames = [
                "Januari", "Februari", "Maret", "April", "Mei", "Juni",
                "Juli", "Agustus", "September", "Oktober", "November", "Desember"
            ];

            const dayNames = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];

            const firstDay = new Date(currentYear, currentMonth, 1);
            const lastDay = new Date(currentYear, currentMonth + 1, 0);
            const startDate = new Date(firstDay);
            startDate.setDate(startDate.getDate() - firstDay.getDay());

            let calendarHTML = `
                <div class="calendar-navigation">
                    <h3 class="calendar-title">${monthNames[currentMonth]} ${currentYear}</h3>
                </div>
                <div class="calendar-container">
                    <div class="calendar-header">
                        ${dayNames.map(day => `<div class="calendar-day-header">${day}</div>`).join('')}
                    </div>
                    <div class="calendar-body">
            `;

            // Generate calendar days
            for (let i = 0; i < 42; i++) {
                const date = new Date(startDate);
                date.setDate(startDate.getDate() + i);

                const isCurrentMonth = date.getMonth() === currentMonth;
                const isToday = date.toDateString() === new Date().toDateString();
                const dayNumber = date.getDate();
                // Format tanggal sebagai YYYY-MM-DD untuk menghindari masalah timezone
                const dateString = date.getFullYear() + '-' +
                    String(date.getMonth() + 1).padStart(2, '0') + '-' +
                    String(date.getDate()).padStart(2, '0');

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

                    jamKerjaHTML += `
                        <div class="jam-kerja-badge" data-kode="${jk.kode_jam_kerja}" data-tanggal="${dateString}">
                            <button class="delete-jam-kerja" type="button">&times;</button>
                            ${namaJamKerja}
                            <br><small>${jamMasuk} - ${jamPulang}</small>
                        </div>
                    `;
                });

                const hasJamKerja = jamKerjaForDay.length > 0;

                calendarHTML += `
                    <div class="calendar-day ${!isCurrentMonth ? 'other-month' : ''} ${isToday ? 'today' : ''} ${hasJamKerja ? 'has-jam-kerja' : ''}"
                         data-date="${dateString}">
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
        }

        function setupDragAndDrop() {
            // Check if jQuery UI is available
            if (typeof $.fn.draggable === 'undefined') {
                console.warn('jQuery UI not available, using click-to-add fallback');
                setupClickToAdd();
                return;
            }

            // Make jam kerja templates draggable
            $('.jam-kerja-template').draggable({
                helper: 'clone',
                cursor: 'grabbing',
                start: function(e, ui) {
                    $(this).addClass('dragging');
                },
                stop: function(e, ui) {
                    $(this).removeClass('dragging');
                }
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

                    // Check if any jam kerja already exists for this date
                    const existing = jamKerjaData.find(jk => {
                        const jkDate = jk.tanggal ? jk.tanggal.split(' ')[0] : '';
                        return jkDate === targetDate;
                    });

                    if (existing) {
                        Swal.fire({
                            title: "Peringatan!",
                            text: "Tanggal ini sudah memiliki jam kerja. Hapus jam kerja yang ada terlebih dahulu atau pilih tanggal lain.",
                            icon: "warning",
                            showCancelButton: true,
                            confirmButtonText: "Ganti Jam Kerja",
                            cancelButtonText: "Batal"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Replace existing jam kerja
                                replaceJamKerja(targetDate, kodeJamKerja, namaJamKerja, jamKerja);
                            }
                        });
                        return;
                    }

                    // Add jam kerja
                    addJamKerja(targetDate, kodeJamKerja, namaJamKerja, jamKerja);
                }
            });
        }

        function setupClickToAdd() {
            // Fallback: Click on template, then click on calendar day
            let selectedTemplate = null;

            $('.jam-kerja-template').off('click').on('click', function() {
                // Remove previous selection
                $('.jam-kerja-template').removeClass('selected-template');
                // Add selection to clicked template
                $(this).addClass('selected-template');
                selectedTemplate = $(this);

                // Show instruction
                Swal.fire({
                    title: "Template Dipilih",
                    text: "Sekarang klik pada tanggal di kalender untuk menambahkan jam kerja",
                    icon: "info",
                    timer: 2000,
                    showConfirmButton: false
                });
            });

            $('.calendar-day').off('click').on('click', function() {
                if (!selectedTemplate) {
                    Swal.fire({
                        title: "Pilih Template Dulu",
                        text: "Klik template jam kerja terlebih dahulu",
                        icon: "warning"
                    });
                    return;
                }

                const targetDate = $(this).data('date');
                const kodeJamKerja = selectedTemplate.data('kode');
                const namaJamKerja = selectedTemplate.data('nama');
                const jamKerja = selectedTemplate.data('jam');

                // Check if any jam kerja already exists for this date
                const existing = jamKerjaData.find(jk => {
                    const jkDate = jk.tanggal ? jk.tanggal.split(' ')[0] : '';
                    return jkDate === targetDate;
                });

                if (existing) {
                    Swal.fire({
                        title: "Peringatan!",
                        text: "Tanggal ini sudah memiliki jam kerja. Hapus jam kerja yang ada terlebih dahulu atau pilih tanggal lain.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Ganti Jam Kerja",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Replace existing jam kerja
                            replaceJamKerja(targetDate, kodeJamKerja, namaJamKerja, jamKerja);
                        }
                    });
                    return;
                }

                // Add jam kerja
                addJamKerja(targetDate, kodeJamKerja, namaJamKerja, jamKerja);

                // Clear selection
                selectedTemplate.removeClass('selected-template');
                selectedTemplate = null;
            });
        }

        function addJamKerja(tanggal, kodeJamKerja, namaJamKerja, jamKerja) {
            const jamKerjaInfo = jamKerja.split(' - ');

            $.ajax({
                type: 'POST',
                url: "{{ route('karyawan.storejamkerjabydate') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: "{{ $karyawan->nik }}",
                    tanggal: tanggal,
                    kode_jam_kerja: kodeJamKerja
                },
                success: function(response) {
                    if (response.success) {
                        // Add to local data
                        jamKerjaData.push({
                            tanggal: tanggal,
                            kode_jam_kerja: kodeJamKerja,
                            nama_jam_kerja: namaJamKerja,
                            jam_masuk: jamKerjaInfo[0],
                            jam_pulang: jamKerjaInfo[1]
                        });

                        // Re-render calendar
                        renderCalendar();
                        setupDragAndDrop();

                        Swal.fire({
                            title: "Berhasil!",
                            text: "Jam kerja berhasil ditambahkan!",
                            icon: "success"
                        });
                    } else {
                        Swal.fire({
                            title: "Error!",
                            text: response.message,
                            icon: "error"
                        });
                    }
                },
                error: function() {
                    Swal.fire({
                        title: "Error!",
                        text: "Gagal menambahkan jam kerja!",
                        icon: "error"
                    });
                }
            });
        }

        function replaceJamKerja(tanggal, kodeJamKerja, namaJamKerja, jamKerja) {
            const jamKerjaInfo = jamKerja.split(' - ');

            // First, delete existing jam kerja for this date
            const existingJamKerja = jamKerjaData.find(jk => {
                const jkDate = jk.tanggal ? jk.tanggal.split(' ')[0] : '';
                return jkDate === tanggal;
            });

            if (existingJamKerja) {
                // Delete existing jam kerja
                $.ajax({
                    type: 'POST',
                    url: "{{ route('karyawan.deletejamkerjabydate') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        nik: "{{ $karyawan->nik }}",
                        tanggal: tanggal,
                        kode_jam_kerja: existingJamKerja.kode_jam_kerja
                    },
                    success: function(deleteResponse) {
                        if (deleteResponse.success) {
                            // Now add new jam kerja
                            $.ajax({
                                type: 'POST',
                                url: "{{ route('karyawan.storejamkerjabydate') }}",
                                data: {
                                    _token: "{{ csrf_token() }}",
                                    nik: "{{ $karyawan->nik }}",
                                    tanggal: tanggal,
                                    kode_jam_kerja: kodeJamKerja
                                },
                                success: function(response) {
                                    if (response.success) {
                                        // Update local data
                                        jamKerjaData = jamKerjaData.filter(jk => {
                                            const jkDate = jk.tanggal ? jk.tanggal.split(' ')[0] : '';
                                            return jkDate !== tanggal;
                                        });

                                        jamKerjaData.push({
                                            tanggal: tanggal,
                                            kode_jam_kerja: kodeJamKerja,
                                            nama_jam_kerja: namaJamKerja,
                                            jam_masuk: jamKerjaInfo[0],
                                            jam_pulang: jamKerjaInfo[1]
                                        });

                                        // Re-render calendar
                                        renderCalendar();
                                        setupDragAndDrop();

                                        Swal.fire({
                                            title: "Berhasil!",
                                            text: "Jam kerja berhasil diganti!",
                                            icon: "success"
                                        });
                                    } else {
                                        Swal.fire({
                                            title: "Error!",
                                            text: response.message,
                                            icon: "error"
                                        });
                                    }
                                },
                                error: function() {
                                    Swal.fire({
                                        title: "Error!",
                                        text: "Gagal mengganti jam kerja!",
                                        icon: "error"
                                    });
                                }
                            });
                        } else {
                            Swal.fire({
                                title: "Error!",
                                text: "Gagal menghapus jam kerja lama!",
                                icon: "error"
                            });
                        }
                    },
                    error: function() {
                        Swal.fire({
                            title: "Error!",
                            text: "Gagal menghapus jam kerja lama!",
                            icon: "error"
                        });
                    }
                });
            }
        }

        function deleteJamKerja(tanggal, kodeJamKerja) {
            console.log('Deleting jam kerja:', {
                tanggal,
                kodeJamKerja
            });

            Swal.fire({
                title: "Konfirmasi",
                text: "Apakah Anda yakin ingin menghapus jam kerja ini?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, Hapus!",
                cancelButtonText: "Batal"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        url: "{{ route('karyawan.deletejamkerjabydate') }}",
                        data: {
                            _token: "{{ csrf_token() }}",
                            nik: "{{ $karyawan->nik }}",
                            tanggal: tanggal,
                            kode_jam_kerja: kodeJamKerja
                        },
                        success: function(response) {
                            console.log('Delete response:', response);

                            if (response.success) {
                                // Remove from local data
                                jamKerjaData = jamKerjaData.filter(jk => {
                                    const jkDate = jk.tanggal ? jk.tanggal.split(' ')[0] : '';
                                    return !(jkDate === tanggal && jk.kode_jam_kerja === kodeJamKerja);
                                });

                                // Re-render calendar
                                renderCalendar();
                                setupDragAndDrop();

                                Swal.fire({
                                    title: "Berhasil!",
                                    text: response.message || "Jam kerja berhasil dihapus!",
                                    icon: "success"
                                });
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: response.message || "Gagal menghapus jam kerja!",
                                    icon: "error"
                                });
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log('Delete error:', {
                                xhr,
                                status,
                                error
                            });
                            Swal.fire({
                                title: "Error!",
                                text: "Gagal menghapus jam kerja! " + error,
                                icon: "error"
                            });
                        }
                    });
                }
            });
        }

        function loadJamKerjaData() {
            const bulan = $("#bulan").val() || (currentMonth + 1).toString().padStart(2, '0');
            const tahun = $("#tahun").val() || currentYear;

            // Show loading indicator
            $('#calendar-container').html(
                '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Memuat data jam kerja...</p></div>'
            );

            $.ajax({
                type: 'POST',
                url: "{{ route('karyawan.getjamkerjabydate') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: "{{ $karyawan->nik }}",
                    bulan: bulan,
                    tahun: tahun
                },
                success: function(response) {
                    jamKerjaData = response || [];
                    console.log('Loaded jam kerja data:', jamKerjaData);
                    // Only render calendar if not already rendered
                    if ($('#calendar-container').find('.calendar-day').length === 0) {
                        renderCalendar();
                    } else {
                        // Just update the calendar with new data
                        renderCalendar();
                    }
                    setupDragAndDrop();
                },
                error: function(xhr, status, error) {
                    console.log('Error loading jam kerja data:', error);
                    $('#calendar-container').html(
                        '<div class="text-center py-5 text-danger"><i class="ti ti-alert-circle" style="font-size: 48px;"></i><p class="mt-2">Gagal memuat data jam kerja</p></div>'
                    );
                }
            });
        }

        // Event handlers using event delegation
        $(document).on('change', '#bulan, #tahun', function() {
            console.log('Bulan/Tahun changed:', $(this).attr('id'), $(this).val());
            const bulan = $("#bulan").val();
            const tahun = $("#tahun").val();

            if (bulan && tahun) {
                currentMonth = parseInt(bulan) - 1;
                currentYear = parseInt(tahun);
                console.log('Loading data for:', currentMonth + 1, currentYear);

                // Render calendar first, then load data
                renderCalendar();
                loadJamKerjaData();
            }
        });

        $(document).on('click', '#btnPrevMonth', function() {
            console.log('Previous month clicked');
            currentMonth--;
            if (currentMonth < 0) {
                currentMonth = 11;
                currentYear--;
            }
            $("#bulan").val((currentMonth + 1).toString().padStart(2, '0'));
            $("#tahun").val(currentYear);
            console.log('Loading data for:', currentMonth + 1, currentYear);

            // Render calendar first, then load data
            renderCalendar();
            loadJamKerjaData();
        });

        $(document).on('click', '#btnNextMonth', function() {
            console.log('Next month clicked');
            currentMonth++;
            if (currentMonth > 11) {
                currentMonth = 0;
                currentYear++;
            }
            $("#bulan").val((currentMonth + 1).toString().padStart(2, '0'));
            $("#tahun").val(currentYear);
            console.log('Loading data for:', currentMonth + 1, currentYear);

            // Render calendar first, then load data
            renderCalendar();
            loadJamKerjaData();
        });

        $(document).on('click', '#btnToday', function() {
            console.log('Today clicked');
            currentDate = new Date();
            currentMonth = currentDate.getMonth();
            currentYear = currentDate.getFullYear();
            $("#bulan").val((currentMonth + 1).toString().padStart(2, '0'));
            $("#tahun").val(currentYear);
            console.log('Loading data for:', currentMonth + 1, currentYear);

            // Render calendar first, then load data
            renderCalendar();
            loadJamKerjaData();
        });

        // Make deleteJamKerja globally available
        window.deleteJamKerja = deleteJamKerja;

        // Also handle delete via event delegation
        $(document).on('click', '.delete-jam-kerja', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const tanggal = $(this).closest('.jam-kerja-badge').data('tanggal');
            const kodeJamKerja = $(this).closest('.jam-kerja-badge').data('kode');

            deleteJamKerja(tanggal, kodeJamKerja);
        });
        $("#btnAddjamkerjabydate").click(function() {
            let nik = "{{ $karyawan->nik }}";
            let tanggal = formJamkerjabydate.find("#tanggal").val();
            let kode_jam_kerja = formJamkerjabydate.find("#kode_jam_kerja").val();

            if (tanggal == "") {
                swal.fire({
                    title: "Oops!",
                    text: "Tanggal Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formJamkerjabydate.find("#tanggal").focus();
                    },
                });
                return false;
            } else if (kode_jam_kerja == "") {
                swal.fire({
                    title: "Oops!",
                    text: "Jam Kerja Harus Diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: (e) => {
                        formJamkerjabydate.find("#kode_jam_kerja").focus();
                    },
                });
                return false;
            } else {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('karyawan.storejamkerjabydate') }}",
                    data: {
                        _token: "{{ csrf_token() }}",
                        nik: nik,
                        tanggal: tanggal,
                        kode_jam_kerja: kode_jam_kerja
                    },
                    cache: false,
                    success: function(respond) {
                        if (respond.success == false) {
                            swal.fire({
                                title: "Oops!",
                                text: respond.message,
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    formJamkerjabydate.find("#tanggal").focus();
                                },
                            });
                            return false;
                        } else {
                            swal.fire({
                                title: "Berhasil!",
                                text: "Berhasil Menambahkan Jam Kerja !",
                                icon: "success",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    formJamkerjabydate.find("#tanggal").val("");
                                    formJamkerjabydate.find("#kode_jam_kerja").val("");
                                    //formJamkerjabydate.find("#tanggal").focus();
                                    loadjamkerjabydate();
                                },
                            })
                        }

                    },
                    error: function(respond) {
                        swal.fire({
                            title: "Oops!",
                            text: "Gagal Menambahkan Jam Kerja ! " + respond.message,
                            icon: "warning",
                            showConfirmButton: true,
                            didClose: (e) => {
                                formJamkerjabydate.find("#kode_jam_kerja").focus();
                            },
                        });
                    }
                });
            }
        });

        $("#bulan, #tahun").change(function() {
            loadjamkerjabydate();
        });

        function loadjamkerjabydate() {
            let bulan = $("#bulan").val();
            let tahun = $("#tahun").val();
            let nik = "{{ $karyawan->nik }}";
            $.ajax({
                type: 'POST',
                url: "{{ route('karyawan.getjamkerjabydate') }}",
                data: {
                    _token: "{{ csrf_token() }}",
                    nik: nik,
                    bulan: bulan,
                    tahun: tahun
                },
                cache: false,
                success: function(respond) {
                    $(document).find("#getjamkerjabydate").html("");
                    respond.map((d) => {
                        //kosongkan row

                        $(document).find("#getjamkerjabydate").append(`
                            <tr>
                                <td>${d.tanggal}</td>
                                <td>${d.nama_jam_kerja} ${d.jam_masuk} - ${d.jam_pulang}</td>
                                <td>
                                    <a href="#" class="deletejamkerjabydate" tanggal="${d.tanggal}" nik="${d.nik}">
                                        <i class="ti ti-trash text-danger"></i>
                                    </a>
                                </td>
                            </tr>
                        `);
                    })
                },
                error: function(respond) {
                    console.log(error);
                }
            });

        }

        loadjamkerjabydate();

        $(document).on("click", ".deletejamkerjabydate", function(e) {
            let tanggal = $(this).attr("tanggal");
            let nik = $(this).attr("nik");
            swal.fire({
                title: "Apakah Anda Yakin ?",
                text: "Data Jam Kerja Tanggal " + tanggal + " Akan Dihapus !",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya, Hapus !",
                cancelButtonText: "Batal",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('karyawan.deletejamkerjabydate') }}",
                        type: "POST",
                        data: {
                            nik: nik,
                            tanggal: tanggal,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function(response) {
                            swal.fire({
                                title: "Berhasil !",
                                text: response.message,
                                icon: "success",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    loadjamkerjabydate();
                                },
                            });

                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            swal.fire({
                                title: "Oops!",
                                text: xhr.responseJSON.message,
                                icon: "warning",
                                showConfirmButton: true,
                                didClose: (e) => {
                                    loadjamkerjabydate();
                                },
                            })
                        },
                    });
                }
            });
        });
    });
</script>
