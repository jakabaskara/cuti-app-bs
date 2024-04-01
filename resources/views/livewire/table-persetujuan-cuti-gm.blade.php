<div>
    <div wire:loading class="f-14 text-dark"> <span class="spinner-grow text-danger align-middle"></span> Loading...</div>
    <div class="table-responsive">
        <table class="table table-sm table-hover table-striped">
            <thead>
                <tr class="text-center">
                    <th class="text-dark"><strong>Aksi</strong></th>
                    <th class="text-dark"><strong>NIK</strong></th>
                    <th class="text-dark"><strong>Nama</strong></th>
                    <th class="text-dark"><strong>Periode Cuti</strong></th>
                    <th class="text-dark"><strong>Jumlah Cuti</strong></th>
                    <th class="text-dark"><strong>Alasan Cuti</strong></th>
                    <th class="text-dark"><strong>Alamat</strong></th>
                    <th class="text-dark"><strong>Status Cuti</strong></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($permintaanCuti as $cutiPending)
                    <tr class="text-center">
                        <td class="text-dark noti"> <button class="btn btn-success"
                                wire:click='setujui({{ $cutiPending->id }})'>Setujui</button>
                            {{-- <button class="btn btn-danger" wire:click='tolak({{ $cutiPending->id }})'>Tolak</button> --}}
                            <button class="btn btn-danger"
                                onclick="showRejectModal({{ $cutiPending->id }})">Tolak</button>
                        </td>
                        <td class="text-dark">{{ $cutiPending->karyawan->NIK }}</td>
                        <td class="text-dark">{{ $cutiPending->karyawan->nama }}</td>
                        <td class="text-dark">{{ $cutiPending->tanggal_mulai }}</td>
                        <td class="text-dark">
                            {{ $cutiPending->jumlah_cuti_tahunan + $cutiPending->jumlah_cuti_panjang }}</td>
                        <td class="text-dark">{{ $cutiPending->alasan }}</td>
                        <td class="text-dark">{{ $cutiPending->alamat }}</td>
                        <td class="text-dark"> <span class="badge badge-warning">Pending</span> </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
