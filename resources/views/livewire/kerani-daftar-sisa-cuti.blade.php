<div class="col">
    <label for="nama" class="form-label">Nama Karyawan</label>
    <select class="form-select" id="" style="display: ; width: 100%" aria-label="Nama Karyawan" name="karyawan"
        required wire:model='namaKaryawan' wire:change='setNama'>
        <option selected value=""> </option>
        @foreach ($dataPairing as $pairing)
            <option value="{{ $pairing->id }}">
                {{ $pairing->nama }}
            </option>
        @endforeach
    </select>
    <p class="mt-3">
        Sisa Cuti Panjang: {{ $sisaCutiPanjang }}<br>
        Sisa Cuti Tahunan: {{ $sisaCutiTahunan }}
    </p>
</div>
