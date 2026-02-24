<?php

namespace App\Http\Controllers\admin;

use App\Models\Karyawan;
use App\Models\Employee;
use App\Models\SisaCuti;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeaveBalanceExport;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminLeaveBalanceReportController extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $jabatan = $user->karyawan->posisi->jabatan;
        $namaUser = $user->karyawan->nama;

        // Get unique personnel subareas for filter
        $units = Employee::select('personnel_subarea', 'desc_personnel_subarea')
            ->whereNotNull('personnel_subarea')
            ->whereNotNull('desc_personnel_subarea')
            ->distinct()
            ->orderBy('desc_personnel_subarea')
            ->get();
        return view('admin.leave-balance-report', [
            'jabatan' => $jabatan,
            'nama' => $namaUser,
            'units' => $units,
        ]);
    }

    public function getReportData(Request $request)
    {
        $perPage = $request->input('per_page', 25);
        $page = $request->input('page', 1);
        $search = $request->input('search', '');
        $unitFilter = $request->input('unit_filter', '');

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
                'sc_tahunan.jumlah as sisa_cuti_tahunan',
                'sc_panjang.jumlah as sisa_cuti_panjang',
                'sc_tahunan.periode_akhir as tgl_jatuh_tempo_tahunan',
                'sc_panjang.periode_akhir as tgl_jatuh_tempo_panjang'
            )
            ->whereNull('k.deleted_at');

        // Apply unit filter
        if ($unitFilter) {
            $query->where('e.personnel_subarea', $unitFilter);
        }

        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('k.NIK', 'like', "%{$search}%")
                  ->orWhere('e.name', 'like', "%{$search}%")
                  ->orWhere('e.desc_position', 'like', "%{$search}%")
                  ->orWhere('e.desc_personnel_subarea', 'like', "%{$search}%");
            });
        }

        $total = $query->count();
        $data = $query->skip(($page - 1) * $perPage)
                      ->take($perPage)
                      ->get();

        return response()->json([
            'data' => $data,
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => ceil($total / $perPage)
        ]);
    }

    public function exportExcel(Request $request)
    {
        $unitFilter = $request->input('unit_filter', '');
        $search = $request->input('search', '');

        return Excel::download(
            new LeaveBalanceExport($unitFilter, $search), 
            'laporan_sisa_cuti_' . date('Y-m-d_His') . '.xlsx'
        );
    }

    public function exportPdf(Request $request)
    {
        $unitFilter = $request->input('unit_filter', '');
        $search = $request->input('search', '');

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
                'sc_tahunan.jumlah as sisa_cuti_tahunan',
                'sc_panjang.jumlah as sisa_cuti_panjang',
                'sc_tahunan.periode_akhir as tgl_jatuh_tempo_tahunan',
                'sc_panjang.periode_akhir as tgl_jatuh_tempo_panjang'
            )
            ->whereNull('k.deleted_at');

        // Apply unit filter
        if ($unitFilter) {
            $query->where('e.personnel_subarea', $unitFilter);
        }

        // Apply search
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('k.NIK', 'like', "%{$search}%")
                  ->orWhere('e.name', 'like', "%{$search}%")
                  ->orWhere('e.desc_position', 'like', "%{$search}%")
                  ->orWhere('e.desc_personnel_subarea', 'like', "%{$search}%");
            });
        }

        $data = $query->get();

        $pdf = Pdf::loadView('admin.leave-balance-pdf', [
            'data' => $data,
            'unitFilter' => $unitFilter,
            'generatedDate' => date('d-m-Y H:i:s')
        ])->setPaper('a4', 'landscape');

        return $pdf->download('laporan_sisa_cuti_' . date('Y-m-d_His') . '.pdf');
    }
}
