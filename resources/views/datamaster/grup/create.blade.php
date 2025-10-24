<form action="{{ route('grup.store') }}" method="POST" id="formGrup">
    @csrf
    <x-input-with-icon label="Kode Grup" name="kode_grup" icon="ti ti-barcode" />
    <x-input-with-icon label="Nama Grup" name="nama_grup" icon="ti ti-users" />
    <di class="form-group mb-3">
        <button class="btn btn-primary w-100"><i class="ti ti-send me-1"></i> Submit</button>
    </di>
</form>
<script src="{{ asset('assets/js/pages/grup.js') }}"></script>



