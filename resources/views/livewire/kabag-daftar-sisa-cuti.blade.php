<div>
    <div class="table-responsive">
        <table class="table table-hover" id="tableData1">
            <thead class="table-dark">
                <tr class="text-center align-middle">
                    <th>No.</th>
                    <th>NIK SAP</th>
                    <th>Nama</th>
                    <th>Sisa<br>Cuti Tahunan</th>
                    <th>Tanggal Jatuh Tempo<br>Cuti Tahunan</th>
                    <th>Sisa<br>Cuti Panjang</th>
                    <th>Tanggal Jatuh Tempo<br>Cuti Panjang</th>
                    <th>Jumlah</th>
                    {{-- <th>Periode Cuti</th> --}}
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach ($sisaCutis as $sisaCuti)
                    <tr class="text-center align-middle">
                        <td>{{ $i }}</td>
                        <td>{{ $sisaCuti->NIK }}</td>
                        <td class="text-start">{{ $sisaCuti->nama }}</td>
                        <td>{{ $sisaCuti->sisa_cuti_tahunan }}</td>
                        <td>
                            {{ $sisaCuti->jatuh_tempo_tahunan ? date('Y', strtotime($sisaCuti->jatuh_tempo_tahunan->periode_mulai)) . '/' . date('Y', strtotime($sisaCuti->jatuh_tempo_tahunan->periode_akhir)) : '' }}
                        </td>
                        </td>
                        <td>{{ $sisaCuti->sisa_cuti_panjang }}</td>
                        <td> {{ $sisaCuti->jatuh_tempo_panjang ? date('Y', strtotime($sisaCuti->jatuh_tempo_panjang->periode_mulai)) . '/' . date('Y', strtotime($sisaCuti->jatuh_tempo_panjang->periode_akhir)) : '' }}
                        </td>
                        <td>{{ $sisaCuti->sisa_cuti_tahunan + $sisaCuti->sisa_cuti_panjang }}</td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach

            </tbody>
        </table>
    </div>
</div>
