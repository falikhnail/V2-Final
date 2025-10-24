@extends('layouts.app')
@section('titlepage', 'Detail Grup - ' . $grup->nama_grup)

@section('content')
    <style>
        .card-member {
            transition: all 0.3s ease;
            border: 1px solid #e9ecef;
        }

        .card-member:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1) !important;
            border-color: #007bff;
        }

        .member-avatar {
            position: relative;
        }

        .member-avatar::after {
            content: '';
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 12px;
            height: 12px;
            background-color: #28a745;
            border: 2px solid white;
            border-radius: 50%;
        }

        .member-info {
            min-height: 60px;
        }

        .member-actions {
            opacity: 1;
            transition: opacity 0.3s ease;
        }

        .card-member:hover .member-actions {
            opacity: 1;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .member-card-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        @media (max-width: 768px) {
            .member-card-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
@section('navigasi')
    <span>Detail Grup - {{ $grup->nama_grup }}</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Anggota Grup: {{ $grup->nama_grup }} ({{ $grup->kode_grup }})</h5>
                    <a href="{{ route('grup.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
            <div class="card-body">
                <!-- Header Grup -->


                <!-- Button Tambah Karyawan -->
                <div class="row mb-3">
                    <div class="col-12">
                        @can('grup.detail')
                            <button type="button" class="btn btn-primary" id="btnTambahKaryawan">
                                <i class="ti ti-user-plus me-2"></i>Tambah ke Grup
                            </button>
                        @endcan
                    </div>
                </div>

                <!-- Card Anggota Grup -->
                <div class="row">
                    <div class="col-12">
                        <div id="anggotaGrupList">
                            @include('datamaster.grup.partials.anggota-list', ['karyawanInGrup' => $karyawanInGrup])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Karyawan -->
<div class="modal fade" id="modalTambahKaryawan" tabindex="-1" aria-labelledby="modalTambahKaryawanLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTambahKaryawanLabel">Tambah Karyawan ke Grup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="loadTambahKaryawan"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        // Button tambah karyawan
        $("#btnTambahKaryawan").click(function() {
            $("#modalTambahKaryawan").modal("show");
            $("#loadTambahKaryawan").load("{{ route('grup.createKaryawanForm', Crypt::encrypt($grup->kode_grup)) }}");
        });

        // Konfirmasi hapus karyawan dari grup dengan AJAX
        $(document).on('click', '.delete-confirm', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const button = $(this);

            Swal.fire({
                title: 'Konfirmasi',
                text: 'Apakah Anda yakin ingin menghapus karyawan ini dari grup?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Disable button untuk mencegah double click
                    button.prop('disabled', true);

                    $.ajax({
                        url: form.attr('action'),
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content'),
                            id: form.find('input[name="id"]').val()
                        },
                        success: function(response) {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: 'Karyawan berhasil dihapus dari grup',
                                icon: 'success',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Update list anggota grup tanpa reload
                                updateAnggotaGrupList();
                            });
                        },
                        error: function(xhr) {
                            button.prop('disabled', false);
                            let message = 'Terjadi kesalahan saat menghapus karyawan';
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
            });
        });

        // Function untuk update list anggota grup
        function updateAnggotaGrupList() {
            $.ajax({
                url: '{{ route('grup.getAnggotaGrup', Crypt::encrypt($grup->kode_grup)) }}',
                method: 'GET',
                success: function(response) {
                    if (response.html) {
                        $('#anggotaGrupList').html(response.html);
                    }
                },
                error: function(xhr) {
                    console.log('Error updating anggota grup list:', xhr);
                }
            });
        }
    });
</script>
@endpush
