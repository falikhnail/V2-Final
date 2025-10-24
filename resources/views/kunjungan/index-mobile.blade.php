@extends('layouts.mobile.app')

@section('content')
    <style>
        .avatar {
            position: relative;
            width: 3.5rem;
            height: 3.5rem;
            cursor: pointer;
        }

        /* Tambahkan style untuk header dan content */
        #header-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
        }

        #content-section {
            margin-top: 70px;
            padding-top: 5px;
            position: relative;
            z-index: 1;
        }

        .avatar-sm {
            width: 3.5rem;
            height: 3.5rem;
        }

        .avatar-sm .avatar-initial {
            font-size: .8125rem;
        }

        .avatar .avatar-initial {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background-color: #eeedf0;
            font-size: .9375rem;
        }

        .rounded-circle {
            border-radius: 12px !important;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #666;
        }

        .empty-state i {
            font-size: 48px;
            color: #ccc;
            margin-bottom: 16px;
        }

        .empty-state h4 {
            color: #999;
            margin-bottom: 8px;
        }

        .empty-state p {
            color: #bbb;
            font-size: 14px;
        }

        /* Timeline Styles - Clean & Modern */
        .timeline {
            position: relative;
            padding: 20px 0;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 25px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: linear-gradient(to bottom, #007bff, #28a745);
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
            padding-left: 60px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .timeline-item:hover {
            transform: translateX(3px);
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 15px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: #007bff;
            border: 3px solid #fff;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            z-index: 2;
        }

        .timeline-item:last-child::before {
            background: #28a745;
        }

        .timeline-content {
            background: #fff;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f3f4;
            position: relative;
            display: flex;
            align-items: flex-start;
            gap: 14px;
            margin-bottom: 8px;
        }

        .timeline-content::before {
            content: '';
            position: absolute;
            left: -6px;
            top: 15px;
            width: 0;
            height: 0;
            border-top: 6px solid transparent;
            border-bottom: 6px solid transparent;
            border-right: 6px solid #fff;
        }

        .timeline-photo {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            object-fit: cover;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .timeline-photo-placeholder {
            width: 60px;
            height: 60px;
            border-radius: 8px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .timeline-photo-placeholder ion-icon {
            color: white;
            font-size: 24px;
        }

        .timeline-info {
            flex: 1;
            min-width: 0;
        }

        .timeline-datetime-group {
            margin-bottom: 2px;
        }

        .timeline-time {
            font-size: 1.1em;
            font-weight: 600;
            color: #2c3e50;
            line-height: 1.2;
            display: block;
        }

        .timeline-date {
            font-size: 1.1em;
            color: #8e8e93;
            font-weight: 500;
            line-height: 1.2;
            margin-top: 2px;
            display: block;
        }

        .timeline-title {
            font-size: 16px;
            font-weight: 600;
            color: #1a1a1a;
            margin-bottom: 2px;
            line-height: 1.3;
        }

        .timeline-description {
            color: #4a4a4a;
            font-size: 14px;
            line-height: 1.4;
            margin-bottom: 4px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .timeline-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 4px;
        }

        .timeline-location {
            display: flex;
            align-items: center;
            color: #007bff;
            font-size: 13px;
            font-weight: 500;
            flex: 1;
            min-width: 0;
        }

        .timeline-location ion-icon {
            margin-right: 6px;
            font-size: 16px;
            flex-shrink: 0;
        }

        .timeline-location span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .timeline-distance {
            background: #e8f4fd;
            color: #1976d2;
            padding: 4px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 600;
            margin-left: 10px;
            flex-shrink: 0;
        }

        .timeline-actions {
            display: flex;
            justify-content: flex-end;
            gap: 6px;
        }

        .timeline-actions .btn {
            padding: 4px 8px;
            font-size: 11px;
            border-radius: 4px;
            min-width: 28px;
            height: 28px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Hide show button */
        .timeline-actions .btn-info {
            display: none;
        }

        /* Style untuk button hapus di sudut kanan card */
        .timeline-item .right {
            position: absolute;
            top: 15px;
            right: 15px;
            z-index: 10;
        }

        .timeline-item .right .price {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .timeline-item .right .btn {
            width: 36px;
            height: 36px;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .timeline-item .right .btn ion-icon {
            font-size: 18px;
        }

        .timeline-item .right .deleteform {
            margin: 0;
        }

        /* Empty State untuk Timeline */
        .timeline-empty {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .timeline-empty i {
            font-size: 64px;
            color: #dee2e6;
            margin-bottom: 20px;
        }

        .timeline-empty h4 {
            color: #adb5bd;
            margin-bottom: 10px;
        }

        .timeline-empty p {
            color: #ced4da;
            font-size: 14px;
        }

        /* Style untuk foto di modal */
        #modalPhoto img {
            width: 100%;
            max-width: 100%;
            height: auto;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            object-fit: cover;
        }

        /* Foto full width di mobile */
        @media (max-width: 576px) {
            #modalPhoto img {
                border-radius: 8px;
                max-height: 300px;
                object-fit: cover;
            }
        }

        /* Custom Modal Styles */
        .custom-modal-overlay {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            width: 100vw !important;
            height: 100vh !important;
            background-color: rgba(0, 0, 0, 0.6) !important;
            z-index: 99999 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            padding: 1rem;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            margin: 0 !important;
            border: none !important;
        }

        .custom-modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Ensure no other elements interfere with backdrop */
        .custom-modal-overlay.show * {
            position: relative;
            z-index: 1;
        }

        /* Hide other elements when modal is open */
        body.modal-open {
            overflow: hidden !important;
        }

        body.modal-open .appHeader,
        body.modal-open .bottomMenu,
        body.modal-open .fab {
            z-index: 1 !important;
        }

        .custom-modal {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 500px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            transform: scale(0.8) translateY(20px);
            transition: all 0.3s ease;
        }

        .custom-modal-overlay.show .custom-modal {
            transform: scale(1) translateY(0);
        }

        .custom-modal-header {
            padding: 1.5rem 1.5rem 0 1.5rem;
            border-bottom: none;
            position: relative;
        }

        .custom-modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #2c3e50;
            margin: 0;
        }

        .custom-modal-close {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #6c757d;
            cursor: pointer;
            padding: 0.25rem;
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }

        .custom-modal-close:hover {
            background-color: #f8f9fa;
            color: #495057;
        }

        .custom-modal-body {
            padding: 1.5rem;
            max-height: calc(90vh - 120px);
            overflow-y: auto;
        }

        .custom-modal-footer {
            padding: 0 1.5rem 1.5rem 1.5rem;
            border-top: none;
            display: flex;
            justify-content: flex-end;
        }

        .custom-btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            border: none;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .custom-btn-secondary {
            background-color: #6c757d;
            color: white;
        }

        .custom-btn-secondary:hover {
            background-color: #5a6268;
            transform: translateY(-1px);
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .custom-modal-overlay {
                padding: 0.5rem !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                z-index: 99999 !important;
                margin: 0 !important;
                border: none !important;
            }

            .custom-modal {
                max-width: 100%;
                max-height: 95vh;
                border-radius: 12px;
            }

            .custom-modal-header {
                padding: 1rem 1rem 0 1rem;
            }

            .custom-modal-body {
                padding: 1rem;
                max-height: calc(95vh - 100px);
            }

            .custom-modal-footer {
                padding: 0 1rem 1rem 1rem;
            }
        }

        @media (max-width: 576px) {
            .custom-modal-overlay {
                padding: 0.25rem !important;
                position: fixed !important;
                top: 0 !important;
                left: 0 !important;
                width: 100% !important;
                height: 100% !important;
                z-index: 99999 !important;
                margin: 0 !important;
                border: none !important;
            }

            .custom-modal {
                border-radius: 8px;
                max-height: 98vh;
            }

            .custom-modal-header {
                padding: 0.75rem 0.75rem 0 0.75rem;
            }

            .custom-modal-body {
                padding: 0.75rem;
                max-height: calc(98vh - 80px);
            }

            .custom-modal-footer {
                padding: 0 0.75rem 0.75rem 0.75rem;
            }
        }
    </style>

    <div id="header-section">
        <div class="appHeader bg-primary text-light">
            <div class="left">
                <a href="{{ route('dashboard.index') }}" class="headerButton goBack">
                    <ion-icon name="chevron-back-outline"></ion-icon>
                </a>
            </div>
            <div class="pageTitle">Kunjungan Saya</div>
            <div class="right">
                <a href="{{ route('kunjungan.export.pdf', request()->query()) }}" class="headerButton" target="_blank" title="Export PDF">
                    <ion-icon name="document-text-outline"></ion-icon>
                </a>
            </div>
        </div>
    </div>

    <div id="content-section">
        <div class="row mb-4" style="margin-top: 30px; ">
            <div class="col">
                <form action="{{ route('kunjungan.index') }}" method="GET">
                    <input type="text" class="feedback-input dari" name="tanggal_awal" placeholder="Dari" id="datePicker"
                        value="{{ Request('tanggal_awal', date('Y-m-d')) }}" />
                    <input type="text" class="feedback-input sampai" name="tanggal_akhir" placeholder="Sampai" id="datePicker2"
                        value="{{ Request('tanggal_akhir', date('Y-m-d')) }}" />
                    <button class="btn btn-primary w-100" id="btnSimpan"><ion-icon name="search-circle-outline"></ion-icon>Cari</button>
                </form>
            </div>
        </div>
        <div class="row overflow-scroll" style="padding-bottom: 100px;">
            <div class="col">
                <!-- Kunjungan Timeline -->
                <div class="timeline">
                    @if ($kunjungan->count() > 0)
                        @foreach ($kunjungan as $index => $item)
                            @php
                                $prevItem = $kunjungan->get($index - 1);
                                $distance = '';
                                if ($prevItem && $item->lokasi && $prevItem->lokasi) {
                                    $coords1 = explode(',', $item->lokasi);
                                    $coords2 = explode(',', $prevItem->lokasi);
                                    if (count($coords1) == 2 && count($coords2) == 2) {
                                        $lat1 = floatval($coords1[0]);
                                        $lon1 = floatval($coords1[1]);
                                        $lat2 = floatval($coords2[0]);
                                        $lon2 = floatval($coords2[1]);

                                        // Haversine formula untuk menghitung jarak
                                        $earthRadius = 6371; // km
                                        $dLat = deg2rad($lat2 - $lat1);
                                        $dLon = deg2rad($lon2 - $lon1);
                                        $a =
                                            sin($dLat / 2) * sin($dLat / 2) +
                                            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon / 2) * sin($dLon / 2);
                                        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
                                        $distanceKm = $earthRadius * $c;

                                        if ($distanceKm < 1) {
                                            $distance = round($distanceKm * 1000) . ' m';
                                        } else {
                                            $distance = round($distanceKm, 1) . ' km';
                                        }
                                    }
                                }
                            @endphp

                            <div class="timeline-item"
                                onclick="showDetailModal({{ $item->id }}, '{{ $item->deskripsi }}', '{{ $item->tanggal_kunjungan->format('d M Y') }}', '{{ $item->created_at->format('H:i') }}', '{{ $item->lokasi }}', '{{ $item->foto }}')">

                                <!-- Action Button Hapus di sudut kanan atas -->
                                <div class="right" onclick="event.stopPropagation();">
                                    <div class="price">
                                        <form method="POST" name="deleteform" class="deleteform d-inline"
                                            action="{{ route('kunjungan.destroy', $item) }}">
                                            @csrf
                                            @method('DELETE')
                                            <a href="#" class="btn btn-sm btn-danger delete-confirm">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </a>
                                        </form>
                                    </div>
                                </div>

                                <div class="timeline-content">
                                    <!-- Photo Section -->
                                    @if ($item->foto)
                                        @if (str_starts_with($item->foto, 'http'))
                                            <img src="{{ $item->foto }}" alt="Foto Kunjungan" class="timeline-photo">
                                        @else
                                            <img src="{{ asset('storage/uploads/kunjungan/' . $item->foto) }}" alt="Foto Kunjungan"
                                                class="timeline-photo">
                                        @endif
                                    @else
                                        <img src="https://placehold.co/60x60/f0f0f0/999999/png?text=Kunjungan" alt="Foto Kunjungan"
                                            class="timeline-photo">
                                    @endif

                                    <!-- Info Section -->
                                    <div class="timeline-info">
                                        <div class="timeline-datetime-group">
                                            <div class="timeline-time">{{ $item->created_at->format('H:i') }}</div>
                                            <div class="timeline-date">{{ $item->tanggal_kunjungan->format('d M Y') }}</div>
                                        </div>

                                        <div class="timeline-title">Kunjungan</div>

                                        <div class="timeline-description">
                                            {{ $item->deskripsi }}
                                        </div>

                                        <div class="timeline-meta">
                                            <div class="timeline-location">
                                                <ion-icon name="location-outline"></ion-icon>
                                                <span>
                                                    @if ($item->lokasi)
                                                        {{ $item->lokasi }}
                                                    @else
                                                        Lokasi tidak tersedia
                                                    @endif
                                                </span>
                                            </div>

                                            @if ($distance)
                                                <div class="timeline-distance">{{ $distance }}</div>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="timeline-empty">
                            <i class="ti ti-map-pin"></i>
                            <h4>Belum Ada Kunjungan</h4>
                            <p>Mulai catat kunjungan harian Anda</p>
                            <a href="{{ route('kunjungan.create') }}" class="btn btn-primary mt-3">
                                <i class="ti ti-plus me-1"></i>Tambah Kunjungan
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if ($kunjungan->hasPages())
                    <div class="text-center mt-4">
                        {{ $kunjungan->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>

        <!-- FAB Button -->
        <div class="fab-button animate bottom-right" style="margin-bottom:70px">
            <a href="{{ route('kunjungan.create') }}" class="fab bg-primary">
                <ion-icon name="add-outline" role="img" class="md hydrated" aria-label="add outline"></ion-icon>
            </a>
        </div>
    </div>

    <!-- Custom Detail Modal -->
    <div class="custom-modal-overlay" id="detailModal">
        <div class="custom-modal">
            <div class="custom-modal-header">
                <h5 class="custom-modal-title">Detail Kunjungan</h5>

            </div>
            <div class="custom-modal-body">
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Tanggal & Waktu</label>
                            <p id="modalDate" class="mb-0"></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Foto Kunjungan</label>
                            <div id="modalPhoto" class="text-center">
                                <p class="text-muted">Tidak ada foto</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi</label>
                            <p id="modalLocation" class="mb-0">-</p>
                        </div>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Deskripsi Kunjungan</label>
                    <p id="modalDescription" class="mb-0"></p>
                </div>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="custom-btn custom-btn-secondary" id="closeModalBtn">Tutup</button>
            </div>
        </div>
    </div>

@endsection

@push('myscript')
    <script>
        $(function() {
            var lang = {
                title: 'Pilih Tanggal',
                cancel: 'Batal',
                confirm: 'Set',
                year: '',
                month: '',
                day: '',
                hour: '',
                min: '',
                sec: ''
            };
            new Rolldate({
                el: '#datePicker',
                format: 'YYYY-MM-DD',
                beginYear: 2000,
                endYear: 2100,
                lang: lang,
                confirm: function(date) {

                }
            });

            new Rolldate({
                el: '#datePicker2',
                format: 'YYYY-MM-DD',
                beginYear: 2000,
                endYear: 2100,
                lang: lang,
                confirm: function(date) {

                }
            });

            function showDetailModal(id, deskripsi, tanggal, waktu, lokasi, foto) {
                // Set data ke modal
                document.getElementById('modalDate').textContent = tanggal + ' - ' + waktu;
                document.getElementById('modalDescription').textContent = deskripsi;

                // Set lokasi
                const modalLocation = document.getElementById('modalLocation');

                if (lokasi && lokasi !== '') {
                    modalLocation.textContent = lokasi;
                } else {
                    modalLocation.textContent = '-';
                }

                // Set foto
                const modalPhoto = document.getElementById('modalPhoto');
                if (foto && foto !== '') {
                    modalPhoto.innerHTML = '<img src="' + '{{ asset('storage/uploads/kunjungan/') }}/' + foto +
                        '" alt="Foto Kunjungan" class="img-fluid">';
                } else {
                    modalPhoto.innerHTML = '<p class="text-muted">Tidak ada foto</p>';
                }

                // Show custom modal
                const modal = document.getElementById('detailModal');
                modal.classList.add('show');
                document.body.classList.add('modal-open');
                document.body.style.overflow = 'hidden';
            }

            // Make function global
            window.showDetailModal = showDetailModal;

            function closeDetailModal() {
                const modal = document.getElementById('detailModal');

                // Hide custom modal
                modal.classList.remove('show');
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
            }

            // Make function global
            window.closeDetailModal = closeDetailModal;

            // Event listener untuk button close
            $(document).ready(function() {
                // Event listener untuk button Tutup
                $(document).on('click', '#closeModalBtn', function() {
                    closeDetailModal();
                });

                // Event listener untuk button X
                $(document).on('click', '#closeModalHeaderBtn', function() {
                    closeDetailModal();
                });

                // Event listener untuk backdrop
                $(document).on('click', '#detailModal', function(e) {
                    if (e.target === this) {
                        closeDetailModal();
                    }
                });
            });

            $('.delete-confirm').click(function(e) {
                var form = $(this).closest('form');
                e.preventDefault();
                Swal.fire({
                    title: 'Konfirmasi Hapus',
                    text: 'Apakah Anda yakin ingin menghapus kunjungan ini?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
