<div class="col">
    <div wire:loading class="f-14 text-dark mt-3"> <span class="spinner-grow text-danger align-middle"></span> Loading...
    </div>

    <p class="ps-2 mt-3">
        @if (!is_null($sisaCutiPanjang))
            <p class="mt-3 ps-3"> <strong> Sisa Cuti Panjang: {{ $sisaCutiPanjang }} </strong></p>
            <p class="mt-1 ps-3"> <strong> Sisa Cuti Tahunan: {{ $sisaCutiTahunan }} </strong></p>
        @endif
    </p>
</div>
