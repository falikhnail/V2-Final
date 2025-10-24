<input type="hidden" id="kode_grup" value="{{ $grup->kode_grup }}">

<!-- Form Pencarian -->
<div class="mb-4">
    <!-- Search Input -->
    <div class="input-group mb-3">
        <input type="text" class="form-control" id="searchKaryawan"
            placeholder="Cari karyawan berdasarkan NIK, nama, jabatan, departemen, atau cabang...">
        <button class="btn btn-outline-secondary" type="button" id="btnSearch">
            <i class="ti ti-search"></i>
        </button>
    </div>

    <!-- Filter Options -->
    <div class="d-flex gap-3 mb-3">
        <select class="form-select" id="filterDepartemen">
            <option value="">Semua Departemen</option>
            @foreach ($departemen as $dept)
                <option value="{{ $dept->kode_dept }}">{{ $dept->nama_dept }}</option>
            @endforeach
        </select>
        <select class="form-select" id="filterCabang">
            <option value="">Semua Cabang</option>
            @foreach ($cabang as $cab)
                <option value="{{ $cab->kode_cabang }}">{{ $cab->nama_cabang }}</option>
            @endforeach
        </select>
    </div>

    <div class="form-text">
        <i class="ti ti-info-circle me-1"></i>
        Ketik minimal 2 karakter untuk mencari, atau pilih filter departemen/cabang untuk menampilkan karyawan
    </div>
</div>

<!-- Loading Indicator -->
<div id="loadingIndicator" class="text-center mb-3" style="display: none;">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
    <div class="mt-2">Mencari karyawan...</div>
</div>

<!-- Hasil Pencarian -->
<div id="searchResults">
    <div class="text-center text-muted">
        <i class="ti ti-search mb-2" style="font-size: 2rem;"></i>
        <p>Masukkan kata kunci untuk mencari karyawan</p>
    </div>
</div>

