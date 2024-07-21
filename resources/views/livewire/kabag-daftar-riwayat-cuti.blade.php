<div>
    <div class="table-responsive">
        <table class="table table-hover" id="tableData2">
            <thead class="table-dark">
                <tr class="text-center align-middle">
                    <th>No.</th>
                    <th>NIK SAP</th>
                    <th>Nama</th>
                    <th>Jenis Cuti</th>
                    <th>Jumlah Hari</th>
                    <th>Periode Tanggal</th>
                    <th>Alasan</th>
                    <th>Alamat</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $i = 1;
                @endphp
                @foreach ($permintaanCutis as $permintaanCuti)
                    <tr class="text-center align-middle">
                        <td>{{ $i }}</td>
                        <td>{{ $permintaanCuti->karyawan->NIK }}</td>
                        <td class="text-start">{{ $permintaanCuti->karyawan->nama }}</td>
                        @if ($permintaanCuti->jumlah_cuti_panjang != 0 && $permintaanCuti->jumlah_cuti_tahunan != 0)
                            <td class="text-center">Cuti Tahunan + Cuti Panjang</td>
                        @elseif($permintaanCuti->jumlah_cuti_panjang != 0)
                            <td class="text-center">Cuti Panjang</td>
                        @else
                            <td class="text-center">Cuti Tahunan</td>
                        @endif
                        <td class="text-center">
                            {{ $permintaanCuti->jumlah_cuti_panjang + $permintaanCuti->jumlah_cuti_tahunan }}
                        </td>
                        <td class="text-center">
                            {{ date('d-M', strtotime($permintaanCuti->tanggal_mulai)) . ' s.d ' . date('d-M', strtotime($permintaanCuti->tanggal_selesai)) }}
                        </td>
                        <td class="text-dark">{{ $permintaanCuti->alasan }}</td>
                        <td class="text-dark">{{ $permintaanCuti->alamat }}</td>

                        {{-- <td>{{ $permintaanCuti->karyawan->sisacuti->sisa_cuti_tahunan }}</td>
                                            <td>{{ $permintaanCuti->karyawan->sisacuti->sisa_cuti_panjang }}</td>
                                            <td>{{ $permintaanCuti->karyawan->sisacuti->sisa_cuti_tahunan + $permintaanCuti->sisa_cuti_panjang }} --}}
                        {{-- </td> --}}
                        {{-- <td>{{ $permintaanCuti->karyawan->sisa_cuti_tahunan }}</td> --}}

                        {{-- </td> --}}
                        @if ($permintaanCuti->is_approved == 1)
                            <td class="text-dark"> <span class="badge badge-success p-2">Disetujui</span>
                            </td>
                        @elseif ($permintaanCuti->is_rejected == 1)
                            <td class="text-dark"> <span class="badge badge-danger p-2">Ditolak</span>
                            </td>
                        @elseif ($permintaanCuti->is_checked == 0)
                            <td class="text-dark"> <span class="badge badge-dark p-2">Belum Diperiksa</span>
                            </td>
                        @else
                            <td class="text-dark"> <span class="badge badge-warning p-2">Pending</span>
                            </td>
                            {{-- <td class="">
                                                    <form id="deleteForm{{ $permintaanCuti->id }}"
                                                        action="{{ route('kerani.delete-cuti', $permintaanCuti->id) }}"
                                                        method="POST" style="display: none;">
                                                        @csrf
                                                        @method('DELETE')
                                                    </form>
                                                </td> --}}
                        @endif
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>
