<div>
    <div class="table-responsive">
        <table class="table table-hover" id="tableData1">
            <thead class="table-dark">
                <tr class="text-center align-middle">
                    <th>No.</th>
                    <th>NIK SAP</th>
                    <th>Nama</th>
                    <th>Sisa<br>Cuti<br>Tahunan</th>
                    <th>Sisa<br>Cuti<br>Panjang</th>
                    <th>Jumlah</th>
                    {{-- <th>Periode Cuti</th> --}}
                </tr>
            </thead>
            <tbody>
                @php $i = 1; @endphp
                @foreach ($sisaCutis as $index => $sisaCuti)
                    <tr class="text-center align-middle" wire:key="{{ $index }}">
                        <td>{{ $i }}</td>
                        <td>{{ $sisaCuti->NIK }}</td>
                        <td class="text-start">{{ $sisaCuti->nama }}</td>
                        <td>
                            <span>
                                {{ $sisaCuti->sisa_cuti_tahunan }}
                            </span>
                        </td>
                        <td>
                            <span>
                                {{ $sisaCuti->sisa_cuti_panjang }}
                            </span>
                        </td>
                        <td>{{ $sisaCuti->sisa_cuti_tahunan + $sisaCuti->sisa_cuti_panjang }}</td>
                    </tr>
                    @php $i++; @endphp
                @endforeach

            </tbody>
        </table>
    </div>
</div>
