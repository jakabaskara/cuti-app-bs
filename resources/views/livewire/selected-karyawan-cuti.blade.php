<div>
    <div class="row">
        <div class="col">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="table-dark">
                        <tr class="text-center">
                            <th>NIK</th>
                            <th>Nama</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (is_null($karyawanSelected) || $karyawanSelected->isEmpty())
                            <tr>
                                <td colspan="3" class="text-center">Silahkan Memilih Karyawan</td>
                            </tr>
                        @else
                            @foreach ($karyawanSelected as $data)
                                <input type="hidden" name="idKaryawanTidakCuti[]" value="{{ $data->id }}">
                                <tr>
                                    <td class="text-center"> {{ $data->karyawan->NIK }}</td>
                                    <td>{{ $data->karyawan->nama }}</td>
                                    <td class="text-center">Hadir</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
