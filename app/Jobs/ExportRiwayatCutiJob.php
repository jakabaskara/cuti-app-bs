<?php

namespace App\Jobs;

use App\Models\PermintaanCuti;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;

class ExportRiwayatCutiJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $fileName;

    public function __construct($fileName)
    {
        $this->fileName = $fileName;
    }

    public function handle()
    {
        $dataCutis = PermintaanCuti::with('karyawan.posisi.unitKerja')
            ->where('is_approved', 1)
            ->orderBy('tanggal_mulai', 'DESC')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Riwayat Cuti');

        // Define headings
        $headings = [
            'Unit Kerja', 'Bagian', 'NIK', 'Nama', 'Jabatan', 'Jumlah Hari', 'Tanggal', 'Alasan', 'Alamat'
        ];
        $sheet->fromArray($headings, null, 'A1');

        // Add data
        $rowIndex = 2;
        foreach ($dataCutis as $dataCuti) {
            $sheet->fromArray([
                $dataCuti->karyawan->posisi->unitKerja->nama_unit_kerja,
                $dataCuti->karyawan->posisi->unitKerja->bagian,
                $dataCuti->karyawan->NIK,
                $dataCuti->karyawan->nama,
                $dataCuti->karyawan->jabatan,
                $dataCuti->jumlah_cuti_panjang + $dataCuti->jumlah_cuti_tahunan,
                date('d-m', strtotime($dataCuti->tanggal_mulai)) . ' s.d ' . date('d-m Y', strtotime($dataCuti->tanggal_selesai)),
                $dataCuti->alasan,
                $dataCuti->alamat,
            ], null, 'A' . $rowIndex++);
        }

        // Apply styles
        $sheet->getStyle('A1:I1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'color' => ['argb' => 'FFE5E5E5'],
            ],
        ]);

        $sheet->getRowDimension(1)->setRowHeight(35);

        // Set column widths
        $columnWidths = [
            'A' => 25,
            'B' => 30,
            'C' => 15,
            'D' => 30,
            'E' => 30,
            'F' => 15,
            'G' => 25,
            'H' => 40,
            'I' => 40,
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



        // Save file to storage
        $writer = new Xlsx($spreadsheet);
        // $filePath = 'exports/riwayat_cuti.xlsx';
        $filePath = 'exports/' . $this->fileName;
        Storage::disk('public')->put($filePath, '');
        $writer->save(storage_path('app/public/' . $filePath));
    }
}
