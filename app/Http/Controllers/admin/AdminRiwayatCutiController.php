<?php

namespace App\Http\Controllers\admin;

use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Models\PermintaanCuti;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Jobs\ExportRiwayatCutiJob;


// use App\Exports\RiwayatCutiExport;

class AdminRiwayatCutiController extends Controller
{
    public function index()
    {
        $idUser = Auth::user()->id;
        $user = User::find($idUser);
        $namaUser = $user->karyawan->nama;
        $jabatan = $user->karyawan->posisi->jabatan;



        return view('admin.riwayat', [
            'nama' => $namaUser,
            'jabatan' => $jabatan,
        ]);
    }

    public function export()
    {
        $fileName = 'riwayat_cuti_.xlsx';
        ExportRiwayatCutiJob::dispatch($fileName);
        return redirect()->back()->with('success', 'Export is being processed and will be available shortly.')->with('fileName', $fileName);
    }


    public function download($fileName)
    {
        $filePath = storage_path('app/public/exports/' . $fileName);
        if (file_exists($filePath)) {
            return response()->download($filePath);
        } else {
            return redirect()->back()->with('error', 'File not found.');
        }
    }

}
