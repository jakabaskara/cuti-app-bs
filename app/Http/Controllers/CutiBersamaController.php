<?php

namespace App\Http\Controllers;

use App\Models\MasterLiburKalender;
use Illuminate\Http\Request;

class CutiBersamaController extends Controller
{
    public function getCutiBersama()
    {
        $cutiBersama = MasterLiburKalender::where('jenis_libur', 'cuti_bersama')
            ->orderBy('tanggal', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->tanggal => [
                        'summary' => $item->description,
                        'holiday' => true,
                        'date' => $item->tanggal
                    ]
                ];
            });

        return response()->json($cutiBersama, 200);
    }

    public function getLiburKalender()
    {
        $libur = MasterLiburKalender::orderBy('tanggal', 'asc')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->tanggal => [
                        'summary' => $item->description,
                        'holiday' => true,
                        'jenis_libur' => $item->jenis_libur
                    ]
                ];
            });

        return response()->json($libur, 200);
    }
}
