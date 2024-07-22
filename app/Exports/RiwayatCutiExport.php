<?php

namespace App\Exports;

use App\Models\PermintaanCuti;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class RiwayatCutiExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    public function collection()
    {
        // Mengambil hanya data cuti yang disetujui dan mengurutkannya dari terbaru ke terlama berdasarkan tanggal mulai
        return PermintaanCuti::with('karyawan.posisi.unitKerja')
            ->where('is_approved', 1)
            ->orderBy('tanggal_mulai', 'DESC') // Mengurutkan data berdasarkan tanggal mulai
            ->get()
            ->map(function ($dataCuti) {
                return [
                    'unit_kerja' => $dataCuti->karyawan->posisi->unitKerja->nama_unit_kerja,
                    'bagian' => $dataCuti->karyawan->posisi->unitKerja->bagian,
                    'nik' => $dataCuti->karyawan->NIK,
                    'nama' => $dataCuti->karyawan->nama,
                    'jabatan' => $dataCuti->karyawan->jabatan,
                    'jumlah_hari' => $dataCuti->jumlah_cuti_panjang + $dataCuti->jumlah_cuti_tahunan,
                    'tanggal' => date('d-m', strtotime($dataCuti->tanggal_mulai)) . ' s.d ' . date('d-m Y', strtotime($dataCuti->tanggal_selesai)),
                    'alasan' => $dataCuti->alasan,
                    'alamat' => $dataCuti->alamat,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Unit Kerja',
            'Bagian',
            'NIK',
            'Nama',
            'Jabatan',
            'Jumlah Hari',
            'Tanggal',
            'Alasan',
            'Alamat',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => ['argb' => 'FFE5E5E5'],
            ],
        ]);

        // Mengatur tinggi baris header
        $sheet->getRowDimension(1)->setRowHeight(35);

        // Mengatur lebar kolom
        $columnWidths = [
            'A' => 25, // Lebar kolom unit kerja
            'B' => 30, // Lebar kolom bagian
            'C' => 15, // Lebar kolom NIK
            'D' => 30, // Lebar kolom nama
            'E' => 30, // Lebar kolom jabatan
            'F' => 15, // Lebar kolom jumlah hari
            'G' => 25, // Lebar kolom tanggal
            'H' => 40, // Lebar kolom alasan
            'I' => 40, // Lebar kolom alamat
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $rowCount = $sheet->getHighestRow();

        // Style untuk data
        $sheet->getStyle('A2:I' . $rowCount)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // Mengatur tinggi baris data
        for ($i = 2; $i <= $rowCount; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(30);
        }

        // Mengatur alignment kiri untuk kolom tertentu
        $leftAlignColumns = ['A', 'B', 'D', 'E', 'H', 'I'];
        foreach ($leftAlignColumns as $column) {
            $sheet->getStyle($column . '2:' . $column . $rowCount)->applyFromArray([
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
            ]);
        }

        return [];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Mengatur filter di header
                $event->sheet->getDelegate()->setAutoFilter('A1:I1');
            },
        ];
    }
}
