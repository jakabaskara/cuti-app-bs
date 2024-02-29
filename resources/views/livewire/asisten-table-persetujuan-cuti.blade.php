<div>
    <div wire:loading class="f-14 text-dark"> <span class="spinner-grow text-danger align-middle"></span> Loading...</div>
    <div class="table-responsive">
        <table class="table table-sm table-hover table-striped">
            <thead>
                <tr class="text-center">
                    <th class="text-dark"><strong>NIK</strong></th>
                    <th class="text-dark"><strong>Nama</strong></th>
                    <th class="text-dark"><strong>Periode Cuti</strong></th>
                    <th class="text-dark"><strong>Jumlah Cuti</strong></th>
                    <th class="text-dark"><strong>Alasan Cuti</strong></th>
                    <th class="text-dark"><strong>Alamat</strong></th>
                    <th class="text-dark"><strong>Status Cuti Cuti</strong></th>
                    <th class="text-dark"><strong>Aksi</strong></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($cutiPendings as $cutiPending)
                    <tr class="text-center">
                        <td class="text-dark">{{ $cutiPending->karyawan->NIK }}</td>
                        <td class="text-dark">{{ $cutiPending->karyawan->nama }}</td>
                        <td class="text-dark">{{ $cutiPending->tanggal_mulai }}</td>
                        <td class="text-dark">{{ $cutiPending->jumlah_hari_cuti }}</td>
                        <td class="text-dark">{{ $cutiPending->alasan }}</td>
                        <td class="text-dark">{{ $cutiPending->alamat }}</td>
                        <td class="text-dark">Pending</td>
                        <td class="text-dark"> <button class="btn btn-success"
                                wire:click='setujui({{ $cutiPending->id }})'>Setujui</button> </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
