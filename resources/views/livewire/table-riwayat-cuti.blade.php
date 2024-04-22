<div>
    <div class="table-responsive">
        <table class="table table-sm table-hover table-bordered table-stiped" id="tableData1">
            <thead>
                <tr class="text-center table-dark align-middle">
                    <th>Unit Kerja</th>
                    <th>Bagian</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Jumlah<br>Hari</th>
                    <th>Tanggal</th>
                    <th>Alasan</th>
                    <th>Alamat</th>
                </tr>
            </thead>
            <tbody>
                @if ($dataCutis)
                    @foreach ($dataCutis as $dataCuti)
                        <tr class="align-middle">
                            <td>{{ $dataCuti->karyawan->posisi->unitKerja->nama_unit_kerja }}</td>
                            <td>{{ $dataCuti->karyawan->posisi->unitKerja->bagian }}</td>
                            <td class="text-center">{{ $dataCuti->karyawan->NIK }}</td>
                            <td>{{ $dataCuti->karyawan->nama }}</td>
                            <td>{{ $dataCuti->karyawan->jabatan }}</td>
                            <td class="text-center">{{ $dataCuti->jumlah_cuti_panjang + $dataCuti->jumlah_cuti_tahunan }}
                            </td>
                            <td class="text-center">
                                {{ date('d-m', strtotime($dataCuti->tanggal_mulai)) . ' s.d ' . date('d-m Y', strtotime($dataCuti->tanggal_selesai)) }}
                            </td>
                            <td>{{ $dataCuti->alasan }}</td>
                            <td>{{ $dataCuti->alamat }}</td>

                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="9">Belum Ada Data</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
