<form action="{{ route('grup.update', Crypt::encrypt($grup->kode_grup)) }}" method="POST" id="formGrup">
    @csrf
    @method('PUT')
    <x-input-with-icon label="Kode Grup" name="kode_grup" icon="ti ti-barcode" value="{{ $grup->kode_grup }}" />
    <x-input-with-icon label="Nama Grup" name="nama_grup" icon="ti ti-users" value="{{ $grup->nama_grup }}" />
    <di class="form-group mb-3">
        <button class="btn btn-primary w-100"><i class="ti ti-send me-1"></i> Submit</button>
    </di>
</form>
<script src="{{ asset('assets/js/pages/grup.js') }}"></script>



