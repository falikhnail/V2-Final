@php
    use Illuminate\Support\Facades\Crypt;
@endphp

@if ($karyawanInGrup->count() > 0)
    <div class="member-card-grid">
        @foreach ($karyawanInGrup as $index => $detail)
            <div class="card card-member h-100">
                <div class="card-body">
                    <div class="d-flex align-items-start">
                        <div class="flex-shrink-0 me-3 member-avatar">
                            @if (!empty($detail->foto ?? null) && $detail->foto_exists)
                                <img src="{{ asset('storage/karyawan/' . $detail->foto) }}" alt="{{ $detail->nama_karyawan }}" class="rounded-circle"
                                    width="60" height="60" style="object-fit: cover;">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                    style="width: 60px; height: 60px; border: 2px solid #e0e0e0;">
                                    <i class="ti ti-user" style="font-size: 24px; color: #6c757d;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 member-info">
                            <h6 class="card-title mb-1 fw-semibold text-dark">{{ $detail->nama_karyawan }}</h6>
                            <p class="card-text text-muted small mb-1">
                                <i class="ti ti-id me-1"></i>{{ $detail->nik }}
                            </p>
                            <p class="card-text text-muted small mb-1">
                                <i class="ti ti-briefcase me-1"></i>{{ $detail->nama_jabatan ?? 'Tidak ada jabatan' }}
                            </p>
                            <p class="card-text text-muted small mb-0">
                                <i class="ti ti-building me-1"></i>{{ $detail->nama_dept ?? 'Tidak ada departemen' }}
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <form method="POST" action="{{ route('grup.removeKaryawan') }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="id" value="{{ Crypt::encrypt($detail->id) }}">
                                <button type="button" class="btn btn-sm btn-outline-danger delete-confirm" title="Hapus dari grup">
                                    <i class="ti ti-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-5">
        <div class="mb-3">
            <i class="ti ti-users-off" style="font-size: 4rem; color: #dee2e6;"></i>
        </div>
        <h5 class="text-muted">Belum ada anggota grup</h5>
        <p class="text-muted">Klik tombol "Tambah ke Grup" di atas untuk menambahkan karyawan ke grup ini.</p>
    </div>
@endif
