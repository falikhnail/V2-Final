@extends('layouts.app')
@section('titlepage', 'Detail Kunjungan')

@section('content')
@section('navigasi')
    <span>Detail Kunjungan</span>
@endsection

<div class="row">
    <div class="col-lg-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                <a href="{{ route('kunjungan.index') }}" class="btn btn-secondary">
                    <i class="ti ti-arrow-left me-2"></i>Kembali
                </a>
                @can('kunjungan.edit')
                    <a href="{{ route('kunjungan.edit', $kunjungan) }}" class="btn btn-primary">
                        <i class="ti ti-edit me-2"></i>Edit
                    </a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-8 col-sm-12 col-md-12">
                        <table class="table table-borderless">
                            <tr>
                                <td width="200"><strong>Karyawan</strong></td>
                                <td>:</td>
                                <td>
                                    <div>
                                        <strong>{{ $kunjungan->karyawan->nama_karyawan ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $kunjungan->nik }}</small>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal Kunjungan</strong></td>
                                <td>:</td>
                                <td>{{ $kunjungan->tanggal_kunjungan->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Lokasi</strong></td>
                                <td>:</td>
                                <td>
                                    @if ($kunjungan->lokasi)
                                        <span class="badge bg-info">{{ $kunjungan->lokasi }}</span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Deskripsi</strong></td>
                                <td>:</td>
                                <td>
                                    @if ($kunjungan->deskripsi)
                                        {{ $kunjungan->deskripsi }}
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Dibuat</strong></td>
                                <td>:</td>
                                <td>{{ $kunjungan->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Diperbarui</strong></td>
                                <td>:</td>
                                <td>{{ $kunjungan->updated_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-4 col-sm-12 col-md-12">
                        @if ($kunjungan->foto)
                            <div class="text-center">
                                <h6><strong>Foto Kunjungan</strong></h6>
                                <img src="{{ asset('storage/uploads/kunjungan/' . $kunjungan->foto) }}" alt="Foto Kunjungan"
                                    class="img-fluid rounded shadow" style="max-height: 300px; cursor: pointer;"
                                    onclick="showImageModal('{{ asset('storage/uploads/kunjungan/' . $kunjungan->foto) }}', 'Foto Kunjungan - {{ $kunjungan->karyawan->nama_karyawan ?? $kunjungan->nik }}')">
                                <div class="mt-2">
                                    <a href="{{ asset('storage/uploads/kunjungan/' . $kunjungan->foto) }}" download class="btn btn-sm btn-primary">
                                        <i class="ti ti-download me-1"></i>Download
                                    </a>
                                </div>
                            </div>
                        @else
                            <div class="text-center text-muted">
                                <i class="ti ti-photo" style="font-size: 48px; opacity: 0.3;"></i>
                                <p class="mt-2">Tidak ada foto</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Foto Kunjungan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Foto Kunjungan" class="img-fluid rounded">
            </div>
            <div class="modal-footer">
                <a id="downloadImage" href="" download class="btn btn-primary">
                    <i class="ti ti-download me-2"></i>Download
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('myscript')
<script>
    function showImageModal(imageSrc, title) {
        document.getElementById('imageModalLabel').textContent = title;
        document.getElementById('modalImage').src = imageSrc;
        document.getElementById('downloadImage').href = imageSrc;
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    }
</script>
@endpush
