<div>
    <input type="hidden" name="idCuti" id="idCuti" value="{{ $id }}">
    @if ($dataCuti)
        <p class="mb-0">Nama: {{ $dataCuti->karyawan->nama }}</p>
        <p class="mt-1">Tanggal:
            {{ date('d M Y', strtotime($dataCuti->tanggal_mulai)) . ' s.d ' . $dataCuti->tanggal_selesai }}</p>
    @else
        <p class="">Nama: -</p>
    @endif
    <textarea wire:model="alasan_ditolak" class="form-control" id="textTolak"
        placeholder="Masukkan alasan penolakan (Opsional)"></textarea>
</div>
