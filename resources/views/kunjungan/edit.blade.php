@extends('layouts.app')
@section('titlepage', 'Edit Kunjungan')

@section('content')
@section('navigasi')
    <span>Edit Kunjungan</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('kunjungan.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-2"></i>Kembali
                </a>
            </div>
            <div class="card-body">
                <form action="{{ route('kunjungan.update', $kunjungan) }}" method="POST" id="formKunjungan" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-6 col-sm-12 col-md-12">
                            <div class="form-group mb-3">
                                <label for="nik" class="form-label">Karyawan <span class="text-danger">*</span></label>
                                <select name="nik" id="nik" class="form-select select2Nik @error('nik') is-invalid @enderror" required>
                                    <option value="">Pilih Karyawan</option>
                                    @foreach ($karyawans as $karyawan)
                                        <option value="{{ $karyawan->nik }}" {{ old('nik', $kunjungan->nik) == $karyawan->nik ? 'selected' : '' }}>
                                            {{ $karyawan->nik }} - {{ $karyawan->nama_karyawan }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('nik')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-md-12">
                            <div class="form-group mb-3">
                                <label for="tanggal_kunjungan" class="form-label">Tanggal Kunjungan <span class="text-danger">*</span></label>
                                <x-input-with-icon icon="ti ti-calendar" label="Tanggal Kunjungan" name="tanggal_kunjungan"
                                    datepicker="flatpickr-date"
                                    value="{{ old('tanggal_kunjungan', $kunjungan->tanggal_kunjungan->format('Y-m-d')) }}" />
                                @error('tanggal_kunjungan')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-12 col-sm-12 col-md-12">
                            <div class="form-group mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="4"
                                    placeholder="Masukkan deskripsi kunjungan">{{ old('deskripsi', $kunjungan->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-md-12">
                            <div class="form-group mb-3">
                                <label for="lokasi" class="form-label">Lokasi</label>
                                <input type="text" class="form-control @error('lokasi') is-invalid @enderror" id="lokasi" name="lokasi"
                                    value="{{ old('lokasi', $kunjungan->lokasi) }}" placeholder="Masukkan lokasi kunjungan">
                                @error('lokasi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-12 col-md-12">
                            <div class="form-group mb-3">
                                <label for="foto" class="form-label">Foto</label>
                                <input type="file" class="form-control @error('foto') is-invalid @enderror" id="foto" name="foto"
                                    accept="image/*">
                                @error('foto')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="text-muted">Format yang didukung: JPG, PNG, GIF. Maksimal 2MB.</small>
                                @if ($kunjungan->foto)
                                    <div class="mt-2">
                                        <small class="text-info">Foto saat ini:</small><br>
                                        <img src="{{ asset('storage/uploads/kunjungan/' . $kunjungan->foto) }}" alt="Foto saat ini"
                                            class="img-thumbnail" style="width: 100px; height: 100px; object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary" id="btnSimpan">
                                <i class="ti ti-device-floppy me-2"></i>Update
                            </button>
                            <a href="{{ route('kunjungan.index') }}" class="btn btn-secondary">
                                <i class="ti ti-x me-2"></i>Batal
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    $(function() {
        // Initialize select2 for karyawan
        const select2Nik = $(".select2Nik");
        if (select2Nik.length) {
            select2Nik.each(function() {
                var $this = $(this);
                $this.wrap('<div class="position-relative"></div>').select2({
                    placeholder: 'Pilih Karyawan',
                    allowClear: true,
                    dropdownParent: $this.parent()
                });
            });
        }

        // Initialize flatpickr for date inputs
        $('.flatpickr-date').flatpickr({
            dateFormat: 'Y-m-d',
            allowInput: false
        });

        function buttonDisabled() {
            $("#btnSimpan").prop('disabled', true);
            $("#btnSimpan").html(`
            <div class="spinner-border spinner-border-sm text-white me-2" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            Loading..`);
        }

        $("#formKunjungan").submit(function(e) {
            const nik = $("#nik").val();
            const tanggal_kunjungan = $("#tanggal_kunjungan").val();
            const deskripsi = $("#deskripsi").val();
            const lokasi = $("#lokasi").val();

            if (nik == '') {
                Swal.fire({
                    title: "Oops!",
                    text: "Karyawan harus diisi !",
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $("#nik").focus();
                    }
                });
                return false;
            } else if (tanggal_kunjungan == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Tanggal Kunjungan Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $("#tanggal_kunjungan").focus();
                    }
                });
                return false;
            } else if (deskripsi == '') {
                Swal.fire({
                    title: "Oops!",
                    text: 'Deskripsi Harus Diisi !',
                    icon: "warning",
                    showConfirmButton: true,
                    didClose: () => {
                        $("#deskripsi").focus();
                    }
                });
                return false;
            } else {
                buttonDisabled();
            }
        });
    });
</script>
@endpush
