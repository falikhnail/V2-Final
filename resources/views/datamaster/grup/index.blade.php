@extends('layouts.app')
@section('titlepage', 'Grup')

@section('content')
@section('navigasi')
    <span>Grup</span>
@endsection

<div class="row">
    <div class="col-lg-6 col-sm-12 col-xs-12">
        <div class="card">
            <div class="card-header">
                @can('grup.create')
                    <a href="#" class="btn btn-primary" id="btnCreate"><i class="fa fa-plus me-2"></i> Tambah
                        Grup</a>
                @endcan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <form action="{{ route('grup.index') }}">
                            <div class="row">
                                {{-- <div class="col-lg-4 col-sm-12 col-md-12">
                           <x-input-with-icon label="Cari Nama Grup" value="{{ Request('nama_grup') }}"
                              name="nama_grup" icon="ti ti-search" />
                        </div>
                        <div class="col-lg-2 col-sm-12 col-md-12">
                           <button class="btn btn-primary"><i
                                 class="ti ti-icons ti-search me-1"></i>Cari</button>
                        </div> --}}
                            </div>

                        </form>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="table-responsive mb-2">
                            <table class="table  table-hover table-bordered table-striped">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Kode Grup</th>
                                        <th>Nama Grup</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($grup as $g)
                                        <tr>
                                            <td>{{ $g->kode_grup }}</td>
                                            <td>{{ $g->nama_grup }}</td>
                                            <td>
                                                <div class="d-flex">
                                                    @can('grup.edit')
                                                        <div>
                                                            <a href="#" class="me-2 btnEdit" kode_grup="{{ Crypt::encrypt($g->kode_grup) }}">
                                                                <i class="ti ti-edit text-success"></i>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('grup.detail')
                                                        <div>
                                                            <a href="#" class="me-2 btnDetail" kode_grup="{{ Crypt::encrypt($g->kode_grup) }}">
                                                                <i class="ti ti-user-plus text-primary"></i>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('grup.setJamKerja')
                                                        <div>
                                                            <a href="#" class="me-2 btnSetJamKerja" kode_grup="{{ Crypt::encrypt($g->kode_grup) }}"
                                                                title="Set Jam Kerja">
                                                                <i class="ti ti-clock-plus text-warning"></i>
                                                            </a>
                                                        </div>
                                                    @endcan

                                                    @can('grup.delete')
                                                        <div>
                                                            <form method="POST" name="deleteform" class="deleteform"
                                                                action="{{ route('grup.delete', Crypt::encrypt($g->kode_grup)) }}">
                                                                @csrf
                                                                @method('DELETE')
                                                                <a href="#" class="delete-confirm ml-1">
                                                                    <i class="ti ti-trash text-danger"></i>
                                                                </a>
                                                            </form>
                                                        </div>
                                                    @endcan

                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<x-modal-form id="modal" show="loadmodal" />
@endsection
@push('myscript')
<script>
    $(function() {

        function loading() {
            $("#loadmodal").html(`<div class="sk-wave sk-primary" style="margin:auto">
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            <div class="sk-wave-rect"></div>
            </div>`);
        };
        loading();

        $("#btnCreate").click(function() {
            $("#modal").modal("show");
            $(".modal-title").text("Tambah Data Grup");
            $("#loadmodal").load("{{ route('grup.create') }}");
        });


        $(".btnEdit").click(function() {
            loading();
            const kode_grup = $(this).attr("kode_grup");
            $("#modal").modal("show");
            $(".modal-title").text("Edit Grup");
            $("#loadmodal").load(`/grup/${kode_grup}`);
        });

        $(".btnDetail").click(function() {
            const kode_grup = $(this).attr("kode_grup");
            window.location.href = `/grup/${kode_grup}/detail`;
        });

        $(".btnSetJamKerja").click(function() {
            const kode_grup = $(this).attr("kode_grup");
            window.location.href = `/grup/${kode_grup}/set-jam-kerja`;
        });
    });
</script>
@endpush
