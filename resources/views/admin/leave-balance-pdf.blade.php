<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Sisa Cuti Karyawan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 14px;
        }
        .header p {
            margin: 3px 0;
            font-size: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }
        th {
            background-color: #e5e5e5;
            font-weight: bold;
            font-size: 8px;
        }
        td {
            font-size: 8px;
        }
        .text-left {
            text-align: left;
        }
        .footer {
            margin-top: 20px;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN SISA CUTI KARYAWAN</h2>
        <p>Data Karyawan dengan Integrasi SAP</p>
        @if($unitFilter)
            <p>Filter Unit: {{ $unitFilter }}</p>
        @endif
        <p>Tanggal Cetak: {{ $generatedDate }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIK</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Personnel Subarea</th>
                <th>Desc Personnel Subarea</th>
                <th>Org Unit</th>
                <th>Desc Org Unit</th>
                <th>Employee Group</th>
                <th>Sisa Cuti Tahunan</th>
                <th>Sisa Cuti Panjang</th>
                <th>Tgl Jatuh Tempo Tahunan</th>
                <th>Tgl Jatuh Tempo Panjang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nik ?? '-' }}</td>
                    <td class="text-left">{{ $item->nama ?? '-' }}</td>
                    <td class="text-left">{{ $item->jabatan ?? '-' }}</td>
                    <td>{{ $item->personnel_subarea ?? '-' }}</td>
                    <td class="text-left">{{ $item->desc_personnel_subarea ?? '-' }}</td>
                    <td>{{ $item->org_unit ?? '-' }}</td>
                    <td class="text-left">{{ $item->desc_org_unit ?? '-' }}</td>
                    <td class="text-left">{{ $item->employee_group ?? '-' }}</td>
                    <td>{{ $item->sisa_cuti_tahunan ?? '0' }}</td>
                    <td>{{ $item->sisa_cuti_panjang ?? '0' }}</td>
                    <td>{{ $item->tgl_jatuh_tempo_tahunan ?? '-' }}</td>
                    <td>{{ $item->tgl_jatuh_tempo_panjang ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="13">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Total Data: {{ count($data) }} karyawan</p>
    </div>
</body>
</html>
