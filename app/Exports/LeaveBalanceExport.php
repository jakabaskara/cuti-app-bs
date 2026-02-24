<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class LeaveBalanceExport implements FromCollection, WithHeadings, WithStyles, WithEvents
{
    protected $unitFilter;
    protected $search;

    public function __construct($unitFilter = '', $search = '')
    {
        $this->unitFilter = $unitFilter;
        $this->search = $search;
    }

    public function collection()
    {
        // Build query with joins
        $query = DB::table('karyawan as k')
            ->leftJoin('employees as e', 'k.NIK', '=', 'e.sap')
            ->leftJoin('sisa_cuti as sc_tahunan', function($join) {
                $join->on('k.id', '=', 'sc_tahunan.id_karyawan')
                     ->where('sc_tahunan.id_jenis_cuti', '=', 1)
                     ->whereNull('sc_tahunan.deleted_at');
            })
            ->leftJoin('sisa_cuti as sc_panjang', function($join) {
                $join->on('k.id', '=', 'sc_panjang.id_karyawan')
                     ->where('sc_panjang.id_jenis_cuti', '=', 2)
                     ->whereNull('sc_panjang.deleted_at');
            })
            ->select(
                'k.NIK as nik',
                'e.name as nama',
                'e.desc_position as jabatan',
                'e.personnel_subarea',
                'e.desc_personnel_subarea',
                'e.org_unit',
                'e.desc_org_unit',
                'e.desc_employee_group as employee_group',
                DB::raw('COALESCE(sc_tahunan.jumlah, 0) as sisa_cuti_tahunan'),
                DB::raw('COALESCE(sc_panjang.jumlah, 0) as sisa_cuti_panjang'),
                DB::raw('COALESCE(sc_tahunan.periode_akhir, "-") as tgl_jatuh_tempo_tahunan'),
                DB::raw('COALESCE(sc_panjang.periode_akhir, "-") as tgl_jatuh_tempo_panjang')
            )
            ->whereNull('k.deleted_at');

        // Apply unit filter
        if ($this->unitFilter) {
            $query->where('e.personnel_subarea', $this->unitFilter);
        }

        // Apply search
        if ($this->search) {
            $query->where(function($q) {
                $q->where('k.NIK', 'like', "%{$this->search}%")
                  ->orWhere('e.name', 'like', "%{$this->search}%")
                  ->orWhere('e.desc_position', 'like', "%{$this->search}%")
                  ->orWhere('e.desc_personnel_subarea', 'like', "%{$this->search}%");
            });
        }

        return $query->get()->map(function($item) {
            return [
                'nik' => $item->nik ?? '-',
                'nama' => $item->nama ?? '-',
                'jabatan' => $item->jabatan ?? '-',
                'personnel_subarea' => $item->personnel_subarea ?? '-',
                'desc_personnel_subarea' => $item->desc_personnel_subarea ?? '-',
                'org_unit' => $item->org_unit ?? '-',
                'desc_org_unit' => $item->desc_org_unit ?? '-',
                'employee_group' => $item->employee_group ?? '-',
                'sisa_cuti_tahunan' => $item->sisa_cuti_tahunan,
                'sisa_cuti_panjang' => $item->sisa_cuti_panjang,
                'tgl_jatuh_tempo_tahunan' => $item->tgl_jatuh_tempo_tahunan,
                'tgl_jatuh_tempo_panjang' => $item->tgl_jatuh_tempo_panjang,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'NIK',
            'NAMA',
            'JABATAN',
            'PERSONNEL SUBAREA',
            'DESC PERSONNEL SUBAREA',
            'ORG. UNIT',
            'DESC ORG UNIT',
            'EMPLOYEE GROUP',
            'SISA CUTI TAHUNAN',
            'SISA CUTI PANJANG',
            'TGL JATUH TEMPO CUTI TAHUNAN',
            'TGL JATUH TEMPO CUTI PANJANG',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Style untuk header
        $sheet->getStyle('A1:L1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12,
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
        $sheet->getRowDimension(1)->setRowHeight(40);

        // Mengatur lebar kolom
        $columnWidths = [
            'A' => 15, // NIK
            'B' => 30, // Nama
            'C' => 30, // Jabatan
            'D' => 20, // Personnel Subarea
            'E' => 35, // Desc Personnel Subarea
            'F' => 15, // Org Unit
            'G' => 35, // Desc Org Unit
            'H' => 25, // Employee Group
            'I' => 20, // Sisa Cuti Tahunan
            'J' => 20, // Sisa Cuti Panjang
            'K' => 25, // Tgl Jatuh Tempo Tahunan
            'L' => 25, // Tgl Jatuh Tempo Panjang
        ];

        foreach ($columnWidths as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $rowCount = $sheet->getHighestRow();

        // Style untuk data
        $sheet->getStyle('A2:L' . $rowCount)->applyFromArray([
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
            $sheet->getRowDimension($i)->setRowHeight(25);
        }

        // Mengatur alignment kiri untuk kolom tertentu
        $leftAlignColumns = ['B', 'C', 'E', 'G', 'H'];
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
                $event->sheet->getDelegate()->setAutoFilter('A1:L1');
            },
        ];
    }
}
