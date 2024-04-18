<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CutiBersamaController extends Controller
{
    public function getCutiBersama()
    {
        // Path ke file JSON
        $filePath = public_path('assets/cuti_bersama.json');

        // Membaca isi file JSON
        $cutiBersama = file_get_contents($filePath);

        // Mengembalikan JSON sebagai respons dengan header yang sesuai
        return response()->json(json_decode($cutiBersama), 200);
    }
}
