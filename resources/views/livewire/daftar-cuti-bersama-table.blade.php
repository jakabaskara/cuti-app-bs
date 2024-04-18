<div>
    <div wire:loading class="f-14 text-dark"> <span class="spinner-grow text-danger align-middle"></span> Loading...</div>
    <div class="d-flex flex-row-reverse">
        <button class="btn btn-primary mb-3" wire:click='setHadir()'>+ Karyawan Hadir</button>
    </div>
    <div class="table-responsive">
        <table class="table table-sm table-bordered table-hover table-striped">
            <thead>
                <tr class="table-dark text-center">
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if (is_null($daftarCuti) || $daftarCuti->isEmpty())
                    <tr>
                        <td colspan="4" class="text-center">Belum Ada Data Cuti Bersama</td>
                    </tr>
                @else
                    @foreach ($daftarCuti as $cuti)
                        <tr class="">
                            <td class="text-center">{{ $cuti->karyawan->NIK }}</td>
                            <td>{{ $cuti->karyawan->nama }}</td>
                            <td>{{ $cuti->karyawan->jabatan }}</td>
                            <td class="text-center"> <input type="checkbox" name="checkKaryawan" id="checkKaryawan"
                                    wire:model='checkKaryawan' value="{{ $cuti->id }}"> </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