<script>
    $(function() {
        let searchTimeout;
        const kodeGrup = $('#kode_grup').val();

        // Search on input
        $('#searchKaryawan').on('input', function() {
            const searchTerm = $(this).val().trim();

            clearTimeout(searchTimeout);

            if (searchTerm.length >= 2) {
                searchTimeout = setTimeout(() => {
                    searchKaryawan(searchTerm);
                }, 500);
            } else if (searchTerm.length === 0) {
                showEmptyState();
            }
        });

        // Search on button click
        $('#btnSearch').click(function() {
            const searchTerm = $('#searchKaryawan').val().trim();
            if (searchTerm.length >= 2) {
                searchKaryawan(searchTerm);
            }
        });

        // Search on enter key
        $('#searchKaryawan').on('keypress', function(e) {
            if (e.which === 13) {
                const searchTerm = $(this).val().trim();
                if (searchTerm.length >= 2) {
                    searchKaryawan(searchTerm);
                }
            }
        });

        // Filter change events - langsung search tanpa perlu keyword
        $('#filterDepartemen, #filterCabang').change(function() {
            // Langsung search dengan filter yang dipilih, tidak perlu keyword
            searchKaryawanWithFilters();
        });

        function searchKaryawanWithFilters() {
            console.log('searchKaryawanWithFilters called');
            $('#loadingIndicator').show();
            $('#searchResults').html('');

            $.ajax({
                url: '{{ route('grup.searchKaryawan') }}',
                method: 'GET',
                data: {
                    search: '', // Kosongkan search term
                    kode_grup: kodeGrup,
                    kode_dept: $('#filterDepartemen').val(),
                    kode_cabang: $('#filterCabang').val()
                },
                success: function(response) {
                    console.log('SearchKaryawanWithFilters response:', response);
                    $('#loadingIndicator').hide();
                    if (response && response.karyawan) {
                        console.log('Found', response.count, 'karyawan in filter search');
                        displayResults(response.karyawan, response.count);
                    } else {
                        console.log('Invalid response format in filter search');
                        $('#searchResults').html(`
                            <div class="alert alert-warning">
                                <i class="ti ti-alert-triangle me-2"></i>
                                Format response tidak valid
                            </div>
                        `);
                    }
                },
                error: function(xhr) {
                    $('#loadingIndicator').hide();
                    console.log('Error response:', xhr);

                    let errorMessage = 'Terjadi kesalahan saat mencari karyawan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 403) {
                        errorMessage = 'Anda tidak memiliki izin untuk melakukan pencarian';
                    } else if (xhr.status === 404) {
                        errorMessage = 'Endpoint pencarian tidak ditemukan';
                    }

                    $('#searchResults').html(`
                        <div class="alert alert-danger">
                            <i class="ti ti-alert-circle me-2"></i>
                            ${errorMessage}
                        </div>
                    `);
                }
            });
        }

        function searchKaryawan(searchTerm) {
            console.log('searchKaryawan called with term:', searchTerm);
            $('#loadingIndicator').show();
            $('#searchResults').html('');

            $.ajax({
                url: '{{ route('grup.searchKaryawan') }}',
                method: 'GET',
                data: {
                    search: searchTerm,
                    kode_grup: kodeGrup,
                    kode_dept: $('#filterDepartemen').val(),
                    kode_cabang: $('#filterCabang').val()
                },
                success: function(response) {
                    console.log('SearchKaryawan response:', response);
                    $('#loadingIndicator').hide();
                    if (response && response.karyawan) {
                        console.log('Found', response.count, 'karyawan in keyword search');
                        displayResults(response.karyawan, response.count);
                    } else {
                        console.log('Invalid response format in keyword search');
                        $('#searchResults').html(`
                            <div class="alert alert-warning">
                                <i class="ti ti-alert-triangle me-2"></i>
                                Format response tidak valid
                            </div>
                        `);
                    }
                },
                error: function(xhr) {
                    $('#loadingIndicator').hide();
                    console.log('Error response in keyword search:', xhr);

                    let errorMessage = 'Terjadi kesalahan saat mencari karyawan';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage = xhr.responseJSON.message;
                    } else if (xhr.status === 403) {
                        errorMessage = 'Anda tidak memiliki izin untuk melakukan pencarian';
                    } else if (xhr.status === 404) {
                        errorMessage = 'Endpoint pencarian tidak ditemukan';
                    }

                    $('#searchResults').html(`
                        <div class="alert alert-danger">
                            <i class="ti ti-alert-circle me-2"></i>
                            ${errorMessage}
                        </div>
                    `);
                }
            });
        }

        function displayResults(karyawan, count) {
            if (count === 0) {
                $('#searchResults').html(`
                    <div class="text-center text-muted">
                        <i class="ti ti-user-x mb-2" style="font-size: 2rem;"></i>
                        <p>Tidak ada karyawan yang ditemukan</p>
                    </div>
                `);
                return;
            }

            let html = `<div class="mb-3"><small class="text-muted">Ditemukan ${count} karyawan</small></div>`;

            karyawan.forEach(function(k) {
                let fotoElement;
                if (k.foto && k.foto_exists) {
                    fotoElement =
                        `<img src="{{ asset('storage/karyawan') }}/${k.foto}" alt="${k.nama_karyawan}" class="rounded-circle" width="60" height="60" style="object-fit: cover;">`;
                } else {
                    fotoElement = `<div class="rounded-circle bg-light d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; border: 2px solid #e0e0e0;">
                        <i class="ti ti-user" style="font-size: 24px; color: #6c757d;"></i>
                    </div>`;
                }

                html += `
                    <div class="card mb-3 border">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    ${fotoElement}
                                </div>
                                <div class="col">
                                    <h6 class="mb-1">${k.nama_karyawan}</h6>
                                    <p class="mb-1 text-muted small">NIK: ${k.nik}</p>
                                    <p class="mb-1 text-muted small">
                                        <i class="ti ti-briefcase me-1"></i>${k.nama_jabatan || '-'}
                                    </p>
                                    <p class="mb-1 text-muted small">
                                        <i class="ti ti-building me-1"></i>${k.nama_dept || '-'}
                                    </p>
                                    <p class="mb-0 text-muted small">
                                        <i class="ti ti-map-pin me-1"></i>${k.nama_cabang || '-'}
                                    </p>
                                </div>
                                <div class="col-auto">
                                    <button type="button" class="btn btn-primary btn-sm btnTambahKaryawan" data-nik="${k.nik}" data-nama="${k.nama_karyawan}">
                                        <i class="ti ti-user-plus me-1"></i>Tambah
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            $('#searchResults').html(html);
        }

        function showEmptyState() {
            $('#searchResults').html(`
                <div class="text-center text-muted">
                    <i class="ti ti-search mb-2" style="font-size: 2rem;"></i>
                    <p>Masukkan kata kunci untuk mencari karyawan</p>
                </div>
            `);
        }

        // Handle tambah karyawan
        $(document).on('click', '.btnTambahKaryawan', function() {
            const nik = $(this).data('nik');
            const nama = $(this).data('nama');

            Swal.fire({
                title: 'Konfirmasi',
                text: `Tambahkan ${nama} (${nik}) ke grup?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Tambahkan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    addKaryawanToGrup(nik);
                }
            });
        });

        function addKaryawanToGrup(nik) {
            $.ajax({
                url: '{{ route('grup.addKaryawan') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    kode_grup: kodeGrup,
                    nik: nik
                },
                success: function(response) {
                    Swal.fire({
                        title: 'Berhasil!',
                        text: 'Karyawan berhasil ditambahkan ke grup',
                        icon: 'success',
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        // Update list anggota grup tanpa reload
                        updateAnggotaGrupList();

                        // Refresh hasil pencarian untuk menghilangkan karyawan yang sudah ditambahkan
                        refreshSearchResults();
                    });
                },
                error: function(xhr) {
                    let message = 'Terjadi kesalahan saat menambahkan karyawan';
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

        // Function untuk update list anggota grup
        function updateAnggotaGrupList() {
            $.ajax({
                url: '{{ route('grup.getAnggotaGrup', Crypt::encrypt($grup->kode_grup)) }}',
                method: 'GET',
                success: function(response) {
                    // Update list anggota grup di halaman detail
                    if (response.html) {
                        $('#anggotaGrupList').html(response.html);
                    }
                },
                error: function(xhr) {
                    console.log('Error updating anggota grup list:', xhr);
                }
            });
        }

        // Function untuk refresh hasil pencarian berdasarkan kondisi saat ini
        function refreshSearchResults() {
            const searchTerm = $('#searchKaryawan').val().trim();
            const deptFilter = $('#filterDepartemen').val();
            const cabangFilter = $('#filterCabang').val();

            console.log('Refreshing search results:', {
                searchTerm: searchTerm,
                deptFilter: deptFilter,
                cabangFilter: cabangFilter
            });

            // Jika ada search term, gunakan search biasa
            if (searchTerm.length >= 2) {
                console.log('Using searchKaryawan with term:', searchTerm);
                searchKaryawan(searchTerm);
            }
            // Jika ada filter aktif (departemen atau cabang), gunakan filter search
            else if (deptFilter || cabangFilter) {
                console.log('Using searchKaryawanWithFilters');
                searchKaryawanWithFilters();
            }
            // Jika tidak ada search term dan filter, tampilkan empty state
            else {
                console.log('Showing empty state');
                showEmptyState();
            }
        }

        // Make function global untuk bisa dipanggil dari parent window
        window.updateAnggotaGrupList = updateAnggotaGrupList;
    });
</script>
