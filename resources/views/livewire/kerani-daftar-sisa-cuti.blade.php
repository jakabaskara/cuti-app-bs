<div class="col">
    <div wire:loading class="f-14 text-dark mt-3"> <span class="spinner-grow text-danger align-middle"></span> Loading...
    </div>

    <p class="ps-2 mt-3">
        @if (!is_null($sisaCutiPanjang))
            <p class="mt-3 ps-3"> <strong> Sisa Cuti Panjang: {{ $sisaCutiPanjang }} </strong></p>
            <input type="hidden" name="sisaCutiPanjang" id='sisa_cuti_panjang' readonly value="{{ $sisaCutiPanjang }}">
            <p class="mt-1 ps-3"> <strong> Sisa Cuti Tahunan: {{ $sisaCutiTahunan }} </strong></p>
            <input type="hidden" name="sisaCutiTahunan" id='sisa_cuti_tahunan' readonly value="{{ $sisaCutiTahunan }}">
            {{-- <p class="mt-1 ps-3"> <strong> Sisa Cuti Total: {{ $sisaCutiPanjang + $sisaCutiTahunan }} </strong></p> --}}
        @endif
    </p>
</div>
